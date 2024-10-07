<?php

namespace App\Mail\StudentSubmissionStatus;

use App\Models\StudentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentSubmissionAccepted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $submission;
     public $token;

    public function __construct(StudentSubmission $submission, $token)
    {
        $this->submission = $submission;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.student_submission_accepted')
                    ->subject('Submission Accepted')
                    ->with(['submission' => $this->submission,
                                'token' => $this->token,
                                ]);
    }
}
