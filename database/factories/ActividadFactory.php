<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\Reservacion;
use App\Models\Respuesta;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservacion>
 */
class ActividadFactory extends Factory
{
    protected $model = Actividad::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nombre" => $this->faker->word(),
            "hora" => $this->faker->time(),
            "idReservacion" => Reservacion::factory()
        ];
    }
}
