<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController; // Haber işlemleri için NewsController'ı kullanacağız.

// API rotalarını tanımlıyoruz
Route::prefix('haberler')->group(function () {
    // Tüm haberleri listeleme veya arama rotası (GET isteği)
    // Örnek kullanım: GET /api/haberler veya GET /api/haberler?arama=aranan_kelime
    Route::get('/', [NewsController::class, 'index']);

    // Yeni bir haber oluşturma rotası (POST isteği)
    // Örnek kullanım: POST /api/haberler (İstek gövdesinde haber bilgileriyle)
    Route::post('/', [NewsController::class, 'store']);

    // Tek bir haberi ID'sine göre detaylı gösterme rotası (GET isteği)
    // Örnek kullanım: GET /api/haberler/5 (ID'si 5 olan haberi getirir)
    Route::get('{id}', [NewsController::class, 'show']);

    // Mevcut bir haberi güncelleme rotası (PUT/PATCH isteği)
    // Örnek kullanım: PUT /api/haberler/5 (İstek gövdesinde güncellenecek bilgilerle)
    Route::put('{id}', [NewsController::class, 'update']);

    // Bir haberi ID'sine göre silme rotası (DELETE isteği)
    // Örnek kullanım: DELETE /api/haberler/5
    Route::delete('{id}', [NewsController::class, 'destroy']);
});