<?php

use App\Http\Controllers\AnatomyController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::prefix('conditions')->name('conditions.')->group(function () {
    Route::get('/', [ConditionController::class, 'index'])->name('index');
    Route::get('/system/{system}', [ConditionController::class, 'bySystem'])->name('system');
    Route::get('/{slug}', [ConditionController::class, 'show'])->name('show');
});

Route::prefix('medications')->name('medications.')->group(function () {
    Route::get('/', [MedicationController::class, 'index'])->name('index');
    Route::get('/{slug}', [MedicationController::class, 'show'])->name('show');
});

Route::prefix('procedures')->name('procedures.')->group(function () {
    Route::get('/', [ProcedureController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProcedureController::class, 'show'])->name('show');
});

Route::prefix('anatomy')->name('anatomy.')->group(function () {
    Route::get('/', [AnatomyController::class, 'index'])->name('index');
    Route::get('/{slug}', [AnatomyController::class, 'show'])->name('show');
});

Route::prefix('research')->name('articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});
