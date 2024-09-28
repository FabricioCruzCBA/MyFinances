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
        Schema::create('cartaocreditos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('familia_id')->constrained();
            $table->string('NomeCartao');
            $table->string('DataVencimentoCartao');
            $table->string('DataFechamentoCartao');
            $table->string('AtivoCartao',1)->default('1');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartaocreditos');
    }
};
