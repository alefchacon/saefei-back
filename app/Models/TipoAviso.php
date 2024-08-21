<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAviso extends Model
{
    use HasFactory;
    protected $table = 'tiposavisos';
    protected $fillable = [
        "nombre",
    ];

    public function avisos(){
        return $this->hasMany(Aviso::class, 'idTipoAviso', 'id');
    }
}
