<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
            'roles' => new RolCollection($this->whenLoaded('roles')),
        ];
    }
}
