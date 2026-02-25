<?php

namespace App\Console\Commands;

use App\Services\JiraService;
use Illuminate\Console\Command;

class TestJiraConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jira:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Jira Service Management connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Jira Service Management connection...');
        
        $jiraService = new JiraService();
        
        // Test basic connection
        $this->info('1. Testing basic connection...');
        if ($jiraService->testConnection()) {
            $this->info('✓ Basic connection successful');
        } else {
            $this->error('✗ Basic connection failed');
            return 1;
        }
        
        // Test service desk info
        $this->info('2. Testing service desk access...');
        $serviceDeskInfo = $jiraService->getServiceDeskInfo();
        if ($serviceDeskInfo) {
            $this->info('✓ Service desk access successful');
            $serviceDeskName = $serviceDeskInfo['name'] ?? $serviceDeskInfo['projectName'] ?? 'Unknown';
            $this->line("   Service Desk: {$serviceDeskName}");
        } else {
            $this->error('✗ Service desk access failed');
            return 1;
        }
        
        // Test request types
        $this->info('3. Testing request types...');
        $requestTypes = $jiraService->getRequestTypes();
        if ($requestTypes && isset($requestTypes['values'])) {
            $this->info('✓ Request types retrieved successfully');
            $this->line("   Available request types: " . count($requestTypes['values']));
            foreach ($requestTypes['values'] as $type) {
                $this->line("   - {$type['name']} (ID: {$type['id']})");
            }
        } else {
            $this->error('✗ Failed to retrieve request types');
            return 1;
        }
        
        $this->info('');
        $this->info('🎉 All tests passed! Jira integration is ready.');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Configure your Jira webhook to point to: ' . url('/webhook/jira'));
        $this->info('2. Test the contact form to create a ticket');
        
        return 0;
    }
}
