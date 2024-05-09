<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
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
            'description' => $this->descripcion,
            'numParticipants' => $this->numParticipantes,
            'cronogram' => $this->cronograma,
            'computerCenterRequirements' => $this->requisitosCentroComputo,
            'numExternalParticipants' => $this->numParticipantesExternos,
            'needsParking' => $this->requiereEstacionamiento,
            'needsConductor' => $this->requiereMaestroDeObra,
            'needsNotifyUVPress' => $this->requiereNotificarPrensaUV,
            'additional' => $this->adicional,
            'response' => $this->respuesta,

            'start' => $this->inicio,
            'end' => $this->fin,

            'evaluation' => new EvaluacionResource($this->whenLoaded('evaluacion')),
            'reservations' => new SolicitudEspacioCollection($this->whenLoaded('solicitudesEspacios')),
            'user' => new UserResource($this->whenLoaded('usuario')),
            'type' => new CatalogoResource($this->whenLoaded('tipo')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),            
        ];
    }
}
