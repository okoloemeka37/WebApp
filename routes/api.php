<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SendMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AppWebHookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//WhatsappWebHook

Route::post('/webhooks/whatsapp',[AppWebHookController::class, 'handle']);

Route::post('/message/sendMessage',[SendMessageController::class, 'sendMessage'])->name('sendMessage');

Route::post('/Dashboard/Dashsearch',[DashboardController::class, 'DashSearch'])->name('DashSearch');