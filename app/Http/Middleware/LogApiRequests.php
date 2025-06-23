<?php

namespace App\Http\Middleware;

// Gerekli sınıfları çağırıyoruz.
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log; // Kendi Log modelimiz
use Closure;
use Illuminate\Support\Facades\Log as LaravelLog; // Laravel'in kendi loglama sistemi (hata yakalamak için)

class LogApiRequests
{
    // İstek işlenmeden önce çalışır. Loglama sonra yapılacağı için sadece isteği ilerletiyoruz.
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    // İstek tamamlanıp yanıt gönderildikten sonra çalışır. Asıl loglama burada.
    public function terminate(Request $request, Response $response): void
    {
        // İstek bilgilerini alıyoruz.
        $ipAddress = $request->ip();        // İstek yapanın IP adresi
        $url = $request->path();            // İsteğin geldiği URL yolu
        $method = $request->method();       // İstek metodu
        $statusCode = $response->getStatusCode(); // Yanıtın durum kodu

        // Bilgileri 'logs' tablosuna kaydediyoruz.
        try {
            Log::create([ // <- Buradaki 'Log' bizim kendi Log modelimiz
                'ip_address' => $ipAddress,
                'url' => $url,
                'method' => $method,
                'status_code' => $statusCode,
            ]);
        } catch (\Exception $e) {
            // hata olursa laravelin loguna kaydet
            LaravelLog::error('API isteği loglanırken hata oluştu: ' . $e->getMessage());
        }
    }
}