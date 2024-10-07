<?php

namespace App\Services;
use App\DTOs\RegisterDTO;
use App\Http\Requests\LoginRequest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Helpers\ResponseHelper;
use App\Mail\PasswordSetupMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PasswordReset;

class AuthService
{

    public function register(RegisterRequest $request){
        if (!Auth::user()->hasRole('admin')){
            return response()->json(['message'=>'UnAuthorized'], 403);
        }


        $dto = new RegisterDTO($request);

        $user = User::create($dto->toArray());
    
        $user->assignRole($dto->role);

        $token = Str::random(60);

        PasswordReset::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        Mail::to($user->email)->queue(new PasswordSetupMail($token, $user->email));

        return ResponseHelper::successResponse('User Registered Successfully', ['user' => $user], 201);
    }
    
    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)){
            return ResponseHelper::errorResponse('Invalid Credentials', 401);
        }
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        $access_token = $this->respondWithToken($token)['access_token'];
        return ResponseHelper::successResponse('Login Successfull', compact('access_token', 'role', 'permissions'),200);
    }

    public function authenticatedUser()
    {
        return ResponseHelper::successResponse('User data retreived', Auth::user(), 200);
    }

    public function logout()
    {
        Auth::logout();
        return ResponseHelper::successResponse('Successfully logged out', null, 200);
    }

    public function refresh()
    {
        $refreshedToken = $this->respondWithToken(Auth::refresh());
        return ResponseHelper::successResponse('Token Refreshed Successfully', $refreshedToken, 200);
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            // 'token_type' => 'Bearer',
            // 'expires_in' => auth()->factory()->getTTL() *60
        ];
    }

}