<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id()->primary()->unsigned()->unique();
            $table->string('observaciones', 1000)->default(config('global.defaultString'));
            $table->tinyInteger("idTipoRespuesta")->default(0);
            $table->boolean("vistoOrganizador")->default(0);
            $table->boolean("vistoStaff")->default(0);

            $table->unsignedBigInteger("idEstado");
            $table->foreign("idEstado")->references("id")->on("estados");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
