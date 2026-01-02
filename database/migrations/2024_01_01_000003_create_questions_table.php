<?php
// database/migrations/2024_01_01_000003_create_questions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->text('question_text');
            $table->decimal('marks', 8, 2);
            $table->enum('question_type', ['single', 'multiple'])->default('single')
                ->comment('single = one correct answer, multiple = multiple correct answers');
            $table->integer('order')->nullable();
            $table->timestamps();
            
            $table->index(['exam_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};