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
        Schema::create('detail_responses', function (Blueprint $table) {
            $table->id();

            $table->string('user_answer')->nullable(); //boleh null karena user kadang tidak menjawab
            $table->string('correct_answer')->nullable(); //mesti diisi oleh sistem
            $table->integer('point');

            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('response_id');
            $table->foreign('response_id')->references('id')->on('responses')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_responses');
    }
};
