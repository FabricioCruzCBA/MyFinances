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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();

            $table->string('NomeUsuario');
            $table->string('EmailUsuario');
            $table->string('SenhaUsuario');
            $table->char('VerificacaoEmailUsuario',1)->default('0');
            $table->string('ImgUsuario')->nullable();
            $table->date('ValidadeSenhaUsuario');
            $table->char('AtivoUsuario',1)->default('1'); 
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
