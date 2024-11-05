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
            'reservations' => new ReservacionCollection($this->whenLoaded('reservaciones')),
            'chronogram' => new ArchivoResource($this->whenLoaded('archivos', function () {            
                return $this->archivos->where('idTipoArchivo', 1)->first();
            })),
            'programs' => new CatalogoCollection($this->whenLoaded("programasEducativos")),
            'audiences' => $this->audiencias,
            'type' => new CatalogoResource($this->whenLoaded('tipo')),
            'scope' => $this->ambito,
            'axis' => $this->eje,
            'themes' => $this->tematicas,
            'page' => $this->pagina,
            
            'media' => is_array($this->medios) ? $this->medios : json_decode($this->medios, true),
            'publicity' => new ArchivoCollection($this->whenLoaded('archivos', function () {            
                return $this->archivos->where('idTipoArchivo', 3);
            })),

            'records' => $this->constancias,
            'presidium' => $this->presidium,

            'decoration' => $this->decoracion,

            'computerCenterRequirements' => $this->requisitosCentroComputo,
            'needsLivestream' => $this->requiereTransmisionEnVivo,

            'numParticipantsExternal' => $this->numParticipantesExternos,
            'needsParking' => $this->requiereEstacionamiento,
            'needsWeekend' => $this->requiereFinDeSemana,
            
            'additional' => $this->adicional,

            'createdAt' => $this->created_at,     
            'reply' => $this->respuesta,     

            "idEstado" => $this->idEstado,
            "idUsuario" => $this->idUsuario,
            'start' => $this->inicio,
            'end' => $this->fin,

            'evaluation' => new EvaluacionResource($this->whenLoaded('evaluacion')),
            'user' => new UserResource($this->whenLoaded('usuario')),
            'status' => new CatalogoResource($this->whenLoaded('estado')),
            'mode' => new CatalogoResource($this->whenLoaded('modalidad')),     
            'evidences' => new ArchivoCollection($this->whenLoaded("evidencias")),

        
        ];
    }
}
