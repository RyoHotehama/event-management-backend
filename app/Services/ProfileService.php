<?php

namespace App\Services;

use App\Consts\Common;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

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
     * @param string $search
     * @param int $role
     * @return void
     */
    public function getProfileList($page, $search, $role)
    {
        $perPage = Common::DEFAULT_PROFILES_PER_PAGE;

        $query = Profile::selectRaw("profiles.id, CONCAT(last_name, ' ', first_name) AS name,CASE role WHEN 0 THEN '一般ユーザー' WHEN 1 THEN '管理者' END AS role_name, DATE_FORMAT(profiles.created_at, '%Y/%m/%d') as create_date")
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->orderBy('profiles.created_at', 'desc');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(last_name, ' ', first_name)"), 'LIKE', "%{$search}%");
            });
        }

        if (!is_null($role)) {
            $query->where('role', $role);
        }

        return $query->where('delete_flg', '=', Common::DELETE_IS_NOT_FLG)->paginate($perPage, ['*'], 'page', $page)->toArray();
    }

    /**
     * プロフィール詳細を取得
     *
     * @param int $id
     * @return void
     */
    public function getProfileDetail($id)
    {
        return Profile::selectRaw("profiles.id, email, last_name, first_name, role, DATE_FORMAT(profiles.created_at, '%Y/%m/%d') as create_date")
        ->join('users', 'users.id', '=', 'profiles.user_id')
        ->where('profiles.id', '=', $id)
        ->where('delete_flg', '=', Common::DELETE_IS_NOT_FLG)
        ->first();
    }

    /**
     * プロフィール情報更新
     *
     * @param array $updateData
     * @param int $id
     * @return void
     */
    public function updateProfile($updateData, $id)
    {
        Profile::join('users', 'users.id', '=', 'profiles.user_id')
        ->where('profiles.id', '=', $id)
        ->update($updateData);
    }

    /**
     * ユーザー情報削除
     *
     * @param int $id
     * @return void
     */
    public function deleteProfile($id)
    {
        Profile::join('users', 'users.id', '=', 'profiles.user_id')
        ->where('profiles.id', '=', $id)
        ->update(['delete_flg' => Common::DELETE_FLG]);
    }
}
