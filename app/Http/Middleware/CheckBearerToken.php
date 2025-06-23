<?php

namespace App\Http\Middleware;

// Gerekli sınıfları çağırıyoruz.
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Gelen API isteklerinde Bearer Token'ı kontrol eder ve yanlış token kullanan IP'leri kara listeye alır.
class CheckBearerToken
{
    // Beklenen geçerli Bearer Token.
    protected const BEKLENEN_TOKEN = '2BH52wAHrAymR7wP3CASt';

    // Bir IP kara listeye alınmadan önceki hatalı deneme limiti.
    protected const MAKS_HATALI_DENEME = 10;

    // Kara listede kalma süresi (dakika).
    protected const BLOKE_SURESI_DAKIKA = 10;

    // Her isteği işleyen ana metot.
    public function handle(Request $request, Closure $next): Response
    {
        $istekYapanIP = $request->ip();

        // IP kara listedeyse, erişimi engelle.
        if ($this->ipKaraListedeMi($istekYapanIP)) {
            return response()->json([
                'mesaj' => 'Erişim engellendi. IP adresiniz bloke edilmiştir.',
                'durum' => 'hata'
            ], Response::HTTP_FORBIDDEN); // 403 Forbidden
        }

        $gelenToken = $request->bearerToken();

        // Token doğruysa, devam et ve hatalı deneme sayacını sıfırla.
        if ($gelenToken === self::BEKLENEN_TOKEN) {
            cache()->forget('hatali_deneme_ip_' . $istekYapanIP);
            return $next($request);
        } else {
            // Token yanlışsa, hatalı deneme sayacını artır.
            $this->hataliDenemeSayaciniArtir($istekYapanIP);

            // Deneme limiti aşıldıysa IP'yi kara listeye al.
            if ($this->hataliDenemeSayisiniAl($istekYapanIP) >= self::MAKS_HATALI_DENEME) {
                $this->ipyiKaraListeyeEkle($istekYapanIP);
                return response()->json([
                    'mesaj' => 'Geçersiz Token. IP adresiniz bloke edilmiştir.',
                    'durum' => 'hata'
                ], Response::HTTP_FORBIDDEN); // 403 Forbidden
            }

            // Token yanlış ama henüz kara listeye alınmadıysa, yetkisiz hatası döndür.
            return response()->json([
                'mesaj' => 'Geçersiz Bearer Token.',
                'durum' => 'hata'
            ], Response::HTTP_UNAUTHORIZED); // 401 Unauthorized
        }
    }

    // IP kara listede mi kontrol eder.
    protected function ipKaraListedeMi(string $ipAdresi): bool
    {
        return cache()->has('blacklist_ip_' . $ipAdresi);
    }

    // IP'nin hatalı deneme sayacını artırır.
    protected function hataliDenemeSayaciniArtir(string $ipAdresi): void
    {
        cache()->increment('hatali_deneme_ip_' . $ipAdresi);
        // Sayaç için de bir ömür verelim (30 dakika).
        cache()->put('hatali_deneme_ip_' . $ipAdresi, cache()->get('hatali_deneme_ip_' . $ipAdresi), now()->addMinutes(30)); 
    }

    // IP'nin hatalı deneme sayısını verir.
    protected function hataliDenemeSayisiniAl(string $ipAdresi): int
    {
        return (int) cache()->get('hatali_deneme_ip_' . $ipAdresi, 0);
    }

    // IP'yi belirli süre kara listeye ekler ve sayacını sıfırlar.
    protected function ipyiKaraListeyeEkle(string $ipAdresi): void
    {
        cache()->put('blacklist_ip_' . $ipAdresi, true, now()->addMinutes(self::BLOKE_SURESI_DAKIKA));
        cache()->forget('hatali_deneme_ip_' . $ipAdresi); // Kara listeye alınınca sayacı sıfırla. 
    }
}