Laravel Haber Sitesi API
Bu proje, Egegen görev tanımına uygun olarak Laravel 11 kullanılarak geliştirilmiş, Postman API üzerinden işlem yapılabilen bir haber sitesi API'sidir. Ön yüz tasarımı içermemektedir, tamamen API odaklıdır.

Proje Hakkında
Bu API, bir haber sitesi için temel CRUD (Create, Read, Update, Delete - Oluşturma, Okuma, Güncelleme, Silme) operasyonlarını sunmaktadır. Proje aşağıdaki ana özellikleri içermektedir:

Güvenli API Rotaları: Bearer Token ile kimlik doğrulama.

IP Kara Liste Mekanizması: Belirlenen hatalı deneme sayısı sonrası IP adresi bloklama.

Kapsamlı İstek Loglama: Gelen her API isteğinin detaylı olarak veritabanına kaydedilmesi.

Görsel İşleme: Yüklenen haber görsellerinin otomatik olarak WebP formatına dönüştürülmesi ve boyutlandırılması.

Veri Doğrulama (Validation): Türkçe ve anlaşılır hata mesajlarıyla sağlam veri girişi kontrolü.

Büyük Veri Seti: Test ve geliştirme amacıyla 250.000 adet sahte haber verisi oluşturulması.

Arama İşlevi: Haberler arasında başlık veya içeriğe göre arama yapabilme.

Clean Code: Temiz kod prensiplerine uygun, okunabilir ve sürdürülebilir kod yapısı.

Gereksinimler
PHP >= 8.2

Composer

MySQL Veritabanı

Laravel 11

Kurulum
Projeyi yerel makinenizde çalıştırmak için aşağıdaki adımları izleyin:

Depoyu Klonlayın veya Projeyi Oluşturun (Eğer Sıfırdan Başlıyorsanız):

Eğer bu depoyu GitHub'dan klonlayacaksanız:

git clone https://github.com/EmirGul0/egegen.git
cd egegen/BackEnd # Klonladığınız klasördeki BackEnd dizinine girin

Veya, eğer projeyi sıfırdan oluşturup bu kodları entegre ettiyseniz (sizin durumunuzda olduğu gibi):

cd YourProjectDirectory # Laravel projenizin ana dizini (örn: BackEnd)

Composer Bağımlılıklarını Yükleyin:

composer install --optimize-autoloader

.env Dosyasını Ayarlayın:
cd BackEnd komutuyla proje dizininize geçin.
.env.example dosyasını cp .env.example .env komutu ile kopyalayın.
.env dosyasını açın ve aşağıdaki veritabanı ayarlarını kendi MySQL sunucunuza göre düzenleyin:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=egegen # Oluşturacağınız veritabanı adı
DB_USERNAME=root   # MySQL kullanıcı adınız
DB_PASSWORD=       # MySQL şifreniz (eğer yoksa boş bırakın)

