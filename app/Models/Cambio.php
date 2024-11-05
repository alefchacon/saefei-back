<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{
    use HasFactory;
    protected $table = 'cambios';
    public $timestamps = false;

    protected $fillable = [
        'columnas',
        'idUsuario',
        'idEvento'
    ];
    
    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }

}
