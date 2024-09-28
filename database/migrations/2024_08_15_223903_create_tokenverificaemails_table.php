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
        Schema::create('tokenverificaemails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->constrained();
            $table->string('TokenVerificacao');
            $table->string('Verificado')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokenverificaemails');
    }
};
