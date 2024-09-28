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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('familia_id')->constrained();
            $table->foreignId('usuario_id')->constrained();
            $table->string('Descricao');
            $table->timestamp('DataStart')->nullable();
            $table->timestamp('DataEnd')->nullable();
            $table->string('Obs')->default(null);
            $table->string('Tipo',1);
            $table->string('Ativo', 1)->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
