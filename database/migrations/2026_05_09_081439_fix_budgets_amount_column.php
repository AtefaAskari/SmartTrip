<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, clear any invalid data
        Schema::table('budgets', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->string('amount')->change();
        });
    }
};