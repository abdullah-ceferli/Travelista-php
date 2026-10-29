<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerificationCode extends Mailable
{
    public function __construct(public readonly string $code) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Travelista Verification Code');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.verification-code');
    }
}
