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
        Schema::table('jira_issues', function (Blueprint $table) {
            // Update status column to use enum
            $table->enum('status', [
                self::STATUS_TO_DO,
                self::STATUS_IN_PROGRESS,
                self::STATUS_RESOLVED,
                self::STATUS_CLOSED
            ])->default(self::STATUS_TO_DO)->change();
            
            // Update priority column to use enum
            $table->enum('priority', [
                self::PRIORITY_HIGH,
                self::PRIORITY_MEDIUM,
                self::PRIORITY_LOW
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jira_issues', function (Blueprint $table) {
            // Revert status column to string
            $table->string('status')->default('To Do')->change();
            
            // Revert priority column to string
            $table->string('priority')->nullable()->change();
        });
    }
};
