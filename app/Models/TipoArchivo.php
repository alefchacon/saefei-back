<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoArchivo extends Model
{
    use HasFactory;
    protected $table = 'tiposarchivos';
    public $timestamps = false;

    protected $fillable = [
        "nombre",
    ];
}
