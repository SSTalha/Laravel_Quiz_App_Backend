<?php

namespace App\Jobs;

use App\Models\QuizAssignment;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;   

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $videoPath;
    protected $quizAssignmentId;
    public function __construct(string $videoPath, $quizAssignmentId)
    {
        $this->videoFile = $videoPath;
        $this->quizAssignmentId = $quizAssignmentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $quizAssignment = QuizAssignment::findOrFail($this->quizAssignmentId);

        $video = new Video();
        $video->quiz_assignment_id = $this->quizAssignmentId;
        $video->video_path = $this->videoPath;
        $video->save();
    }
}
