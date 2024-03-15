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
        Schema::create('sensor_data_sims', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_id');
            $table->string('sim_id');
            $table->string('direction');
            $table->float('speed');
            $table->integer('detected_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data_sims');
    }
};
