<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class C_user extends Controller
{
    //
    //
    public function user(User $user)
    {
        // Mendapatkan semua user
        try {
            $users = $user->all();
            return response()->json($users);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            // Membuat token apabila berhasil lolos auth login
            $token = $user->createToken('token-name')->plainTextToken;
            return response()->json([
                'message'   => 'success',
                'user'      => $user,
                'token'     => $token,
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function register(Request $request)
    {
        $validation = $this->validator($request->all());
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }
        try {
            $users = new User;
            $users->name        = $request['name'];
            $users->email       = $request['email'];
            $users->password    = Hash::make($request['password']);
            // Menyimpan data
            $users->save();
            return response()->json([
                'message'   => 'success',
                'user'      => $users
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            // Menghapus token yang sedang digunakan
            $user->currentAccessToken()->delete();
            return response()->json([
                'message'   => 'success'
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function validator($data)
    {
        // Melakukan validasi request
        return Validator::make($data, [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8'
        ]);
    }
}
