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
        Schema::table('agendas', function (Blueprint $table) {
            //
            $table->string('Obs')->nullable()->default('Sem obs')->change();
            $table->string('Descricao')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            //
            $table->string('Descricao')->nullable(false)->change();
            $table->string('Obs')->nullable(false)->change();
            
        });
    }
};
