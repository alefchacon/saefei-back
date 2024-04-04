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
            "razonCalificacionAtencion" => $this->faker->paragraph(),
            "calificacionComunicacion" => $this->faker->numberBetween(1, 5),
            "mejorasApoyo" => $this->faker->paragraph(),
            "calificacionEspacio" => $this->faker->numberBetween(1, 5),
            "problemasEspacio" => $this->faker->paragraph(),
            "calificacionCentroComputo" => $this->faker->numberBetween(1, 5),
            "razonCalificacionCentroComputo" => $this->faker->paragraph(),
            "calificacionRecursos" => $this->faker->numberBetween(1, 5),
            "razonCalificacionRecursos" => $this->faker->paragraph(),
            "problemasRecursos" => $this->faker->paragraph(),
            "mejorasRecursos" => $this->faker->paragraph(),
            "adicional" => $this->faker->paragraph(),
            "idEvento" => Evento::factory()
            
        ];
    }
}
