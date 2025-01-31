<?php

namespace App\Mail;

use App\Models\Acquisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserAcquisitionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $acquisition;

    /**
     * Create a new event instance.
     * @param Acquisition $acquisition
     */
    public function __construct(Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Acquisition Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.acquisition.user_acquisition',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
