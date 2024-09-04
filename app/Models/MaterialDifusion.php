<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDifusion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "idEvento",
        "archivo"
    ];

    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }
   
}
