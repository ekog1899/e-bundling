<script>
	$(document).ready(function() {
		load_sidang_sidang('<?php echo $idpn; ?>', '<?php echo $perkara_id; ?>');
	})
</script>
<?php 
$url = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : ''; 
?>
<form>
	<a href="<?php echo $url;?>" class="btn btn-danger plus float-right">Kembali</a>

	<?php echo $this->session->flashdata("k"); ?>
	<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
			<col width="250">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="2"><strong>Data Perkara Tingkat Pertama (SIPP)</strong></td>
			</tr>
			<tr>
				<td class="first-colum">Nomor Perkara</td>
				<td><?php echo $banding_detil[0]['nomor_perkara_pa']; ?> (<?php echo $banding_detil[0]['jenis_perkara_text']; ?>)</td>
			</tr>
			<tr>
				<td class="first-colum">Tanggal Putusan</td>
				<td><?php echo $banding_detil[0]['tgl_putusan_pa']; ?></td>
			</tr>
			<tr>
				<td class="first-colum">Majelis Hakim</td>
				<td><?php echo $banding_detil[0]['majelis_hakim_pa']; ?></td>
			</tr>
			<tr>
				<td class="first-colum">Para Pihak</td>
				<td><?php echo $banding_detil[0]['para_pihak']; ?></td>
			</tr>
	</table>

	<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
			<col width="250">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="2"><strong>Data Permohonan Banding (SIPP)</strong></td>
			</tr>

			<tr>
				<td class="first-colum">Tanggal Permohonan Banding</td>
				<td><?php echo $banding_detil[0]['permohonan_banding']; ?></td>
			</tr>
			<tr>
				<td class="first-colum">Pihak Pemohon Banding</td>
				<td><?php echo $banding_detil[0]['pemohon_banding']; ?></td>
			</tr>
			<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
			<input type="hidden" name="idpn" value="<?php echo $idpn; ?>">

	</table>


	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#bundle-a" role="tab" aria-controls="bundle-a" aria-selected="true">Bundel A</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="profile-tab" data-toggle="tab" href="#bundle-b" role="tab" aria-controls="bundle-b" aria-selected="false">Bundel B</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#sidang" role="tab" aria-controls="contact" aria-selected="false">Pendaftaran dan Persidangan</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#pbtbanding" role="tab" aria-controls="contact" aria-selected="false">PBT Putusan Banding</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#pemeriksa" role="tab" aria-controls="contact" aria-selected="false">Tim Pemeriksa Pra Daftar</a>
		</li>
	</ul>



	<!-- TABS CONTENT -->


	<div class="tab-content" id="myTabContent">

		<div class="tab-pane fade show active" id="bundle-a" role="tabpanel" aria-labelledby="home-tab">

			<table class="table_form" style="font-size:14px;width:100%;" border="0">
				<colgroup>
					<col width="250">
					<col width="25%">
					<col width="200">
					<col>
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>E-Doc Bundle A</strong></td>
					</tr>
					<?php
					$lengkap = true;
					foreach ($ref_jenis_edoc_a as $row) :


						if ($row['file_edoc'] == '' && $row['is_required'] == 1)
							$lengkap = false;

						// klo file_edoc = bas, then override dengan html_persidangan
						if ($row['jenis_edoc'] == 'bas') {
							echo '<tr>
									<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>PERSIDANGAN </strong></td>
									</tr>';
							echo '<tr>
									<td style=""  colspan="5"><strong>' . $html_sidang_1 . ' </strong></td>
									</tr>';
						} else {

							// sisipkan sub title
							if ($row['jenis_edoc'] == 'verzet') {
								echo '<tr>
										<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>VERZET</strong></td>
										</tr>';
							}

							// sisipkan sub title MEDIASI 
							if ($row['jenis_edoc'] == 'penjelasan_mediasi') {
								echo '<tr>
										<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>MEDIASI </strong></td>
										</tr>';
							}
							if ($row['jenis_edoc'] == 'alat_bukti_surat_p') {
								echo '<tr>
										<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>ALAT BUKTI SURAT </strong></td>
										</tr>';
							}





					?>
							<tr>
								<td class="first-colum form-group <?php echo ($row['file_edoc'] == '' ? "bg-warning text-white" : "bg-success text-white"); ?>"> <?php echo $row['full_jenis_edoc']; ?></td>
								<input type="hidden" name="id_edoc" value="<?php echo $row['id_edoc']; ?>">
								<input type="hidden" name="jenis_edoc" value="<?php echo $row['jenis_edoc']; ?>">
								<?php
								if ($row['file_edoc'] <> '') :
								?>
									<td class="status"><?php echo ($row['org_name'] <> '' ? "<a class='preview-edoc btn btn-secondary' href=\"javascript:popup_edoc('" . $row['file_edoc'] . "')\"><i class='fa fa-file-pdf'></i> " . $row['org_name'] . "</a>" : ""); ?></td>

								<?php
								else :
									echo "<td>-- FILE TIDAK ADA --</td>";
								endif; ?>
								<td>

									<?php if ($row['val_edoc'] == '' &&  $this->session->userdata('is_verifikator') == TRUE) {

									?>

										<select class="form-control <?php echo ($row['file_edoc'] == '' ? "bg-warning text-white" : "bg_blink text-white"); ?>" name="select_validasi">
											<option> -- Pilih Validasi --</option>
											<option value="1"> Valid </option>
											<option value="0"> Tidak Valid </option>
										</select>

									<?php
									} else if ($row['val_edoc'] == 1) {
										echo "<span class='badge badge-success'><i class='fa fa-check'></i> edoc sudah valid</span>";
									} else if ($this->session->userdata('is_verifikator') == FALSE && $row['val_edoc'] == 0 && $row['file_edoc'] <> '') {
										echo "<span class='badge badge-warning'><i class='fa fa-hourglass-half'></i> menunggu validasi PTA</span>";
									} else if ($row['val_edoc'] == 0 && $row['file_edoc'] <> '') {
										echo "<span class='badge badge-danger'><i class='fa fa-times'></i> menunggu perbaikan satker</span>";
									}

									?>

								</td>
								<td>
									<?php if ($this->session->userdata('is_verifikator') == TRUE) :
									?>
										<input type="text" class="catatan_validasi form-control" <?php echo ($row['val_edoc'] == '') ? "" : "readonly"; ?> name="catatan_validasi" value="<?php echo $row['catatan_pta']; ?>">
									<?php
									endif;
									?>
								</td>
								<?php
								if ($row['val_edoc'] == '' &&  $this->session->userdata('is_verifikator') == TRUE) {
									echo '<td><a class="btn btn-validasi btn-success" href="javascript:;">Simpan</a></td>';
								} else if ($row['waktu_validasi'] != '') {
									echo "<td>tgl validasi " . $row['waktu_validasi'] . "</td>";
								}
								?>

							</tr>
					<?php
						}
					endforeach;
					//	exit;
					?>

			</table>

		</div>


		<div class="tab-pane fade" id="bundle-b" role="tabpanel" aria-labelledby="bundle-b">

			<table class="table_form" style="font-size:14px;width:100%;" border="0">
				<colgroup>
					<col width="250">
					<col width="25%">
					<col width="200">
					<col>
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>E-Doc Bundle B</strong></td>
					</tr>
					<?php
					$lengkap = true;
					foreach ($ref_jenis_edoc_b as $row) :
						if ($row['file_edoc'] == '' && $row['is_required'] == 1)
							$lengkap = false;

					?>

						<tr>
							<td class="first-colum form-group <?php echo ($row['file_edoc'] == '' ? "bg-warning text-white" : "bg-success text-white"); ?>"> <?php echo $row['full_jenis_edoc']; ?></td>
							<input type="hidden" name="id_edoc" value="<?php echo $row['id_edoc']; ?>">
							<input type="hidden" name="jenis_edoc" value="<?php echo $row['jenis_edoc']; ?>">
							<?php
							if ($row['file_edoc'] <> '') :
							?>
								<td class="status"><?php echo ($row['org_name'] <> '' ? "<a class='preview-edoc btn btn-secondary' href=\"javascript:popup_edoc('" . $row['file_edoc'] . "')\"><i class='fa fa-file-pdf'></i> " . $row['org_name'] . "</a>" : ""); ?></td>

							<?php
							else :
								echo "<td>-- FILE TIDAK ADA --</td>";
							endif; ?>
							<td>

								<?php if ($row['val_edoc'] == '' &&  $this->session->userdata('is_verifikator') == TRUE) { ?>
									<select class="form-control <?php echo ($row['file_edoc'] == '' ? "bg-warning text-white" : "bg_blink text-white"); ?>" name="select_validasi">
										<option> -- Pilih Validasi --</option>
										<option value="1"> Valid </option>
										<option value="0"> Tidak Valid </option>
									</select>

								<?php
								} else if ($row['val_edoc'] == 1) {
									echo "<span class='badge badge-success'>edoc sudah valid</span>";
								} else if ($this->session->userdata('is_verifikator') == FALSE && $row['val_edoc'] == 0 && $row['file_edoc'] <> '') {
									echo "<span class='badge badge-warning'><i class='fa fa-hourglass-half'></i> menunggu validasi</span>";
								} else if ($row['val_edoc'] == 0 && $row['file_edoc'] <> '') {
									echo "<span class='badge badge-danger'><i class='fa fa-times'></i>menunggu perbaikan satker</span>";
								}
								?>

							</td>

							<td>
								<?php if ($this->session->userdata('is_verifikator') == TRUE) :
								?>
									<input type="text" class="catatan_validasi form-control" <?php echo ($row['val_edoc'] == '') ? "" : "readonly"; ?> name="catatan_validasi" value="<?php echo $row['catatan_pta']; ?>">
								<?php
								endif;
								?>
							</td>

							<?php
							if ($row['val_edoc'] == '' &&  $this->session->userdata('is_verifikator') == TRUE) {
								echo '<td><a class="btn btn-validasi btn-success" href="javascript:;">Simpan</a></td>';
							} else if ($row['waktu_validasi'] != '') {
								echo "<td>tgl validasi " . $row['waktu_validasi'] . "</td>";
							}
							?>

						</tr>
					<?php
					endforeach;
					//	exit;
					?>

			</table>
		</div>



		<div class="tab-pane fade" id="sidang" role="tabpanel" aria-labelledby="sidang">


			<table class="table_form" style="font-size:14px;width:100%;" border="0">
				<colgroup>
					<col width="250">
					<col width="250">
					<col>
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td colspan="3"><strong>Anda dapat mengupload file secara bertahap, misal hari ini Tanda Terima. Begitu diupload, maka Pihak dan Satuan Kerja akan mendapat notifikasi</strong></td>
					</tr>
					<tr>
						<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Proses Banding</strong></td>
					</tr>
					<tr class="">
						<td td class="first-colum form-group bg-warning text-white">
							Nomor Perkara Banding</td>
						<td colspan=3>
							<?php
							if ($banding_detil[0]['nomor_perkara_banding'] == '') :
								echo " otomatis akan muncul setelah 1 hari setelah diinput ke SIPP Banding";
							else :
								echo $banding_detil[0]['nomor_perkara_banding'];
							endif;
							?>

						</td>
					</tr>
					<?php
					$lengkap = true;
					foreach ($ref_jenis_edoc_c as $row) :
						if ($row['file_edoc'] == '' && $row['is_required'] == 1)
							$lengkap = false;
						$user_role = 'pa';
						echo '<tr><td class="first-colum form-group ' . ($row['file_edoc'] == '' ? "bg-warning text-white" : "bg-success text-white") . '">' . $row['full_jenis_edoc'] . '</td><td><input type="file" class="file_edoc" name="' . $row['jenis_edoc'] . '"></td><td class="status">' . ($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('" . $row['file_edoc'] . "')\">" . $row['org_name'] . "</a> telah diupload" : "") . '</td></tr>';


					endforeach;
					?>



					<!-- tundaan sidang -->
					<tr>
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>JADWAL SIDANG</strong></td>
					</tr>
					<tr>
						<td colspan="3"><a href="javascript:popup_tunda('','<?php echo $perkara_id; ?>','<?php echo $idpn; ?>','','','')" class="btn btn-success">Tambah Jadwal Sidang</a></td>
					<tr>
					<tr>
						<td colspan="3" id="tab_jsidang">
							<!-- loaded by ajax -->
						</td>
					</tr>




			</table>


		</div>



		<div class="tab-pane fade" id="pbtbanding" role="tabpanel" aria-labelledby="bundle-b">

			<table class="table_form" style="font-size:14px;width:100%;" border="0">
				<colgroup>
					<col width="250">
					<col width="25%">
					<col width="200">
					<col>
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="5"><strong>E-Doc PBT BANDING</strong></td>
					</tr>
					<?php
					$lengkap = true;
					foreach ($ref_jenis_edoc_d as $row) :
						if ($row['file_edoc'] == '' && $row['is_required'] == 1)
							$lengkap = false;

					?>

						<tr>
							<td class="first-colum form-group <?php echo ($row['val_edoc'] == '' ? "bg-warning text-white" : "bg-success text-white"); ?>"> <?php echo $row['full_jenis_edoc']; ?></td>
							<input type="hidden" name="id_edoc" value="<?php echo $row['id_edoc']; ?>">
							<input type="hidden" name="jenis_edoc" value="<?php echo $row['jenis_edoc']; ?>">
							<?php
							if ($row['file_edoc'] <> '') :
							?>
								<td class="status"><?php echo ($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('" . $row['file_edoc'] . "')\">" . $row['org_name'] . "</a>" : ""); ?></td>

							<?php
							else :
								echo "<td>-- FILE TIDAK ADA --</td>";
							endif; ?>
							<td>

								<?php if ($row['val_edoc'] == '') { ?>
									<select class="form-control <?php echo ($row['file_edoc'] == '' ? "bg-warning text-white" : ""); ?>" name="select_validasi">
										<option> -- Pilih Validasi --</option>
										<option value="1"> Valid </option>
										<option value="0"> Tidak Valid </option>
									</select>

								<?php
								} else if ($row['val_edoc'] == 1) {
									echo "<span class='badge badge-success'>edoc sudah valid</span>";
								} else if ($row['val_edoc'] == 0) {
									echo "<span class='badge badge-danger'>menunggu perbaikan satker</span>";
								}
								?>

							</td>
							<td><input type="text" class="catatan_validasi form-control" <?php echo ($row['val_edoc'] == '') ? "" : "readonly"; ?> name="catatan_validasi" value="<?php echo $row['catatan_pta']; ?>"></td>
							<?php
							if ($row['val_edoc'] == '') {
								echo '<td><a class="btn btn-validasi btn-success" href="javascript:;">Simpan</a></td>';
							} else {
								echo "<td>sudah validasi tgl " . $row['waktu_validasi'] . "</td>";
							}
							?>

						</tr>
					<?php
					endforeach;
					//	exit;
					?>

			</table>
		</div>

		<div class="tab-pane fade" id="pemeriksa" role="tabpanel" aria-labelledby="sidang">


			<table class="table_form" style="font-size:14px;width:100%;" border="0">
				<colgroup>
					<col width="250">
					<col width="250">
					<col>
					<col>
				</colgroup>
				<tbody>
					<tr>
						<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Susunan Tim Pemeriksa</strong></td>
					</tr>
					<tr>
						<td width="15%">
							<?php
							if ($this->session->userdata('user_jabatan_id') == '1') { ?><a class="btn btn-success" data-toggle="modal" data-target="#tambahpemeriksa"><i class="fa fa-plus"></i> Tambah</a></td>
						<td><a href="<?php echo base_url("Edoc/hapus_timpemeriksa/$perkara_id/$idpn"); ?>" title='Hapus TIM Pemeriksa Pra Daftar'>
								<img src="<?php echo base_url('aset/img/hapus.png'); ?>" height=30>
							</a> <?php } ?>
						</td>
					<tr>
					<tr class="">
						<td td class="first-colum form-group bg-warning text-white">
							Ketua Tim</td>
						<td>
							<?php
							echo $cektim['nama_gelar'];
							?>
						</td>
					</tr>
					<tr class="">
						<td td class="first-colum form-group bg-warning text-white">
							Abggota Tim 1</td>
						<td width=75%> <?php
										echo $cektim['nama_tim1'];
										?> </td>
					</tr>
					<tr class="">
						<td td class="first-colum form-group bg-warning text-white">
							Anggota Tim 2</td>
						<td> <?php
								echo $cektim['nama_tim2'];
								?> </td>
					</tr>
					<tr class="">
						<td td class="first-colum form-group bg-warning text-white">
							Panitera Pengganti</td>
						<td> <?php
								echo $cektim['nama_panitera'];
								?></td>
					</tr>

					<tr>
						<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
						<td class="bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Hasil Pemeriksaan</strong></td>
						<td width="15%"> <a class="btn btn-warning" data-toggle="modal" data-target="#tambah_catatan"><i class="fa fa-plus"></i> Tambah Catatan</a></td>
					</tr>
					<tr class="">
						<table style='width:100%' class="table table-bordered table-striped">
							<thead>
								<tr>
									<th style='width:1%'>No</th>
									<th style='width:20%'>Nama Pemeriksa</th>
									<th style='width:70%'>Catatan</th>
									<th style='width:10%'>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$start = 0;
								foreach ($catatan as $c) {
								?>
									<tr>
										<td width="5%"><?php echo ++$start ?></td>
										<td width="15%"><?php echo $c['nama_pengguna'] ?></td>
										<td width="15%"><?php echo $c['catatan'] ?> ( <?php echo $c['tanggal_insert'] ?>)</td>
										<td width="15%">
											<!-- <button type="button" name="delete" id="delete" class="btn btn-danger"><i class="fa fa-trash"></i></button> -->
											<?php echo anchor(site_url('Edoc/delete_catatan/'.$c['id'].'/'.$idpn.'/'.$perkara_id), '<i class="fa fa-trash"></i>', 'title="hapus" class="btn btn-danger" onclick="javasciprt: return confirm(\'Apakah anda yakin menghapus data ini ?\')"');
											?>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</tr>

			</table>


		</div>


</form>
<hr>

<div class="modal fade" id="tambahpemeriksa" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-m modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="permintaan">Tambah Tim Pemeriksa Pra Daftar</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<form name="add_pemeriksa" id="add_pemeriksa" method="post" action="<?php echo base_url('Edoc/save_timpemeriksa'); ?>">
						<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
						<input type="hidden" name="idpn" value="<?php echo $idpn; ?>">
						<table>
							<tr>
								<td><input type="hidden" class="form-control form-control-sm" name="idp" id="idp">
									<div class="form-group">Ketua Tim</div>
								</td>
								<td width="5%">
									<div class="form-group">: </div>
								</td>
								<td>
									<select required name="id_ketua_tim" id="id_ketua_tim" class="form-control form-control-sm">
										<option value="">--Pilih Ketua--</option>
										<?php foreach ($hakim as $t) : ?>
											<option value="<?php echo $t['id']; ?>"><?php echo $t['nama_gelar']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group">Hakim 1 </div>
								</td>
								<td width="5%">
									<div class="form-group">: </div>
								</td>
								<td>
									<select required name="id_anggota_tim_1" id="id_anggota_tim_1" class="form-control form-control-sm">
										<option value="">--Pilih Hakim 1--</option>
										<?php foreach ($hakim as $t) : ?>
											<option value="<?php echo $t['id']; ?>"><?php echo $t['nama_gelar']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group">Hakim 2 </div>
								</td>
								<td width="5%">
									<div class="form-group">: </div>
								</td>
								<td>
									<select required name="id_anggota_tim_2" id="id_anggota_tim_2" class="form-control form-control-sm">
										<option value="">--Pilih Hakim 2--</option>
										<?php foreach ($hakim as $t) : ?>
											<option value="<?php echo $t['id']; ?>"><?php echo $t['nama_gelar']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<div class="form-group">Panitera Pengganti </div>
								</td>
								<td width="5%">
									<div class="form-group">: </div>
								</td>
								<td>
									<select required name="id_panitera_tim" id="id_panitera_tim" class="form-control form-control-sm">
										<option value="">--Pilih Panitera Pengganti--</option>
										<?php foreach ($pp as $p) : ?>
											<option value="<?php echo $p['id']; ?>"><?php echo $p['nama_gelar']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td width="5%">
									<div class="form-group"></div>
								</td>
							</tr>
						</table>
						<div class="modal-footer">
							<input type="submit" name="simpan" id="simpan" class="btn btn-primary" value="Simpan" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="tambah_catatan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-m modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="permintaan">Tambah Catatan Tim Pemeriksa Pra Daftar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<form name="add_catatan" id="add_catatan" method="post" action="<?php echo base_url('Edoc/save_catatan'); ?>">
						<input type="hidden" name="perkara_id" value="<?php echo $perkara_id; ?>">
						<input type="hidden" name="idpn" value="<?php echo $idpn; ?>">
						<input type="hidden" name="username" value="<?php echo $this->session->userdata('nama') ?>">
						<table>
							<tr width="100%">
								<textarea required class="form-control" rows="5" name="catatan" id="catatan" placeholder="Catatan tim pemeriksa"></textarea>

							</tr>
						</table>
						<div class="modal-footer">
							<input type="submit" name="simpan" id="simpan" class="btn btn-primary" value="Simpan" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<a href="<?php echo base_url("Edoc/cetak_instrumen_timpmh/$perkara_id/$idpn"); ?>" title='Cetak Instrumen TIM Pemeriksa Pra Daftar'>
	<img style="position: fixed; bottom: 30px; right: 8px;" src="<?php echo base_url('aset/img/cetak.png'); ?>" height=80>
</a>