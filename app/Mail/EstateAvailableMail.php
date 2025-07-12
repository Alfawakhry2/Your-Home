<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Estate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class EstateAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public $estate ;
    public $user ;
    /**
     * Create a new message instance.
     */
    public function __construct(Estate $estate , User $user)
    {
        $this->estate = $estate ;
        $this->user = $user ;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Estate Available Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.EstateAvailableMail',
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
