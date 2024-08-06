<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startDateTime = new \DateTime($this->inicio);
        return [
            'id' => $this->id,
            'response' => $this->respuesta,
            'start' => $this->inicio,
            'startTime' => $startDateTime->format('H:i'),
            'end' => $this->fin,
            'idEstado' => $this->idEstado,

            'user' => new UserResource($this->whenLoaded('usuario')),
            'space' => new EspacioResource($this->whenLoaded('espacio')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),

        ];
    }
}
