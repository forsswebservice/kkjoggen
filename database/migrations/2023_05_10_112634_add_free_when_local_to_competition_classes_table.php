<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_classes', function (Blueprint $table) {
            $table->integer('price')->default(0);
            $table->integer('price_multiple')->default(0);
            $table->boolean('is_free_when_local')->default(false);
        });
    }
};
