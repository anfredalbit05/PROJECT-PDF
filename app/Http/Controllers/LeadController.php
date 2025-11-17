<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        // Save to database
        Lead::create($data);

        // Send email with PDF
        Mail::send('emails.pdf', [], function($message) use ($data) {
            $message->to($data['email'])
                    ->subject('Your PDF Download')
                    ->attach(public_path('pdf/sample.pdf'));
        });

        return response()->json([
            'success' => true,
            'download_url' => url('/download-pdf')
        ]);
    }

    public function downloadPdf()
    {
        return response()->download(public_path('pdf/sample.pdf'));
    }
}

