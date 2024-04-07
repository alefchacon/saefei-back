<?php

namespace Database\Factories;

use App\Models\Evento;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    protected $model = Evento::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $nombre = $this->faker->company();
        return [
            "nombre" => $nombre,
            "descripcion" => $nombre,
            "numParticipantes" => $this->faker->numberBetween(1,100),
            "requisitosCentroComputo" => $this->faker->paragraph(),
            "numParticipantesExternos" => $this->faker->numberBetween(1,100),
            "requiereEstacionamiento" => $this->faker->boolean(),
            "requiereFinDeSemana" => $this->faker->boolean(),
            "requiereMaestroDeObra" => $this->faker->boolean(),
            "requiereNotificarPrensaUV" => $this->faker->boolean(),
            "adicional" => $this->faker->paragraph(),
            "respuesta"=> null, 
            "idUsuario" => 1,  
            "idModalidad" => 1,
            "idEstado" => $this->faker->numberBetween(1,4),
            "idtipo" => 1,
        ];
    }
}
