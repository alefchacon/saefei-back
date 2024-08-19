<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthCustom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header("authorization")){
            return response()->json(["message" => config('global.forbidden')], 403);
        }
        
        $user = User::findByToken($request);
        
        if (!$user) {
            return response()->json(["message" => config('global.forbidden')], 403);
        }
        
        $latestToken =  \DB::select(
            'SELECT * 
             FROM personal_access_tokens 
             WHERE tokenable_id = :id
             ORDER BY expires_at
             DESC LIMIT 1', 
             ["id" => $user->id]
        )[0];
        
        $rightNow = new \DateTime();
        $expiresAt = new \DateTime($latestToken->expires_at);
        
        if ($expiresAt < $rightNow){
            \DB::delete(
                'DELETE 
                 FROM personal_access_tokens 
                 WHERE tokenable_id = :id', 
                 ['id' => $user->id]
            );
            return response()->json(["message" => config('global.unauthorized')], 401);
        }

        return $next($request);
    }
}
