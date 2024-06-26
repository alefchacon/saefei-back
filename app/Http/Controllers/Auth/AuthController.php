<?php
namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LDAPValidator;
use Carbon\Carbon;
use Config;

class AuthController extends Controller
{
    // Method for user login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->with('rol')->first();

        
        // #IMPORTANTE Esta línea forza el login: quitar para despliegue
        $validationResult =  [
            "status" => 200,
            "message" => "ESTE LOG IN FUE FALSIFICADO. Si ves este mensaje, eres dev y desactivaste la validación LDAP :D",
        ];

        // #IMPORTANTE descomentar esta para hacer el login bien:
        //$validationResult = LDAPValidator::validate($request);

        if (!$user || $validationResult['status'] != 200) {
            return response()->json([
                'message' => $validationResult['message'],
            ], 
                $validationResult['status']
            );
        }

        $token = $user->createToken('access_token', abilities: [$user->rol->nombre], expiresAt: now()->addMinutes(config('sanctum.expiration')))->plainTextToken;

        
        $response = [
            'message' => $validationResult['message'],
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return response()
            ->json($response, $validationResult['status']);
    }



    /**
     * Cierra sesión mediante el bearer token. La solicitud no necesita cuerpo, 
     * sólo el header Authorization con su respectivo bearer token activo.
     */
    public function logout()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'El usuario no estaba autenticado'], 401);
        }

        $user->currentAccessToken()->delete();

        $user->tokens()->delete();

        return response()->json(['message' => 'La sesión ha concluido.']);
    }

    public function debugOutput()
    {
    }
}

