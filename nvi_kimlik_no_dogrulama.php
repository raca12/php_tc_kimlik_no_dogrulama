
<?php
// 21.06.2018  Murat Karagöz    https://github.com/muratkaragoz/php_ve_algoritma_ile_tc_kimlik_no_dogrulama
//******This work is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License. Link: https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode


if(@$_POST["gonder"])
{




//**********************TC KİMLİK NO KONTROLÜ BAŞLANGIÇ**********************************//		

			

		
		            //BÜYÜK HARF ÇEVİR
				    function buyuk_harf_cevir($text) 
					{
					$text = trim($text);
					$search = array('ç','ğ','ı','ö','ş','ü','i');
					$replace = array('Ç','Ğ','I','Ö','Ş','Ü','İ');
					$new_text = str_replace($search,$replace,$text);
					return mb_strtoupper($new_text);
					}

					
					$TCKimlikNo = $_POST["KimlikNo"];
		            $Ad =buyuk_harf_cevir($_POST["Ad"]);
					$Soyad =buyuk_harf_cevir($_POST["Soyad"]);
					$DogumYili =$_POST["DogumYil"];

					$DogumGun = $_POST["DogumGun"];
					$DogumAy = $_POST["DogumAy"];
					$DogumYil =$_POST["DogumYil"];
					

//**********************ALGORİTMAYLA TC KİMLİK NO KONTROLÜ BAŞLANGIÇ**********************************//
function TCKimlikNoDogrula_Algoritma($TCKimlikNo)
	{			
			if ( strlen($TCKimlikNo) == 11 )   //onbir haneyse işleme devam et
			{
			$basamak = str_split($TCKimlikNo);  //basamaklarına ayır
			$basamak1 = $basamak[0];
			$basamak2 = $basamak[1];
			$basamak3 = $basamak[2];
			$basamak4 = $basamak[3];
			$basamak5 = $basamak[4];
			$basamak6 = $basamak[5];
			$basamak7 = $basamak[6];
			$basamak8 = $basamak[7];
			$basamak9 = $basamak[8];
			$basamak10 = $basamak[9];
			$basamak11 = $basamak[10];

			$basamak10_test=fmod( ( $basamak1 + $basamak3 + $basamak5 + $basamak7 + $basamak9 ) * 7  - ( $basamak2 + $basamak4 + $basamak6 + $basamak8 )     ,10) ;
			$basamak11_test = fmod( $basamak1 + $basamak2 + $basamak3 + $basamak4 + $basamak5 + $basamak6 + $basamak7 + $basamak8 + $basamak9 + $basamak10     ,10);
			}
			
			if ( strlen($TCKimlikNo) != 11 )   //onbir hane değilse geçersizdir.
			{
				$sonuc="false";
			}
			elseif ($basamak1 == 0)   //birinci basamak sıfır olamaz
			{
				$sonuc="false";
			}
			elseif (!is_numeric($basamak1) or !is_numeric($basamak2) or !is_numeric($basamak3) or   // rakam yoksa geçersizdir
				  !is_numeric($basamak4) or !is_numeric($basamak5) or !is_numeric($basamak6) or 
				  !is_numeric($basamak7) or !is_numeric($basamak8) or !is_numeric($basamak9) or 
				  !is_numeric($basamak10) or !is_numeric($basamak11) )
			{
				$sonuc="false";		
			}
			elseif($basamak10_test != $basamak10) // T.C. Kimlik Numaralarımızın 1. 3. 5. 7. ve 9. hanelerinin toplamının 7 katından, 2. 4. 6. ve 8. hanelerinin toplamı çıkartıldığında, elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 10. haneyi verir.
			{
				$sonuc="false";
			}
			elseif($basamak11_test != $basamak11 )   // 1. 2. 3. 4. 5. 6. 7. 8. 9. ve 10. hanelerin toplamından elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 11. haneyi verir.
			{
				$sonuc="false";
			}
			else
			{
				$sonuc="true";
			}
			
	return 	$sonuc;	
	}
//**********************ALGORİTMAYLA TC KİMLİK NO KONTROLÜ BİTİŞ**********************************//


//**********************PHP CURL Bağlantısı **********************************//
					function TCKimlikNoDogrula_Curl($TCKimlikNo,$Ad,$Soyad,$DogumYili)
					{
					$gonder = '<?xml version="1.0" encoding="utf-8"?>
					<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
					<soap:Body>
					<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<TCKimlikNo>'.$TCKimlikNo.'</TCKimlikNo>
					<Ad>'.$Ad.'</Ad>
					<Soyad>'.$Soyad.'</Soyad>
					<DogumYili>'.$DogumYili.'</DogumYili>
					</TCKimlikNoDogrula>
					</soap:Body>
					</soap:Envelope>';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx" );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt($ch, CURLOPT_POST, true );
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $gonder);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'POST /Service/KPSPublic.asmx HTTP/1.1',
					'Host: tckimlik.nvi.gov.tr',
					'Content-Type: text/xml; charset=utf-8',
					'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
					'Content-Length: '.strlen($gonder)
					));
					$gelen = curl_exec($ch);
					curl_close($ch);
					$gelensonuc=strip_tags($gelen);
					
					if ($gelensonuc =="1" or $gelensonuc =="true")
					{
						$sonuc="true";
					}
					else
					{
						$sonuc="false";
					}
					
					return $sonuc;
					}
