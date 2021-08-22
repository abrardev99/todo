<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UserResource;

class UserController
{
    public function me(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
