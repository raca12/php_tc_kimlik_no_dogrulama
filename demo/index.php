<?php

if(@$_POST["gonder"])
{

					$TCKimlikNo = $_POST["KimlikNo"];
		                        $Ad =$_POST["Ad"];
					$Soyad =$_POST["Soyad"];
					
                                        $DogumTarihi = $_POST["DogumTarihi"];

					$tarih_ayir = explode('.', $DogumTarihi);
					$DogumGun   = $tarih_ayir[0];
					$DogumAy = $tarih_ayir[1];
					$DogumYil  = $tarih_ayir[2];

					
					
					include 'kutuphane/tckimlikno.php';
					$kontrol = new TCKimlikNoSinifi;
				    $sonuc= $kontrol->KimlikNoDogrula($TCKimlikNo,$Ad,$Soyad,$DogumGun,$DogumAy,$DogumYil);

}

?>





<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>PHP ile Kimlik No Doğrulama</title>
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>

<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js"></script>


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
<label for="dp" class="col-sm-2 control-label">Kimlik No</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="KimlikNo" placeholder="TC Kimlik No / Yabancı Kimlik No girin" value="<?php if(isset($_POST["KimlikNo"])){ echo $_POST["KimlikNo"];}?>" required />
</div>
</div>

<div class="form-group">
<label for="tid" class="col-sm-2 control-label">İsim</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="Ad" placeholder="Adınızı girin" value="<?php if(isset($_POST["Ad"])){ echo $_POST["Ad"];}?>" required />
</div>
</div>
<div class="form-group">
<label for="ck" class="col-sm-2 control-label">Soyad</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="Soyad" placeholder="Soyadınızı girin" value="<?php if(isset($_POST["Soyad"])){ echo $_POST["Soyad"];}?>" required />
</div>
</div>




<div class="form-group">
<label for="ck" class="col-sm-2 control-label">Doğum Tarihi</label>
<div class="col-sm-10">
<input type="text" class="form-control" name="DogumTarihi" id="DogumTarihi" data-date-format="DD.MM.YYYY" placeholder="Dogum Tarihinizi girin" value="<?php if(isset($_POST["DogumTarihi"])){ echo $_POST["DogumTarihi"];}?>" required />
</div>

<script type="text/javascript">
            $(function () {
                $('#DogumTarihi').datetimepicker({locale:'tr'});
            });
</script>
		
</div>


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