//**********************PHP Curl Bağlantısı **********************************//


//**********************PHP SOAP Bağlantısı **********************************//	
					function TCKimlikNoDogrula_SoapClient($TCKimlikNo,$Ad,$Soyad,$DogumYili)
					{
						
						$https = stream_context_create(
						[
					            'ssl' => 
								[
					            'verify_peer' => false,
 					           'verify_peer_name' => false
 					           ]
						]);
	


						$client = new SoapClient('https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL',
						array( 'trace' => 1,
					       'soapaction' => 'http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula',
						   'encoding' => 'UTF-8',
						   'user_agent' => '',
						   'keep_alive' => false,
						   'cache_wsdl' => WSDL_CACHE_NONE,    //wsdl önbellek kapalı
					       'stream_context' => $https  // Ssl certificate
						) );

					$gonder = new SoapVar('<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS"><TCKimlikNo>'.$TCKimlikNo.'</TCKimlikNo><Ad>'.$Ad.'</Ad><Soyad>'.$Soyad.'</Soyad><DogumYili>'.$DogumYili.'</DogumYili></TCKimlikNoDogrula>', XSD_ANYXML);

					$gelensonuc = $client->TCKimlikNoDogrula($gonder)->TCKimlikNoDogrulaResult;
					
					if ($gelensonuc =="1" or $gelensonuc =="true")
					{
						$sonuc="true";
					}
					else
					{
						$sonuc="false";
					}
					
					return $sonuc;
					}
//**********************PHP SOAP Bağlantısı **********************************//













