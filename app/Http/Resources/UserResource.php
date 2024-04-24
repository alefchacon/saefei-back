<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'names' => $this->nombres,
            'paternalName' => $this->apellidoPaterno,
            'maternalName' => $this->apellidoMaterno,
            //'password' => $this->password,
            'email' => $this->email,
            'job' => $this->puesto,
            'rol' => new RolResource($this->whenLoaded('rol')),
        ];
    }
}
