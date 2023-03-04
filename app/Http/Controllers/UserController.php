<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getMe(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
