<!DOCTYPE html>
<html lang="en">
	
	<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>eBundling - PROSES ADMINISTRASI DAN INFORMASI BANDING</title>
		
		<!-- Custom fonts for this template-->		
		<link rel="stylesheet" href="<?php echo base_url(); ?>aset/css/jquery.dataTables.min.css" />
		<link href="<?php echo base_url(); ?>/aset/sb/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		
		<!-- Custom styles for this template-->		
		<link href="<?php echo base_url(); ?>/aset/sb/css/sb-admin-2.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>/aset/css/custom-pakis.css?v=2.0" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo base_url(); ?>aset/js/jquery/jquery-ui.css" />
		
		
		<!-- Special version of Bootstrap that is isolated to content wrapped in .bootstrap-iso -->
		
		<!-- Bootstrap core JavaScript-->
		<script src="<?php echo base_url(); ?>/aset/sb/vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>/aset/sb/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		
		<!-- Core plugin JavaScript-->
		<script src="<?php echo base_url(); ?>/aset/sb/vendor/jquery-easing/jquery.easing.min.js"></script>
		<!-- Custom scripts for all pages-->
		
		<!-- Page level plugins -->
		<script src="<?php echo base_url(); ?>/aset/sb/vendor/chart.js/Chart.min.js"></script>
		
		<!-- Page level custom scripts -->
		<script src="<?php echo base_url(); ?>aset/js/moment.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/jquery/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/jquery.datatables.js"></script>
		
		<script src="<?php echo base_url(); ?>aset/js/bootstrap-daterangepicker.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/bootswatch.js"></script>
		<script src="<?php echo base_url(); ?>aset/js/scripts-pakis.js"></script>
		
		<link rel="stylesheet" href="<?php echo base_url(); ?>/aset/css/bootstrap-iso.css" />
		
		
		
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
		
		<!-- Inline CSS based on choices in "Settings" tab -->
		
		<script>
			var BASE_URL = '<?php echo base_url(); ?>';
			$(document).ready(function() {		
			});
		</script>
	</head>
	
	<body id="page-top" class="sidebar-toggled">
		
		<!-- Page Wrapper -->
		<div id="wrapper">
						
			<!-- Topbar -->
			<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 noprint static-top shadow">
				
				<!-- Sidebar Toggle (Topbar) -->
				<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
					<i class="fa fa-bars"></i>
				</button>
				
				<img src="<?php echo base_url('images/logo_eberbanding.png'); ?>" alt="Welcome" class="img-fluid m-4">
						
				<!-- Topbar Navbar -->
				<ul class="navbar-nav ml-auto">
					<div class="topbar-divider d-none d-sm-block"></div>
					
					<!-- Nav Item - User Information -->
					<li class="nav-item dropdown no-arrow">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="rounded mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $this->session->userdata('nama'); ?> (<?php echo $this->session->userdata('satker'); ?>)</span>
							<img class="img-profile rounded-circle border" src="<?php echo base_url('aset/img/icon.png'); ?>">
						</a>
						<!-- Dropdown - User Information -->
						<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
							
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo base_url('login/logout'); ?>">
								<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
								Keluar
							</a>
						</div>
					</li>
					
				</ul>			
				
			</nav>
			<!-- End of Topbar -->
			
			<!-- Sidebar -->
			<ul class="navbar-nav bg-gradient-hijau sidebar sidebar-dark noprint accordion" id="accordionSidebar">			
				
				<!-- Divider -->
				<hr class="sidebar-divider">
				
				<!-- Heading -->
				<div class="sidebar-heading">
					Administrasi
				</div>				
				
				<li class="nav-item">
					<a class="nav-link" href="<?php echo site_url('main'); ?>">
						<i class="fas fa-fw fa-home"></i>
					<span>Beranda</span></a>
				</li>				
				
				<?php
					if ($this->session->userdata('role') == 'op_pta') {
					?>
					
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('monitoring/req_unlock'); ?>">
								<i class="fas fa-fw fa-unlock"></i>
							<span>Permintaan Buka Kunci</span></a>
						</li>				
						
						<li class="nav-item">
							<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
								<i class="fas fa-fw fa-table"></i>
								<span>Monitoring</span>
							</a>
							<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
								<div class="bg-white py-2 collapse-inner rounded">
									<h6 class="collapse-header">Monitoring:</h6>
									<a class="collapse-item" href="<?php echo site_url('monitoring/belum_register'); ?>">Belum Register</a>
									<a class="collapse-item" href="<?php echo site_url('monitoring/belum_ada_pemeriksa'); ?>">Belum Ada Tim Pemeriksa</a>
									<a class="collapse-item" href="<?php echo site_url('monitoring/mon_02/wait/menunggu_revisi'); ?>">Menunggu Perbaikan</a>
									<a class="collapse-item" href="<?php echo site_url('monitoring/mon_02'); ?>">Banding Berjalan</a>
									<a class="collapse-item" href="<?php echo site_url('monitoring/mon_01'); ?>">Banding vs Kasasi</a>
									<a class="collapse-item" href="<?php echo site_url('monitoring/perkara_banding_putus'); ?>">Perkara Banding Putus</a>
								</div>
							</div>
						</li>					
					
					<?php
						} else {
					?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo site_url('main/berjalan'); ?>">
							<i class="fas fa-fw fa-cog"></i>
						<span>Banding Berjalan</span></a>
						<a class="nav-link" href="<?php echo site_url('main/kasasi_satker/'); ?>">
							<i class="fas fa-fw fa-cog"></i>	
						<span>Banding vs Kasasi</span></a>
					</li>
					
					<?php
						
					} ?>
					
					<?php
						if ($this->session->userdata('user_jabatan_id') == 100) {
						?>
						
						<li class="nav-item">
							<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities_akun" aria-expanded="true" aria-controls="collapseUtilities_akun">
								<i class="fas fa-fw fa-cog"></i>
								<span>Pengaturan</span>
							</a>
							<div id="collapseUtilities_akun" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
								<div class="bg-white py-2 collapse-inner rounded">
									<h6 class="collapse-header">Pengaturan:</h6>
									
									<a class="collapse-item" href="<?php echo site_url('config/users'); ?>"><i class="fas fa-fw fa-users"></i> Akun Pengguna </a>
									<a class="collapse-item" href="<?php echo site_url('config'); ?>"><i class="fas fa-fw fa-cog"></i>Setting Aplikasi</a>
									<a class="collapse-item" href="<?php echo site_url('Home/update_system'); ?>"><i class="fas fa-fw fa-users"></i> Update eBundling </a>
								</div>
							</div>
						</li>
					<?php } ?>					
					
					<!-- Divider -->
					<hr class="sidebar-divider d-none d-md-block">
					
					<div class="text-center d-none d-md-inline mb-3">
						<!-- <div class=""><small class='badge badge-info'>Tanggal Server : <?php echo $this->session->userdata('tgl_server'); ?></small></div> -->
						<div class=""><small class='badge badge-info'>Tanggal Server : <?=date('d-M-Y'); ?></small></div>
					</div>
					
					<hr class="sidebar-divider d-none d-md-block">
					
					<!-- Sidebar Toggler (Sidebar) -->
					<div class="text-center d-none d-md-inline">
						<button class="rounded-circle border-0" id="sidebarToggle"></button>
					</div>
					
			</ul>
			<!-- End of Sidebar -->
			
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
				
				<!-- Main Content -->
				<div id="content">					
					
					<!-- Begin Page Content -->
					<div class="container-fluid">
						
						<!-- Page Heading -->						
						<?php
							if ($title == 'DASHBOARD') {
								echo $content;
								} else {
							?>
							
							<div class="card shadow mb-4">
								<div class="card-header py-3">
									<h6 class="m-0 font-weight-bold text-primary"><?php echo $title; ?></h6>
								</div>
								<div class="card-body">
									<?php echo $content; ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<!-- /.container-fluid -->
					
				</div>
				<!-- End of Main Content -->
				
				<!-- Footer -->
				<footer class="sticky-footer bg-white noprint">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>Dikembangkan oleh TIM IT PTA SEMARANG @<?=date('Y');?></span>
						</div>
					</div>
				</footer>
				<!-- End of Footer -->
				
			</div>
			<!-- End of Content Wrapper -->
			
		</div>
		<!-- End of Page Wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		
		<div id="edocViewerModal" class="modal fade" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					
					<div class="modal-body" id="preview">
						<iframe width="100%" height="400px" src=""></iframe>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="tundaSidangModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">PENUNDAAN JADWAL SIDANG</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <table id="tableDetilPerkara" style="font-size:14px;">
							<tbody>
								<tr>
									<td colspan="2" class="bg-success text-white" align="center"><strong>PENUNDAAN JADWAL SIDANG</strong></td>
								</tr>
								
							</tbody>
							<colgroup>
								<col width="20%">
								<col width="80%">
							</colgroup>
							<tbody>
								<tr>
									<td class="first-colum form-group">Tanggal<span style="color:red">*</span></td>
									<td><input type="text" id="tanggal" name="tanggal" class="datepicker hasDatepicker" maxlength="10" placeholder="tgl-bln-tahun" required=""></td>
								</tr>								
								
								<td class="first-colum form-group">Agenda<span style="color:red">*</span></td>
								<td>
									<textarea name="agenda" id="agenda" maxlength="500" class="form-control" style="margin: 0px; width: 610px; height: 80px;resize: none;" required="">SIDANG PERTAMA</textarea>
								</td>
								</tr>							
							
								<tr>
								<td class="first-colum form-group ">Alasan Penundaan<span style="color:red">*</span></td>
								<td>
									<textarea name="alasan_tunda" id="alasan_tunda" maxlength="500" class="form-control" style="margin: 0px; width: 610px; height: 80px;resize: none;" placeholder="Uraikan Alasan Penundaan Sidang Sebelumnya" required=""></textarea>
								</td>
								</tr>							
							
								<tr>
								<td colspan="2" class="buttongroup align-center">
									<input type="hidden" name="enc_perkara_id" id="enc_perkara_id" value="">
									<input type="hidden" name="enc_id" id="enc_id" value="">
									<input type="hidden" name="satkercode" id="satkercode" value="">
									<a href="javascript:close_modal('tundaSidangModal')" class="btn btn-warning">Kembali</a>
									<input type="submit" id="simpan" value="Simpan" onclick="javascript:save_jadwal_sidang()" class="btn btn-success">
								</td>
								</tr>
							</tbody>
					</table>
					
      </div>
      
    </div>
  </div>
</div>		
	
	<!-- unlock Modal -->
	<div id="unlockModal" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<div class="modal-body">
					
					<form>
						<input type="hidden" name="enc_perkara_id" id="enc_perkara_id">
						<input type="hidden" name="satkercode" id="satkercode">
						<div class="form-group">
							<label for="exampleInputEmail1">KOLOM YANG TERKUNCI</label>
							<input type="text" name="jenis_edoc" id="jenis_edoc" class="form-control" readonly>
							
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Alasan Terlambat upload edoc</label>
							<input type="text" class="form-control" id="req_alasan" placeholder="Misal Mati Lampu">
						</div>
						<div class="error-msg"></div>
						<div class="btn-area">
							<a href="javascript:close_modal('unlockModal')" class="btn btn-warning">Kembali</a> <button type="button" class="btn btn-primary" onclick="save_req_unlock()">Kirim Request Unlock</button>
						</div>
					</form>			
					
				</div>
				
			</div>
		</div>
	</div>
	<!-- /end unlock -->
	
</body>
<script src="<?php echo base_url(); ?>/aset/sb/js/sb-admin-2.min.js"></script>

</html>