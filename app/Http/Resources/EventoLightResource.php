<?php

namespace App\Http\Resources;

use App\Models\ProgramaEducativo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoLightResource extends JsonResource
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
            'programs' => new CatalogoCollection($this->whenLoaded("programasEducativos")),
            'reservations' => new ReservacionCollection($this->whenLoaded("reservaciones")),
            'user' => new UserResource($this->usuario),
            'status' => new CatalogoResource($this->estado),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),    
        ];
    }
}
