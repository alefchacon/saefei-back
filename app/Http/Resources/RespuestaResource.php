<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RespuestaResource extends JsonResource
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
            'notes' => $this->observaciones,
            'organizerRead' => $this->vistoOrganizador,
            'staffRead' => $this->vistoStaff,
            'idEstado' => $this->idEstado,

            'event' => new EventoResource($this->whenLoaded('evento')),
            'reservation' => new ReservacionResource($this->whenLoaded('reservacion')),

        ];
    }
}
