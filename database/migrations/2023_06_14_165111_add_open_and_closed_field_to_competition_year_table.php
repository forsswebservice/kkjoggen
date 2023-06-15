<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_years', function (Blueprint $table) {
            $table->longText('after_closed_message')->nullable();
            $table->longText('after_open_message')->nullable();
        });
    }
};
