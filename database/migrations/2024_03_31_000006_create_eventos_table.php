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
            $table->string("nombre", 1000)->nullable();
            $table->string("descripcion", 1000)->nullable();
            $table->integer("numParticipantes")->default(0)->nullable();
            $table->string("audiencias", 1000)->nullable();
            $table->string("ambito", 1000)->nullable();
            $table->string("eje", 1000)->nullable();
            $table->string("tematicas", 1000)->nullable();
            $table->string("pagina")->nullable()->default("uv.mx/fei");
            
            /*DIFUSION DEL EVENTO*/
            $table->string("medios", 1000)->nullable();

            $table->string("constancias", 1000)->nullable();
            
            $table->string("decoracion", 1000)->nullable();
            $table->string("presidium", 1000)->nullable();
            
            /*RECURSOS ADICIONALES*/
            $table->string("requisitosCentroComputo", 1000)->nullable();
            $table->boolean("requiereTransmisionEnVivo")->default(false)->nullable();

            $table->integer("numParticipantesExternos")->nullable()->default(0);
            $table->boolean("requiereEstacionamiento")->nullable()->default(false);
            $table->boolean("requiereFinDeSemana")->nullable()->default(false);
            
            /*OTROS COMENTARIOS O SOLICITUDES ESPECIALES*/
            $table->string("adicional", 1000)->nullable();
            $table->string("mediosNotificados", 1000)->nullable();
            $table->boolean("respondido")->nullable()->default(false);


            $table->string("respuesta", 3000)->nullable();

            
            $table->unsignedBigInteger("idEstado");
            $table->unsignedBigInteger("idUsuario");
            $table->unsignedBigInteger("idModalidad")->nullable();
            $table->unsignedBigInteger("idTipo")->nullable();

            $table->foreign("idEstado")->references("id")->on("estados");
            $table->foreign("idUsuario")->references("id")->on("users");
            $table->foreign("idModalidad")->references("id")->on("modalidads");
            $table->foreign("idTipo")->references("id")->on("tipos");
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
