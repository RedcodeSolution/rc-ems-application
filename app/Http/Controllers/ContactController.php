<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // 1. Validate the data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // 2. Send the email (Inline method for simplicity)
        Mail::raw("Name: {$validated['name']}\nEmail: {$validated['email']}\nCompany: {$validated['company']}\n\nMessage:\n{$validated['message']}", function ($message) {
            $message->to('hr@redcodesolution.com')
                ->subject('New Contact Form Submission');
        });

        // 3. Return success response for the JavaScript
        return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
    }
}
