<div class="clearfix">
<div class="row">
  <div class="col-lg-12">

	

  </div>
</div>

<?php echo $this->session->flashdata("info");?>




<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th>Nomor Perkara</th>
			
			<th width="10%">Nomor Banding</th>
			<th width="">Pemohon Banding</th>
			<th width="10%">Status Banding</th>
			<th width="10%">Nomor Kasasi</th>
			<th width="10%">Status Kasasi</th>
			<th width="10%">Aksi</th>
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
			<td><?php echo $no;?></td>
			<td><?php echo $b['nomor_perkara_pa'];?></td>
			<td><?php echo $b['nomor_perkara_banding'];?></td>
            <td><?php echo $b['pemohon_banding'];?></td>
			<td><?php echo $b['status_perkara_banding'];?></td>
            <td><?php echo $b['nomor_perkara_kasasi'];?></td>
			<td><?php echo $b['status_kasasi_text'];?></td>
			<td><a class="btn btn-success noprint" href="<?php echo base_url('edoc/validasi/'.$b['idpn'].'/'.$perkara_id_enc);?>">edoc</a> </td>
		<tr>
			
			
			
		</tr>
		<?php
			}
		}
		?>
	</tbody>
	
</table>



</div>
