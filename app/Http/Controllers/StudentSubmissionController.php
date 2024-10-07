<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentSubmissionRequest;
use App\Services\StudentSubmissionService;
use Illuminate\Http\Request;

class StudentSubmissionController extends Controller
{
    protected $studentService;

    public function __construct(StudentSubmissionService $studentService){
        $this->studentService = $studentService;
    }

    public function submit(StudentSubmissionRequest $request){
        return $this->studentService->submitForm($request);
    }

    public function submitQuizAnswers(Request $request){
        $quizId = $request->input('quiz_id');
        $userId = auth()->id();
        $options = $request->input('options');

        return $this->studentService->submitQuizAnswers($quizId, $userId, $options);
    }

    public function storeVideo(Request $request){
        return $this->studentService->storeVideo($request);
    }

}