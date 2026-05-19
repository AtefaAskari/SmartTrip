<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->enum('vote_type', ['up', 'down'])->default('up');
            $table->timestamps();
            
            $table->unique(['trip_id', 'user_id', 'destination_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};