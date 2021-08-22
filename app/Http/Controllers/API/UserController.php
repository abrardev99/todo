<?php

namespace App\Http\Controllers\API;

class UserController
{
    public function me()
    {
        return auth()->user();
    }
}
