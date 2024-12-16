<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\ManageStudentController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionAnalyticsController;
use App\Http\Controllers\Admin\ElectionController;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::prefix('admin/candidates')->name('admin.candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/create', [CandidateController::class, 'create'])->name('create');
        Route::post('/', [CandidateController::class, 'store'])->name('store');
        Route::get('/{candidate}', [CandidateController::class, 'show'])->name('show');
        Route::get('/{candidate}/edit', [CandidateController::class, 'edit'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');
    });


    // Students Routes
    Route::prefix('admin/students')->name('admin.students.')->group(function () {
        Route::get('/', [ManageStudentController::class, 'index'])->name('index');
        Route::get('/create', [ManageStudentController::class, 'create'])->name('create');
        Route::post('/', [ManageStudentController::class, 'store'])->name('store');
        Route::get('/{student}', [ManageStudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [ManageStudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [ManageStudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [ManageStudentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/elections')->name('admin.elections.')->group(function () {
        Route::get('/', [ElectionController::class, 'index'])->name('index');
        Route::get('/create', [ElectionController::class, 'create'])->name('create');
        Route::post('/', [ElectionController::class, 'store'])->name('store');
        Route::get('/{election}', [ElectionController::class, 'show'])->name('show');
        Route::get('/{election}/edit', [ElectionController::class, 'edit'])->name('edit');
        Route::put('/{election}', [ElectionController::class, 'update'])->name('update');
        Route::delete('/{election}', [ElectionController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // New route for election candidates
    Route::get('/election/{election}/candidates', [StudentController::class, 'showElectionCandidates'])
        ->name('student.election.candidates');

    // Voting route
    Route::post('/vote', [StudentController::class, 'vote'])
        ->name('student.vote');
});

Route::get('/email/verify', [AuthController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed', 'auth', 'throttle:6,1'])
    ->name('verification.verify'); // Ensure this route name is EXACTLY 'verification.verify'

Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
