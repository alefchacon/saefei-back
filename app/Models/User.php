<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'email',
        'puesto',
        'idRol'
        //'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol() {
        return $this->belongsTo(Rol::class, 'idRol', 'id');
    }

    public function eventos() {
        return $this->hasMany( Evento::class, 'idUsuario', 'id');
    }
    public function reservaciones() {
        return $this->hasMany( Reservacion::class, 'idUsuario', 'id');
    }

    public static function findByToken(Request $request){


        //Laravel regresa el token como <token ID>|<token>, pero para autenticar el token sólo se debe
        //user el <token>, entonces se remueve el <token ID> y el |.
                
                        
        $tokenParts = explode("|", $request->header("authorization"));
        if (count($tokenParts) < 2) {
            return response()->json([
                'message' => "Unauthenticated",
                
            ], 401);
        }

        $token = $tokenParts[1];

        $user = \DB::table("users")
            ->join("personal_access_tokens", "users.id", "=", "personal_access_tokens.tokenable_id")
            ->where("personal_access_tokens.token", "=", hash("sha256", $token))
            ->select(["users.*"])
            ->get()->first();


        return $user;
    }

}
