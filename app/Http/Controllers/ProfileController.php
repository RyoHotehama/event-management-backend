<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Exceptions\CustomException;
use App\Http\Requests\ProfileDetailRequest;
use App\Http\Requests\ProfileListRequest;
use App\Http\Requests\ProfileRequest;
use App\Services\PasswordService;
use App\Services\ProfileService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    private $passwordService;
    private $userService;
    private $profileService;

    public function __construct(PasswordService $passwordService, UserService $userService, ProfileService $profileService)
    {
        $this->passwordService = $passwordService;
        $this->userService = $userService;
        $this->profileService = $profileService;
    }

    /**
     * ユーザー登録
     *
     * @param ProfileRequest $request
     * @return JsonResponse
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

        try {
            DB::beginTransaction();

            // ユーザー登録
            $userId = $this->userService->createUser($userData);

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

    /**
     * ユーザー一覧取得
     *
     * @param ProfileListRequest $request
     * @return JsonResponse
     */
    public function list(ProfileListRequest $request)
    {
        $page = $request->page;
        $search = $request->search;
        $role = $request->role;

        if (!$page) {
            $page = Common::DEFAULT_PAGE;
        }

        $profileList = $this->profileService->getProfileList($page, $search, $role);

        return response()->json(['profileList' => $profileList]);
    }

    /**
     * ユーザー詳細取得
     *
     * @param ProfileDetailRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function detail(ProfileDetailRequest $request, $id)
    {
        $profileDetail = $this->profileService->getProfileDetail($id);

        return response()->json(['profileDetail' => $profileDetail]);
    }
}
