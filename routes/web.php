<?php

use App\Http\Controllers\ExchangeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExchangeController::class, 'home'])->name('home');
Route::post('/exchanges', [ExchangeController::class, 'create'])->name('exchange.create');

Route::get('/join/{token}', [ExchangeController::class, 'joinPage'])->name('exchange.join');
Route::post('/join/{token}', [ExchangeController::class, 'join'])
    ->middleware('throttle:12,1')
    ->name('exchange.join.store');

Route::get('/manage/{token}', [ExchangeController::class, 'managePage'])->name('exchange.manage');
Route::post('/manage/{token}', [ExchangeController::class, 'verifyCreator'])
    ->middleware('throttle:6,1')
    ->name('exchange.manage.verify');
Route::post('/manage/{token}/draw', [ExchangeController::class, 'draw'])
    ->middleware('throttle:3,1')
    ->name('exchange.draw');

Route::get('/join/{token}/reveal/{revealToken}', [ExchangeController::class, 'reveal'])
    ->name('exchange.reveal');
