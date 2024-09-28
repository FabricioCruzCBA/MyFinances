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
        Schema::create('movimentacaofinanceiras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('familia_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->constrained();
            $table->foreignId('banco_id')->constrained();
            $table->string('TipoMovimentacaoFinanc',1);
            $table->date('DataVencimentoMovimentacaoFinanc');
            $table->date('DataPagamentoMovimentacaoFinanc')->nullable()->default(null);
            $table->double('ValorMovimentacaoFinanc',11,2);
            $table->double('ValorPagoMovimentacaoFinanc',11,2)->nullable()->default(null);
            $table->string('ObsMovimentacaoFinanc')->nullable()->default(null);
            $table->string('PagoMovimentacaoFinanc',1)->default('0');
            $table->string('DividaMovimentacaoFinanc',1)->default('0');
            $table->string('QualDividaMovimentacaoFinanc')->nullable()->default(null);
            $table->string('MetaMovimentacaoFinanc',1)->default('0');
            $table->string('QualMetaMovimentacaoFinanc')->nullable()->default(null);
            $table->string('InvestimentoMovimentacaoFinanc',1)->default('0');
            $table->string('QualInvestimentoMovimentacaoFinanc')->nullable()->default(null);
            $table->string('AtivoMovimentacaoFinanc',1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacaofinanceiras');
    }
};
