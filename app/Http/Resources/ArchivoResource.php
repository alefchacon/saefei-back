<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArchivoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            'id' => $this->id,
            'name' => $this->nombre,
            'type' => $this->tipo,
            'idEvento' => $this->idEvento,
            'idEvaluacion' => $this->idEvaluacion,
            'file' => $this->archivo,

        ];
    }
}
