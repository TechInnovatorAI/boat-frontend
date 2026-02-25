<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Jira Issue Status Constants
    const STATUS_TO_DO = 'To Do';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_RESOLVED = 'Resolved';
    const STATUS_CLOSED = 'Closed';
    
    // Jira Issue Priority Constants
    const PRIORITY_HIGH = 'High';
    const PRIORITY_MEDIUM = 'Medium';
    const PRIORITY_LOW = 'Low';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jira_issues', function (Blueprint $table) {
            $table->id();
            $table->string('jira_issue_key')->unique(); // BC-1, BC-2, etc.
            $table->string('jira_issue_id')->nullable(); // Internal Jira ID
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', [
                self::STATUS_TO_DO,
                self::STATUS_IN_PROGRESS,
                self::STATUS_RESOLVED,
                self::STATUS_CLOSED
            ])->default(self::STATUS_TO_DO);
            $table->enum('priority', [
                self::PRIORITY_HIGH,
                self::PRIORITY_MEDIUM,
                self::PRIORITY_LOW
            ])->nullable();
            $table->string('assignee')->nullable(); // Assigned to
            $table->text('description')->nullable(); // Full description from Jira
            $table->json('jira_data')->nullable(); // Store full Jira response for reference
            $table->timestamp('jira_created_at')->nullable(); // When created in Jira
            $table->timestamp('jira_updated_at')->nullable(); // When last updated in Jira
            $table->timestamps(); // Laravel created_at, updated_at
            
            // Indexes for better performance
            $table->index('customer_email');
            $table->index('status');
            $table->index('jira_issue_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jira_issues');
    }
};
