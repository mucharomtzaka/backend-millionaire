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
        Schema::create('puzzle_questions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('no', false);
            $table->text('clue');
            $table->string('word');
            $table->string('image_url')->nullable();
            $table->string('point')->nullable();
            $table->tinyInteger('letter_position', false)->nullable();

            $table->unsignedBigInteger('puzzle_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzle_questions');
    }
};
