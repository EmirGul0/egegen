<?php

namespace App\Http\Controllers;

use App\Models\Haber; // Haber modelimiz
use App\Http\Requests\StoreHaberRequest; // Yeni haber eklerken doğrulama kuralları
use App\Http\Requests\UpdateHaberRequest; // Haber güncellerken doğrulama kuralları
use Illuminate\Http\Request; // Genel HTTP isteklerini yönetmek için
use Symfony\Component\HttpFoundation\Response; // HTTP durum kodlarını kullanmak için
use Intervention\Image\Laravel\Facades\Image; // Görsel işleme kütüphanesi
use Illuminate\Support\Facades\Storage; // Dosyaları depolama ve silme işlemleri için

// Bu kontrolcü, haberlerle ilgili tüm API işlemlerini yönetecek ana sınıfımız
class NewsController extends Controller
{
    // Tüm haberleri listeler veya arama yapar
    public function index(Request $request)
    {
        $aramaKelimesi = $request->query('arama'); // Arama kelimesini alıyoruz

        // Eğer arama kelimesi varsa, başlık veya içerikte arama yap.
        $haberler = Haber::when($aramaKelimesi, function ($query, $aramaKelimesi) {
                $query->where('baslik',  'like', '%' . $aramaKelimesi . '%')
                      ->orWhere('icerik', 'like', '%' . $aramaKelimesi . '%');
            })
            ->orderBy('created_at', 'desc') // En yeni haberler en üstte görünsün
            ->paginate(10); // Her sayfada 10 haber göster

        return response()->json([
            'mesaj' => 'Haberler başarıyla listelendi.',
            'durum' => 'başarılı',
            'veri'  => $haberler, 
        ], Response::HTTP_OK);
    }

    // Yeni bir haber oluşturur.
    public function store(StoreHaberRequest $request)
    {
        $gorselYolu = null;

        // Eğer istekle birlikte bir görsel dosyası geldiyse, onu işle
        if ($request->hasFile('gorsel')) {
            $gorsel        = $request->file('gorsel'); //Yüklenen görseli al
            $dosyaAdi      = uniqid() . '.webp';
            $relativePath  = 'haber_gorselleri/' . $dosyaAdi;

            // Görseli oku, 800px genişliğe orantılı küçült ve WebP formatında %90 kaliteyle dönüştür
            $encodedImage  = Image::read($gorsel)
                                  ->scaleDown(width: 800)
                                  ->toWebp(90);

            Storage::disk('public')->put($relativePath, (string) $encodedImage);
            $gorselYolu = $relativePath;
        }

        // Gelen verilerle yeni bir haber kaydı oluştur
        $haber = Haber::create([
            'baslik'      => $request->baslik,
            'icerik'      => $request->icerik,
            'gorsel_yolu' => $gorselYolu, // İşlenmiş görselin yolu
            'yayinda_mi'  => $request->yayinda_mi ?? true, // Eğer belirtilmezse varsayılan true
        ]);

        return response()->json([
            'mesaj' => 'Haber başarıyla eklendi.',
            'durum' => 'başarılı',
            'veri'  => $haber, // Yeni oluşturulan haber bilgisini gönder.
        ], Response::HTTP_CREATED);
    }

    // Belirli bir haberin detaylarını gösterir.
    public function show(string $id)
    {
        $haber = Haber::find($id); // ID'ye göre haberi bul.

        // Eğer haber bulunamazsa hata döndür.
        if (!$haber) {
            return response()->json([
                'mesaj' => 'Haber bulunamadı.',
                'durum' => 'hata',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'mesaj' => 'Haber başarıyla getirildi.',
            'durum' => 'başarılı',
            'veri'  => $haber,
        ], Response::HTTP_OK); // HTTP 200: Her şey yolunda.
    }

    // Mevcut bir haberi günceller.
    public function update(UpdateHaberRequest $request, string $id)
    {
        $haber = Haber::find($id); // ID'ye göre güncellenecek haberi bul.

        // Eğer haber bulunamazsa hata döndür.
        if (!$haber) {
            return response()->json([
                'mesaj' => 'Güncellenecek haber bulunamadı.',
                'durum' => 'hata',
            ], Response::HTTP_NOT_FOUND); // HTTP 404: Bulunamadı.
        }

        $guncelData = $request->validated(); // Validasyondan geçmiş temiz verileri al.

        // Eğer istekte yeni bir görsel dosyası geldiyse, onu işle.
        if ($request->hasFile('gorsel')) {
            // Önceki görseli depolamadan sil ama eğer var ise.
            if ($haber->gorsel_yolu && Storage::disk('public')->exists($haber->gorsel_yolu)) {
                Storage::disk('public')->delete($haber->gorsel_yolu);
            }

            $gorsel        = $request->file('gorsel');
            $dosyaAdi      = uniqid() . '.webp';
            $relativePath  = 'haber_gorselleri/' . $dosyaAdi;
            $encodedImage  = Image::read($gorsel)
                                  ->scaleDown(width: 800)
                                  ->toWebp(90);

            Storage::disk('public')->put($relativePath, (string) $encodedImage);
            $guncelData['gorsel_yolu'] = $relativePath; // Güncel verilere yeni görsel yolunu ekle.
        } elseif (isset($guncelData['gorsel_yolu']) && $guncelData['gorsel_yolu'] === null) {
            // Eğer görseli silmek için null değeri gönderildiyse...
            if ($haber->gorsel_yolu && Storage::disk('public')->exists($haber->gorsel_yolu)) {
                Storage::disk('public')->delete($haber->gorsel_yolu);
            }
            $guncelData['gorsel_yolu'] = null; // Veritabanındaki yolu null yap.
        } else {
            // Görsel gönderilmediyse veya silme isteği yoksa, 'gorsel' alanını güncelleme verisinden çıkar.
            unset($guncelData['gorsel']);
        }

        $haber->update($guncelData); // Haberi güncel verilerle güncelle.

        // Haber başarıyla güncellendiğini belirten yanıt.
        return response()->json([
            'mesaj' => 'Haber başarıyla güncellendi.',
            'durum' => 'başarılı',
            'veri'  => $haber, // Güncellenen haberin bilgilerini gönder.
        ], Response::HTTP_OK);
    }

    // Belirli bir haberi ID'sine göre siler.
    public function destroy(string $id)
    {
        $haber = Haber::find($id); // Silinecek haberi bul.

        // Eğer haber bulunamazsa hata döndür.
        if (!$haber) {
            return response()->json([
                'mesaj' => 'Silinecek haber bulunamadı.',
                'durum' => 'hata',
            ], Response::HTTP_NOT_FOUND); // HTTP 404: Bulunamadı.
        }

        // Haberin görseli varsa, onu da depolamadan sil.
        if ($haber->gorsel_yolu && Storage::disk('public')->exists($haber->gorsel_yolu)) {
            Storage::disk('public')->delete($haber->gorsel_yolu);
        }

        $haber->delete(); // Haberi veritabanından sil.

        return response()->json([
            'mesaj' => 'Haber başarıyla silindi.',
            'durum' => 'başarılı',
        ], Response::HTTP_OK);
    }
}