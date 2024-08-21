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
        Schema::create('evidencias', function (Blueprint $table) {
            
            $table->id()->primary()->unsigned()->unique();
            $table->timestamps();
            $table->string("nombre");
            $table->string("tipo");

            $table->unsignedBigInteger('idEvaluacion');

            $table->foreign('idEvaluacion')->references('id')->on('evaluaciones');

        });
        DB::statement("ALTER TABLE evidencias ADD archivo MEDIUMBLOB");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencias');
    }
};
