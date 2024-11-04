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
        Schema::create('cambios', function (Blueprint $table) {
            $table->id()->primary()->unsigned()->unique();
            $table->string("columna");
            $table->unsignedBigInteger("idUsuario");
            $table->unsignedBigInteger("idEvento");

            $table->foreign("idUsuario")->references("id")->on("users");
            $table->foreign("idEvento")->references("id")->on("eventos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambios');
    }
};
