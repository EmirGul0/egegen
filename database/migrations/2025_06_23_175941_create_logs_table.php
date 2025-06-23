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
        Schema::create('logs', function (Blueprint $table) {
            $table->id(); // Her log kaydının kendine özel bir numarası (ID) olacak. Otomatik artar

            // İsteği yapan kişinin IP adresi.
            $table->string('ip_address');

            // İsteğin gönderildiği URL
            $table->string('url');

            // İsteğin HTTP metodu
            $table->string('method', 10); // Metot ismi genelde kısa olur, 10 karakter yeterli.

            // İsteğin HTTP durum kodu
            $table->integer('status_code')->nullable(); // Durum kodu her zaman olmayabilir. Boş bırakılabilir.

            // İsteğin ne zaman yapıldığı bilgisi. Otomatik
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
