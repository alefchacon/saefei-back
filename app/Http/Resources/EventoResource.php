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
            'needsLivestream' => $this->requiereTransmisionEnVivo,
            'needsRecords' => $this->requiereConstancias,
            'needsWeekend' => $this->requiereFinDeSemana,
            "needsComputerCenterSupport" => $this->requiereApoyoCentroComputo,
            'additional' => $this->adicional,
            'createdAt' => $this->created_at,     
            'notes' => $this->observaciones,     
            'media' => $this->medios,
            'presidium' => $this->presidium,
            'decoration' => $this->decoracion,
            'speakers' => $this->ponientes,

            'wasAccepted' => $this->idEstado === 2 || $this->idEstado === 3,

            'cronogram' => new ArchivoResource($this->whenLoaded('cronograma')),
            'publicity' => new ArchivoCollection($this->whenLoaded('publicidades')),
            "idEstado" => $this->idEstado,
            "idUsuario" => $this->idUsuario,


            'start' => $this->inicio,
            'end' => $this->fin,

            'evaluation' => new EvaluacionResource($this->whenLoaded('evaluacion')),
            'reservations' => new ReservacionCollection($this->whenLoaded('reservaciones')),
            'user' => new UserResource($this->whenLoaded('usuario')),
            'type' => new CatalogoResource($this->whenLoaded('tipo')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),     
            'programs' => new CatalogoCollection($this->whenLoaded("programasEducativos")),
            'evidences' => new ArchivoCollection($this->whenLoaded("evidencias"))
        
        ];
    }
}
