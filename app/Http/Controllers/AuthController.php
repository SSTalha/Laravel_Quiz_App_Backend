<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function registerUser(RegisterRequest $request){
        // dd('working');
        return $this->authService->register($request);
    }

    protected function respondWithToken($token)
    {
        return $this->authService->respondWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function authenticatedUser()
    {
        return $this->authService->authenticatedUser();
    }

    public function logout()
    {
        return $this->authService->logout();
    }

    public function refresh()
    {
        return $this->authService->refresh();
    }

}
