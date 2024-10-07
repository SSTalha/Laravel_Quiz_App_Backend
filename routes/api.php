<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordSetupController;
use App\Http\Controllers\StudentSubmissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'log.request']], function(){

    Route::post('/login', [AuthController::class,'login']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/refresh',[AuthController::class,'refresh']);
    Route::get('/auth-user',[AuthController::class,'authenticatedUser']);
    Route::post('/user/set-password', [PasswordSetupController::class, 'setPassword']);
 
    Route::post('/submit', [StudentSubmissionController::class , 'submit']);

    Route::group(['middleware'=> ['jwt.auth']], function(){
        Route::post('/register/user',[AuthController::class,'registerUser'])->name('register');
        
        Route::get('/get-submissions', [AdminController::class, 'viewSubmissions']);
        Route::post('/accept/submissions', [AdminController::class, 'acceptSubmissions']);
        Route::post('/reject/submissions', [AdminController::class, 'rejectSubmissions']);
        Route::delete('/delete-quiz/{quiz_id}', [AdminController::class , 'deleteQuiz']);
        Route::put('/update-quiz/{quiz_id}', [AdminController::class, 'updateQuiz']);
        Route::post('/change-role', [AdminController::class, 'changeRole']);
        Route::get('/get-users-by-role', [AdminController::class, 'getUserByRole']);
        Route::post('/create/quiz', [AdminController::class, 'createQuiz']);
        
        Route::post('/assign/quizzes', [UserController::class, 'assignQuiz']);
        Route::get('/get-quiz', [UserController::class , 'getAllQuizzes']);
        Route::get('/get/quiz/questions/{quizId}', [UserController::class , 'getQuizQuestions']);
        Route::put('/edit-questions/{questionId}', [UserController::class, 'editQuestion']);
        Route::delete('/delete-question/{questionId}', [UserController::class, 'deleteQuestion']);

        Route::get('/get-students', [UserController::class, 'viewStudents']);
        Route::delete('/delete-user/{user_id}', [UserController::class , 'removeUser']);
        Route::put('/update-student/{user_id}',[UserController::class , 'updateStudentInfo']);
        Route::get('/get/student-assigned/quiz', [UserController::class, 'getUserAssignedQuiz']);

        Route::post('/submit/quiz/options', [StudentSubmissionController::class, 'submitQuizAnswers']);
        Route::post('/store-video',[StudentSubmissionController::class, 'storeVideo']);

    });

});