<?php
// Bu sınıf, mevcut bir haberi güncellerken gelen verilerin doğruluğunu kontrol eder.
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHaberRequest extends FormRequest
{
    // Bu metod, kullanıcının bu isteği yapma yetkisini kontrol eder.
    public function authorize(): bool
    {
        return true;
    }

    // Bu metod, güncelleme işlemi için gerekli doğrulama kurallarını tanımlar.
    public function rules(): array
    {
        // Haber güncelleme işlemi için gerekli doğrulama kurallarını tanımlıyoruz.
        return [
            'baslik' => ['nullable', 'string', 'max:255'],
            'icerik' => ['nullable', 'string'],
            'gorsel' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'yayinda_mi' => ['nullable', 'boolean'],
        ];
    }

    // Bu metod, doğrulama kurallarının başarısız olması durumunda kullanıcıya gösterilecek hata mesajlarını tanımlar.
    public function messages(): array
    {
        // Her alan için özel hata mesajları döndürüyoruz.
        return [
            'baslik.string' => 'Haber başlığı metin formatında olmalıdır.',
            'baslik.max' => 'Haber başlığı en fazla 255 karakter olabilir.',
            'icerik.string' => 'Haber içeriği metin formatında olmalıdır.',
            'gorsel.file' => 'Görsel bir dosya olmalıdır.',
            'gorsel.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'gorsel.mimes' => 'Görselin formatı JPEG, PNG, JPG, GIF veya WEBP olmalıdır.',
            'gorsel.max' => 'Görselin boyutu en fazla 2 MB olabilir.',
            'yayinda_mi.boolean' => 'Yayında durumu doğru/yanlış (Evet/Hayır) formatında olmalıdır.',
        ];
    }
}