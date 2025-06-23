<?php

namespace Database\Factories;

use App\Models\Haber; // haber modeli
use Illuminate\Database\Eloquent\Factories\Factory;

// Bu sınıf, Haber modelimiz için sahte veri üretecek
class HaberFactory extends Factory
{
    // Hangi model için sahte veri üreteceğimizi belirtiyoruz.
    protected $model = Haber::class;

    // Bu metod, sahte verileri nasıl oluşturacağımızı tanımlar.
    public function definition(): array
    {
        // Faker kütüphanesini kullanarak rastgele veri oluşturuyoruz.
        return [
            'baslik' => $this->faker->sentence(mt_rand(5, 10)),
            'icerik' => $this->faker->paragraphs(mt_rand(5, 20), true),
            'gorsel_yolu' => null,
            'yayinda_mi' => $this->faker->boolean(90)
        ];
    }
}