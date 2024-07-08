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
            "avisarUsuario" => $this->faker->boolean(),
            "avisarStaff" => 0,
            "idUsuario" => 1,            
            "idEvento" => null,
            "idSolicitudEspacio" => null,
            
                        
        ];
    }
}
