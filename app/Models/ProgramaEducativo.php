<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ProgramaEducativo extends Model
{
    use HasFactory;

    protected $table = 'programa_educativos';
    protected $fillable = [
        'nombre'
    ];

    public function eventos(): MorphToMany{
        return $this->belongsToMany(Evento::class, "eventos_programaeducativos", "idProgramaEducativo", "idEvento");
    }
}
