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
        Schema::table('faturacartaos', function (Blueprint $table) {
            //
            $table->date('DateStartFatura');
            $table->date('DateEndFatura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturacartaos', function (Blueprint $table) {
            //
            $table->dropColumn('DateStartFatura');
            $table->dropColumn('DateEndFatura');
        });
    }
};
