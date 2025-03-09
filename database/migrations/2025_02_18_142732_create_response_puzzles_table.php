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
        Schema::create('response_puzzles', function (Blueprint $table) {
            $table->id();
            $table->string('answer');
            $table->boolean('isCorrect');
            $table->tinyInteger('point', false);
            $table->unsignedBigInteger('puzzle_question_id');
            $table->unsignedBigInteger('puzzle_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_puzzles');
    }
};
