<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email',$request->email)->first();
        if ($user && Hash::check($request->password,$user->password)) {
            $token = Str::random(40);
            $user->update(['api_token' => $token]);
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'error'
        ]);

    }

    public function sendResetToken (Request $request)
    {   
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email',$request->email)->first();
        $user->update(['reset_token' => Str::random(40)]);
        //kirim token via email sebagai otentikasi kepemilikan
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $user_token
        ]);
    }

}
