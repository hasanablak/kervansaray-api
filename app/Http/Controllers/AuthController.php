<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserToken;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if (Auth::attempt($credentials)) {
            $userId = User::select('id')->where('email', $request->email)->first();
            $token = UserToken::create([
                "usersQid"  =>  $userId->id,
                "token"     =>  Str::random(60)
            ]);

            return response([
                "status"    =>  "success",
                "token"     =>  $token->token,
                "id"        =>  $userId->id
            ]);
        } else {
            return response([
                "status"    =>  "error",
                "message"   =>  "E-posta ve şifre ile eşleşen kayıt bulunamadı"
            ]);
        }
    }
}