//*******************************************YABANCI KİMLİK NO DOĞRULAMA *****************************************
				
					function YabanciKimlikNoDogrula_Curl($TCKimlikNo,$Ad,$Soyad,$DogumGun,$DogumAy,$DogumYil)
					{					
					$gonder = '<?xml version="1.0" encoding="utf-8"?>
					<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
					<soap:Body>
					<YabanciKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<KimlikNo>'.$TCKimlikNo.'</KimlikNo>
					<Ad>'.$Ad.'</Ad>
					<Soyad>'.$Soyad.'</Soyad>
					<DogumGun>'.$DogumGun.'</DogumGun>
					<DogumAy>'.$DogumAy.'</DogumAy>
					<DogumYil>'.$DogumYil.'</DogumYil>
					</YabanciKimlikNoDogrula>
					</soap:Body>
					</soap:Envelope>';
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublicYabanciDogrula.asmx" );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt($ch, CURLOPT_POST, true );
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $gonder);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'POST /Service/KPSPublicYabanciDogrula.asmx HTTP/1.1',
					'Host: tckimlik.nvi.gov.tr',
					'Content-Type: text/xml; charset=utf-8',
					//'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/YabanciKimlikNoDogrula',
					'Content-Length: '.strlen($gonder)
					));
					$gelen = curl_exec($ch);
					curl_close($ch);
					$gelensonuc=strip_tags($gelen);
					
					if ($gelensonuc =="1" or $gelensonuc =="true")
					{
						$sonuc="true";
					}
					else
					{
						$sonuc="false";
					}
					
					return $sonuc;
					}
					
					
					function YabancıKimlikNoDogrula_SoapClient ($TCKimlikNo,$Ad,$Soyad,$DogumGun,$DogumAy,$DogumYil)
					{
						
						$https = stream_context_create(
						[
					            'ssl' => 
								[
					            'verify_peer' => false,
 					           'verify_peer_name' => false
 					           ]
						]);
	


						$client = new SoapClient('https://tckimlik.nvi.gov.tr/Service/KPSPublicYabanciDogrula.asmx?WSDL',
						array( 'trace' => 1,
					       'soapaction' => 'http://tckimlik.nvi.gov.tr/WS/YabanciKimlikNoDogrula',
						   'encoding' => 'UTF-8',
						   'user_agent' => '',
						   'keep_alive' => false,
						   'cache_wsdl' => WSDL_CACHE_NONE,    //wsdl önbellek kapalı
					       'stream_context' => $https  // Ssl certificate
						) );

					$gonder = new SoapVar('<YabanciKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS"> <KimlikNo>'.$TCKimlikNo.'</KimlikNo><Ad>'.$Ad.'</Ad><Soyad>'.$Soyad.'</Soyad><DogumGun>'.$DogumGun.'</DogumGun><DogumAy>'.$DogumAy.'</DogumAy><DogumYil>'.$DogumYil.'</DogumYil> </YabanciKimlikNoDogrula>',XSD_ANYXML);

					$gelensonuc = $client->YabanciKimlikNoDogrula($gonder)->YabanciKimlikNoDogrulaResult;
					
					if ($gelensonuc =="1" or $gelensonuc =="true")
					{
						$sonuc="true";
					}
					else
					{
						$sonuc="false";
					}
					
					
					return $sonuc;
					}
//**********************PHP SOAP Bağlantısı **********************************//


function YabanciKimlikMi($TCKimlikNo)
{
			$basamak = str_split($TCKimlikNo);  //basamaklarına ayır
			$basamak1 = $basamak[0];
			$basamak2 = $basamak[1];
			
			if ($basamak1=="9" and $basamak2=="9")   // Kimlik No 99 ile başlıyorsa Yabancı kimlik nodur
			{
				$sonuc="true";
			}
			else
			{
				$sonuc="false";
			}
return $sonuc;
}



$algoritma_sonuc = TCKimlikNoDogrula_Algoritma($TCKimlikNo);



if ($algoritma_sonuc =="true")    //Algoritma doğruysa Nüfüs Müdürlüğünden Kontrol et
{
	
		if( function_exists('curl_version') == true)   //curl açık mı diye kontrol et
			{
				if (YabanciKimlikMi($TCKimlikNo) =="true" )
				{
				$sonuc = YabanciKimlikNoDogrula_Curl($TCKimlikNo,$Ad,$Soyad,$DogumGun,$DogumAy,$DogumYil);
				}
				else
				{
				$sonuc = TCKimlikNoDogrula_SoapClient($TCKimlikNo,$Ad,$Soyad,$DogumYili);
				}
			}
		elseif ( class_exists('SOAPClient') == true)   //soap açık mı diye kontrol et
			{
				if (YabanciKimlikMi($TCKimlikNo) =="true" )
				{
				$sonuc = YabancıKimlikNoDogrula_SoapClient($TCKimlikNo,$Ad,$Soyad,$DogumGun,$DogumAy,$DogumYil);
				}
				else
				{
			     $sonuc = TCKimlikNoDogrula_SoapClient($TCKimlikNo,$Ad,$Soyad,$DogumYili);
				}
			}
		elseif( function_exists('curl_version') == false and class_exists('SOAPClient') == false) //curl ve soap çalışmıyorsa sadece algoritma kontrolü yap
		    {
	        $sonuc = "true";   			
		    }

}




