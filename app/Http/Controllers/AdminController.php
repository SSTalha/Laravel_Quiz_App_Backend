<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
 use \Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService){
        $this->adminService = $adminService;
    }

    public function viewSubmissions(){
        return $this->adminService->viewSubmissions();
    }

    public function acceptSubmissions(Request $request){
        // dd("working");
        return $this->adminService->acceptSubmissions($request);
    }

    public function rejectSubmissions(Request $request){
        return $this->adminService->rejectSubmissions($request);
    }

    public function changeRole(Request $request){
        $id = $request->input('id');
        $role = $request->input('role');
        return $this->adminService->changeRole($id, $role);
    }

    public function createQuiz(Request $request)
    {
        $data = $request->all();
        return $this->adminService->createQuiz($data);
    }

    public function deleteQuiz($quiz_id){
        return $this->adminService->deleteQuiz($quiz_id);
    }
    public function updateQuiz(Request $request, $quiz_id){
        return $this->adminService->updateQuiz($quiz_id,$request);
    }

    public function getUserByRole(Request $request){
        $specificRole = $request->input('role');
        return $this->adminService->getUsersByRole($specificRole);
    }
}
