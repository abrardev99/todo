<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request): UserResource
    {
        if (! $token = auth()->attempt($request->validated())) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return (new UserResource(auth()->user()))
            ->additional(['meta' => [
                'token_type' => 'Bearer',
                'access_token' => $token,
            ]]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['meta' =>  ['message' => 'Successfully logged out'] ]);
    }
}
