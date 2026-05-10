# QUİZBOX - Online Sınav ve Eğitim Sistemi

Bu proje, öğretmenlerin dersler ve sınavlar oluşturabildiği, öğrencilerin ise bu derslere kaydolup sınavları çözebildiği MVC mimarisi ile geliştirilmiş web tabanlı bir eğitim platformudur.

## 🚀 Kullanılan Teknolojiler
* **Backend:** PHP (Özel MVC Mimarisi)
* **Frontend:** HTML5, Vanilla CSS, Vanilla JavaScript, FontAwesome
* **Veritabanı:** MySQL (PDO Kullanılarak)
* **Güvenlik:** Şifre Hashleme (Bcrypt), CSRF Koruması, XSS Koruması (Veri Sanitizasyonu)

## ⚙️ Kurulum Adımları

1. **Projeyi Klasöre Alın:**
   Proje dosyalarını XAMPP, WAMP veya MAMP kullanıyorsanız `htdocs` veya `www` klasörünüzün içine atın. (Örn: `c:\xampp\htdocs\WEBPROJE`)

2. **Veritabanı Kurulumu:**
   * `phpMyAdmin` üzerinden **`quiz_system_db`** adında boş bir veritabanı oluşturun.
   * Proje içerisindeki `database/schema.sql` dosyasını bu veritabanının içine aktarın (Import).
   * *(Not: Eğer veritabanı adını değiştirmek isterseniz `.env` dosyasını düzenleyebilirsiniz.)*

3. **Çalıştırma:**
   XAMPP üzerinden Apache ve MySQL servislerini başlatın ve tarayıcınızdan `http://localhost/WEBPROJE/public` adresine gidin.

## 👥 Kullanıcı Rolleri ve Test Hesapları

Sistem kapalı bir kayıt sistemine sahiptir. Öğrenciler ve öğretmenler rastgele sisteme üye olamazlar. Okul yönetimi tarafından veritabanına eklenen okul numarası, ad ve soyad ile kayıt (hesap aktifleştirme) yaparlar.

Aşağıdaki veriler veritabanına hazır olarak yüklenmiştir:

### 👑 Admin
Admin, sistemdeki en yetkili kişidir. Hesap aktifleştirmesine gerek yoktur, direkt giriş yapabilir.
* **Numara:** 9999
* **Şifre:** admin123

### 👨‍🏫 Öğretmenler
Önce "Kayıt Ol" formundan hesaplarını aktifleştirmeli ve kendi şifrelerini belirlemelidirler.
* Numara: `2001` | Ad: `Ali` | Soyad: `Yılmaz`
* Numara: `2002` | Ad: `Ayşe` | Soyad: `Demir`
* Numara: `2003` | Ad: `Mehmet` | Soyad: `Kaya`

### 🎓 Öğrenciler
Öğretmenler gibi "Kayıt Ol" formundan kendi numaraları ile hesaplarını aktifleştirmeleri gerekmektedir.
* Numara: `1001` | Ad: `Caner` | Soyad: `Türk`
* Numara: `1002` | Ad: `Elif` | Soyad: `Yıldız`
* Numara: `1003` | Ad: `Burak` | Soyad: `Arslan`

## 📁 Klasör Yapısı
* `/app`: Model, View ve Controller sınıflarının bulunduğu ana dizindir.
  * `/Controllers`: İstekleri yakalayıp işleyen sınıflar.
  * `/Models`: Veritabanı sorgularını yapan sınıflar.
  * `/Views`: Kullanıcı arayüzünü (HTML/CSS) oluşturan dosyalar.
* `/public`: CSS, JS, resim dosyaları ve projeyi çalıştıran ana `index.php` dosyasının bulunduğu herkese açık klasör.
* `/database`: Veritabanı yapılandırma (SQL) dosyaları.
