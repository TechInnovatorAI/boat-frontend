<?php

namespace App\Http\Controllers;

use App\Models\JiraIssue;
use Illuminate\Http\Request;

class JiraIssueController extends Controller
{
    /**
     * Display a listing of Jira issues
     */
    public function index(Request $request)
    {
        $query = JiraIssue::query();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by customer email if provided
        if ($request->has('email') && $request->email) {
            $query->where('customer_email', 'like', '%' . $request->email . '%');
        }
        
        // Search by subject or message
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%')
                  ->orWhere('jira_issue_key', 'like', '%' . $search . '%');
            });
        }
        
        $issues = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('jira-issues.index', compact('issues'));
    }
    
    /**
     * Display the specified Jira issue
     */
    public function show($id)
    {
        $issue = JiraIssue::findOrFail($id);
        
        return view('jira-issues.show', compact('issue'));
    }
    
    /**
     * Get customer's issues
     */
    public function customerIssues($email)
    {
        $issues = JiraIssue::getCustomerIssues($email);
        
        return response()->json($issues);
    }
    
    /**
     * Get issues by status
     */
    public function issuesByStatus($status)
    {
        $issues = JiraIssue::getIssuesByStatus($status);
        
        return response()->json($issues);
    }
    
    /**
     * Get recent issues
     */
    public function recentIssues($limit = 10)
    {
        $issues = JiraIssue::getRecentIssues($limit);
        
        return response()->json($issues);
    }
}
