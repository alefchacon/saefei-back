<?php

namespace Database\Factories;

use App\Models\Reservacion;
use App\Models\Respuesta;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservacion>
 */
class ReservacionFactory extends Factory
{
    protected $model = Reservacion::class;
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
            "motivo" => $this->faker->word(),
            "fecha" => $start,
            "inicio" => $start,
            "fin" => $end,
            "idUsuario" => 1,  
            "idEspacio" => $this->faker->numberBetween(1,4),
            "idEvento" => $this->faker->numberBetween(1,4), 
            "idEstado" =>  $this->faker->numberBetween(1,4), 
        ];
    }
}
