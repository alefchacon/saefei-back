<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Evento;
use App\Models\Evaluacion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluacion>
 */
class EvaluacionFactory extends Factory
{
    protected $model = Evaluacion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "calificacionAtencion" => $this->faker->numberBetween(1, 5),
            "razonCalificacionAtencion" => $this->faker->sentence(),
            "calificacionComunicacion" => $this->faker->numberBetween(1, 5),
            "mejorasApoyo" => $this->faker->sentence(),
            "calificacionEspacio" => $this->faker->numberBetween(1, 5),
            "problemasEspacio" => $this->faker->sentence(),
            "calificacionCentroComputo" => $this->faker->numberBetween(1, 5),
            "razonCalificacionCentroComputo" => $this->faker->sentence(),
            "calificacionRecursos" => $this->faker->numberBetween(1, 5),
            "razonCalificacionRecursos" => $this->faker->sentence(),
            "problemasRecursos" => $this->faker->sentence(),
            "mejorasRecursos" => $this->faker->sentence(),
            "adicional" => $this->faker->sentence(),
            "idEvento" => Evento::factory()
            
        ];
    }
}
