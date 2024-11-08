<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvidenciaResource extends JsonResource
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
            'file' => $this->archivo,
            'type' => $this->tipo,
            'name' => $this->nombre,
            'idEvaluacion' => $this->idEvaluacion
        ];
    }
}
