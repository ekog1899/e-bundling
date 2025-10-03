
<table class="table table-hover dataTable stripe">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th width="100">Nomor Perkara</th>			
			<th width="15%">Tanggal Putusan</th>
			<th width="100%">Pemohon Banding</th>
			<th width="20%">Status Perkara Banding</th>
			<th width="50">Aksi</th>
		</tr>
	</thead>

	<tbody>
		<?php
			$no = 0;
			if (empty($data_mon)) {
				echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
			} else {
				foreach ($data_mon as $b) {
				$no++;
				$perkara_id_enc = str_replace('/','__',$this->encryption->encrypt($b['perkara_id']));
		?>
		<tr>
			<td><?php echo $no;?></td>
			<td>
			<?php echo $b['nomor_perkara_pa'];
				if ( $b['blm_validasi'] > 0 ) {
					echo "<span class='badge badge-danger'>".$b['blm_validasi']." Edoc Menunggu Validasi</span>";
				}
			?></td>
			<td><?php echo $b['tgl_putusan_pa'];?></td>
            <td><?php echo $b['pemohon_banding'];?></td>
			<td><?php echo $b['status_perkara_banding'];?></td>
			<td><a class="btn btn-success noprint" href="<?php echo base_url('edoc/validasi/'.$b['idpn'].'/'.$perkara_id_enc);?>">lihat</a> </td>
		</tr>
		<?php
			}
		}
		?>
	</tbody>
	
</table>


