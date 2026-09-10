<?php

namespace App\Actions\Auth;

use App\Models\User;

class LogoutAction
{
    /**
     * Revoke the current user's personal access token.
     */
    public function execute(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
