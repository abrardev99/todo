<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\SendEmailVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Mail;

class RegisterController extends Controller
{
    public function __invoke(RegisterUserRequest $request): UserResource
    {
        $code = random_int(1000, 10000);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $code
        ]);

        Mail::to($user)->send(new SendEmailVerificationMail($code));

        return (new UserResource($user))
            ->additional(['meta' => [
                'message' => 'User registered successfully. Please verify your email by entering code sent to your email.',
            ]]);
    }
}
