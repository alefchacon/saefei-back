<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Enums\RolEnum;


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

    public function eventos() {
        return $this->hasMany( Evento::class, 'idUsuario', 'id');
    }
    public function reservaciones() {
        return $this->hasMany( Reservacion::class, 'idUsuario', 'id');
    }

    public function roles(){
        return $this->belongsToMany(Rol::class, "users_roles", "idUsuario", "idRol");
    }

    public function administradores(){
        return $this->belongsToMany(Administrador::class, 'users_administradores', "idUsuario", "idAdministrador");
    }
        
/*
    public function roles(){
        return $this->belongsToMany(Rol::class, 'users_roles');
    }
*/
    public static function getTokenFrom(string $header){
        $tokenParts = explode("|", $header);
        if (count($tokenParts) < 2) {
            return null;
        }
        $id = $tokenParts[0];
        $token = $tokenParts[1];
        return $token;
    }

    public static function findByToken(Request $request){

        $token = self::getTokenFrom($request->header("authorization"));
        $user = User::whereHas('tokens', function ($query) use ($token) {
            $query->where('token', hash('sha256', $token));
        })
        ->with('roles')
        ->first();


        return $user;
    }

    public function isCoordinator(){
        return in_array(
            needle: RolEnum::coordinador->value, 
            haystack: $this->getRoleIds(), 
            strict: false
        );
    }
    public function isAdministrator(){
        return in_array(
            needle: RolEnum::administrador->value, 
            haystack: $this->getRoleIds(), 
            strict: false
        );
    }
    public function isAdministrator2(){
        return $this->administradores()->get()->count() > 0;
    }
    public function isAdministratorOf(int $idEspacio){

        if (!$this->isAdministrator()){
            return false;
        }

        $espacio = Espacio::find($idEspacio);

        foreach($this->administradores()->get() as $admin){
            if ($admin->id === $espacio->idAdministrador){
                return true;
            }
        }

        return false;
    }


    public function getRoleIds(){
        return $this->roles->pluck('id')->toArray();
    }
}
