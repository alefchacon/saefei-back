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
        Schema::create('users_administradores', function (Blueprint $table) {
            //$table->id()->primary()->unsigned()->unique();
            $table->unsignedBigInteger("idAdministrador");
            $table->unsignedBigInteger("idUsuario");

            $table->foreign("idAdministrador")->references("id")->on("administradores");
            $table->foreign("idUsuario")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_administradores');
    }
};
