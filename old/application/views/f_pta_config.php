<div class="row">
    <div class="col-md-6">
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
					
					<div class="row">
						<div class="col-md-4">
							<label>  Silahkan Pilih Wilayah (PTA)</label>
						</div>
						<div class="col-md-6">
							<select name="id_satker_pta" class="form-control">
								<?php
									foreach ($wilayah_list as $row ):
									echo "<option value='".$row['id_satker_sipp']."' ".( $current_id_satker_pta == $row['id_satker_sipp'] ? "selected": "" ).">".$row['nama_satker']."</option>";
									endforeach; 
								?>
								
							</select>
						</div>
						<div class="col-md-4">
							<label>  Nama Ketua Pengadilan Tinggi</label>
						</div>
						<div class="col-md-6">
							<input type='text' id='nama_kpta' name='nama_kpta' value="<?php echo $nama_kpta;?>" class="form-control"  />
						</div>
					</div>
					<input type="submit" value="Simpan" class="btn btn-success">
				</div>
			</div>
		</form>
	</div>
