<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Requests\AcademicYears\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYears\UpdateAcademicYearRequest;
use App\Http\Requests\Users\GenerateVerificationCodeRequest;
use App\Http\Requests\Users\PasswordResetRequest;
use App\Http\Requests\Users\PasswordResetTokenGenerateRequest;
use App\Http\Requests\Users\VerifyUserRequest;
use App\Models\File;
use App\Models\PasswordReset;
use App\Models\School;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class UsersController extends Controller
{
    public function generateVerificationCode(GenerateVerificationCodeRequest $request){
        $user = Auth::user();
        $user = User::findOrFail($user->id);
        $user->verificationCodes()->delete();

        $expiration = now()->addMinutes(5);
        
        $verificationCode = new VerificationCode([
            'expires_at' => $expiration,
        ]);

        $verificationCode->generateCode();
        
        $user->verificationCodes()->save($verificationCode);

        if (env('ENABLE_SMS') == true) {
            sendSMS(
                "Boofey - Your verification code is: {$verificationCode->code}",
                $user->phone
            );
        }

        $response = [
            'status' => 'success',
        ];

        if (env('ENABLE_SMS') == false) {
            $response['data'] = [
                'verificationCode' => $verificationCode->code
            ];
        }

        return response()->json($response);
    }

    public function verify(VerifyUserRequest $request){
        $user = Auth::user();
        $user = User::findOrFail($user->id);

        $verificationCode = $user->verificationCodes()
            ->where('code', $request->input('verification_code'))
            ->where('expires_at', '>=', now())
            ->first();
        
        if ($verificationCode) {
            $verificationCode->delete(); 
            $user->phone_verified_at = now();
            $user->save();

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'verification_code' => [
                        __('translations.invalid_verification_code')
                    ]
                ],
            ], 422);
        }
    }

    public function generatePasswordResetToken(PasswordResetTokenGenerateRequest $request) {
        $user = Auth::user();

        if($user !== null){
            return response()->json([
                'status' => 'error',
                'error' => 'You are already logged in!'  
            ], 400);
        }

        $passwordReset = PasswordReset::where('phone', $request->input('phone'))->first();

        if($passwordReset != null)
            $passwordReset->delete();

        $expiration = now()->addMinutes(5);
        
        $passwordReset = new PasswordReset([
            'phone' => $request->input('phone'),
            'expires_at' => $expiration,
        ]);

        $passwordReset->generateToken();
        $passwordReset->save();

        if (env('ENABLE_SMS') == true) {
            sendSMS(
                "Boofey - Your password reset token is: {$passwordReset->token}",
                $request->input('phone')
            );
        }

        $response = [
            'status' => 'success',
        ];

        if (env('ENABLE_SMS') == false) {
            $response['data'] = [
                'passwordReset' => $passwordReset->token
            ];
        }

        return response()->json($response);
    }

    public function passwordReset(PasswordResetRequest $request){
        $user = Auth::user();

        if($user !== null){
            return response()->json([
                'status' => 'error',
                'error' => 'You are already logged in!'  
            ], 400);
        }
        
        $passwordReset = PasswordReset::where([
            'phone' => $request->input('phone'),
            'token' => $request->input('token')
        ])->where('expires_at', '>=', now())
        ->first();

        $user = User::where('phone', $request->input('phone'))->first();

        if ($passwordReset && $user) {
            $passwordReset->delete(); 
            
            $user->update([
                'password' => $request->input('password'),
            ]);

            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'token' => [
                        __('translations.invalid_password_reset_token')
                    ]
                ],
            ], 422);
        }
    }
}