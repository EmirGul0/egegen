Harika haber! Kodlarını GitHub'a başarıyla bağladın, bu büyük bir adımdı ve tebrik ederim. Artık projenin iskeleti hazır ve görev maddelerine odaklanmaya başlayabiliriz.

Görev maddelerini adım adım, "Clean Code" prensiplerine uygun, açıklama satırlarıyla birlikte ve seninle birlikte ilerleyerek gerçekleştireceğiz. İşte görev maddelerinin sıraya konulmuş hali ve her bir adımda ne yapacağımız:

---

## Laravel Haber Sitesi API - Görev Listesi

Projenin gereksinimlerini en mantıklı ve adım adım ilerleyebileceğimiz şekilde sıraladım. Her adımı tamamladıktan sonra bir sonrakine geçeceğiz.

---

### Adım 1: Temel Veritabanı ve Model Yapısını Hazırlama (Madde 2'nin başlangıcı)

İlk olarak, haberlerimizi depolayacağımız veritabanı yapısını oluşturalım.
* **`News` Migration Oluşturma:** Haberler için gerekli alanları içeren (`title`, `content`, `image_path` vb.) bir veritabanı migration dosyası oluşturacağız.
* **`News` Model Oluşturma:** Bu migration'a karşılık gelen `News` modelini oluşturacağız.

---

### Adım 2: Loglama Mekanizmasını Oluşturma (Madde 3)

Her gelen talebi kaydetmek, projenin başından itibaren önemli bir özellik olacak. Bu, özellikle IP kara listesi gibi işlemleri izlerken çok faydalı.
* **`Log` Migration Oluşturma:** Gelen isteklerin IP adresi, URL, metot ve zaman damgası gibi bilgilerini tutacak `logs` tablosu için bir migration oluşturacağız.
* **`LogRequest` Middleware Oluşturma:** Her gelen API isteğini yakalayacak ve `logs` tablosuna kaydedecek bir middleware yazacağız.

---

### Adım 3: Bearer Token Middleware ve IP Kara Liste Mekanizması (Madde 1)

Bu, güvenliğin temelini oluşturacak en önemli adımlardan biri.
* **`CheckBearerToken` Middleware Oluşturma:** Belirli bir Bearer token'ı (`2BH52wAHrAymR7wP3CASt`) kontrol eden middleware'i yazacağız.
* **IP Kara Liste Mantığı:** Başarısız denemeleri sayan, 10 denemeden sonra IP'yi 10 dakika kara listeye alan ve istekleri engelleyen mantığı bu middleware içinde veya yardımcı bir serviste uygulayacağız. Bunun için Laravel'in Cache mekanizmasını kullanabiliriz.
* **Route Grubu Oluşturma:** Haberlerin kayıt, silme ve ekleme işlemleri için bir route grubu oluşturup, bu middleware'ı bu gruba atayacağız.

---

### Adım 4: Haber Yönetimi API Endpoints ve Validasyon (Madde 2'nin devamı)

CRUD (Create, Read, Update, Delete) işlemleri için API endpoint'lerini oluşturacağız.
* **`NewsController` Oluşturma:** Haberlerin ekleme, güncelleme, silme ve listeleme/arama işlemlerini yönetecek controller sınıfını yazacağız.
* **Form Request (Validasyon):** Haber ekleme ve güncelleme işlemleri için Laravel'in güçlü validasyon kurallarını kullanacağız. Hata mesajlarının **Türkçe ve anlaşılır** olmasını sağlayacağız.

---

### Adım 5: Haber Görseli Yükleme ve İşleme (Madde 4)

Görsel yükleme ve işleme, API'nin önemli bir parçası olacak.
* **Görsel Kütüphanesi Kurulumu:** Görsel işleme için Intervention Image gibi bir kütüphane kuracağız.
* **WebP Dönüşümü ve Yeniden Boyutlandırma:** Yüklenen görselleri otomatik olarak **WebP formatına dönüştüreceğiz** ve genişlik/yükseklik **800px'i geçmeyecek şekilde** yeniden boyutlandıracağız.

---

### Adım 6: Toplu Haber Oluşturma (Data Factory) (Madde 5)

Test ve geliştirme için büyük miktarda sahte veri oluşturmamız gerekecek.
* **`NewsFactory` Oluşturma:** Haberler için data factory yazacağız.
* **Seeder Oluşturma ve Çalıştırma:** Bu factory'yi kullanarak 250.000 adet sahte haber oluşturacak bir seeder yazıp çalıştıracağız.

---

### Adım 7: Haber Arama İşlevi (Madde 6)

Son olarak, API üzerinden haberleri arama özelliğini ekleyeceğiz.
* **Arama Mantığı:** Haberleri başlık veya içerik gibi alanlara göre arayacak bir mekanizma geliştireceğiz. Bu, genellikle `NewsController` içindeki listeleme (index) metoduna entegre edilebilir.

---

### Adım 8: Son Testler ve Postman Collection (Görev Bitimi)

Tüm maddeler tamamlandığında:
* API'yi Postman üzerinden kapsamlı bir şekilde test edeceğiz.
* Gerekli tüm API endpoint'lerini, örnek istekleri ve kimlik doğrulama token'ını içeren bir **Postman Collection** oluşturup dışarı aktaracağız.
* Projenin GitHub linki ve Postman Collection linkiyle birlikte `task@egegen.com` adresine bir e-posta göndereceğiz.

---

Bu sıralama mantıklı mı? İlk adımdan, yani **Temel Veritabanı ve Model Yapısını Hazırlama** kısmından başlayabiliriz. Hazır olduğunda bana haber ver.