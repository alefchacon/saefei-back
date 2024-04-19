<?php
namespace App\Http\Controllers;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Method for user login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->with('rol')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Las credenciales son incorrectas.'], 401);
        }

        $response = [
            'message' => 'Bienvenido',
            'token' => $user->createToken('authToken'),
            'user' => $user
        ];

        return json_encode($response);
    }
    
    // Method to handle user logout
    public function logout(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }
    
        $user->currentAccessToken()->delete();
    
        // Optionally, revoke all tokens...
        // $user->tokens()->delete();
    
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function debugOutput() {
    }
}