CACHE_STORE=file   # Önbellek depolama için 'file' kullanılması önerilir
```APP_KEY` değerinin dolu olduğundan emin olun. Eğer boşsa:
```bash
php artisan key:generate

Veritabanı Oluşturun ve Migration'ları Çalıştırın:
PHPMyAdmin (veya başka bir veritabanı yöneticisi) kullanarak .env dosyasında belirttiğiniz isimde (örn: egegen) boş bir MySQL veritabanı oluşturun.
Ardından terminalde:

php artisan migrate:fresh

Bu komut, tüm tabloları silip (varsa) yeniden oluşturacaktır.

250.000 Adet Sahte Haber Verisi Ekleyin (Data Seeding):
Bu adım biraz zaman alabilir (birkaç dakika sürebilir), sabırla bekleyin. PHP'nin hafıza limitini artırmamız gerekebilir:

php -d memory_limit=2048M artisan db:seed --class=HaberlerTableSeeder

Depolama Linkini Oluşturun (Görsel Erişimi İçin):

php artisan storage:link

Bu komut, yüklenen görsellere web üzerinden erişim sağlar.

Laravel Geliştirme Sunucusunu Başlatın:

php artisan serve

Uygulama genellikle http://127.0.0.1:8000 adresinde çalışacaktır.

🖥️ API Endpoints (Postman İçin)
Tüm API rotaları /api önekiyle başlar ve bootstrap/app.php içinde tanımlanan api middleware grubu (bizim CheckBearerToken ve LogApiRequests middleware'larımız dahil) tarafından otomatik olarak korunur.

Varsayılan Bearer Token: 2BH52wAHrAymR7wP3CASt

1. Yeni Haber Oluşturma (POST)
URL: http://127.0.0.1:8000/api/haberler

Method: POST

Headers:

Authorization: Bearer 2BH52wAHrAymR7wP3CASt

Accept: application/json

Body (form-data):

baslik (text): Haber başlığı (Zorunlu, string, max:255)

icerik (text): Haber içeriği (Zorunlu, string)

yayinda_mi (text): Haber yayında mı? (1 veya 0) (Opsiyonel, boolean)

gorsel (file): Haber görseli (Opsiyonel, dosya, resim, max:2MB, mimes:jpeg,png,jpg,gif,webp)

2. Haberleri Listeleme ve Arama (GET)
URL: http://127.0.0.1:8000/api/haberler

Method: GET

Headers:

Authorization: Bearer 2BH52wAHrAymR7wP3CASt

Accept: application/json

Query Parameters (Opsiyonel Arama İçin):

arama: Arama yapmak istediğiniz kelime (başlık veya içerikte arar).

Örnek Arama: http://127.0.0.1:8000/api/haberler?arama=test

3. Haber Detayı Gösterme (GET)
URL: http://127.0.0.1:8000/api/haberler/{id} (Örn: http://127.0.0.1:8000/api/haberler/250001)

Method: GET

Headers:

Authorization: Bearer 2BH52wAHrAymR7wP3CASt

Accept: application/json

4. Haber Güncelleme (PUT)
URL: http://127.0.0.1:8000/api/haberler/{id} (Örn: http://127.0.0.1:8000/api/haberler/250001)

Method: PUT

Headers:

Authorization: Bearer 2BH52wAHrAymR7wP3CASt

Accept: application/json

Body (form-data): (Güncellemek istediğiniz alanları gönderin, hepsi opsiyoneldir.)

baslik (text): Yeni haber başlığı (Opsiyonel, string, max:255)

icerik (text): Yeni haber içeriği (Opsiyonel, string)

yayinda_mi (text): Haber yayında mı? (1 veya 0) (Opsiyonel, boolean)

gorsel (file): Yeni haber görseli (Opsiyonel, dosya, resim, max:2MB, mimes:jpeg,png,jpg,gif,webp). Not: Eğer görseli silmek istiyorsanız, gorsel_yolu key'ini null değeriyle (text tipiyle) gönderebilirsiniz.

5. Haber Silme (DELETE)
URL: http://127.0.0.1:8000/api/haberler/{id} (Örn: http://127.0.0.1:8000/api/haberler/250001)

Method: DELETE

Headers:

Authorization: Bearer 2BH52wAHrAymR7wP3CASt

Accept: application/json

Güvenlik ve Ek Özellikler
Bearer Token Kimlik Doğrulaması
Tüm haber yönetimi API rotaları, özel bir Bearer Token (2BH52wAHrAymR7wP3CASt) ile korunmaktadır. API isteklerinde Authorization başlığında bu token'ın doğru şekilde gönderilmesi gerekmektedir.

IP Kara Liste Mekanizması
Yanlış Bearer Token ile yapılan ardışık 10 deneme sonrası, ilgili IP adresi 10 dakika boyunca otomatik olarak kara listeye alınır ve bu IP'den gelen tüm API istekleri bloke edilir (403 Forbidden yanıtı döner). Blokaj süresi sonunda IP otomatik olarak serbest bırakılır.

İstek Loglama
Sisteme gelen tüm API istekleri (IP adresi, URL, HTTP metodu, durum kodu ve zaman bilgileriyle birlikte) veritabanındaki logs tablosuna otomatik olarak kaydedilmektedir.

Haber Görseli İşleme
Haber ekleme veya güncelleme sırasında bir görsel yüklendiğinde:

Görsel otomatik olarak WebP formatına dönüştürülür.

Görselin genişliği veya yüksekliği 800px'i geçmeyecek şekilde orantılı olarak yeniden boyutlandırılır (küçük görseller büyütülmez).

İşlenen görseller storage/app/public/haber_gorselleri klasörüne kaydedilir ve public/storage linki üzerinden erişilebilir hale gelir.

250.000 Adet Sahte Haber Verisi
Proje veritabanı, database/seeders/HaberlerTableSeeder.php aracılığıyla 250.000 adet sahte haber verisi ile doldurulmuştur. Bu, performans testleri ve geliştirme için büyük bir veri seti sunar.

Validasyon ve Türkçe Hata Mesajları
Haber ekleme ve güncelleme işlemleri, Laravel'in Form Request'leri (StoreHaberRequest, UpdateHaberRequest) ile güçlü bir şekilde doğrulanır. Doğrulama kuralları ihlal edildiğinde, kullanıcıya Türkçe ve anlaşılır hata mesajları döndürülür.

Postman Collection
Bu API'yi kolayca test edebilmeniz için bir Postman Collection dosyası sağlanmıştır. Bu koleksiyonu Postman'inize içe aktararak tüm yukarıdaki API uç noktalarını ve örnek istekleri hemen kullanmaya başlayabilirsiniz.

Postman'i açın.

Sol taraftaki "Collections" sekmesine gidin.

"Import" (İçe Aktar) butonuna tıklayın.

"Link" sekmesini seçin ve size sağlanan Postman Collection dosyasının linkini yapıştırın veya dosyayı bilgisayarınızdan seçerek içe aktarın.

Clean Code Prensipleri
Proje geliştirilirken "Clean Code" prensiplerine (temiz kod) uyulmuştur. Bu, kodun okunabilir, anlaşılır, kolayca değiştirilebilir ve sürdürülebilir olmasını sağlamak amacıyla yapılmıştır. Anlamlı isimlendirmeler, tek sorumluluk ilkesine uygun fonksiyon ve sınıflar, DRY (Don't Repeat Yourself) prensibi ve akıllıca yorum satırları kullanılmıştır.