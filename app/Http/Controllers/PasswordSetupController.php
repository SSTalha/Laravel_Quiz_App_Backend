<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordSetupController extends Controller
{
    public function setPassword(SetPasswordRequest $request)
    {
        try {     
            $passwordReset = PasswordReset::where('token', $request->token)
                ->where('email', $request->email)
                ->first();
            

            if (!$passwordReset || Carbon::parse($passwordReset->expires_at)->isPast()) {
                return ResponseHelper::errorResponse('This password reset token is invalid or has expired.', 400);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $user->update(['password' => Hash::make($request->password)]);

            $passwordReset->delete();
            // dd('Password updated successfully');

            return ResponseHelper::successResponse('Password has been successfully updated.', null, 200);

        } catch (\Exception $e) {
            return ResponseHelper::errorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

}
