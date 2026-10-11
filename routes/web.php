<?php

use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $locale = request()->getPreferredLanguage(array_keys(config('app.locales'))) ?: config('app.locale');

    return redirect()->route('home', ['locale' => $locale]);
});

Route::prefix('{locale}')->where(['locale' => 'hu|ro|en'])->middleware(SetLocale::class)->group(function () {
    Route::controller(SiteController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/rolam', 'about')->name('about');
        Route::get('/szakmak', 'disciplines')->name('disciplines');
        Route::get('/szakmak/{slug}', 'discipline')->name('discipline');
        Route::get('/tapasztalat', 'experience')->name('experience');
        Route::get('/keszsegek', 'skills')->name('skills');
        Route::get('/szenvedelyek', 'interests')->name('interests');
        Route::get('/kapcsolat', 'contact')->name('contact');
    });

    Route::get('/oneletrajz.pdf', [ResumeController::class, 'pdf'])->name('resume');

    Route::get('/projektek', fn () => redirect()->route('discipline', ['slug' => 'informatika', 'projektek' => 1]))->name('projects');
});
