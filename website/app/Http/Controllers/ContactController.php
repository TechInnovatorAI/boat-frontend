<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        // TODO: Future Jira Service Management Integration
        // This is where you would integrate with Jira Service Management
        // For now, we'll just store the contact form data
        
        // You can add Jira integration here later:
        // $jiraService = new JiraServiceManagement();
        // $jiraService->createTicket($request->all());

            return redirect()->route('contact')
                ->with('success', trans('messages.contact.form.success'));
    }
}
