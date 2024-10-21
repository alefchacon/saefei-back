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
        "idUsuario",
    ];

    public function usuario(){
        return $this->hasOne(User::class, 'idUsuario', 'id');
    }
}
