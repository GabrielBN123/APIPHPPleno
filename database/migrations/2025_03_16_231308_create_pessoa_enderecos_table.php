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
        Schema::create('pessoa_enderecos', function (Blueprint $table) {
            $table->foreignId('pes_id')->constrained('pessoas', 'pes_id');
            $table->foreignId('end_id')->constrained('enderecos', 'end_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_enderecos');
    }
};
