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
        Schema::create('competition_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_year_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('competition_year_id')
                ->references('id')
                ->on('competition_years')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_classes');
    }
};
