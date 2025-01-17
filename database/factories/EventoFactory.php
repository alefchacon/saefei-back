<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\Respuesta;

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
        $start = $this->faker->dateTime();
        $end = $start->modify('+1 day');
        $audiences = ["Estudiantes", "Académicos", "Personal Administrativo", "Público en general"]; 
        $scopes = ["Local/Regional","Estatal","Nacional","Internacional"];
        $axis = ["Derechos Humanos", "Sustentabilidad","Docencia e innovación académica","Investigación e innovación", "Difusión de la cultura y extensión de los servicios"];
        $themes = [
            "Biodeversidad e integridad ecosistémica", 
            "Calidad ambiental y gestión de campus",
            "Cultura de paz/Erradicación de la violencia/Integridad Académica",
            "Difusión de la oferta educativa",
            "Derechos Humanos",
            "Disciplinar",
            "Estilos de vida y patrones de consumo",
            "Equidad de género y diversidad sexual",
            "Interculturalidad",
            "Salud y deporte",
            "Consciencia ecológica",
            "Inclusión y no discriminación",
        ];

        return [
            "nombre" => $nombre,
            "descripcion" => $nombre,
            "pagina" => "uv.mx/fei",
            "ambito" => $this->faker->randomElement($scopes),
            "audiencias" => $this->faker->randomElement($audiences),
            "eje" => $this->faker->randomElement($axis),
            "tematicas" => $this->faker->randomElement($themes),

            "numParticipantes" => $this->faker->numberBetween(1,100),

            "constancias" => $this->faker->paragraph(1),
            "requisitosCentroComputo" => $this->faker->paragraph(1),
            "requiereTransmisionEnVivo" => $this->faker->boolean(),
            "decoracion" => $this->faker->paragraph(1),
            "numParticipantesExternos" => $this->faker->numberBetween(1,100),
            "requiereEstacionamiento" => $this->faker->boolean(),
            "requiereFinDeSemana" => $this->faker->boolean(),
            
            "medios" => '["Radio UV"]',
                        
            "idUsuario" => $this->faker->numberBetween(1,3),
            
            "idModalidad" => 1,
            "idTipo" => 1,
            "idEstado" => $this->faker->numberBetween(1,4),
        ];
    }
}
