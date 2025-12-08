<?php

namespace App\Constants;

class JiraIssueConstants
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
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_TO_DO,
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED
        ];
    }
    
    /**
     * Get all available priorities
     */
    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_HIGH,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_LOW
        ];
    }
    
    /**
     * Check if status is valid
     */
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, self::getStatuses());
    }
    
    /**
     * Check if priority is valid
     */
    public static function isValidPriority(string $priority): bool
    {
        return in_array($priority, self::getPriorities());
    }
    
    /**
     * Get status color for UI
     */
    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_TO_DO => 'blue',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_CLOSED => 'gray',
            default => 'gray'
        };
    }
    
    /**
     * Get priority color for UI
     */
    public static function getPriorityColor(string $priority): string
    {
        return match($priority) {
            self::PRIORITY_HIGH => 'red',
            self::PRIORITY_MEDIUM => 'orange',
            self::PRIORITY_LOW => 'green',
            default => 'gray'
        };
    }
}
