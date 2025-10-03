
  <table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th width="15%">Nomor Perkara</th>
			<th width="15%">Permohonan Banding</th>			
			<th width="15%">Request Buka Kunci</th>
			<th width="15%">JENIS EDOC</th>
			<th>Alasan Terlambat</th>
			<th width="15%">Aksi</th>
		</tr>
	</thead>

	<tbody>
		<?php
        $no = 0;
		if (empty($data)) {
			echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
		} else {
			foreach ($data as $b) {
			$no++;
			$perkara_id_enc = str_replace('/','__',$this->encryption->encrypt($b['perkara_id']));
         
			?>
			<td><?php echo $no;?></td>
			<td><?php echo $b['nomor_perkara_pa'];?>
			<td><?php echo $b['permohonan_banding'];?></td>
			
			</td>
			<td><?php echo $b['tgl_request'];?></td>
			<td><?php echo $b['jenis_edoc'];?></td>
			<td><?php echo $b['req_alasan'];?></td>
			<td class='col-action-<?php echo $b['id'];?>'><a class="btn btn-success noprint" href="javascript:unlock_upload('<?php echo $b['id'];?>','<?php echo $b['perkara_id'];?>');"><i class="fa fa-unlock"></i> Buka Kunci</a> </td>
		<tr>
			
			
			
		</tr>
		<?php
			}
		}
		?>
	</tbody>
	
</table>




