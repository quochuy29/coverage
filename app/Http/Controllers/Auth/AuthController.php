<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Member::where([
            ['member_login_name', '=', $request->member_login_name],
        ])->first();

        if (!$user || !Hash::check($request->password, $user->member_password)) {
            return response()->json([
                'message' => 'Incorrect login account information!'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'access_token' => $user->createToken('accessToken')->plainTextToken,
            'auth_user' => $user,
        ], Response::HTTP_OK);
    }
}
