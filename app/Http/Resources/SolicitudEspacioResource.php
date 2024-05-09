<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudEspacioResource extends JsonResource
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
            'response' => $this->respuesta,
            'start' => $this->inicio,
            'end' => $this->fin,

            'user' => new UserResource($this->whenLoaded('usuario')),
            'space' => new EspacioResource($this->whenLoaded('espacio')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),

        ];
    }
}
