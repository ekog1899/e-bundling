
<form id="uploadForm" action="<?php echo base_url('edoc/do_upload_kasasi'); ?>" method="post" enctype="multipart/form-data">

<?php //print_r($kasasi_detil[0]); ?>

<?php  //print_r($ref_jenis_edoc_t); ?>
<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup><col width="250">
		<col>
		</colgroup><tbody>
			<tr>
				<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="2"><strong>Data Perkara Tingkat Pertama (SIPP)</strong></td>
			</tr>
			<tr><td class="first-colum">Nomor Perkara</td><td><?php echo $kasasi_detil[0]['nomor_perkara_pa'];?> (<?php echo $kasasi_detil[0]['jenis_perkara_text'];?>)</td></tr>
			<tr><td class="first-colum">Tanggal Putusan</td><td><?php echo $kasasi_detil[0]['tgl_putusan_pa'];?></td></tr>
			<tr><td class="first-colum">Majelis Hakim</td><td><?php echo $kasasi_detil[0]['majelis_hakim_pa'];?></td></tr>
			<tr><td class="first-colum">Para Pihak</td><td><?php echo $kasasi_detil[0]['para_pihak'];?></td></tr>
</table>	

	<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup><col width="250">
		<col>
		</colgroup><tbody>
			<tr>
				<td class= "bg-gradien-blue" style="padding:5px;color:white" colspan="2"><strong>Data Permohonan Banding (SIPP)</strong></td>
			</tr>
			<tr><td class="first-colum">Tanggal Permohonan Banding</td><td  colspan=2><?php echo $kasasi_detil[0]['permohonan_banding'];?></td></tr>
			<tr><td class="first-colum">Nomor Perkara Banding</td><td  colspan=2><?php echo $kasasi_detil[0]['nomor_perkara_banding'];?></td></tr>
			<tr><td class="first-colum">Tanggal Putusan Banding</td><td  colspan=2><?php echo $kasasi_detil[0]['putusan_banding'];?></td></tr>
			<tr><td class="first-colum">Pihak Pemohon Banding</td><td  colspan=2><?php echo $kasasi_detil[0]['pemohon_banding'];?></td></tr>
	</table>	

	<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup><col width="250">
		<col>
		</colgroup><tbody>
			<tr>
				<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Data Perkara Kasasi (SIPP)</strong></td>
			</tr>
			<tr><td class="first-colum">Tanggal Permohonan Kasasi</td><td  colspan=2><?php echo $kasasi_detil[0]['permohonan_kasasi'];?></td></tr>
			<tr><td class="first-colum">Nomor Perkara Kasasi</td><td  colspan=2><?php echo $kasasi_detil[0]['nomor_perkara_kasasi'];?></td></tr>
			<!-- <tr><td class="first-colum">Tanggal Putusan Kasasi</td><td  colspan=2><?php echo $kasasi_detil[0]['putusan_banding'];?></td></tr> -->
			<tr><td class="first-colum">Status Kasasi</td><td  colspan=2><?php echo $kasasi_detil[0]['status_kasasi_text'];?></td></tr>
			<tr><td class="first-colum">Upload Putusan Kasasi</td><td colspan=2>
				<input type="hidden" name="perkara_id" value="<?php echo $kasasi_detil[0]['perkara_id'] ?>">
				<input type="hidden" name="idpn" value="<?php echo $this->session->userdata('idpn'); ?>">
				<input type="hidden" name="jenis_edoc" value="kasasi">
				<input type="file" class="file_edoc" name="file_doc" id="fileInput" required>
			</td></tr>
	</table>	

</form>
<hr>
<script>
  document.getElementById('fileInput').addEventListener('change', function () {
    document.getElementById('uploadForm').submit();
  });
</script>


	
	
	
