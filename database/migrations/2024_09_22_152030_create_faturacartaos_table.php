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
        Schema::create('faturacartaos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('cartaocredito_id')->constrained();
            $table->foreignId('familia_id')->constrained();
            $table->double('ValorFatura',11,2);
            $table->date('DataVencimento');
            $table->string('MesFatura');
            $table->string('StatusFatura')->default('Fechada');
            $table->string('AtivoFatura')->default('1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturacartaos');
    }
};
