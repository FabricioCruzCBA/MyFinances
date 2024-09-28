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
        Schema::create('movimentacaoinvestimentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('investimento_id')->constrained();
            $table->foreignId('familia_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->constrained();
            $table->string('TipoMovimentacaoInvestimento',1);
            $table->date('DataMovimentacaoInvestimento');
            $table->double('ValorMovimentacaoInvestimento',11,2);
            $table->string('ObsMovimentacaoInvestimento')->nullable()->default(null);
            $table->string('AtivoMovimentacaoInvestimento',1)->default('1');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacaoinvestimentos');
    }
};
