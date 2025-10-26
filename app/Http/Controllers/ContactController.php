<?php

namespace App\Http\Controllers;

use App\Services\MailService;
use App\Services\JiraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => trans('messages.contact.form.required'),
            'email.required' => trans('messages.contact.form.required'),
            'email.email' => trans('messages.contact.form.email_invalid'),
            'subject.required' => trans('messages.contact.form.required'),
            'message.required' => trans('messages.contact.form.required'),
            'name.max' => trans('messages.contact.form.max_length', ['max' => 255]),
            'email.max' => trans('messages.contact.form.max_length', ['max' => 255]),
            'subject.max' => trans('messages.contact.form.max_length', ['max' => 255]),
            'message.max' => trans('messages.contact.form.max_length', ['max' => 1000]),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create Jira Service Management ticket
        $jiraService = new JiraService();
        $jiraTicket = $jiraService->createServiceRequest($request->all());
        
        $ticketCreated = false;
        if ($jiraTicket) {
            $ticketCreated = true;
            Log::info('Jira ticket created successfully', [
                'ticket_key' => $jiraTicket['issueKey'] ?? 'Unknown',
                'customer_email' => $request->email
            ]);
        } else {
            Log::warning('Failed to create Jira ticket', [
                'customer_email' => $request->email
            ]);
        }

        // Send email to admin
        $subject = "New Contact Message from {$request->name}";
        $body = "
            <h3>Contact Message</h3>
            <p><strong>Name:</strong> {$request->name}</p>
            <p><strong>Email:</strong> {$request->email}</p>
            <p><strong>Subject:</strong> {$request->subject}</p>
            <p><strong>Message:</strong><br>{$request->message}</p>
        ";

        $sent = MailService::sendContactMail(config('mail.from.address'), $subject, $body);

        // Prepare success message
        $successMessage = trans('messages.contact.form.success');
        if ($ticketCreated && isset($jiraTicket['issueKey'])) {
            $successMessage .= ' Your support ticket ' . $jiraTicket['issueKey'] . ' has been created.';
        }

        return redirect()->route('contact')
            ->with($sent ? 'success' : 'error', $sent
                ? $successMessage
                : trans('messages.contact.form.error'));
    }
}
