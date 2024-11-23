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
        return [
            'id' => $this->id,
            'response' => $this->respuesta,
            'date' => $this->fecha,
            'start' => $this->inicio,
            'end' => $this->fin,
            'motive' => $this->motivo,
            'idEstado' => $this->idEstado,
            
            'wasAccepted' => $this->idEstado === 2 || $this->idEstado === 3,
            'user' => new UserResource($this->whenLoaded('usuario')),
            'space' => new EspacioResource($this->whenLoaded('espacio')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),
            'activities' => new ActividadCollection($this->whenLoaded(
                'actividades', function () {
                    return $this->actividades->sortBy('hora');
                }
            )),
            'startEvent' => $this->actividades->isNotEmpty() 
                ? $this->actividades->sortBy('hora')->first()->hora 
                : null,

            'endEvent' => $this->actividades->isNotEmpty() 
                ? $this->actividades->sortBy('hora')->last()->hora 
                : null,

        ];
    }
}
