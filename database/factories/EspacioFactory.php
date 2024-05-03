<?php

namespace Database\Factories;

use App\Models\Espacio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluacion>
 */
class EspacioFactory extends Factory
{
    protected $model = Espacio::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nombre" => $this->faker->word(),            
            "descripcion" => $this->faker->word(),
            "estado" => $this->faker->randomElement(["disponible", "solicitado", "reservado"]),
                        
        ];
    }
}