if ($algoritma_sonuc !="true")  //Algoritma yanlışsa Nüfüs Müdürlüğüyle hiç uğraşma
{
	        $sonuc = "false";
}
	

	



//**********************TC KİMLİK NO KONTROLÜ BİTİŞ**********************************//		

}
?>













<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>PHP ile Yabancı Kimlik No Doğrulama</title>
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<style type="text/css">
.container { max-width: 800px !important; }
.col-sm-10 { max-width: 420px !important; }
.col-xs-4 { max-width: 150px !important; }
.col-sm-2 control-label { max-width: 100px !important; }

</style
</head>
<body>
<div class="container"> <div class="row">

<h3>T.C. Nüfus Müdürlüğü ile Kimlik No Doğrulama</h3>
<hr />
<?php if(@$_POST["gonder"]){
if(@$sonuc=="true"){
echo '<div class="alert alert-success"><strong>BAŞARILI</strong> Bilgiler eşleşti!</div>';
}else{
echo '<div class="alert alert-danger"><strong>HATA!</strong> Bilgiler uyuşmadı!</div>';
}
?>
<hr />
<?php } ?>
<form method="post" enctype="multipart/form-data" class="form-horizontal">


<div class="form-group">
<label for="dp" class="col-sm-2 control-label">TC Kimlik No / Yabancı Kimlik No</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="KimlikNo" placeholder="TC Kimlik numaranızı girin" value="<?php if(isset($_POST["KimlikNo"])){ echo $_POST["KimlikNo"];}?>" required />
</div>
</div>

<div class="form-group">
<label for="tid" class="col-sm-2 control-label">İsim</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="Ad" placeholder="Adınızı girin" value="<?php if(isset($_POST["Ad"])){ echo buyuk_harf_cevir($_POST["Ad"]);}?>" required />
</div>
</div>
<div class="form-group">
<label for="ck" class="col-sm-2 control-label">Soyad</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="Soyad" placeholder="Soyadınızı girin" value="<?php if(isset($_POST["Soyad"])){ echo buyuk_harf_cevir($_POST["Soyad"]);}?>" required />
</div>
</div>





<div class="form-group">
<label for="input-birthdate" class="col-sm-2 control-label">Doğum Tarihi</label>
    <div class="col-sm-10">
    <div class="form-group row">
	
    <div class="col-xs-4">
