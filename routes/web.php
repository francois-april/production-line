<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/orders/create');
});

Route::resource('orders', OrderController::class);

Route::resource('schedule', ScheduleController::class);