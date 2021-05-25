<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if($user)
        {
            if(Hash::check($password, $user->password))
            {
                $user->api_token = Str::random(60);
                $user->save();

                return response(['token' => $user->api_token]);
            }

            return response(['error' => 'Password salah'], 401);
        }
        else
        {
            return response(['error' => 'E-mail tidak terdaftar'], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response(['success' => true]);
    }

    public function register(Request $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        // $user->api_token = Str::random(60);
        $user->save();

        return response(['success' => true]);
    }
}