<select class="form-control" name="DogumGun" data-width="auto">
  <option value="" disabled="disabled" <?php if(!isset($_POST["DogumGun"]) ){ echo "selected"; } ?> > - Gün - </option>
  <option value="1" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==1 ){ echo "selected"; } ?> >1</option>
  <option value="2" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==2 ){ echo "selected"; } ?> >2</option>
  <option value="3" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==3 ){ echo "selected"; } ?> >3</option>
  <option value="4" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==4 ){ echo "selected"; } ?> >4</option>
  <option value="5" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==5 ){ echo "selected"; } ?> >5</option>
  <option value="6" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==6 ){ echo "selected"; } ?> >6</option>
  <option value="7" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==7 ){ echo "selected"; } ?> >7</option>
  <option value="8" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==8 ){ echo "selected"; } ?> >8</option>
  <option value="9" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==9 ){ echo "selected"; } ?> >9</option>
  <option value="10" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==10 ){ echo "selected"; } ?> >10</option>
  <option value="11" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==11 ){ echo "selected"; } ?> >11</option>
  <option value="12" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==12 ){ echo "selected"; } ?> >12</option>
  <option value="13" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==13 ){ echo "selected"; } ?> >13</option>
  <option value="14" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==14 ){ echo "selected"; } ?> >14</option>
  <option value="15" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==15 ){ echo "selected"; } ?> >15</option>
  <option value="16" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==16 ){ echo "selected"; } ?> >16</option>
  <option value="17" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==17 ){ echo "selected"; } ?> >17</option>
  <option value="18" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==18 ){ echo "selected"; } ?> >18</option>
  <option value="19" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==19 ){ echo "selected"; } ?> >19</option>
  <option value="20" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==20 ){ echo "selected"; } ?> >20</option>
  <option value="21" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==21 ){ echo "selected"; } ?> >21</option>
  <option value="22" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==22 ){ echo "selected"; } ?> >22</option>
  <option value="23" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==23 ){ echo "selected"; } ?> >23</option>
  <option value="24" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==24){ echo "selected"; } ?> >24</option>
  <option value="25" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==25){ echo "selected"; } ?> >25</option>
  <option value="26" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==26){ echo "selected"; } ?> >26</option>
  <option value="27" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==27 ){ echo "selected"; } ?> >27</option>
  <option value="28" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==28 ){ echo "selected"; } ?> >28</option>
  <option value="29" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==29 ){ echo "selected"; } ?> >29</option>
  <option value="30" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==30 ){ echo "selected"; } ?> >30</option>
  <option value="31" <?php if(isset($_POST["DogumGun"]) and $_POST["DogumGun"]==31 ){ echo "selected"; } ?> >31</option>
</select></div>

  <div class="col-xs-4">
<select class="form-control" name="DogumAy" data-width="auto">
  <option value="" disabled="disabled" <?php if(!isset($_POST["DogumAy"]) ){ echo "selected"; } ?> /> - Ay -</option>
  <option value="1" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==1 ){ echo "selected"; } ?>/>1</option>
  <option value="2" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==2 ){ echo "selected"; } ?>/>2</option>
  <option value="3" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==3 ){ echo "selected"; } ?>/>3</option>
  <option value="4" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==4 ){ echo "selected"; } ?>/>4</option>
  <option value="5" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==5 ){ echo "selected"; } ?>/>5</option>
  <option value="6" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==6 ){ echo "selected"; } ?>/>6</option>
  <option value="7" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==7 ){ echo "selected"; } ?>/>7</option>
  <option value="8" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==8 ){ echo "selected"; } ?>/>8</option>
  <option value="9" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==9 ){ echo "selected"; } ?>/>9</option>
  <option value="10" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==10 ){ echo "selected"; } ?>/>10</option>
  <option value="11" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==11 ){ echo "selected"; } ?>/>11</option>
  <option value="12" <?php if(isset($_POST["DogumAy"]) and $_POST["DogumAy"]==12 ){ echo "selected"; } ?>/>12</option>
</select></div>


<div class="col-xs-4">
<select class="form-control" name="DogumYil" data-width="auto">
  <option value="" disabled="disabled" <?php if(!isset($_POST["DogumYil"]) ){ echo "selected"; } ?> /> - Yıl -</option>
