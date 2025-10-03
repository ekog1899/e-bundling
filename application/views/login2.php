<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>APLIKASI PENDUKUNG SIPP BANDING - PTA SEMARANG</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('aset/css/login2.css'); ?>">
</head>
<video autoplay muted loop id="myVideo">
	<source src="<?php echo base_url('images/video_badilag.mp4'); ?>" type="video/mp4">
</video>

<body>
	<section class="main-content">
		<div class="container">
			<div class="d-none d-sm-none d-md-block">
			</div>

			<div class="login-card d-flex rounded-lg overflow-hidden bg-white" style="background-color:#ffffffa1!important">
				<div class="login-message d-none d-sm-none d-md-flex flex-column justify-content-center p-5" style="background:#28a745ad">
					<img src="<?php echo base_url('images/logo_timbangan.png'); ?>" alt="Welcome" class="img-fluid mb-3 p-5">
					<div class="text_logo" style='color:#003333;'>
						<h4>e-BUNDLING</h4>
					</div>
					<span class="text-center"> DITJEN BADILAG</span>
					<span class="text-center">MAHKAMAH AGUNG RI</span>
					<span class="text-center"> &copy; <?=date('Y');?></span>

				</div>
				<div class="login-body">
					<div class="login-body-wrapper mx-auto">
						<form class="login100-form validate-form" method="POST" action="<?php echo base_url() ?>/login">
							<div class="text-center">
								<img src="<?php echo base_url('images/logo_ma.png'); ?>" alt="Welcome" class="img-fluid" width="70">
							</div>
							<!-- <h3 class="text-center text-uppercase mb-3 text-danger">Login</h3> -->
							<div class="form-group">
								<label for="email">User</label>
								<input class="form-control form-control-lg" name="username" aria-describedby="helpId" placeholder="">
								<?php echo form_error('username'); ?>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control form-control-lg" name="password" id="password" aria-describedby="helpId" placeholder="">
								<?php echo form_error('username'); ?>
							</div>
							<div class="form-group">
								<?php echo $this->session->flashdata('k'); ?>
							</div>
							<button class="btn btn-block btn-lg text-white" type="submit" style="background:#bfa103">LOGIN</button>


						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
</body>

</html>