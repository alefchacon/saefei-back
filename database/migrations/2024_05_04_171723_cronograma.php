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
        Schema::create('cronogramas', function (Blueprint $table) {
            
            $table->id()->primary()->unsigned()->unique();
            $table->timestamps();
            $table->text("nombre");
            $table->text("tipo");

            $table->unsignedBigInteger('idEvento');

            $table->foreign('idEvento')->references('id')->on('eventos');

        });
        DB::statement("ALTER TABLE cronogramas ADD archivo MEDIUMBLOB");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias');
    }
};
