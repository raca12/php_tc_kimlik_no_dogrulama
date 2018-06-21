
# PHP ile TC Kimlik No Doğrulama

Özellikleri:

1)Algoritma ile TC Kimlik No ve Yabancı Kimlik No doğruluk kontrolü yapar,

2)Nüfus ve Vatandaşlık İşleri Genel Müdürlüğü ile 
-TC Kimlik No, 
-Yabancı Kimlik No,
-Nüfus Cüzdanı,
-Yeni Kimlik Kartı doğruluk kontrolü yapar,

3)PHP Curl ve Soap bağlantısını destekler,

4)PHP Curl ve Soap aktif değilse sadece Algoritma ile doğruluk kontrolü yapar.

## Kullanım

include 'tckimlikno.php';
$tckimliknokontrolu = new TCKimlikNoSinifi;

//Kimlik No Algoritma Doğrulama   (Nüfus Müdürlüğüne bağlanmaz)
$sonuc= $tckimliknokontrolu->TCKimlikNoDogrulaAlgoritma("11111111111");

//TC kimlik No Doğrulama    (Nüfus Müdürlüğüne bağlanıp kontrol eder)
$sonuc= $tckimliknokontrolu->TCKimlikNoDogrula("11111111111","Ad","Soyad","Doğum Yılı");

//Yabancı Kimlik no Doğrulama   (Nüfus Müdürlüğüne bağlanıp kontrol eder)
$sonuc= $tckimliknokontrolu->YabanciKimlikNoDogrula("11111111111","Ad","Soyad","Doğum Günü","Doğum Ayı","Doğum Yılı");

//TC Kimlik No ve Yabancı Kimlik No Doğrulama  (Nüfus Müdürlüğüne bağlanıp kontrol eder)
$sonuc= $tckimliknokontrolu->KimlikNoDogrula("11111111111","Ad","Soyad","Doğum Günü","Doğum Ayı","Doğum Yılı");

 //Eski Nüfus Cüzdanı Doğrulama  (Nüfus Müdürlüğüne bağlanıp kontrol eder)
 $sonuc= $tckimliknokontrolu->NufusCuzdaniDogrula("11111111111","Ad","Soyad","Doğum Günü","Doğum Ayı","Doğum Yılı","Nüfus Cüzdanı Seri","Nüfus Cüzdanı No");
 
 //Yeni Kimlik Kartı Doğrulama  (Nüfus Müdürlüğüne bağlanıp kontrol eder)
 $sonuc= $tckimliknokontrolu->KimlikKartiDogrula("11111111111","Ad","Soyad","Doğum Günü","Doğum Ayı","Doğum Yılı","Kimlik Kartı Seri No");
   

### Lisans
Creative Commons Atıf-GayriTicari-Türetilemez 4.0 Uluslararası Kamu Lisansı ile lisanslanmıştır. Detaylar için LİSANS dosyasına bakın.
