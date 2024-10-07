<?php

namespace App\Services;
use App\Helpers\ResponseHelper;
use App\Mail\StudentSubmissionStatus\StudentSubmissionAccepted;
use App\Mail\StudentSubmissionStatus\StudentSubmissionRejected;
use App\Models\PasswordReset;
use App\Models\Quiz;
use App\Models\StudentSubmission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Str;

class AdminService
{
    public function viewSubmissions(){
        // dd("working");

        if (!auth()->user()->hasRole('admin')){
            return response()->json(['message'=>'UnAuthorized'], 403);
        }

        $submissions = StudentSubmission::all();
        return ResponseHelper::successResponse('Submissions retreived', $submissions, 200);
    }

   
    public function acceptSubmissions($request){
        
        if (!auth()->user()->hasRole('admin')){
            return response()->json(['message'=>'UnAuthorized'], 403);
        }

        $id = $request['id'];
        $submission = StudentSubmission::findOrFail($id);
        $submission->update(['status' => 'Accepted']);

        $user = User::create([
            'name' => $submission->name,
            'email' => $submission->email,
            'password' => bcrypt(Str::random(16)),
        ]);
        $user->assignRole('student');

        $token = Str::random(60);

        PasswordReset::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);
        Mail::to($submission->email)->queue(new StudentSubmissionAccepted($submission, $token));
        
        return ResponseHelper::successResponse('Your request has been approved please check your mail', null, 200);
    }
    
    public function rejectSubmissions($request){
        
        if (!auth()->user()->hasRole('admin')){
            return response()->json(['message'=>'UnAuthorized'], 403);
        }
        $id = $request['id'];
        $submission = StudentSubmission::findOrFail($id);

        $submission->update(['status' => 'Rejected']);
        Mail::to($submission->email)->queue(new StudentSubmissionRejected($submission));

        return ResponseHelper::successResponse('Rejection mail has been sent to the user', null, 200);
    }

    /**
     * Assign a role to an existing user
     */
    public function changeRole($id, $role){
        if(!auth()->user()->hasrole('admin')){
            return ResponseHelper::errorResponse('UnAuthorized', 403);
        }

        $user = User::findOrFail($id);
        $user->syncRoles([$role]);

        return ResponseHelper::successResponse('Role changed successfully', null, 200);
    }

    /**
     * Create quizzes
     */

     public function createQuiz($data){
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz = Quiz::create([
            'quiz_name' => $data['quiz_name'],
            'duration' => $data['duration'],
            'total_marks' => $data['total_marks']
        ]);

        foreach($data['questions'] as $questionData){
            $quiz->questions()->create([
                'question' => $questionData['question'],
                'options' => json_encode($questionData['options']),
                'correct_answer' => $questionData['correct_answer'],
                'selected_answer' => $questionData['selected_answer'] ?? null,
            ]);
        }
        return response()->json(['message' => 'Quiz created successfully']);
     }


     /**
      * Delete Quiz
      */
      public function deleteQuiz($quiz_id){
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quiz = Quiz::findOrFail($quiz_id);
        $quiz->delete();

        return ResponseHelper::successResponse('Quiz soft deleted successfully', null, 200);
      }

      /**
       * Update Quiz
       */
      public function updateQuiz($quiz_id, $data){
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $quiz = Quiz::findOrFail($quiz_id);
        // dd($quiz);
        $quiz->update($data);

        return ResponseHelper::successResponse('Quiz updated successfully', $quiz, 200);
      }

      /**
       * Get the users based on role
       */
      public function getUsersByRole($specificRole = null){
        $data = User::role(['admin', 'manager', 'supervisor'])->select('id', 'name', 'email');

        if($specificRole){
            $data->role($specificRole);
        }

        $data = $data->paginate(5);
        return ResponseHelper::successResponse('Users fetched successfully.', $data , 200);

      }

}