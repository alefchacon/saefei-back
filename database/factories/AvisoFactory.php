<?php

namespace Database\Factories;

use App\Models\Aviso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluacion>
 */
class AvisoFactory extends Factory
{
    protected $model = Aviso::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "visto" => 0,
            "idEvento" => null,
            "idReservacion" => null,
            "idTipoAviso" => 1,
            "idEstado" => $this->faker->randomElement([1,2,3,4]),
            "idUsuario" => 3
        ];
    }
}
