<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {

            $table->id()->primary()->unsigned()->unique();

            /*DETALLES DEL ORGANIZADOR*/
            /*DETALLES DEL EVENTO*/
            $table->string("nombre", 1000);
            $table->string("descripcion", 1000);
            $table->string("pagina")->nullable()->default("uv.mx/fei");
            $table->string("ambito", 1000);
            $table->string("audiencias", 1000);
            $table->string("eje", 1000);
            $table->string("tematicas", 1000);
            
            /*DETALLES LOGÍSTICOS*/
            $table->date("inicio");
            $table->date("fin");
            $table->integer("numParticipantes")->default(0);
            
            /*ESPACIOS FISICOS*/
            /*ESPACIOS VIRTUAL*/
            /*ESPACIOS FISICO Y VIRTUAL*/
            $table->string("plataformas", 1000)->nullable()->default(config('global.defaultString'));

            /*RECURSOS ADICIONALES*/
            $table->string("requisitosCentroComputo", 1000)->nullable()->default(config('global.defaultString'));
            $table->boolean("requiereTransmisionEnVivo")->default(false);
            $table->string("presidium", 1000)->nullable()->default(config('global.defaultString'));
            $table->string("decoracion", 1000)->nullable()->default(config('global.defaultString'));
            $table->integer("numParticipantesExternos")->nullable()->default(0);
            $table->boolean("requiereEstacionamiento")->default(false);
            $table->boolean("requiereFinDeSemana")->default(false);
            
            /*DIFUSION DEL EVENTO*/
            $table->string("medios", 1000);
            
            /*CONSTANCIAS*/
            $table->boolean("requiereConstancias")->default(false);
            $table->string("ponientes", 1000)->default(false);
            
            /*OTROS COMENTARIOS O SOLICITUDES ESPECIALES*/
            $table->string("adicional", 1000)->nullable()->default(config('global.defaultString'));


            $table->string("observaciones", 1000)->nullable()->default(config('global.defaultString'));

            
            $table->unsignedBigInteger("idEstado");
            $table->unsignedBigInteger("idUsuario");
            $table->unsignedBigInteger("idModalidad");
            $table->unsignedBigInteger("idTipo");
            $table->unsignedBigInteger("idPrograma");
            $table->unsignedBigInteger('idPlataforma');

            $table->foreign("idEstado")->references("id")->on("estados");
            $table->foreign("idUsuario")->references("id")->on("users");
            $table->foreign("idModalidad")->references("id")->on("modalidads");
            $table->foreign("idTipo")->references("id")->on("tipos");
            $table->foreign('idPrograma')->references("id")->on("programa_educativos");
            $table->foreign("idPlataforma")->references("id")->on("plataformas");
            $table->timestamps(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('eventos');
        
    }
};
