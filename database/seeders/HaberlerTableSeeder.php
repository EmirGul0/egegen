<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Haber; // haber modelimiz

// sahte haber verilerini oluşturmak için Seeder sınıfı
class HaberlerTableSeeder extends Seeder
{

    // run metodu, seeder çalıştırıldığında otomatik olarak çağrılır.
    public function run(): void
    {
        // Haber modelimize factory kullanarak 250.000 adet sahte haber oluşturuyoruz.
        Haber::factory()->count(250000)->create();

        $this->command->info('250.000 adet haber başarıyla oluşturuldu!'); //bilgi mesajı
    }
}
//data basmak için "php -d memory_limit=2048M artisan db:seed --class=HaberlerTableSeeder" yazabiliriz