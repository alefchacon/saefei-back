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
            'page' => $this->pagina,

            'audiences' => $this->audiencias,
            'scope' => $this->ambito,
            'axi' => $this->eje,
            'themes' => $this->tematicas,
            'platforms' => $this->plataformas,

            'description' => $this->descripcion,
            'numParticipants' => $this->numParticipantes,
            'computerCenterRequirements' => $this->requisitosCentroComputo,
            'numExternalParticipants' => $this->numParticipantesExternos,
            'needsParking' => $this->requiereEstacionamiento,
            'needsConductor' => $this->requiereMaestroDeObra,
            'needsNotifyUVPress' => $this->requiereNotificarPrensaUV,
            'needsWeekend' => $this->requiereFinDeSemana,
            "needsComputerCenterSupport" => $this->requiereApoyoCentroComputo,
            'additional' => $this->adicional,
            'response' => $this->respuesta,
            'createdAt' => $this->created_at,     
            'notifyCoordinator' => $this->avisarCoordinador,
            'notifyUser' => $this->avisarUsuario,
            'cronogram' => new ArchivoResource($this->whenLoaded('cronograma')),
            'publicity' => new ArchivoCollection($this->whenLoaded('publicidades')),

            'start' => $this->inicio,
            'end' => $this->fin,

            'hasEvaluation' => $this->evaluacion !== null,
            'evaluation' => new EvaluacionResource($this->whenLoaded('evaluacion')),
            'reservations' => new SolicitudEspacioCollection($this->whenLoaded('solicitudesEspacios')),
            'user' => new UserResource($this->whenLoaded('usuario')),
            'type' => new CatalogoResource($this->whenLoaded('tipo')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),     
            'programs' => new CatalogoCollection($this->whenLoaded("programasEducativos")),

        
        ];
    }
}
