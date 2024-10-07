<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function viewStudents(){
        return $this->userService->viewStudents();
    }

    public function assignQuiz(Request $request)
    {
        $userIds = $request->input('user_ids'); 
        $quizId = $request->input('quiz_id');
        // dd($userId, $quizId);
        return $this->userService->assignQuiz($userIds, $quizId);
    }

    public function getAllQuizzes(){
        return $this->userService->getAllQuizzes();
    }

    public function getQuizQuestions($quizId){
        return $this->userService->getQuizQuestions($quizId);
    }

    public function getUserAssignedQuiz(Request $request){
        $authUserRole = auth()->user()->getRoleNames()->first();
        if ($authUserRole === 'student') {
            $userId = auth()->id();
        } else {
            $userId = $request->input('user_id');

            if (!$userId) {
                return ResponseHelper::errorResponse('User ID is required', 400);
            }
        }
        return $this->userService->getUserAssignedQuiz($userId);
    }

    public function removeUser($user_id){
        return $this->userService->removeUser($user_id);
    }

    public function updateStudentInfo(Request $request, $user_id){
        $data = $request->all();
        return $this->userService->updateStudentInfo($user_id,$data);
    }

    public function editQuestion(Request $request, $questionId){
        $data = $request->all();
        return $this->userService->editQuestion($questionId, $data);
    }

    public function deleteQuestion($questionId)
    {
        return $this->userService->deleteQuestion($questionId);
    }
}
