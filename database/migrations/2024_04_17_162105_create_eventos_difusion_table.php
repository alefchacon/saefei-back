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
        Schema::create('eventos_difusion', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idDifusion');

            $table->foreign('idEvento')->references('id')->on('eventos');
            $table->foreign('idDifusion')->references('id')->on('difusions');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_difusion');
    }
};
