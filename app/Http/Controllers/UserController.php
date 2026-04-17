<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getUser($phone)
    {
        $user = User::where('phone_number', $phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'phone_number' => $user->phone_number,
            'pulsa' => $user->pulsa ?? 0,
            'quota' => $user->quota ?? 0,
        ]);
    }
}