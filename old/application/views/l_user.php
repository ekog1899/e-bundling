<div class="clearfix">
	<div class="row">
		<div class="col-lg-12">
			
			
			
		</div>
	</div>
	
	<?php echo $this->session->flashdata("info");?>
	
	<a class="btn btn-success" href="<?php echo site_url('config/user_add');?>">Tambah User</a>
	
	
	<!--
		<div class="alert alert-dismissable alert-danger">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>Oh snap!</strong> <a href="http://bootswatch.com/amelia/#" class="alert-link">Change a few things up</a> and try submitting again.
		</div>
	-->
	
	
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th width="2%">No</th>
				<th>Nama</th>
				<th width="30%">Jabatan</th>
				<th width="15%">NIP</th>
				<th width="10%">Aksi</th>
			</tr>
		</thead>
		
		<tbody>
			<?php
				$no = 0;
				if (empty($list_user_pta)) {
					echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
					} else {
					foreach ($list_user_pta as $b) {
						$no++;
						
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $b->nama; ?></td>
						<td><?php echo $b->jabatan; ?></td>
						<td><?php echo $b->nip; ?></td>
						
						<td class="ctr" style='backgroundx:<?php  echo ($bg);?>'>
							
							<div class="btn-group">
								<a href="<?php echo base_URL()?>config/user_edt/<?php echo $b->id?>" class="btn btn-warning btn-sm" title="Ubah Data Pengguna"><i class="icon-edit icon-white"> </i> Ubah</a>
								<a href="<?php echo base_URL()?>config/user_del/<?php echo $b->id?>" class="btn btn-danger btn-sm" title="Hapus Data"><i class="icon-trash icon-white"> </i> Hapus</a>
								
								
							</div>
							
							
							
						</td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
</div>
