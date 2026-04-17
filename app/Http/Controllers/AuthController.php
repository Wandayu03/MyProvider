<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $otp = (string) rand(1000, 9999);

        $user = User::updateOrCreate(
            ['phone_number' => $request->phone_number],
            [
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'OTP generated',
            'otp' => $otp
        ]);
    }
    public function verifyOtp(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)
                    ->where('otp', (string) $request->otp)
                    ->first();

        // ❗ WAJIB: cek user dulu
        if (!$user) {
            return response()->json([
                'message' => 'OTP is incorrect'
            ], 401);
        }

        // cek otp_expires_at null
        if (!$user->otp_expires_at) {
            return response()->json([
                'message' => 'OTP not valid'
            ], 401);
        }

        // cek expired
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP already expired'
            ], 401);
        }

        // sukses login
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'message' => 'Login Success',
            'user' => $user
        ]);
    }
}
