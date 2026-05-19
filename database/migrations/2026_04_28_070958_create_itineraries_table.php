<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->date('day_date');
            $table->integer('day_number'); // 1,2,3...
            $table->string('title');
            $table->text('description')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index(['trip_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};