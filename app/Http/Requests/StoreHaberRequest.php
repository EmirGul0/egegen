<?php
// Bu sınıf, yeni bir haber eklerken gelen verilerin doğruluğunu kontrol eder.
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHaberRequest extends FormRequest
{
    // Kullanıcının bu isteği yapmaya yetkili olup olmadığını kontrol ediyoruz.
    public function authorize(): bool
    {
        return true;
    }

    // Doğrulama kurallarını tanımlıyoruz.
    public function rules(): array
    {
        // Haber başlığı, içeriği, görseli ve yayında durumu için doğrulama kurallarını tanımlıyoruz.
        return [
            'baslik' => ['required', 'string', 'max:255'],
            'icerik' => ['required', 'string'],
            'gorsel' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'yayinda_mi' => ['nullable', 'boolean'],
        ];
    }

    // Doğrulama kurallarının başarısız olması durumunda gösterilecek hata mesajları.
    public function messages(): array
    {
        return [
            'baslik.required' => 'Haber başlığı boş bırakılamaz.',
            'baslik.string' => 'Haber başlığı metin formatında olmalıdır.',
            'baslik.max' => 'Haber başlığı en fazla 255 karakter olabilir.',
            'icerik.required' => 'Haber içeriği boş bırakılamaz.',
            'icerik.string' => 'Haber içeriği metin formatında olmalıdır.',
            'gorsel.file' => 'Görsel bir dosya olmalıdır.',
            'gorsel.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'gorsel.mimes' => 'Görselin formatı JPEG, PNG, JPG, GIF veya WEBP olmalıdır.',
            'gorsel.max' => 'Görselin boyutu en fazla 2 MB olabilir.',
            'yayinda_mi.boolean' => 'Yayında durumu doğru/yanlış (Evet/Hayır) formatında olmalıdır.',
        ];
    }
}