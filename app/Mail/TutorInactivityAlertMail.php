<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorInactivityAlertMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tutor;
    public $student;
    
    public function __construct($tutor, $student)
    {
        $this->tutor = $tutor;
        $this->student = $student;
    }


    public function build()
    {
        return $this->subject('Inactive Student Notification')
            ->view('emails.tutor-inactivity');
    }
}
