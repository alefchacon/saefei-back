<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluacionResource extends JsonResource
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
            'ratingAttention' => $this->calificacionAtencion,
            'ratingAttentionReason' => $this->razonCalificacionAtencion,
            'ratingCommunication' => $this->calificacionComunicacion,
            'improvementsSupport' => $this->mejorasApoyo,
            'ratingSpace'=> $this->calificacionEspacio,
            'problemsSpace' => $this->problemasEspacio,
            'ratingComputerCenter' => $this->calificacionCentroComputo,
            'ratingComputerCenterReason' => $this->razonCalificacionCentroComputo,
            'ratingResources' => $this->calificacionRecursos,
            'ratingResourcesReason' => $this->razonCalificacionRecursos,
            'improvementsResources' => $this->mejorasRecursos,
            'additional' => $this->adicional,
            
            'idEvento' => $this->idEvento,
            
            'evidences' => new EvidenciaCollection($this->whenLoaded('evidencias')),

        ];
    }
}
