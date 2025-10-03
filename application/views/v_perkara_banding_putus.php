<div class="row">
	<div class="col-md-12 col-xl-12">
		<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">DATA PERKARA BANDING PUTUS</h6>
		</div>
		<div class="card-body">
<table class="table table-hover dataTable stripe">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th width="100">Nomor Perkara</th>
			
			<th width="15%">Tgl Putusan Banding</th>
			<th width="">Pemohon Banding</th>
			<th width="10%">Tgl Permohonan Banding</th>
			<th width="20%">Status Perkara Banding</th>
			<th width="50">Aksi</th>
		</tr>
	</thead>

	<tbody>
		<?php
        $no = 0;
		if (empty($data_banding_putus)) {
			echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
		} else {
			foreach ($data_banding_putus as $b) {
			$no++;
			$perkara_id_enc = str_replace('/','__',$this->encryption->encrypt($b['perkara_id']));
         
			?>
			<tr>
			<td><?php echo $no;?></td>
			<td><?php echo '<b>Nomor Perkara PA</b><br>'.$b['nomor_perkara_pa'].'<br><b>Nomor Banding</b><br>'.$b['nomor_perkara_banding']; ?></td>
			<td><?php echo $b['putusan_banding'];?></td>
			<td><?php echo $b['pemohon_banding'];?></td>
			<td><?php echo $b['permohonan_banding'];?>
			<?php 
			// if ( $b['jml_hari'] > 20 and $b['status_perkara_banding']== 'Permohonan Banding' ){
			// 	echo "<span class='badge badge-danger'>".$b['jml_hari']." hari belum dikirim</span>";
			// }
			?>
			</td>
			<td><?php echo $b['status_perkara_banding'];?></td>
			<td><a class="btn btn-success noprint" href="<?php echo base_url('edoc/validasi/'.$b['idpn'].'/'.$perkara_id_enc);?>">Lihat</a> </td>
			</tr>
		<?php
			}
		}
		?>
	</tbody>
	
</table>


</div>
		</div>
	</div>	
</div>