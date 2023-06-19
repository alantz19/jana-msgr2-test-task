<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;

class AuthService
{
    public static function isModelOwner($model, $user = null): bool|AuthorizationException
    {
        if (!$user) {
            $user = auth()->user();
        }

        if ($model->team_id !== $user->current_team_id) {
            throw new AuthorizationException('You are not authorized to access this resource.');
        }

        return true;
    }
}
