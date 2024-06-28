<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
    /**
     * ランダムなパスワードを生成
     *
     * @param int $length
     * @return string
     */
    public function generateRandomPassword($length)
    {
        return Str::random($length);
    }

    /**
     * パスワードをハッシュ化
     *
     * @param string $password
     * @return string
     */
    public function hashPassword($password)
    {
        return Hash::make($password);
    }
}
