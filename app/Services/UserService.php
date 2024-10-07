<?php

namespace App\Services;
use App\Helpers\ResponseHelper;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAssignment;
use App\Models\User;
use Response;

class UserService
{
    
    /**
     * View all students (only for admin, manager, or supervisor)
     */
    public function viewStudents(){
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            return ResponseHelper::errorResponse('UnAuthorized', 403);
        }

        $data = User::role('student')->select('id', 'name', 'email')->paginate(5);
        return ResponseHelper::successResponse('Students fetched successfully.', $data , 200);
    }


     /**
     * Assign a quiz to a student (admin, manager, supervisor)
     */
    public function assignQuiz($userIds, $quizId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if(!is_array($userIds)) {
            $userIds = [$userIds];
        }
        // Assign quiz to the user
        foreach ($userIds as $userId) {
            QuizAssignment::create([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'start_time' => now()->addDays(2),
                'status' => 'assigned',
                'marks_obtained' => null,
                'end_time' => null,
                'attempt' => false
            ]);
        }

        return ResponseHelper::successResponse('Quiz assigned successfully',null , 200);
    }

     /**
     * Get all quizzes from the quiz table
     */
    public function getAllQuizzes()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = Quiz::all();
        return ResponseHelper::successResponse('Quizzes fetched successfully',$data , 200);
    }

    /**
     * Get the quiz questions and options
     */

     public function getQuizQuestions($quizId){
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor', 'student'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $quiz = Quiz::findOrFail($quizId);
        $questions = $quiz->questions()->select('id', 'question', 'options')->get()->map(function ($question){
            $question->options = json_decode($question->options);
            return $question;
        });

        return ResponseHelper::successResponse('Questions retrieved', $questions, 201);
     }

    /**
     * Get Assigned Quiz
     */

     public function getUserAssignedQuiz($userId){
        if (auth()->user()->hasRole('student')) {
            $data = QuizAssignment::with('quiz:id,quiz_name,duration,total_marks')
                ->where('user_id', $userId)
                ->get();

            return ResponseHelper::successResponse('Student assigned quizzes fetched', $data, 200);
        }
        $data = QuizAssignment::with('quiz:id,quiz_name,duration,total_marks')
            ->where('user_id', $userId)
            ->get();
        
        return ResponseHelper::successResponse('Assigned quizzes fetched', $data, 200);
     }

     /**
      * Remove Students
      */
      public function removeUser($user_id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $student = User::findOrFail($user_id);
        $student->delete();

        return ResponseHelper::successResponse('Student removed successfully', null, 200);
    }

    /**
     * Update student info
     */
    public function updateStudentInfo($user_id, $data){
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $student = User::findOrFail($user_id);
        $student->update($data);

        return ResponseHelper::successResponse('Student Info updated successfully',$student , 200);
    }

    /**
     * Edit quiz questions
     */
    public function editQuestion($questionId, $data)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $question = Question::findOrFail($questionId);
        $question->update($data);

        return ResponseHelper::successResponse('Question updated successfully', $question, 200);
    }

    /**
     * Delete questions
     */
    public function deleteQuestion($questionId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $question = Question::findOrFail($questionId);
        $question->delete();

        return ResponseHelper::successResponse('Question deleted successfully', null, 200);
    }

}