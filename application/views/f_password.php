<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PTA SEMARANG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <meta name="msapplication-tap-highlight" content="no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="<?php echo base_url(); ?>/aset/css/telusur.css" rel="stylesheet">	
<script src="<?php echo base_url(); ?>/aset/sb/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>/aset/sb/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


</head>
<body>


<!------ Include the above in your HEAD tag ---------->

<div class="container">
<div class="row pt-5">
<div class="col-sm-6 col-sm-offset-3">
<h4 class="text-center ">Ganti Password</h4>
<p class="text-center ">Mohon Ganti Password anda</p>
<form method="post" id="passwordForm">
<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
<div class="row">
<div class="col-sm-6">

</div>
<div class="col-sm-6">

</div>
</div>
<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Repeat Password" autocomplete="off">
<div class="row">
<div class="col-sm-12">
<span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords Match
</div>
</div>
<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Simpan Password">
</form>
</div><!--/col-sm-6-->
</div><!--/row-->
