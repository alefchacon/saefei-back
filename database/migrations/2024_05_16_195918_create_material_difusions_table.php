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
        Schema::create('material_difusions', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvento');

            $table->foreign('idEvento')->references('id')->on('eventos');
        });
        DB::statement("ALTER TABLE material_difusions ADD archivo MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_difusions');
    }
};
