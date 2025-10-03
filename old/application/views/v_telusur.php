
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
<div class="container-contact2">
<div class="wrap-contact2">
<form class="contact2-form validate-form" method="post" action="">
<div class="text-center mb-5">
<img src="<?php echo base_url('images/logo_pariban_header.png');?>" alt="Welcome" class="img-fluid m-4">
<h6>PARIBAN - PROSES ADMINISTRASI DAN INFORMASI BANDING</h6>
</div>



<div class="row">
           <div class="col-xs-6 col-sm-4" style="">
            <label style="font-size: 13pt;mt-1"><b>Pengadilan Agama</b></label>
        </div>
        <div class="col-xs-6 col-sm-8">

        <select class="form-select col-2" name="satker">
        <option value="">-- Pilih Pengadilan Agama Pengaju --</option>
                   <?php
                   
                    foreach ($satker_list as $row):
                          $sing = "PA.".substr($row['satker_code'],2,3);
                          echo '<option value="'.$sing.'">'.$row['satker_name'].'</option>';  
                    endforeach;
                   ?>
        </select>
        <span class='msg msg_satker text-danger' style="display:none">Silahkan Pilih Pengadilan Agama tempat Mendaftar</span>

        </div>
</div>

<div class="row"  style="margin-top:15px">
           <div class="col-xs-6 col-sm-4">
            <label style="font-size: 13pt;mt-1"><b>Nomor Perkara</b></label>
            
        </div>
        <div class="col-xs-6 col-sm-8">

            <div class="input-group mb-3">
              <input type="text" class="form-control  col-4" name="np_nomor" required="" placeholder="Nomor">
              <select class="form-select col-2" name="np_pg">
                    <option>Pdt.G</option>    
                    <option>Pdt.P</option>       
                </select>
                <select  class="form-select col-2" name="np_tahun">
                    <option>2022</option>    
                    <option>2021</option>       
                </select>
              <div class="input-group-append">
                <span class="input-group-text" id="np_satsing">/</span>
              </div>
              
            </div>
           

        </div>
</div>
<div class="container-contact2-form-btn">
<div class="wrap-contact2-form-btn">
<div class="contact2-form-bgbtn"></div>
<a href="javascript:;" class="contact2-form-btn btn-telusur">
Telusuri 
                  </a>
</div>
</div>
</form>

<div class="text-center" id="loading" style="display:none">
  <img src="<?php echo base_url('aset/img/loading1.gif');?>" alt="Welcome" class="img-fluid m-4" style="width:120px">       Mohon Tunggu ...       
</div>

<div class="row mt-2" id="resultArea">

   
        
</div>

</div>
</div>

</div>



</body>
</html>
