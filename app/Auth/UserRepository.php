<?php

declare(strict_types=1);

namespace App\Auth;

class UserRepository implements \Auth0\Laravel\Contract\Auth\User\Repository
{
    public function fromSession(array $user): ?\Illuminate\Contracts\Auth\Authenticatable 
    {
        return new \App\Models\User([
            'id' => $user['sub'] ?? $user['user_id'] ?? null,
            'name' => $user['name'],
            'email' => $user['email'],
        ]);
    }

    public function fromAccessToken(array $user): ?\Illuminate\Contracts\Auth\Authenticatable 
    {
        return null;
    }
}