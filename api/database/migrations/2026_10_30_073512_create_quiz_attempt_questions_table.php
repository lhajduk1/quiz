<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
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
        Schema::create('quiz_attempt_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(QuizAttempt::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Question::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(Answer::class, 'correct_answer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(Answer::class, 'user_answer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('question_snapshot');
            $table->json('answers_snapshot');
            $table->text('user_answer_snapshot')->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->unique(['quiz_attempt_id', 'question_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_questions');
    }
};
