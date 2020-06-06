<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

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

    public function sendResetToken(Request $request)
    {   
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email',$request->email)->first();
        $token = Str::random(40);
        $user->update(['reset_token' => $token]);
        //kirim token via email sebagai otentikasi kepemilikan
        Mail::to($user->email)->send(new ResetPasswordMail($user));
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $user->reset_token
        ]);
    }

    public function verifyResetPassword(Request $request, $token)
    {
        $this->validate($request, [
            'password' => 'required|min:5'
        ]);

        $user = User::where('reset_token',$token)->first();
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            return response()->json([
                'success' => true,
                'message' => 'Success'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Error'
        ]);
    }

}
