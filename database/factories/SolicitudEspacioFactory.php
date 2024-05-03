<?php

namespace Database\Factories;

use App\Models\SolicitudEspacio;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SolicitudEspacio>
 */
class SolicitudEspacioFactory extends Factory
{
    protected $model = SolicitudEspacio::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTime();
        $end = $start->modify('+1 hour');
        return [
            "respuesta" => $this->faker->word(),
            "inicio" => $start,
            "fin" => $end,
            "idUsuario" => 1,  
            "idEspacio" => $this->faker->numberBetween(1,4),
            "idEstado" => $this->faker->numberBetween(1,4),
            "idEvento" => $this->faker->numberBetween(1,4), 
        ];
    }
}
