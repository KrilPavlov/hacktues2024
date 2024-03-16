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
        Schema::create('gridsquares', function (Blueprint $table) {
            $table->id();
            $table->string('alat');
            $table->string('along');
            $table->string('blat');
            $table->string('blong');
            $table->string('clat');
            $table->string('clong');
            $table->string('dlat');
            $table->string('dlong');
            $table->float('danger');
            $table->unsignedBigInteger('population');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gridsquares');
    }
};
