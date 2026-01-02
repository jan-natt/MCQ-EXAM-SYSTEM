<?php
// database/seeders/ExamSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Create Laravel Exam
        $laravelExam = Exam::create([
            'title' => 'Laravel Fundamentals Quiz',
            'description' => 'Test your knowledge of Laravel basics including routing, Eloquent, and Blade templates.',
            'duration_minutes' => 30,
            'passing_marks' => 7,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // Question 1: Single Choice
        $q1 = Question::create([
            'exam_id' => $laravelExam->id,
            'question_text' => 'What is the default database driver in Laravel?',
            'marks' => 2,
            'question_type' => 'single',
            'order' => 1,
        ]);

        Option::create(['question_id' => $q1->id, 'option_text' => 'MySQL', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'PostgreSQL', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'SQLite', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'MongoDB', 'is_correct' => false, 'order' => 4]);

        // Question 2: Multiple Choice
        $q2 = Question::create([
            'exam_id' => $laravelExam->id,
            'question_text' => 'Which of the following are Laravel artisan commands? (Select all that apply)',
            'marks' => 3,
            'question_type' => 'multiple',
            'order' => 2,
        ]);

        Option::create(['question_id' => $q2->id, 'option_text' => 'php artisan migrate', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'php artisan serve', 'is_correct' => true, 'order' => 2]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'php artisan compile', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'php artisan make:model', 'is_correct' => true, 'order' => 4]);

        // Question 3: Single Choice
        $q3 = Question::create([
            'exam_id' => $laravelExam->id,
            'question_text' => 'What is the purpose of the .env file in Laravel?',
            'marks' => 2,
            'question_type' => 'single',
            'order' => 3,
        ]);

        Option::create(['question_id' => $q3->id, 'option_text' => 'To store environment-specific configuration', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $q3->id, 'option_text' => 'To define routes', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $q3->id, 'option_text' => 'To create migrations', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $q3->id, 'option_text' => 'To define models', 'is_correct' => false, 'order' => 4]);

        // Question 4: Single Choice
        $q4 = Question::create([
            'exam_id' => $laravelExam->id,
            'question_text' => 'Which Blade directive is used to display data?',
            'marks' => 1.5,
            'question_type' => 'single',
            'order' => 4,
        ]);

        Option::create(['question_id' => $q4->id, 'option_text' => '{{ $variable }}', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $q4->id, 'option_text' => '@display($variable)', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $q4->id, 'option_text' => '{!! $variable !!}', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $q4->id, 'option_text' => '@show($variable)', 'is_correct' => false, 'order' => 4]);

        // Question 5: Multiple Choice
        $q5 = Question::create([
            'exam_id' => $laravelExam->id,
            'question_text' => 'Which HTTP methods are supported in Laravel routes? (Select all that apply)',
            'marks' => 2.5,
            'question_type' => 'multiple',
            'order' => 5,
        ]);

        Option::create(['question_id' => $q5->id, 'option_text' => 'GET', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'POST', 'is_correct' => true, 'order' => 2]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'FETCH', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $q5->id, 'option_text' => 'DELETE', 'is_correct' => true, 'order' => 4]);

        // Create PHP Exam
        $phpExam = Exam::create([
            'title' => 'PHP Basics Assessment',
            'description' => 'Evaluate your understanding of PHP fundamentals including syntax, arrays, and OOP.',
            'duration_minutes' => 20,
            'passing_marks' => 6,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // PHP Question 1
        $pq1 = Question::create([
            'exam_id' => $phpExam->id,
            'question_text' => 'What does PHP stand for?',
            'marks' => 1,
            'question_type' => 'single',
            'order' => 1,
        ]);

        Option::create(['question_id' => $pq1->id, 'option_text' => 'Hypertext Preprocessor', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $pq1->id, 'option_text' => 'Personal Home Page', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $pq1->id, 'option_text' => 'Private Home Page', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $pq1->id, 'option_text' => 'Programming Hypertext Processor', 'is_correct' => false, 'order' => 4]);

        // PHP Question 2
        $pq2 = Question::create([
            'exam_id' => $phpExam->id,
            'question_text' => 'Which symbols are used to denote variables in PHP?',
            'marks' => 1.5,
            'question_type' => 'single',
            'order' => 2,
        ]);

        Option::create(['question_id' => $pq2->id, 'option_text' => '$', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $pq2->id, 'option_text' => '@', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $pq2->id, 'option_text' => '#', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $pq2->id, 'option_text' => '&', 'is_correct' => false, 'order' => 4]);

        // PHP Question 3
        $pq3 = Question::create([
            'exam_id' => $phpExam->id,
            'question_text' => 'Which functions are used to include files in PHP? (Select all that apply)',
            'marks' => 3,
            'question_type' => 'multiple',
            'order' => 3,
        ]);

        Option::create(['question_id' => $pq3->id, 'option_text' => 'include()', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $pq3->id, 'option_text' => 'require()', 'is_correct' => true, 'order' => 2]);
        Option::create(['question_id' => $pq3->id, 'option_text' => 'import()', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $pq3->id, 'option_text' => 'include_once()', 'is_correct' => true, 'order' => 4]);

        // PHP Question 4
        $pq4 = Question::create([
            'exam_id' => $phpExam->id,
            'question_text' => 'What is the correct way to end a PHP statement?',
            'marks' => 1.5,
            'question_type' => 'single',
            'order' => 4,
        ]);

        Option::create(['question_id' => $pq4->id, 'option_text' => ';', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $pq4->id, 'option_text' => '.', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $pq4->id, 'option_text' => ':', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $pq4->id, 'option_text' => ',', 'is_correct' => false, 'order' => 4]);

        // Create JavaScript Exam
        $jsExam = Exam::create([
            'title' => 'JavaScript Essentials',
            'description' => 'Test your knowledge of JavaScript fundamentals and ES6+ features.',
            'duration_minutes' => null, // No time limit
            'passing_marks' => 5,
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        // JS Question 1
        $jq1 = Question::create([
            'exam_id' => $jsExam->id,
            'question_text' => 'Which keyword is used to declare a constant in JavaScript?',
            'marks' => 2,
            'question_type' => 'single',
            'order' => 1,
        ]);

        Option::create(['question_id' => $jq1->id, 'option_text' => 'const', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $jq1->id, 'option_text' => 'var', 'is_correct' => false, 'order' => 2]);
        Option::create(['question_id' => $jq1->id, 'option_text' => 'let', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $jq1->id, 'option_text' => 'constant', 'is_correct' => false, 'order' => 4]);

        // JS Question 2
        $jq2 = Question::create([
            'exam_id' => $jsExam->id,
            'question_text' => 'Which array methods do NOT modify the original array? (Select all that apply)',
            'marks' => 3,
            'question_type' => 'multiple',
            'order' => 2,
        ]);

        Option::create(['question_id' => $jq2->id, 'option_text' => 'map()', 'is_correct' => true, 'order' => 1]);
        Option::create(['question_id' => $jq2->id, 'option_text' => 'filter()', 'is_correct' => true, 'order' => 2]);
        Option::create(['question_id' => $jq2->id, 'option_text' => 'push()', 'is_correct' => false, 'order' => 3]);
        Option::create(['question_id' => $jq2->id, 'option_text' => 'slice()', 'is_correct' => true, 'order' => 4]);

        $this->command->info('Exams seeded successfully!');
    }
}