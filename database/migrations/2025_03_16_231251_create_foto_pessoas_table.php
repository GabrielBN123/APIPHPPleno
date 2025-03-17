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
        Schema::create('foto_pessoas', function (Blueprint $table) {
            $table->id('fp_id');
            $table->foreignId('pes_id')->constrained('pessoas', 'pes_id');
            $table->dateTime('fp_data');
            $table->string('fp_bucket');
            $table->string('fp_hash');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pessoas');
    }
};
