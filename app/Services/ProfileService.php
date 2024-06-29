<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;

class ProfileService
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

    /**
     * プロフィールを作成
     *
     * @param array $profileData
     * @return void
     */
    public function createProfile($profileData)
    {
        Profile::create($profileData);
    }
}
