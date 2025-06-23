<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController; // Haber işlemleri için NewsController'ı kullanacağız.

// API rotalarını tanımlıyoruz.
// Bu dosya, Laravel'in API rotalarını tanımlamak için kullanılır.

Route::middleware(['check.bearer.token'])->prefix('haberler')->group(function () {
    // Haberleri listeleme ve arama rotası (GET isteği)
    Route::get('/', [NewsController::class, 'index']);

    // Yeni haber ekleme rotası (POST isteği)
    Route::post('/', [NewsController::class, 'store']);

    // Tek bir haberi ID'sine göre detaylı gösterme rotası (GET isteği)
    Route::get('{id}', [NewsController::class, 'show']);

    // Mevcut bir haberi güncelleme rotası (PUT/PATCH isteği)
    Route::put('{id}', [NewsController::class, 'update']);

    // Bir haberi ID'sine göre silme rotası (DELETE isteği)
    Route::delete('{id}', [NewsController::class, 'destroy']);
});