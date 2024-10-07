<?php

namespace App\Mail;

use App\Models\StudentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentSubmissionConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $submission;
    public function __construct(StudentSubmission $submission)
    {
        $this->submission = $submission;
    }

   public function build(){
    return $this->view('emails.student_submission')
                ->subject('Student Confirmation')
                ->with(['submission'=> $this->submission]);
   }
}
