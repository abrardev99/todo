<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendEmailVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;

class VerificationController extends Controller
{
    public function verify(VerificationRequest $request): UserResource
    {
        $user = User::whereEmail($request->email)->first();

        if ($user->hasVerifiedEmail())
            return (new UserResource($user))
                ->additional([
                    'meta' => [
                        'message' => 'Email already verified'
                    ]
                ]);

        if ($user->verification_code == $request->code) {
            $user->markEmailAsVerified();
        }

        return (new UserResource($user))
            ->additional([
                'meta' => [
                    'message' => 'Email has been verified'
                ]
            ]);
    }

    public function sendVerificationEmail(Request $request): UserResource
    {
        $request->validate(['email' => 'required|email']);

        $user = User::whereEmail($request->email)->first();

        if ($user->hasVerifiedEmail())
            return (new UserResource($user))
                ->additional([
                    'meta' => [
                        'message' => 'Email already verified'
                    ]
                ]);

        $code = random_int(1000, 10000);
        $user->verification_code = $code;
        $user->save();

        Mail::to($user)->send(new SendEmailVerificationMail($code));

        return (new UserResource($user))
            ->additional(['meta' => [
                'message' => 'Verification email sent',
            ]]);
    }
}
