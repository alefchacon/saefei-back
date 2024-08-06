<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rol>
 */
class RespuestaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'observaciones' => $this->faker->paragraph(1),
            'idEstado' => $this->faker->randomElement([1,2,4]),
            "vistoOrganizador" => $this->faker->boolean(),
            "vistoStaff" => $this->faker->boolean(),
        ];
    }
}
