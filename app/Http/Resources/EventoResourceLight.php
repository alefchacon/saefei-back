<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoResourceLight extends JsonResource
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
            'createdAt' => $this->created_at,     
            'notes' => $this->observaciones,     
            'idEstado' => $this->idEstado,     

            'start' => $this->inicio,
            'end' => $this->fin,

            'user' => new UserResource($this->usuario),
            'status' => new CatalogoResource($this->estado),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),    
        ];
    }
}
