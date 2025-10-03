<div class="row">
    <div class="col-md-12">
		<div class="draft panel panel-success">
			
			<div class="panel-body">
				<form method="POST">   
					<?php
						if ( $this->session->flashdata('flashdata') != '' ) :
					?>
					<div class="row">
						<div class="col-md-10 mb-2">
							<div class="alert alert-dismissable alert-success">
								<button type="button" class="close" data-dismiss="alert">x</button>
								<?php echo $this->session->flashdata('flashdata');?>
							</div>
						</div>    
					</div>
					<?php endif;?>
					
					<div class="container">
						<div class="col-md-12">
							<table class="table">
								<thead class="thead-dark">
									<tr>
										<th>Keterangan</th>
										<th>Data</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Nama Pengadilan Tinggi</td>
										<td><?php echo $pta_name;?></td>
									</tr>
									<tr>
										<td>Nama Ketua Pengadilan Tinggi</td>
										<td><?php echo $nama_kpta;?></td>
									</tr>
									<tr>
										<td>Sys Version ebundling</td>
										<td><?php echo $sys_version;?></td>
									</tr>
									<tr>
										<td>Link Update Online</td>
										<td><?php echo '<br> Ketersediaan Update : '.$st_updates.'<br/>'.$ver_updates;?></td>
									</tr>
								</tbody>
							</table>
							<div class="row px-5 py-2 justify-content-center">
								<?php
									if($update){
									?>
									<button type="button" class="btn btn-primary mb-2" onclick="update_sistem()"> Update</button> &nbsp;
									<?php }else {  ?>
									<button type="button" class="btn btn-primary mb-2" onclick="update_sistem_ccc()"> Belum Tersedia</button> &nbsp;
									
								<?php }?>
							</div>
							<div class="row justify-content-center" id='response'>
							</div>
							
						</div>
						
					</div>
				</div>
			</form>
		</div>
		<script type="text/javascript">	
			
			function update_sistem() {
				postComplete=false;
				var time = new Date().getTime();
				$('#response').text('');
				$.ajax({
					type: 'post',
					url: "<?php echo base_url().'Home/download_update';?>",
					data: {},
					success: function(data) {
						postComplete = true;
						$('#response').append(data);
						//send();
					}
				});
				
			};
			function repair_db() {
				$('#response').text('');
				$.ajax({
					type: 'get',
					url: "<?php echo base_url().'keuangan/menu/perbaiki_db';?>",
					success: function(data) {      	
						$('#response').append(data);
					}
				});
				
			};	
			/* function send() {
				var id_satker = '<?php echo $id_satker; ?>';
				var nm_satker = "<?php echo $nm_satker; ?>";
				var nm_pta = "<?php echo $nm_pta; ?>";
				var ver_up = '<?php echo $ver_updates; ?>';
				
				$.ajax({
					type: 'post',
					url: "<?php echo $url_server_update;?>monitoring_update",
					data: {
						"id_satker" : id_satker,
						"nm_satker" :nm_satker,
						"nm_pta" :nm_pta,
						"versi_update" : ver_up,
					},
					success: function(data) {
						//$(location).attr('href', '<?php echo base_url()?>keuangan/menu/update_system');	
						console.log('sukses');
					}
				});
				
			}; */
			
		</script>
		