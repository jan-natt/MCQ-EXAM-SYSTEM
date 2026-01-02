<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

// Home Route
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// Google OAuth Routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Exam Management
    Route::resource('exams', AdminExamController::class);
    Route::post('exams/{exam}/toggle-status', [AdminExamController::class, 'toggleStatus'])
        ->name('exams.toggle-status');
    Route::get('exams/{exam}/statistics', [AdminExamController::class, 'statistics'])
        ->name('exams.statistics');
    
    // Question Management
    Route::get('exams/{exam}/questions/create', [AdminQuestionController::class, 'create'])
        ->name('questions.create');
    Route::post('questions', [AdminQuestionController::class, 'store'])
        ->name('questions.store');
    Route::get('questions/{question}/edit', [AdminQuestionController::class, 'edit'])
        ->name('questions.edit');
    Route::put('questions/{question}', [AdminQuestionController::class, 'update'])
        ->name('questions.update');
    Route::delete('questions/{question}', [AdminQuestionController::class, 'destroy'])
        ->name('questions.destroy');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Exam Taking
    Route::get('exams/{exam}', [StudentExamController::class, 'show'])->name('exams.show');
    Route::post('exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
    Route::get('attempts/{attempt}/take', [StudentExamController::class, 'take'])->name('exams.take');
    Route::post('attempts/{attempt}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
    
    // Results
    Route::get('results', [ResultController::class, 'index'])->name('results.index');
    Route::get('attempts/{attempt}/result', [ResultController::class, 'show'])->name('results.show');
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    abort(404);
});
