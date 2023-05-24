<?php

namespace App\Mail;

use App\Models\Competitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Competitor $competitor
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Bokningsbekräftelse {$this->competitor->competitionYear->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.registration-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
