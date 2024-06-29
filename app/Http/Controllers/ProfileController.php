<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\ProfileRequest;
use App\Services\PasswordService;
use App\Services\ProfileService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    private $passwordService;
    private $profileService;

    public function __construct(PasswordService $passwordService, ProfileService $profileService)
    {
        $this->passwordService = $passwordService;
        $this->profileService = $profileService;
    }

    /**
     * ユーザー登録
     *
     * @param ProfileRequest $request
     * @return
     */
    public function create(ProfileRequest $request)
    {
        // 8桁のパスワードをランダムで作成
        $passwordLength = 8;
        $password = $this->passwordService->generateRandomPassword($passwordLength);

        // ユーザー情報
        $userData = [
            'email' => $request->email,
            'password' => $this->passwordService->hashPassword($password),
            'role' => $request->role,
        ];

        // プロフィール情報
        $profileData = [
            'last_name' => $request->lastName,
            'first_name' => $request->firstName,
        ];

        DB::beginTransaction();
        try {
            // ユーザー登録
            $userId = $this->profileService->createUser($userData);

            $profileData['user_id'] = $userId;

            // プロフィール登録
            $this->profileService->createProfile($profileData);

            // todo メール送信

            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();

            throw new CustomException('ユーザーの作成に失敗しました。', 500);
        }

        return response()->json(['userId' => $userId]);
    }
}
