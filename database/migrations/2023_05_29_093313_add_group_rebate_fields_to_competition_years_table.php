<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_years', function (Blueprint $table) {
            $table->integer('max_registration')->default(10);
            $table->integer('rebate_from')->default(0);
            $table->integer('rebate_percent')->default(0);
        });
    }
};
