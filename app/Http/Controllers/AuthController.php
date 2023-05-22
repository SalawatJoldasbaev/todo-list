<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Category;
use App\Models\User;
use App\Src\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken($request->header('User-Agent'))->plainTextToken;
        Category::create([
            'user_id' => $user->id,
            'name' => 'Main category'
        ]);
        return Response::success(payload: [
            'name' => $user->name,
            'phone' => $user->phone,
            'token' => $token,
        ]);
    }

    public function login(AuthRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user or !Hash::check($request->password, $user->password)) {
            return Response::error('phone or password is incorrect', 401);
        }
        $token = $user->createToken($request->header('User-Agent'))->plainTextToken;
        return Response::success(payload: [
            'name' => $user->name,
            'phone' => $user->phone,
            'token' => $token,
        ]);
    }
}
