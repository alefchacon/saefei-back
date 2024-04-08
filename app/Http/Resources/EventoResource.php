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
            #'usuario' => $this->postal_code,
            #'modalidad' => $this->postal_code,
            #'estado' => $this->postal_code,
            #'tipo' => $this->postal_code,
            'evaluation' => new EvaluacionResource($this->whenLoaded('evaluacion')),
            'status' => new EstadoResource($this->whenLoaded('estado')),
            'idTipo' => $this->idTipo,
            
        ];
    }
}
