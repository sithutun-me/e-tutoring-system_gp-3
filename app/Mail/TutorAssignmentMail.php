<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $tutor;
    public $students;

    public function __construct($tutor, $students)
    {
        $this->tutor = $tutor;
        $this->students = $students;
    }

    public function build()
    {
        return $this->subject('New Students Assigned')
                    ->view('emails.tutor-assigned');
    }
}
