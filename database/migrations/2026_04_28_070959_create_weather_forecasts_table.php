<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->date('forecast_date');
            $table->string('city');
            $table->string('condition'); // e.g., Sunny, Rainy
            $table->decimal('temp_high', 5, 2);
            $table->decimal('temp_low', 5, 2);
            $table->string('icon')->nullable();
            $table->timestamps();
            
            $table->unique(['trip_id', 'forecast_date', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_forecasts');
    }
};