# Moodle AI Chatbot Plugin 

Bu proje, Moodle tabanlı bir öğrenim yönetim sistemine (LMS) **AI destekli bir Chatbot** entegre etmeyi amaçlar. Chatbot, belirli PDF kitaplardan oluşturulan **vektör veritabanı** (ChromaDB) üzerinden kullanıcı sorularına yanıt üretir. Arka planda Google Gemini API ve Sentence Transformers modeli kullanılır.

## Proje Yapısı
```
moodle-chatbot-plugin/
├── chatbot.py
├── lang/
│   └── en/
│       └── local_chatbot.php
├── db/
│   └── access.php
├── templates/
│   └── chatbutton.mustache
├── version.php
├── fullchat.php
├── lib.php
├── api.php
├── adminreport.php
├── settings.php
└── README.md
```
## Dosya Açıklamaları

`chatbot.py`: 

- Amaç: Komut satırından (CLI) veya Moodle web arayüzünden gelen soruları alır.

- İşlevi: ChromaDB vektör veritabanından benzer belgeleri alır, Gemini LLM ile anlamlı bir cevap üretir.

- Bağımlılıklar: google.generativeai, chromadb, sentence-transformers, transformers

`lang/en/local_chatbot.php`: Eklentiye ait İngilizce dil dosyasıdır. Moodle arayüzünde görünen metinlerin çevirilerini içerir:
  ```
  $string['pluginname'] = 'Chatbot';
  $string['chatbotreports'] = 'Chatbot Etkileşim Raporu';
  ```
- Bu metinler, Moodle içinde get_string() fonksiyonlarıyla çağrılır.

`db/access.php`:  Eklentinin tanımladığı özel yetkileri (capabilities) içerir. Örneğin local/chatbot:view yetkisi ile sadece belirli rollerin bu eklentiye erişmesi sağlanabilir:
  ```
  'captype' => 'read',
  'contextlevel' => CONTEXT_SYSTEM,
  'archetypes' => [ 'student' => CAP_ALLOW, ... ]
  ```

`templates/chatbutton.mustache`: Sayfa altına yerleştirilen chatbot butonunun HTML şablonudur. lib.php dosyasındaki 'local_chatbot_before_footer()' fonksiyonu ile render edilir.

`version.php`: Eklentinin Moodle tarafından tanınmasını sağlar. Bileşenin adı, versiyon numarası, minimum Moodle sürümü gibi bilgiler içerir. 

`fullchat.php`: Girilen kurs sekmesinde sağ alt kısımda bulunan chatbot butonuna basıldıktan sonra açılan pencere. Query ile API'ye istek atar, yanıtları dinamik olarak ekrana yansıtır. CSS ile stillendirilmiş, mesaj balonları, yükleniyor animasyonu gibi detaylar içerir.

`lib.php`: 
Moodle’ın hook mekanizmalarını kullanarak:

- Kurs sayfalarına yöneticiye özel navigasyon linki (adminreport.php)

- Sayfa altına chatbutton.mustache butonunu yerleştirme

işlevlerini tanımlar:
  ```
  function local_chatbot_before_footer() { ... }
  function local_chatbot_extend_navigation_course(...) { ... }
  ```

`api.php`: AJAX ile çağrılan arka uç API’dir. Kullanıcının gönderdiği soruyu alır, Python scriptine iletir ve cevabı geri döndürür. Ayrıca bu etkileşimi `chatbot_logs` tablosuna kaydeder. 

`adminreport.php`: Sadece yöneticilerin erişebileceği bir rapor ekranıdır. Kullanıcıların soruları,chatbot yanıtlarını ve kullanıcı adını veritabanından alarak tablo şeklinde gösterir. 

`settings.php`: Moodle yönetim paneli altında ayar sayfası altında bulunan reports kısmının altında "Chatbot Etkileşim Raporu" ara yüzü ile adminreport sayfasına erişmemiz sağlayan dosya. 

## Tablo OLuşturma: "chatbot_logs"
```
CREATE TABLE mdl_chatbot_logs (
    id BIGINT(10) NOT NULL AUTO_INCREMENT,
    userid BIGINT(10) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    timecreated BIGINT(10) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_userid (userid)
);

```

## API Anahtarı ve Ortam Değişkenleri
`chatbot.py` aşağıdaki ortam değişkenlerini kullanır:

- GOOGLE_API_KEY → Gemini LLM için gerekli.

- .env yerine, sunucunun ortam değişkenlerinde tanımlanmalıdır (/etc/environment veya export).

## Kurulum ve Çalıştırma
1. chromadb/ klasörünü ve chatbot.py'yi Moodle sunucusuna yerleştir.

2. Gerekli Python ortamını oluştur:
      ```
      python3 -m venv chatbotenv
      source chatbotenv/bin/activate
      pip install -r requirements.txt
      ```

