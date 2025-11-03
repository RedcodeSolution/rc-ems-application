<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    public function __construct($document)
    {
        $this->document = $document;
    }

    public function build()
    {
        $filePath = storage_path('app/public/' . preg_replace('/^storage\//', '', $this->document->file_path));

        return $this->subject('Shared Document: ' . $this->document->title)
            ->view('emails.share-document') // 👈 Make sure this view exists
            ->attach($filePath, [
                'as' => $this->document->file_name,
                'mime' => mime_content_type($filePath),
            ]);
    }

}
