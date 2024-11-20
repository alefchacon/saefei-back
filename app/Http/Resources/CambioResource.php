<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CambioResource extends JsonResource
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
            'columns' => json_decode($this->columnas),
            'idEvento' => $this->idEvento,
            'idUsuario' => $this->idUsuario,
        ];
    }
}
