<?php

namespace App\Http\Controllers;

use App\Models\Haber; // Haber modelini kullanıyoruz.
use App\Http\Requests\StoreHaberRequest; // Yeni haber ekleme validasyonu için
use App\Http\Requests\UpdateHaberRequest; // Haber güncelleme validasyonu için
use Illuminate\Http\Request; // Index metodu hala genel Request kullanıyor.
use Symfony\Component\HttpFoundation\Response; // HTTP durum kodları için.


// Haberlerle ilgili tüm API işlemlerini yönetecek kontrolcü.
class NewsController extends Controller
{
    // Haberleri listeleme ve arama işlemi.
    public function index(Request $request)
    {
        $aramaKelimesi = $request->query('arama');

        $haberler = Haber::when($aramaKelimesi, function ($query, $aramaKelimesi) {
            $query->where('baslik', 'like', '%' . $aramaKelimesi . '%')
                  ->orWhere('icerik', 'like', '%' . $aramaKelimesi . '%');
        })
        ->orderBy('created_at', 'desc') // En yeni haberler üstte.
        ->paginate(10); // Sayfa başına 10 haber.

        return response()->json([
            'mesaj' => 'Haberler başarıyla listelendi.',
            'durum' => 'başarılı',
            'veri' => $haberler,
        ], Response::HTTP_OK);
    }

    // Yeni bir haber ekler.
    public function store(StoreHaberRequest $request)
    {

        $haber = Haber::create([
            'baslik' => $request->baslik,
            'icerik' => $request->icerik,
            'gorsel_yolu' => null, // Görsel işleme sonra eklenecek.
            'yayinda_mi' => $request->yayinda_mi ?? true,
        ]);

        return response()->json([
            'mesaj' => 'Haber başarıyla eklendi.',
            'durum' => 'başarılı',
            'veri' => $haber,
        ], Response::HTTP_CREATED); // 201 Created
    }

    // Belirli bir haberi ID'sine göre detaylı gösterir.
    public function show(string $id)
    {
        $haber = Haber::find($id);

        if (!$haber) {
            return response()->json([
                'mesaj' => 'Haber bulunamadı.',
                'durum' => 'hata'
            ], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        return response()->json([
            'mesaj' => 'Haber başarıyla getirildi.',
            'durum' => 'başarılı',
            'veri' => $haber,
        ], Response::HTTP_OK);
    }

    // Mevcut bir haberi günceller.
    public function update(UpdateHaberRequest $request, string $id)
    {
        $haber = Haber::find($id);

        if (!$haber) {
            return response()->json([
                'mesaj' => 'Güncellenecek haber bulunamadı.',
                'durum' => 'hata'
            ], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        // Gelen veriler UpdateHaberRequest tarafından zaten doğrulandı.
        $haber->update([
            'baslik' => $request->baslik ?? $haber->baslik,
            'icerik' => $request->icerik ?? $haber->icerik,
            'gorsel_yolu' => $request->gorsel_yolu ?? $haber->gorsel_yolu, // Görsel işleme sonra eklenecek.
            'yayinda_mi' => $request->yayinda_mi ?? $haber->yayinda_mi,
        ]);

        return response()->json([
            'mesaj' => 'Haber başarıyla güncellendi.',
            'durum' => 'başarılı',
            'veri' => $haber,
        ], Response::HTTP_OK);
    }

    //Belirli bir haberi ID'sine göre siler.
    public function destroy(string $id)
    {
        $haber = Haber::find($id);

        if (!$haber) {
            return response()->json([
                'mesaj' => 'Silinecek haber bulunamadı.',
                'durum' => 'hata'
            ], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        $haber->delete();

        return response()->json([
            'mesaj' => 'Haber başarıyla silindi.',
            'durum' => 'başarılı'
        ], Response::HTTP_OK); // 200 OK (veya 204 No Content de olabilir)
    }
}