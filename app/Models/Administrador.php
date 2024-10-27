<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrador extends Model
{
    protected $table = 'administradores';
    //use HasFactory;

    protected $fillable = [
        "nombre",
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_administradores', "idAdministrador", "idUsuario");
    }

    public function espacios(){
        return $this->hasMany(Espacio::class, 'idAdministrador', 'id');
    }
}
