<?php

namespace App\Services;

use Laravel\Sanctum\NewAccessToken;

class TokenManager
{
    public function createToken($user, array $abilities = ['*']): NewAccessToken
    {
        return $user->createToken(config('app.name'), $abilities);
    }

    public function destroyTokens($user): void
    {
        $user->tokens()->delete();
    }
}
