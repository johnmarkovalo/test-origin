<?php

namespace App\Http\Controllers;

use App\Hospital;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Isolation;
use App\Occupant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        $validatedUsername = $request->validated()['username'];
        $validatedPassword = $request->validated()['password'];

        $user = User::where('username', $validatedUsername)->firstOrFail();

        if (Hash::check($validatedPassword, $user->password)) {
            $token = $user->createToken('LaravelPasswordGrantClient')->accessToken;
            if ($user->role == 'ADMINISTRATOR') {
                return response(["token" => $token, "user" => $user]);
            } elseif ($user->role == 'HOSPITAL') {
                $hospital = Hospital::where('user_id', $user->id)->first();
                return response(["token" => $token, "user" => $user, "hospital" => $hospital]);
            } elseif ($user->role == 'ISOLATION') {
                $isolation = Isolation::where('user_id', $user->id)->first();
                return response(["token" => $token, "user" => $user, "isolation" => $isolation]);
            } elseif ($user->role == 'OCCUPANT') {
                $occupant = Occupant::where('user_id', $user->id)->first();
                return response(["token" => $token, "user" => $user, "occupant" => $occupant]);
            }
        }

        return response('Invalid Credentials', 401);
    }

    public function logout()
    {
        $token = request()->user()->token();
        $token->revoke();
        return response(null, 204);
    }
}
