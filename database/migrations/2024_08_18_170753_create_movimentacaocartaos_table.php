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
        Schema::create('movimentacaocartaos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('familia_id')->constrained();
            $table->foreignId('cartaocredito_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->constrained();
            $table->date('DataMovimentacaoCartao');
            $table->double('ValorMovimentacaoCartao',11,2);
            $table->string('AtivoMovimentacaoCartao',1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacaocartaos');
    }
};
