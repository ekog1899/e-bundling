<?php
	
	if ($mode == "edt" || $mode == "act_edt") {
		$act		= "act_edt";
		$idp		= $data->id;
		$username	= $data->username;
		$password	= "-";
		$nama		= $data->nama;
		$tempat_lahir		= $data->tempat_lahir;
		$tgl_lahir		= $data->tgl_lahir;
		$nip		= $data->nip;
		$hp		= $data->hp;
		$no_ktp		= $data->no_ktp;
		$grup		= $data->grup;
		$kode_jabatan		= $data->kode_jabatan;
		$aktif		= $data->aktif;
		
		} else {
		$act		= "act_add";
		$idp		= "";
		$username	= "";
		$password	= "";
		$nama		= "";
		$nip		= "";
		$no_ktp		= "";
		$tempat_lahir		= "";
		$tgl_lahir		= "";
		$hp		= "";
		$grup		= "";
		$aktif = 1;
	}
?>




<form class="row" action="<?php echo base_URL(); ?>config/user_save/<?php echo $act; ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	<input type="hidden" name="idp" value="<?php echo $idp; ?>">
	
	
	<div class="col-md-6">
		<table width="100%" class="table-form">
			<tr><td width="25%">Username</td><td><b><input type="text" name="username" required value="<?php echo $username; ?>" style="width: 300px" class="form-control" tabindex="1" autofocus></b></td></tr>
			<tr><td width="25%">Nama</td><td><b><input type="text" name="nama" required value="<?php echo $nama; ?>" style="width: 300px" class="form-control" tabindex="4" ></b></td></tr>
			<tr><td width="25%">Password</td><td><b><input type="password" name="password" required value="<?php echo $password; ?>" id="dari" style="width: 300px" class="form-control" tabindex="5" ></b></td></tr>
			<tr><td width="25%">Ulangi Password</td><td><b><input type="password" name="password2" required value="<?php echo $password; ?>" id="dari" style="width: 300px" class="form-control" tabindex="6" ></b></td></tr>
			
			
			<tr>
				<tr><td colspan="2">
					<br><button type="submit" class="btn btn-primary" tabindex="7" ><i class="icon icon-ok icon-white"></i>Simpan</button>
					<a href="<?php echo base_URL(); ?>config/users" class="btn btn-success" tabindex="8" ><i class="icon icon-arrow-left icon-white"></i>Kembali</a>
				</td></tr>
			</table>
		</div>
		
		<div class="col-md-6">
			
			<table width="100%" class="table-form">
				<tr><td width="25%">NIP</td><td><b><input type="text" name="nip" value="<?php echo $nip; ?>" style="width: 300px" class="form-control" tabindex="2" ></b></td></tr>
				<tr><td width="25%">Jabatan</td><td><b>
					<select name="kode_jabatan" class="form-control" style="width: 200px" tabindex="6" required ><option value=""> - Jabatan - </option>
						<?php
							foreach ($l_jabatan as $v ) :
							echo "<option ".( $v->kode_jabatan == $data->kode_jabatan ? 'selected':'') ." value=$v->kode_jabatan>$v->nama_jabatan</option>";
							endforeach;
						?>
					</select>
				</b></td></tr>
				
				<tr><td width="25%">Jenis User</td><td><b>
					<select name="grup" class="form-control" style="width: 200px" tabindex="6" required ><option value=""> - Jenis - </option>
						<?php
							$l_group = array( 1 =>'Hakim', 2 => 'Kepaniteraan', 3 => 'Kesekretariatan' , 4=> 'Non PNS');
							foreach ($l_group as $k => $v ) :
							echo "<option ".( $k == $data->grup ? 'selected':'') ." value=$k>$v</option>";
							endforeach;
						?>
					</select>
				</b></td></tr>
				
				
				<tr><td width="25%">Status Aktif</td><td><b>
					<select name="aktif" class="form-control" style="width: 200px" required tabindex="6" >
						<option <?php echo ($aktif ==1 ? 'selected':'') ;?> value="1">Aktif</option>
						<option <?php echo ($aktif ==0 ? 'selected':'') ;?> value="0">Tidak Aktif</option>
						
					</select>
				</b></td></tr>	
				
				
				
				
			</table>
		</div>
		
		
		
	</form>
