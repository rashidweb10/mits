<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('selected_option_id')->unsigned();
            $table->tinyInteger('is_correct')->default(0);
            $table->timestamps();

            $table->foreign('attempt_id')->references('id')->on('quiz_attempts');
            $table->foreign('question_id')->references('id')->on('quiz_questions');
            $table->foreign('selected_option_id')->references('id')->on('quiz_options');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};