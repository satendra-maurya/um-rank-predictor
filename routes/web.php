<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticeController;
use App\Livewire\RankPredictor;
use Illuminate\Support\Facades\Route;

// Public Homepage & Predictor Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rank-predictor/{authority}/available-exams', [HomeController::class, 'availableExams'])->name('rank-predictor.authority.available-exams');

// Rank Predictor Flow Routes (supporting optional category / exam / year / stage parameters)
Route::get('/rank-predictor/{category?}/{exam?}/{year?}/{stage?}', RankPredictor::class)->name('rank-predictor');

// Official Notices & Updates Routes
Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{slug}', [NoticeController::class, 'show'])->name('notices.show');

// Informational & Legal Pages
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/privacy-notice', [HomeController::class, 'privacyNotice'])->name('privacy-notice');
