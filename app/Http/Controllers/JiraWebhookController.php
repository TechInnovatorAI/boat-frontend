<?php

namespace App\Http\Controllers;

use App\Services\MailService;
use App\Models\JiraIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JiraWebhookController extends Controller
{
    /**
     * Handle Jira webhook notifications
     */
    public function handle(Request $request)
    {
        try {
            Log::info('Jira webhook received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all(),
                'timestamp' => now()->toISOString()
            ]);
            // Validate the webhook payload
            $validator = Validator::make($request->all(), [
                'webhookEvent' => 'required|string',
                'issue' => 'required|array',
                'issue.key' => 'required|string',
                'issue.fields' => 'required|array',
                'changelog' => 'sometimes|array',
            ]);

            if ($validator->fails()) {
                Log::warning('Invalid Jira webhook payload', [
                    'errors' => $validator->errors(),
                    'payload' => $request->all()
                ]);
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            $webhookEvent = $request->input('webhookEvent');
            $issue = $request->input('issue');
            $issueKey = $issue['key'];
            $fields = $issue['fields'];

            Log::info('Jira webhook received', [
                'event' => $webhookEvent,
                'issue_key' => $issueKey,
                'issue_id' => $issue['id'] ?? null
            ]);

            // Handle different webhook events
            switch ($webhookEvent) {
                case 'jira:issue_created':
                    $this->handleIssueCreated($issue, $fields);
                    break;
                
                case 'jira:issue_updated':
                    // Check if this is actually a comment event
                    $changelog = $request->input('changelog', []);
                    if ($this->isCommentUpdate($changelog)) {
                        $this->handleIssueCommented($issue, $fields);
                    } else {
                        $this->handleIssueUpdated($issue, $fields, $changelog);
                    }
                    break;
                
                case 'jira:issue_commented':
                    $this->handleIssueCommented($issue, $fields);
                    break;
                
                default:
                    Log::info('Unhandled Jira webhook event', [
                        'event' => $webhookEvent,
                        'issue_key' => $issueKey
                    ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Exception handling Jira webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);
            
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle issue created event
     */
    private function handleIssueCreated($issue, $fields)
    {
        Log::info('Issue created', [
            'issue_key' => $issue['key'],
            'summary' => $fields['summary'] ?? 'No summary'
        ]);
        
        // Update database if issue exists
        $this->updateJiraIssueInDatabase($issue, $fields);
        
        // Send confirmation email to customer when ticket is created
        $this->sendIssueCreatedNotificationEmail($issue, $fields);
    }

    /**
     * Handle issue updated event
     */
    private function handleIssueUpdated($issue, $fields, $changelog)
    {
        $issueKey = $issue['key'];
        
        Log::info('Issue updated', [
            'issue_key' => $issueKey,
            'changelog_items' => count($changelog['items'] ?? [])
        ]);
        
        // Update database with latest issue data
        $this->updateJiraIssueInDatabase($issue, $fields);
        
        // Check if status changed to "Resolved" or "Closed"
        $statusChanged = $this->getStatusChange($changelog);
        
        if ($statusChanged) {
            Log::info('Issue status changed', [
                'issue_key' => $issueKey,
                'old_status' => $statusChanged['from'],
                'new_status' => $statusChanged['to']
            ]);
            
            // Send email notification to customer about status change
            $this->sendStatusUpdateEmail($issue, $fields, $statusChanged);
        } else {
            // Send general update notification for other changes
            $this->sendIssueUpdatedNotificationEmail($issue, $fields, $changelog);
        }
    }

    /**
     * Handle issue commented event
     */
    private function handleIssueCommented($issue, $fields)
    {
        $issueKey = $issue['key'];
        
        Log::info('Comment added to issue', [
            'issue_key' => $issueKey
        ]);
        
        // Get the latest comment from the request
        $comment = $this->getLatestComment($issue, $fields);
        
        if ($comment) {
            // Check if it's a public comment or internal note
            $isPublicComment = $this->isPublicComment($comment);
            
            Log::info('Comment details', [
                'issue_key' => $issueKey,
                'comment_id' => $comment['id'] ?? 'unknown',
                'is_public' => $isPublicComment,
                'author' => $comment['author']['displayName'] ?? 'unknown'
            ]);
            
            // Only send email for public comments (not internal notes)
            if ($isPublicComment) {
                $this->sendCommentNotificationEmail($issue, $fields, $comment);
            } else {
                Log::info('Internal note detected, skipping email notification', [
                    'issue_key' => $issueKey,
                    'comment_id' => $comment['id'] ?? 'unknown'
                ]);
            }
        } else {
            Log::warning('Could not extract comment details', [
                'issue_key' => $issueKey
            ]);
        }
    }

    /**
     * Check if the update is actually a comment
     */
    private function isCommentUpdate($changelog)
    {
        if (empty($changelog['items'])) {
            return false;
        }

        foreach ($changelog['items'] as $item) {
            if ($item['field'] === 'comment') {
                return true;
            }
        }

        return false;
    }

    /**
     * Get status change from changelog
     */
    private function getStatusChange($changelog)
    {
        if (empty($changelog['items'])) {
            return null;
        }

        foreach ($changelog['items'] as $item) {
            if ($item['field'] === 'status') {
                return [
                    'from' => $item['fromString'] ?? 'Unknown',
                    'to' => $item['toString'] ?? 'Unknown'
                ];
            }
        }

        return null;
    }

    /**
     * Send status update email to customer
     */
    private function sendStatusUpdateEmail($issue, $fields, $statusChange)
    {
        try {
            // Extract customer email from issue description or custom field
            $customerEmail = $this->extractCustomerEmail($fields);
            
            if (!$customerEmail) {
                Log::warning('Could not extract customer email from issue', [
                    'issue_key' => $issue['key']
                ]);
                return;
            }

            $subject = "Update on your support request - {$issue['key']}";
            $body = "
                <h3>Support Request Update</h3>
                <p>Your support request <strong>{$issue['key']}</strong> has been updated.</p>
                
                <p><strong>Status:</strong> {$statusChange['from']} → {$statusChange['to']}</p>
                <p><strong>Summary:</strong> {$fields['summary']}</p>
                
                <p>You can view the full details in your support portal.</p>
                
                <p>Best regards,<br>Boat Support Team</p>
            ";

            $sent = MailService::sendContactMail($customerEmail, $subject, $body);
            
            if ($sent) {
                Log::info('Status update email sent successfully', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            } else {
                Log::error('Failed to send status update email', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending status update email', [
                'issue_key' => $issue['key'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send comment notification email to customer
     */
    private function sendCommentNotificationEmail($issue, $fields, $comment = null)
    {
        try {
            // Extract customer email from issue description or custom field
            $customerEmail = $this->extractCustomerEmail($fields);
            
            if (!$customerEmail) {
                Log::warning('Could not extract customer email from issue', [
                    'issue_key' => $issue['key']
                ]);
                return;
            }

            $subject = "New comment on your support request - {$issue['key']}";
            
            // Build email body with comment details if available
            $commentDetails = '';
            if ($comment) {
                $commentAuthor = $comment['author']['displayName'] ?? 'Support Team';
                $commentBody = $comment['body'] ?? '';
                
                // Clean up comment body (remove HTML tags for email)
                $commentBody = strip_tags($commentBody);
                $commentBody = html_entity_decode($commentBody);
                
                $commentDetails = "
                    <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>
                        <p><strong>From:</strong> {$commentAuthor}</p>
                        <p><strong>Comment:</strong></p>
                        <p style='margin: 10px 0;'>{$commentBody}</p>
                    </div>
                ";
            }
            
            $body = "
                <h3>New Comment on Support Request</h3>
                <p>A new comment has been added to your support request <strong>{$issue['key']}</strong>.</p>
                
                <p><strong>Summary:</strong> {$fields['summary']}</p>
                
                {$commentDetails}
                
                <p>You can view the full details and respond in your support portal.</p>
                
                <p>Best regards,<br>Boat Support Team</p>
            ";

            $sent = MailService::sendContactMail($customerEmail, $subject, $body);
            
            if ($sent) {
                Log::info('Comment notification email sent successfully', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail,
                    'comment_author' => $comment['author']['displayName'] ?? 'unknown'
                ]);
            } else {
                Log::error('Failed to send comment notification email', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending comment notification email', [
                'issue_key' => $issue['key'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send issue created notification email to customer
     */
    private function sendIssueCreatedNotificationEmail($issue, $fields)
    {
        try {
            // Extract customer email from issue description or custom field
            $customerEmail = $this->extractCustomerEmail($fields);
            
            if (!$customerEmail) {
                Log::warning('Could not extract customer email from issue', [
                    'issue_key' => $issue['key']
                ]);
                return;
            }

            $subject = "Support Request Created - {$issue['key']}";
            $statusName = $fields['status']['name'] ?? 'Open';
            $body = "
                <h3>Support Request Confirmation</h3>
                <p>Thank you for contacting us! Your support request has been successfully created.</p>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>
                    <p><strong>Request ID:</strong> {$issue['key']}</p>
                    <p><strong>Subject:</strong> {$fields['summary']}</p>
                    <p><strong>Status:</strong> {$statusName}</p>
                </div>
                
                <p>We will review your request and get back to you as soon as possible. You can track the progress of your request using the ID above.</p>
                
                <p>If you have any additional information or questions, please don't hesitate to contact us.</p>
                
                <p>Best regards,<br>Boat Support Team</p>
            ";

            $sent = MailService::sendContactMail($customerEmail, $subject, $body);
            
            if ($sent) {
                Log::info('Issue created notification email sent successfully', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            } else {
                Log::error('Failed to send issue created notification email', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending issue created notification email', [
                'issue_key' => $issue['key'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send issue updated notification email to customer
     */
    private function sendIssueUpdatedNotificationEmail($issue, $fields, $changelog)
    {
        try {
            // Extract customer email from issue description or custom field
            $customerEmail = $this->extractCustomerEmail($fields);
            
            if (!$customerEmail) {
                Log::warning('Could not extract customer email from issue', [
                    'issue_key' => $issue['key']
                ]);
                return;
            }

            // Build changelog details
            $changelogDetails = $this->buildChangelogDetails($changelog);

            $subject = "Update on your support request - {$issue['key']}";
            $currentStatus = $fields['status']['name'] ?? 'Unknown';
            $body = "
                <h3>Support Request Update</h3>
                <p>Your support request <strong>{$issue['key']}</strong> has been updated.</p>
                
                <p><strong>Summary:</strong> {$fields['summary']}</p>
                <p><strong>Current Status:</strong> {$currentStatus}</p>
                
                {$changelogDetails}
                
                <p>You can view the full details in your support portal.</p>
                
                <p>Best regards,<br>Boat Support Team</p>
            ";

            $sent = MailService::sendContactMail($customerEmail, $subject, $body);
            
            if ($sent) {
                Log::info('Issue updated notification email sent successfully', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            } else {
                Log::error('Failed to send issue updated notification email', [
                    'issue_key' => $issue['key'],
                    'customer_email' => $customerEmail
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending issue updated notification email', [
                'issue_key' => $issue['key'],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Build changelog details for email
     */
    private function buildChangelogDetails($changelog)
    {
        if (empty($changelog['items'])) {
            return '<p><em>No specific changes were made.</em></p>';
        }

        $details = '<div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;">';
        $details .= '<h4>Changes Made:</h4><ul>';

        foreach ($changelog['items'] as $item) {
            $field = $item['field'] ?? 'Unknown field';
            $from = $item['fromString'] ?? '';
            $to = $item['toString'] ?? '';

            // Skip internal fields that customers don't need to know about
            if (in_array($field, ['assignee', 'reporter', 'priority', 'labels', 'components'])) {
                continue;
            }

            $fieldName = $this->getFieldDisplayName($field);
            
            if ($from && $to) {
                $details .= "<li><strong>{$fieldName}:</strong> {$from} → {$to}</li>";
            } elseif ($to) {
                $details .= "<li><strong>{$fieldName}:</strong> {$to}</li>";
            }
        }

        $details .= '</ul></div>';
        return $details;
    }

    /**
     * Get display name for field
     */
    private function getFieldDisplayName($field)
    {
        $fieldNames = [
            'summary' => 'Summary',
            'description' => 'Description',
            'status' => 'Status',
            'resolution' => 'Resolution',
            'fixVersions' => 'Fix Version',
            'components' => 'Component',
            'labels' => 'Labels',
            'assignee' => 'Assignee',
            'reporter' => 'Reporter',
            'priority' => 'Priority'
        ];

        return $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    /**
     * Get the latest comment from the webhook payload
     */
    private function getLatestComment($issue, $fields)
    {
        // Try to get comment from the webhook payload
        $request = request();
        
        // Check if comment is in the main payload
        if ($request->has('comment')) {
            return $request->input('comment');
        }
        
        // Check if comment is in the changelog
        $changelog = $request->input('changelog', []);
        if (!empty($changelog['items'])) {
            foreach ($changelog['items'] as $item) {
                if ($item['field'] === 'comment') {
                    return $item;
                }
            }
        }
        
        // If no comment found in payload, try to get from issue fields
        // This might happen if the webhook doesn't include comment details
        if (isset($fields['comment'])) {
            return $fields['comment'];
        }
        
        return null;
    }
    
    /**
     * Determine if a comment is public or internal
     */
    private function isPublicComment($comment)
    {
        // Check if comment has visibility restrictions
        if (isset($comment['visibility'])) {
            $visibility = $comment['visibility'];
            
            // If visibility is set, it's usually an internal note
            if (isset($visibility['type']) && $visibility['type'] === 'role') {
                return false; // Internal note
            }
            
            if (isset($visibility['type']) && $visibility['type'] === 'group') {
                return false; // Internal note
            }
        }
        
        // Check if comment has restricted visibility
        if (isset($comment['restricted'])) {
            return !$comment['restricted']; // If restricted, it's internal
        }
        
        // Check comment body for internal note indicators
        $body = $comment['body'] ?? '';
        
        // Common patterns that indicate internal notes
        $internalPatterns = [
            '/internal note/i',
            '/staff only/i',
            '/team only/i',
            '/private/i',
            '/confidential/i'
        ];
        
        foreach ($internalPatterns as $pattern) {
            if (preg_match($pattern, $body)) {
                return false;
            }
        }
        
        // Default to public comment if no restrictions found
        return true;
    }

    /**
     * Extract customer email from issue fields
     */
    private function extractCustomerEmail($fields)
    {
        // Try to extract email from description
        $description = $fields['description'] ?? '';
        
        // Look for email pattern in description
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $description, $matches)) {
            return $matches[0];
        }
        
        // Try custom fields if available
        if (isset($fields['customfield_10000'])) { // Replace with actual custom field ID
            return $fields['customfield_10000'];
        }
        
        return null;
    }

    /**
     * Update Jira issue in database
     */
    private function updateJiraIssueInDatabase($issue, $fields)
    {
        try {
            $jiraIssue = JiraIssue::where('jira_issue_key', $issue['key'])->first();
            
            if ($jiraIssue) {
                $jiraIssue->update([
                    'status' => $fields['status']['name'] ?? $jiraIssue->status,
                    'priority' => $fields['priority']['name'] ?? $jiraIssue->priority,
                    'assignee' => $fields['assignee']['displayName'] ?? $jiraIssue->assignee,
                    'description' => $fields['description'] ?? $jiraIssue->description,
                    'jira_data' => array_merge($jiraIssue->jira_data ?? [], [
                        'issue' => $issue,
                        'fields' => $fields
                    ]),
                    'jira_updated_at' => now(),
                ]);
                
                Log::info('Jira issue updated in database', [
                    'jira_issue_key' => $issue['key'],
                    'database_id' => $jiraIssue->id,
                    'new_status' => $fields['status']['name'] ?? 'unchanged'
                ]);
            } else {
                Log::warning('Jira issue not found in database', [
                    'jira_issue_key' => $issue['key']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update Jira issue in database', [
                'jira_issue_key' => $issue['key'],
                'error' => $e->getMessage()
            ]);
        }
    }
}
