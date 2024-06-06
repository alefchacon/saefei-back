<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LDAPValidator;

class AuthController extends Controller
{
    // Method for user login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->with('rol')->first();

        $validationResult = LDAPValidator::validate($request);

        /*
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Las credenciales son incorrectas.'], 401);
        }*/

        if (!$user || $validationResult['status'] != 200) {
            return response()->json(
                ['message' => $validationResult['message']], 
                $validationResult['status']
            );
        }

        $response = [
            'message' => $validationResult['message'],
            'token' => $user->createToken('authToken', abilities: [$user->rol->nombre])->plainTextToken,
        ];

        return response()->json($response, $validationResult['status']);
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

    public function debugOutput()
    {
    }
}

