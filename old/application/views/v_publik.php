
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PARIBAN PTA Medan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <meta name="msapplication-tap-highlight" content="no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="<?php echo base_url(); ?>/aset/css/telusur.css" rel="stylesheet">	
<script src="<?php echo base_url(); ?>/aset/sb/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/aset/js/telusur.js"></script>
<script src="<?php echo base_url(); ?>/aset/sb/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
	var BASE_URL = '<?php echo base_url();?>';
	
</script>	





</head>
<body>



<div class="bg-contact2" style="background-image: url('images/bg-01.jpg');">
<div class="text-center mb-1 p-3 bg-warning">
<h6>PARIBAN - PROSES ADMINISTRASI DAN INFORMASI BANDING</h6>
</div>


<div class="container">
<div class="row border-bottom">
  <div class="col-md-4">Nomor Perkara</div>      
  <div class="col-md-8"><strong><?php echo $info['nomor_perkara_pn'];?></strong></div>
</div>

<div class="row border-bottom">
  <div class="col-md-4">Pihak Pembanding</div>      
  <div class="col-md-8"><strong><?php echo $info['pemohon_banding'];?></strong></div>
</div>
<?php if ($info['nomor_perkara_banding'] != '' ) { ?>
<div class="row border-bottom">
  <div class="col-md-4">Nomor Perkara Banding</div>      
  <div class="col-md-8"><strong><?php echo $info['nomor_perkara_banding'];?></strong></div>
</div>

<?php } ?>

<div class="row border-bottom">
  <div class="col-md-4">Tanda Terima Banding</div>      
  <div class="col-md-8"><a class="btn btn-info" href="<?php echo base_url('uploads/edoc/'.$info['file_edoc']);?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Unduh PDF</i></a></div>
</div>

<?php if ($info['amar_putusan_banding'] != '' ) { ?>
<div class="row border-bottom">
  <div class="col-md-4">Amar Putusan Banding</div>      
  <div class="col-md-8"><?php echo $info['amar_putusan_banding'];?><br />
Salinan Putusan dapat diambil di Pengadilan Agama tempat anda mendaftar Perkara banding</div>
</div>
<?php } ?>


</div>
</div>

</body>
</html>
