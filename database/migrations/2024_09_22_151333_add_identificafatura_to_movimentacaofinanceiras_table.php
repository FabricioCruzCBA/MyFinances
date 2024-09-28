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
        Schema::table('movimentacaofinanceiras', function (Blueprint $table) {
            //
            $table->string('FaturaCardMovimentacaoFinanc',1)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacaofinanceiras', function (Blueprint $table) {
            //
            $table->dropColumn('FaturaCardMovimentacaoFinanc');
        });
    }
};
