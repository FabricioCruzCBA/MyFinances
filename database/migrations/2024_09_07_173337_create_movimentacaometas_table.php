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
        Schema::create('movimentacaometas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meta_id')->constrained();
            $table->foreignId('familia_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->foreignId('subcategoria_id')->constrained();
            $table->string('TipoMovimentacaoMeta',1);
            $table->date('DataMovimentacaoMeta');
            $table->double('ValorMovimentacaoMeta',11,2);
            $table->string('ObsMovimentacaoMeta')->nullable()->default(null);
            $table->string('AtivoMovimentacaoMeta',1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacaometas');
    }
};
