<?php

namespace App\Http\Resources;

use App\Models\ProgramaEducativo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoCalendarioResource extends JsonResource
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
            'date' => $this->reservaciones->first()->fecha,
            'start' => $this->reservaciones->first()->actividades->sortBy("hora")->first()->hora,
            'end' => $this->reservaciones->first()->actividades->sortBy("hora")->last()->hora,
            'programs' => new CatalogoCollection($this->whenLoaded("programasEducativos")),
            'reservations' => new ReservacionCollection($this->whenLoaded("reservaciones")),
            'space' => new CatalogoResource($this->reservaciones[0]->espacio),
            'user' => new UserResource($this->usuario),
        ];
    }
}
