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
        Schema::create('movimentacaodividas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('divida_id')->constrained();
            $table->foreignId('familia_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->constrained();
            $table->string('TipoMovimentacaoDivida',1);
            $table->date('DataMovimentacaoDivida');
            $table->double('ValorMovimentacaoDivida',11,2);
            $table->string('ObservacaoMovimentacaoDivida')->nullable()->default(null);
            $table->string('AtivoMovimentacaoDivida',1)->default('1');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacaodividas');
    }
};
