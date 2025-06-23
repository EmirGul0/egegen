<?php

namespace App\Models;

//Veri tabanı ile konuşmak için gerekli olan kütüphaneler
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Haber Modeli
class Haber extends Model
{
    // Bu 'HasFactory' sihirli dokunuşu, ileride bolca sahte haber (250.000 tane demiştin!) oluşturmak için sistemi etkinleştirme
    use HasFactory;

    //Modelin tablosunu belirtiyoruz.
    protected $table = 'haberler';

    //Hangi alanlara veri girebileceğimizi belirtiyoruz.
    protected $fillable = [
        'baslik',       // Haberimizin başlığı buraya gelecek
        'icerik',       // Haberimizin uzun içeriği buraya gelecek
        'gorsel_yolu',  // Haberin görselinin bilgisayardaki veya sunucudaki adresi
        'yayinda_mi',   // Bu haber şu an yayında mı (Evet/Hayır)
    ];

    // 'casts' özelliği, bazı alanların veri tiplerini otomatik olarak dönüştürür.
    protected $casts = [
        'yayinda_mi' => 'boolean', // 'yayinda_mi' alanını otomatik olarak Evet/Hayır (doğru/yanlış) değerine çevir
    ];
}