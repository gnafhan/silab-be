<?php

namespace App\Mail;

use App\Models\RoomReserf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LabReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $labReserve;
    public $user;
    
    /**
     * Create a new message instance.
     */
    public function __construct(RoomReserf $labReserve, $user)
    {
        $this->labReserve = $labReserve;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lab Reservation from '. $this->user->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lab_reservation_confirmation',
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
