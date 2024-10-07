<?php

namespace App\Services;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StudentSubmissionRequest;
use App\Jobs\ProcessVideoUpload;
use App\Mail\StudentSubmissionConfirmation;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAssignment;
use App\Models\StudentSubmission;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;

class StudentSubmissionService
{
    public function submitForm(StudentSubmissionRequest $request){
        
        $cvPath = $request->file('cv')->store('cvs');
        
        $submission = StudentSubmission::create([
            'name' => $request->name,
            'email' => $request->email,
            'cv_path' => $cvPath,
            'submitted_at' => Carbon::now(),
        ]);
        try {
            Mail::to($submission->email)->queue(new StudentSubmissionConfirmation($submission));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return ResponseHelper::successResponse('Submission successful! You will receive a confirmation email shortly.', null, 200); 
    }

        /**
     * Submit a quiz answer by the user
     */
    public function submitQuizAnswers($quizId, $userId,  $options)
    {
        if (!auth()->user()->hasRole('student')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz = Quiz::findOrFail($quizId);
        $totalMarksObtained = 0;

        foreach($options as $questionId => $selectedAnswer){
            $question = Question::where('quiz_id', $quizId)
                                ->where('id', $questionId)
                                ->firstOrFail();

            $isCorrect = $question->correct_answer === $selectedAnswer;
            $question->update([
                'selected_answer' => $selectedAnswer,
                'marks' => $isCorrect ? 1 : 0
            ]);
            if ($isCorrect) {
                $totalMarksObtained += $question->marks;
            }
        }
        $quizAssignment = QuizAssignment::where('quiz_id', $quizId)
                                    ->where('user_id', $userId)
                                    ->firstOrFail();
        $quizAssignment->update([
            'marks_obtained' => $totalMarksObtained,
            'attempt' => 1,
            'end_time' => now()
        ]);
        return ResponseHelper::successResponse('Quiz answers submitted successfully', null, 200);
    }

    /**
     * Store video from frontend and save its path in the database
     */

     public function storeVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi,mkv|max:20480',
            'quiz_assignment_id' => 'required|integer|exists:quiz_assignments,id',
        ]);

        $videoFile = $request->file('video');
        $videoPath = $videoFile->store('videos', 'public');

        $quizAssignmentId = $request->input('quiz_assignment_id');

        $video = new Video();
        $video->quiz_assignment_id = $quizAssignmentId;
        $video->video_path = $videoPath;
        $video->save();
        
        // ProcessVideoUpload::dispatch($videoPath, $quizAssignmentId);
        
        return ResponseHelper::successResponse('Video uploaded and path saved successfully', null, 201);
    }
}