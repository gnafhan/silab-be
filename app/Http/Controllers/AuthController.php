<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        // Validasi input
        $request->validate([
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@mail.ugm.ac.id')) {
                        $fail('Email harus menggunakan domain @mail.ugm.ac.id.');
                    }
                },
            ],            'username' => 'required|min:1|max:255',
            'first_name' => 'required|min:1|max:255',
            'last_name' => 'required|min:1|max:255',
            'role' => 'required',
            'password' => 'required',
        ]);

        $hashedPassword  = Hash::make($request->password);

        // Register user
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
            'password' => $hashedPassword,
        ]);

        // Response, create token
        return response()->json([
            'message' => 'User berhasil mendaftar',
            'token' => $user->createToken('user_login')->plainTextToken
        ], 201);
    }
    
    public function login(Request $request){
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@mail.ugm.ac.id')) {
                        $fail('Email harus menggunakan domain @mail.ugm.ac.id.');
                    }
                },
            ],
            'password' => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        // Auth check
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if(!$user->email_verified_at){
            return response()->json(['message'=> "Email belum terferifikasi"]);
        }
     
        // Response, create token
        return response()->json([
            'message' => 'Berhasil login',
            'token' => $user->createToken('user_login')->plainTextToken,
            'data' => $user
        ]);
    }

    public function logout(Request $request){
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'User berhasil logout'], 201);
    }

    public function current(Request $request){
        return response()->json($request->user());

    }
    public function checkAuth(Request $request){
        return response()->json(Auth::user());
    }

    public function updateProfil(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
    
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|exists:users,email'
        ]);
    
        try {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username
            ]);
    
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}