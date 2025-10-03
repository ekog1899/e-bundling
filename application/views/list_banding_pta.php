<div class="clearfix">
<div class="row">
  <div class="col-lg-12">

	

  </div>
</div>

<?php echo $this->session->flashdata("info");?>


<?php
if (isset($sub_title))
echo $sub_title;
?>


<table class="table table-hover dataTable stripe">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th width="100">Nomor Perkara</th>
			
			<th width="10%">Tgl Putusan</th>
			<th width="">Pemohon Banding</th>
			<th width="10%">Tgl Permohonan Banding</th>
			<th width="10%">Nomor Perkara Banding</th>
			<th width="20%">Status Perkara Banding</th>
			<th width="50">Aksi</th>
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
			<tr>
			<td><?php echo $no;?></td>
			<td><?php echo $b['nomor_perkara_pa'];?>
			<?php
			if ( $b['jml'] > 0 ) {
				echo "<span class='badge badge-danger'>".$b['jml']." Edoc ".$badge_text."</span>";
			} ?>

		</td>
			<td><?php echo $b['tgl_putusan_pa'];?></td>
			<td><?php echo $b['pemohon_banding'];?></td>
			<td><?php echo $b['permohonan_banding'];?></td>
			<td><?php echo $b['nomor_perkara_banding'];?></td>
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
