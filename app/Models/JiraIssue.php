<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JiraIssue extends Model
{
    use HasFactory;

    // Jira Issue Status Constants
    const STATUS_TO_DO = 'To Do';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_RESOLVED = 'Resolved';
    const STATUS_CLOSED = 'Closed';
    
    // Jira Issue Priority Constants
    const PRIORITY_HIGH = 'High';
    const PRIORITY_MEDIUM = 'Medium';
    const PRIORITY_LOW = 'Low';

    protected $fillable = [
        'jira_issue_key',
        'jira_issue_id',
        'customer_name',
        'customer_email',
        'subject',
        'message',
        'status',
        'priority',
        'assignee',
        'description',
        'jira_data',
        'jira_created_at',
        'jira_updated_at',
    ];

    protected $casts = [
        'jira_data' => 'array',
        'jira_created_at' => 'datetime',
        'jira_updated_at' => 'datetime',
    ];

    /**
     * Get the customer's issues
     */
    public static function getCustomerIssues($email)
    {
        return self::where('customer_email', $email)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get issues by status
     */
    public static function getIssuesByStatus($status)
    {
        return self::where('status', $status)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get recent issues
     */
    public static function getRecentIssues($limit = 10)
    {
        return self::orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Update issue from Jira webhook data
     */
    public function updateFromJiraData($jiraData)
    {
        $fields = $jiraData['fields'] ?? [];
        
        $this->update([
            'status' => $fields['status']['name'] ?? $this->status,
            'priority' => $fields['priority']['name'] ?? $this->priority,
            'assignee' => $fields['assignee']['displayName'] ?? $this->assignee,
            'description' => $fields['description'] ?? $this->description,
            'jira_data' => $jiraData,
            'jira_updated_at' => now(),
        ]);
    }

    /**
     * Check if issue is resolved or closed
     */
    public function isResolved()
    {
        return in_array($this->status, [
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColor()
    {
        return match($this->status) {
            self::STATUS_TO_DO => 'blue',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_CLOSED => 'gray',
            default => 'gray'
        };
    }
}
