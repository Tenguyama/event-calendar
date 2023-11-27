<?php

use App\Http\Controllers\Api\v1\DateController;
use App\Http\Controllers\Api\v1\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')
    ->name('v1')
    ->group(function () {
        //++
        Route::get('/date-range',[DateController::class, 'getDateRange'])->name('date.getDateRange');
        //++
        Route::get('/calendar',[DateController::class, 'getCalendar'])->name('date.getCalendar');
        //++
        Route::post('/events',[EventController::class , 'getAllByFilters'])->name('event.getAllByFilters');
        //++
        Route::delete('/event/{id}',[EventController::class , 'delete'])->name('event.delete');
        //++
        Route::post('/event/create',[EventController::class , 'create'])->name('event.create');
        //++
        Route::put('/event/update',[EventController::class , 'update'])->name('event.update');
    });

