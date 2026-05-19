<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->string('city');
            $table->string('country');
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->text('notes')->nullable();
            $table->integer('order')->default(0); // to sort destinations sequence
            $table->timestamps();
            
            $table->index('trip_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};