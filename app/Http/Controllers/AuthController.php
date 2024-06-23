<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'メールアドレスまたはパスワードが違います。'], 401);
        }

        $user = Auth::user();

        // ロールのチェック
        if ($user->role !== $request->role) {
            return response()->json(['message' => 'メールアドレスまたはパスワードが違います。'], 403);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
}
