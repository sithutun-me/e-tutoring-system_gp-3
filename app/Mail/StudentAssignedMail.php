<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $student;
    public $tutor;

    
    public function __construct($student, $tutor)
    {
        $this->student = $student;
        $this->tutor = $tutor;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('New Tutor Assigned')
                    ->view('emails.student-assigned');
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
