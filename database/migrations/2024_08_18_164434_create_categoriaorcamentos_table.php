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
        Schema::create('categoriaorcamentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('familia_id')->constrained();
            $table->foreignId('orcamento_id')->constrained();
            $table->foreignId('categoria_id')->constrained();
            $table->double('ValorItemOrc',11,2);
            $table->string('AtivoCategoriaOrcamento',1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoriaorcamentos');
    }
};
