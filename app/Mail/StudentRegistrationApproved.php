<?php

namespace App\Mail;

use App\Models\PendingStudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentRegistrationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PendingStudentRegistration $registration)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Account Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-registration-approved',
        );
    }
}
