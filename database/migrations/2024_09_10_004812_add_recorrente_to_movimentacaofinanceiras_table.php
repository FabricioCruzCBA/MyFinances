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
            $table->string('RecorrenteMovimentacaoFinanc',1)->default('0');
            $table->string('QntParcelasMovimentacaoFinanc')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimentacaofinanceiras', function (Blueprint $table) {
            //
            $table->dropColumn('RecorrenteMovimentacaoFinanc');
            $table->dropColumn('QntParcelasMovimentacaoFinanc');
        });
    }
};
