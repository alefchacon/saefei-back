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
            'notifyUser' => $this->avisarUsuario,
            'notifyStaff' => $this->avisarStaff,
            'idUsuario' => $this->idUsuario,
            'idEvento' => $this->idEvento,
            'idSolicitudEspacio' => $this->idSolicitudEspacio,
            
            'event' => new EventoResource($this->whenLoaded("evento")),
            'reservation' => new SolicitudEspacioResource($this->whenLoaded("solicitudEspacio")),

        ];
    }
}
