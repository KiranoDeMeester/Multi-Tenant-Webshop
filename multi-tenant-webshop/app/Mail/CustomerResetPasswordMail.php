<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CustomerResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $resetUrl,
        public string $customerName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Wachtwoord resetten - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.customer-reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'customerName' => $this->customerName,
            ],
        );
    }
}
