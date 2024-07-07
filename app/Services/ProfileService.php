<?php

namespace App\Services;

use App\Consts\Common;
use App\Models\Profile;
use App\Models\User;

class ProfileService
{
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

    /**
     * プロフィール一覧を取得
     *
     * @param int $page
     * @return void
     */
    public function getProfileList($page)
    {
        $perPage = Common::DEFAULT_PROFILES_PER_PAGE;

        return Profile::selectRaw("user_id, CONCAT(last_name, ' ', first_name) AS name,CASE role WHEN 0 THEN '一般ユーザー' WHEN 1 THEN '管理者' END AS role_name, DATE_FORMAT(profiles.created_at, '%Y/%m/%d') as create_date")
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->orderBy('profiles.created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page)
            ->toArray();
    }
}
