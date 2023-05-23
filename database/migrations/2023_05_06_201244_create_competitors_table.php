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
        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_year_id');
            $table->unsignedBigInteger('competition_class_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('born')->nullable();
            $table->string('team')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('previous_starts')->default(0);
            $table->string('reference_number')->nullable();
            $table->string('shirt_size')->nullable();
            $table->string('shirt_name')->nullable();
            $table->string('time_10k')->nullable();
            $table->json('payment_data')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('competitors')
                ->cascadeOnDelete();

            $table->foreign('competition_class_id')
                ->references('id')
                ->on('competition_classes')
                ->nullOnDelete();

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
        Schema::dropIfExists('competitors');
    }
};
