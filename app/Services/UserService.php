<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;

class UserService
{
    /**
     * ユーザーを作成
     *
     * @param array $userData
     * @return int
     */
    public function createUser($userData)
    {
        $user = User::create($userData);

        return $user->id;
    }
}