<option value="1900" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1900 ){ echo "selected"; } ?> >1900</option>
<option value="1901" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1901 ){ echo "selected"; } ?> >1901</option>
<option value="1902" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1902 ){ echo "selected"; } ?> >1902</option>
<option value="1903" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1903 ){ echo "selected"; } ?> >1903</option>
<option value="1904" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1904 ){ echo "selected"; } ?> >1904</option>
<option value="1905" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1905 ){ echo "selected"; } ?> >1905</option>
<option value="1906" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1906 ){ echo "selected"; } ?> >1906</option>
<option value="1907" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1907 ){ echo "selected"; } ?> >1907</option>
<option value="1908" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1908 ){ echo "selected"; } ?> >1908</option>
<option value="1909" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1909 ){ echo "selected"; } ?> >1909</option>
<option value="1910" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1910 ){ echo "selected"; } ?> >1910</option>
<option value="1911" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1911 ){ echo "selected"; } ?> >1911</option>
<option value="1912" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1912 ){ echo "selected"; } ?> >1912</option>
<option value="1913" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1913 ){ echo "selected"; } ?> >1913</option>
<option value="1914" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1914 ){ echo "selected"; } ?> >1914</option>
<option value="1915" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1915 ){ echo "selected"; } ?> >1915</option>
<option value="1916" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1916 ){ echo "selected"; } ?> >1916</option>
<option value="1917" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1917 ){ echo "selected"; } ?> >1917</option>
<option value="1918" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1918 ){ echo "selected"; } ?> >1918</option>
<option value="1919" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1919 ){ echo "selected"; } ?> >1919</option>
<option value="1920" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1920 ){ echo "selected"; } ?> >1920</option>
<option value="1921" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1921 ){ echo "selected"; } ?> >1921</option>
<option value="1922" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1922 ){ echo "selected"; } ?> >1922</option>
<option value="1923" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1923 ){ echo "selected"; } ?> >1923</option>
<option value="1924" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1924 ){ echo "selected"; } ?> >1924</option>
<option value="1925" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1925 ){ echo "selected"; } ?> >1925</option>
<option value="1926" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1926 ){ echo "selected"; } ?> >1926</option>
<option value="1927" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1927 ){ echo "selected"; } ?> >1927</option>
<option value="1928" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1928 ){ echo "selected"; } ?> >1928</option>
<option value="1929" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1929 ){ echo "selected"; } ?> >1929</option>
<option value="1930" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1930 ){ echo "selected"; } ?> >1930</option>
<option value="1931" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1931 ){ echo "selected"; } ?> >1931</option>
<option value="1932" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1932 ){ echo "selected"; } ?> >1932</option>
<option value="1933" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1933 ){ echo "selected"; } ?> >1933</option>
<option value="1934" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1934 ){ echo "selected"; } ?> >1934</option>
<option value="1935" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1935 ){ echo "selected"; } ?> >1935</option>
<option value="1936" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1936 ){ echo "selected"; } ?> >1936</option>
<option value="1937" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1937 ){ echo "selected"; } ?> >1937</option>
<option value="1938" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1938 ){ echo "selected"; } ?> >1938</option>
<option value="1939" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1939 ){ echo "selected"; } ?> >1939</option>
<option value="1940" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1940 ){ echo "selected"; } ?> >1940</option>
<option value="1941" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1941 ){ echo "selected"; } ?> >1941</option>
<option value="1942" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1942 ){ echo "selected"; } ?> >1942</option>
<option value="1943" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1943 ){ echo "selected"; } ?> >1943</option>
<option value="1944" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1944 ){ echo "selected"; } ?> >1944</option>
<option value="1945" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1945 ){ echo "selected"; } ?> >1945</option>
<option value="1946" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1946 ){ echo "selected"; } ?> >1946</option>
<option value="1947" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1947 ){ echo "selected"; } ?> >1947</option>
<option value="1948" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1948 ){ echo "selected"; } ?> >1948</option>
<option value="1949" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1949 ){ echo "selected"; } ?> >1949</option>
<option value="1950" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1950 ){ echo "selected"; } ?> >1950</option>
<option value="1951" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1951 ){ echo "selected"; } ?> >1951</option>
<option value="1952" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1952 ){ echo "selected"; } ?> >1952</option>
<option value="1953" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1953 ){ echo "selected"; } ?> >1953</option>
<option value="1954" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1954 ){ echo "selected"; } ?> >1954</option>
<option value="1955" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1955 ){ echo "selected"; } ?> >1955</option>
<option value="1956" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1956 ){ echo "selected"; } ?> >1956</option>
<option value="1957" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1957 ){ echo "selected"; } ?> >1957</option>
<option value="1958" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1958 ){ echo "selected"; } ?> >1958</option>
<option value="1959" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1959 ){ echo "selected"; } ?> >1959</option>
<option value="1960" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1960 ){ echo "selected"; } ?> >1960</option>
<option value="1961" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1961 ){ echo "selected"; } ?> >1961</option>
<option value="1962" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1962 ){ echo "selected"; } ?> >1962</option>
<option value="1963" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1963 ){ echo "selected"; } ?> >1963</option>
<option value="1964" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1964 ){ echo "selected"; } ?> >1964</option>
<option value="1965" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1965 ){ echo "selected"; } ?> >1965</option>
<option value="1966" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1966 ){ echo "selected"; } ?> >1966</option>
<option value="1967" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1967 ){ echo "selected"; } ?> >1967</option>
<option value="1968" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1968 ){ echo "selected"; } ?> >1968</option>
<option value="1969" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1969 ){ echo "selected"; } ?> >1969</option>
<option value="1970" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1970 ){ echo "selected"; } ?> >1970</option>
<option value="1971" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1971 ){ echo "selected"; } ?> >1971</option>
<option value="1972" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1972 ){ echo "selected"; } ?> >1972</option>
<option value="1973" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1973 ){ echo "selected"; } ?> >1973</option>
<option value="1974" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1974 ){ echo "selected"; } ?> >1974</option>
<option value="1975" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1975 ){ echo "selected"; } ?> >1975</option>
<option value="1976" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1976 ){ echo "selected"; } ?> >1976</option>
<option value="1977" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1977 ){ echo "selected"; } ?> >1977</option>
<option value="1978" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1978 ){ echo "selected"; } ?> >1978</option>
<option value="1979" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1979 ){ echo "selected"; } ?> >1979</option>
<option value="1980" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1980 ){ echo "selected"; } ?> >1980</option>
<option value="1981" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1981 ){ echo "selected"; } ?> >1981</option>
<option value="1982" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1982 ){ echo "selected"; } ?> >1982</option>
<option value="1983" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1983 ){ echo "selected"; } ?> >1983</option>
<option value="1984" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1984 ){ echo "selected"; } ?> >1984</option>
<option value="1985" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1985 ){ echo "selected"; } ?> >1985</option>
<option value="1986" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1986 ){ echo "selected"; } ?> >1986</option>
<option value="1987" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1987 ){ echo "selected"; } ?> >1987</option>
<option value="1988" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1988 ){ echo "selected"; } ?> >1988</option>
<option value="1989" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1989 ){ echo "selected"; } ?> >1989</option>
<option value="1990" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1990 ){ echo "selected"; } ?> >1990</option>
<option value="1991" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1991 ){ echo "selected"; } ?> >1991</option>
<option value="1992" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1992 ){ echo "selected"; } ?> >1992</option>
<option value="1993" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1993 ){ echo "selected"; } ?> >1993</option>
<option value="1994" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1994 ){ echo "selected"; } ?> >1994</option>
<option value="1995" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1995 ){ echo "selected"; } ?> >1995</option>
<option value="1996" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1996 ){ echo "selected"; } ?> >1996</option>
<option value="1997" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1997 ){ echo "selected"; } ?> >1997</option>
<option value="1998" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1998 ){ echo "selected"; } ?> >1998</option>
<option value="1999" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==1999 ){ echo "selected"; } ?> >1999</option>
<option value="2000" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2000 ){ echo "selected"; } ?> >2000</option>
<option value="2001" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2001 ){ echo "selected"; } ?> >2001</option>
<option value="2002" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2002 ){ echo "selected"; } ?> >2002</option>
<option value="2003" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2003 ){ echo "selected"; } ?> >2003</option>
<option value="2004" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2004 ){ echo "selected"; } ?> >2004</option>
<option value="2005" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2005 ){ echo "selected"; } ?> >2005</option>
<option value="2006" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2006 ){ echo "selected"; } ?> >2006</option>
<option value="2007" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2007 ){ echo "selected"; } ?> >2007</option>
<option value="2008" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2008 ){ echo "selected"; } ?> >2008</option>
<option value="2009" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2009 ){ echo "selected"; } ?> >2009</option>
<option value="2010" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2010 ){ echo "selected"; } ?> >2010</option>
<option value="2011" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2011 ){ echo "selected"; } ?> >2011</option>
<option value="2012" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2012 ){ echo "selected"; } ?> >2012</option>
<option value="2013" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2013 ){ echo "selected"; } ?> >2013</option>
<option value="2014" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2014 ){ echo "selected"; } ?> >2014</option>
<option value="2015" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2015 ){ echo "selected"; } ?> >2015</option>
<option value="2016" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2016 ){ echo "selected"; } ?> >2016</option>
<option value="2017" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2017 ){ echo "selected"; } ?> >2017</option>
<option value="2018" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2018 ){ echo "selected"; } ?> >2018</option>
<option value="2019" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2019 ){ echo "selected"; } ?> >2019</option>
<option value="2020" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2020 ){ echo "selected"; } ?> >2020</option>
<option value="2021" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2021 ){ echo "selected"; } ?> >2021</option>
<option value="2022" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2022 ){ echo "selected"; } ?> >2022</option>
<option value="2023" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2023 ){ echo "selected"; } ?> >2023</option>
<option value="2024" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2024 ){ echo "selected"; } ?> >2024</option>
<option value="2025" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2025 ){ echo "selected"; } ?> >2025</option>
<option value="2026" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2026 ){ echo "selected"; } ?> >2026</option>
<option value="2027" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2027 ){ echo "selected"; } ?> >2027</option>
<option value="2028" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2028 ){ echo "selected"; } ?> >2028</option>
<option value="2029" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2029 ){ echo "selected"; } ?> >2029</option>
<option value="2030" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2030 ){ echo "selected"; } ?> >2030</option>
<option value="2031" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2031 ){ echo "selected"; } ?> >2031</option>
<option value="2032" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2032 ){ echo "selected"; } ?> >2032</option>
<option value="2033" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2033 ){ echo "selected"; } ?> >2033</option>
<option value="2034" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2034 ){ echo "selected"; } ?> >2034</option>
<option value="2035" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2035 ){ echo "selected"; } ?> >2035</option>
<option value="2036" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2036 ){ echo "selected"; } ?> >2036</option>
<option value="2037" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2037 ){ echo "selected"; } ?> >2037</option>
<option value="2038" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2038 ){ echo "selected"; } ?> >2038</option>
<option value="2039" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2039 ){ echo "selected"; } ?> >2039</option>
<option value="2040" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2040 ){ echo "selected"; } ?> >2040</option>
<option value="2041" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2041 ){ echo "selected"; } ?> >2041</option>
<option value="2042" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2042 ){ echo "selected"; } ?> >2042</option>
<option value="2043" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2043 ){ echo "selected"; } ?> >2043</option>
<option value="2044" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2044 ){ echo "selected"; } ?> >2044</option>
<option value="2045" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2045 ){ echo "selected"; } ?> >2045</option>
<option value="2046" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2046 ){ echo "selected"; } ?> >2046</option>
<option value="2047" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2047 ){ echo "selected"; } ?> >2047</option>
<option value="2048" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2048 ){ echo "selected"; } ?> >2048</option>
<option value="2049" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2049 ){ echo "selected"; } ?> >2049</option>
<option value="2050" <?php if(isset($_POST["DogumYil"]) and $_POST["DogumYil"]==2050 ){ echo "selected"; } ?> >2050</option>
</select></div>
</div></div></div>



<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input type="submit" name="gonder" class="btn btn-success" value="Şimdi Doğrula">
</div>
</div>
</form>

<hr />

</div>
</div>
</div> </div>


</body>
</html>
