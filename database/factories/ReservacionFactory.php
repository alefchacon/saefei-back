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
        $year = 2024; // Specify the year
        $month = 12; // Specify the month
        
        // Create the start and end dates for the given month and year
        $startDate = "$year-$month-01";
        $endDate = "$year-$month-" . cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        // Generate a random date within the specified range
        $randomDate = $this->faker->dateTimeBetween($startDate, $endDate);
        $end = $randomDate->modify('+1 hour');
        return [
            "respuesta" => $this->faker->word(),
            "motivo" => $this->faker->word(),
            "fecha" => $randomDate,
            "inicio" => $randomDate,
            "fin" => $end,
            "idUsuario" => 1,  
            "idEspacio" => $this->faker->numberBetween(1,4),
            "idEvento" => $this->faker->numberBetween(1,4), 
            "idEstado" =>  2, 
        ];
    }
}
