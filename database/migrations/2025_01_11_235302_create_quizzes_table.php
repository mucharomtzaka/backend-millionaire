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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->text('title'); //Latihan Soal 1 SD
            $table->string('slug')->unique(); //latihan-soal-1-sd
            $table->text('description');
            $table->boolean('status')->default(0);
            $table->string('type'); //exercise, exam
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
