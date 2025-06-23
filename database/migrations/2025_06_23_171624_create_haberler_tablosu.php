<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('haberler', function (Blueprint $table) {
            $table->id(); // Her haberin kendine özel bir numarası (ID) olacak. Otomatik artar.

            // Haber başlığı.
            $table->string('baslik');

            // Haber metni, içeriği. Uzun bir metin olabilir.
            $table->text('icerik');

            // Haber görselinin nerede olduğunu gösteren yol. Görsel olmayabilir diye boş bırakılabilir.
            $table->string('gorsel_yolu')->nullable();

            // Haber yayında mı değil mi? Varsayılan olarak "evet" (true) olsun.
            $table->boolean('yayinda_mi')->default(true);

            // Haber ne zaman oluşturuldu ve ne zaman güncellendi bilgileri. Laravel otomatik ekler.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('haberler_tablosu');
    }
};
