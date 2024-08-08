<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvisoResource extends JsonResource
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
            'read' => $this->visto,
            'idUsuario' => $this->idUsuario,
            'idEvento' => $this->idEvento,
            'idReservacion' => $this->idReservacion,
            "type" => new CatalogoResource($this->tipoAviso),
            "forOrganizer" => $this->idUsuario === null,
            
            'event' => new EventoResourceLight($this->whenLoaded("evento")),
            'reservation' => new ReservacionResource($this->whenLoaded("reservacion")),

        ];
    }
}
