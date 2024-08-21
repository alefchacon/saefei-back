<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EspacioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        $reservations = new ReservacionCollection($this->whenLoaded('reservaciones'));
        return [
            'id' => $this->id,
            'name' => $this->nombre,
            'description' => $this->descripcion,
            'reservations' => $reservations,

        ];
    }
}
