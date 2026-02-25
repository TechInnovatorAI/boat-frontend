<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class JiraService
{
    private $baseUrl;
    private $username;
    private $apiToken;
    private $projectKey;
    private $serviceDeskId;
    private $requestTypeId;

    public function __construct()
    {
        $this->baseUrl = config('services.jira.base_url');
        $this->username = config('services.jira.username');
        $this->apiToken = config('services.jira.api_token');
        $this->projectKey = config('services.jira.project_key');
        $this->serviceDeskId = config('services.jira.service_desk_id');
        $this->requestTypeId = config('services.jira.request_type_id');
    }

    /**
     * Create or get a customer in Jira Service Management
     */
    public function createCustomer($email, $displayName = null)
    {
        try {
            // First, try to create the customer
            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/rest/servicedeskapi/customer", [
                    'email' => $email,
                    'displayName' => $displayName ?: $email,
                ]);

            if ($response->successful()) {
                Log::info('Jira customer created successfully', [
                    'email' => $email,
                    'response' => $response->json()
                ]);
                return $response->json();
            } else {
                // If customer already exists (400 error), try to get existing customer
                if ($response->status() === 400 && str_contains($response->body(), 'already exists')) {
                    Log::info('Customer already exists, using existing customer', ['email' => $email]);
                    $existingCustomer = $this->getExistingCustomer($email);
                    if ($existingCustomer) {
                        return $existingCustomer;
                    }
                    // If we can't find the existing customer, create a mock customer object
                    Log::warning('Could not retrieve existing customer, creating mock customer object', ['email' => $email]);
                    return [
                        'emailAddress' => $email,
                        'displayName' => $displayName ?: $email,
                        'accountId' => 'existing-customer-' . md5($email)
                    ];
                }
                
                Log::error('Failed to create Jira customer', [
                    'email' => $email,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }
        } catch (Exception $e) {
            Log::error('Exception creating Jira customer', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get existing customer by email
     */
    private function getExistingCustomer($email)
    {
        try {
            // Use the correct endpoint for searching customers
            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/rest/servicedeskapi/customer", [
                    'query' => $email
                ]);

            if ($response->successful()) {
                $customers = $response->json();
                if (!empty($customers['values'])) {
                    Log::info('Found existing customer', [
                        'email' => $email,
                        'customer' => $customers['values'][0]
                    ]);
                    return $customers['values'][0]; // Return first matching customer
                }
            } else {
                Log::warning('Failed to search for existing customer', [
                    'email' => $email,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
            return null;
        } catch (Exception $e) {
            Log::error('Exception getting existing customer', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Create a service request in Jira Service Management
     */
    public function createServiceRequest($formData)
    {
        try {
            // First, create or get the customer
            $customer = $this->createCustomer($formData['email'], $formData['name']);
            
            if (!$customer) {
                Log::error('Customer creation/retrieval failed, proceeding without customer association', [
                    'email' => $formData['email']
                ]);
                // Continue without customer association - Jira will handle it
            }

            // Prepare the request data (matching your working curl structure)
            $requestData = [
                'serviceDeskId' => $this->serviceDeskId,
                'requestTypeId' => $this->requestTypeId,
                'requestFieldValues' => [
                    'summary' => $formData['subject'],
                    'description' => $this->formatDescription($formData),
                ],
            ];

            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/rest/servicedeskapi/request", $requestData);

            if ($response->successful()) {
                $ticketData = $response->json();
                Log::info('Jira service request created successfully', [
                    'ticket_key' => $ticketData['issueKey'] ?? 'Unknown',
                    'customer_email' => $formData['email'],
                    'response' => $ticketData
                ]);
                return $ticketData;
            } else {
                Log::error('Failed to create Jira service request', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'form_data' => $formData
                ]);
                return null;
            }
        } catch (Exception $e) {
            Log::error('Exception creating Jira service request', [
                'error' => $e->getMessage(),
                'form_data' => $formData
            ]);
            return null;
        }
    }

    /**
     * Format the description for the Jira ticket
     */
    private function formatDescription($formData)
    {
        return "
**Contact Form Submission**

**Name:** {$formData['name']}
**Email:** {$formData['email']}
**Subject:** {$formData['subject']}

**Message:**
{$formData['message']}

---
*This ticket was automatically created from the website contact form.*
        ";
    }

    /**
     * Get service desk information
     */
    public function getServiceDeskInfo()
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/rest/servicedeskapi/servicedesk/{$this->serviceDeskId}");

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (Exception $e) {
            Log::error('Exception getting service desk info', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get available request types for the service desk
     */
    public function getRequestTypes()
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/rest/servicedeskapi/servicedesk/{$this->serviceDeskId}/requesttype");

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (Exception $e) {
            Log::error('Exception getting request types', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Test the Jira connection
     */
    public function testConnection()
    {
        try {
            $response = Http::withBasicAuth($this->username, $this->apiToken)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/rest/api/3/myself");

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Jira connection test failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
