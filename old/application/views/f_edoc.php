
<form>
<?php //print_r($banding_detil[0]); ?>

<?php  //print_r($ref_jenis_edoc_t); ?>
<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup><col width="250">
		<col>
		</colgroup><tbody>
			<tr>
				<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="2"><strong>Data Perkara Tingkat Pertama (SIPP)</strong></td>
			</tr>
			<tr><td class="first-colum">Nomor Perkara</td><td><?php echo $banding_detil[0]['nomor_perkara_pa'];?> (<?php echo $banding_detil[0]['jenis_perkara_text'];?>)</td></tr>
			<tr><td class="first-colum">Tanggal Putusan</td><td><?php echo $banding_detil[0]['tgl_putusan_pa'];?></td></tr>
			<tr><td class="first-colum">Majelis Hakim</td><td><?php echo $banding_detil[0]['majelis_hakim_pa'];?></td></tr>
			<tr><td class="first-colum">Para Pihak</td><td><?php echo $banding_detil[0]['para_pihak'];?></td></tr>
</table>	

<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup><col width="250">
		<col width="300">
		<col>
		</colgroup><tbody>
			<tr>
				<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Data Permohonan Banding (SIPP)</strong></td>
			</tr>

			<tr><td class="first-colum">Tanggal Permohonan Banding</td><td  colspan=2><?php echo $banding_detil[0]['permohonan_banding'];?></td></tr>
			<tr><td class="first-colum">Pihak Pemohon Banding</td><td  colspan=2><?php echo $banding_detil[0]['pemohon_banding'];?></td></tr>
			
			<?php
			if (count($ref_jenis_edoc_t) > 0 )
			foreach ($ref_jenis_edoc_t as $row):
					?>
			<tr><td class="first-colum"><?php echo $row['full_jenis_edoc'];?></td><td><input type="hidden"  name="jenis_edoc" value="<?php echo $row['jenis_edoc'];?>" ><input type="text" value="<?php echo $row['org_name'];?>" class="form-control" width="100" name="value"></td><td><input class="btn btn-success save_text" type="button" value="Simpan"></td></tr>
			<?php endforeach; ?>
	</table>	
	
	

	<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#bundle-a" role="tab" aria-controls="bundle-a" aria-selected="true">Bundel A </a>
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
		</ul>



<!-- TABS CONTENT -->

<div class="tab-content" id="myTabContent">

<div class="tab-pane fade show active" id="bundle-a" role="tabpanel" aria-labelledby="home-tab">

<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
		<col width="250">
		<col width="250">
		<col>
		</colgroup><tbody>
			<tr>
			<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>E-Doc Bundle A </strong></td>
			</tr>
			<tr><td colspan="3"><strong>Anda dapat mengupload file secara bertahap/parsial, misal hari ini Surat Pengantar dan besok e-doc sisanya. file edoc masih dapat diubah/diupload ulang selama belum divalidasi di tingkat Banding  </strong></td></tr>
			<?php
			$lengkap = true;
			$html_unlock_bundle_a = "";
				foreach ($ref_jenis_edoc_a as $row):
				
				// klo file_edoc = bas, then override dengan html_persidangan
				if ( $row['jenis_edoc'] == 'bas')
				{ 
					$nama_field = $row['jenis_edoc'];
					$ref_lock = $row['ref_column_lock'];
				
					

					echo '<tr>
					<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>PERSIDANGAN </strong></td>
					</tr>';

					if ( $lock_bundle_a == TRUE )
					{
							echo '<tr><td  colspan=3> <i class="fa fa-lock"></i> DATA PERSIDANGAN tidak ditampilkan karena BUNDLE A TERLOCK, Max. 7 hari dari Penerimaan Banding</td></tr>';		
						
					}
					else
						echo '<tr>
					<td style=""  colspan="3"><strong>'.$html_sidang_1.' </strong></td>
					</tr>';

				} 
				else { // else jenis_edoc = bas
				//

				// sisipkan sub title
				if ( $row['jenis_edoc'] == 'verzet'){
					echo '<tr>
					<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>VERZET</strong></td>
					</tr>';
				}

				if ( $row['jenis_edoc'] == 'jawaban'){
					echo '<tr>
					<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>JAWABAN/REPLIK/DUPIK </strong></td>
					</tr>';
				}
				// sisipkan sub title
				if ( $row['jenis_edoc'] == 'penjelasan_mediasi'){
					echo '<tr>
					<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>MEDIASI </strong></td>
					</tr>';
				}

				if ( $row['jenis_edoc'] == 'alat_bukti_surat_p'){
					echo '<tr>
					<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>ALAT BUKTI SURAT </strong></td>
					</tr>';
				}
				
				if ($row['file_edoc'] == '' && $row['is_required'] == 1)
					$lengkap = false;
				
				$nama_field = $row['jenis_edoc'];
				$ref_lock = $row['ref_column_lock'];
				$html_unlock = '<a class="btn btn-warning" href="javascript:popup_unlock(\''.$perkara_id.'\',\''.$this->session->userdata('idpn').'\',\''.$nama_field.'\')"><i class="fa fa-unlock"></i> Request Buka Kunci ke PTA</a>';
				
								
				$selisih_hari_setelah_req_unlock = 100;	
				
				if ( $lock_bundle_a == TRUE && $row['file_edoc'] == '')
				{
						echo '<tr><td class="first-colum form-group bg-danger text-white col-md-3">'.$row['full_jenis_edoc'].'</td><td  colspan=2> <i class="fa fa-lock"></i> BUNDLE A TERLOCK, Max. 7 hari dari Penerimaan Banding</td></tr>';		
					
				}
				else if ($lock_bundle_a == TRUE && $row['file_edoc'] != '' ) {
					echo '<tr><td class="first-colum form-group bg-success text-white col-md-3">'.$row['full_jenis_edoc'].'</td>'."<td><a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a></td>".'<td> <i class="fa fa-lock"></i> BUNDLE A TERLOCK, Max. 7 hari dari Penerimaan Banding</td></tr>';		
				}
				
				// IF ada ref_lock, maka check apakah masih dalam tenggat waktu

				else if (isset($banding_detil[0][$ref_lock])){					
					$selisih_hari =  _hitung_hari($banding_detil[0][$ref_lock], $tgl_hari_ini,$arr_hari_libur);
					if (isset($arr_req_unlock[$nama_field]['tgl_unlock']))
					{
						$selisih_hari_setelah_req_unlock =  _hitung_hari($arr_req_unlock[$nama_field]['tgl_unlock'], $tgl_hari_ini,$arr_hari_libur);
					}
					
					else if ( $selisih_hari <= $row['limit_lock'] || $selisih_hari_setelah_req_unlock <= 4 || $row['file_edoc'] != ''){	
						$unlock_icon = ($selisih_hari_setelah_req_unlock <= 4 ) ? '<i class="fa fa-unlock"></i>':"";
						if ($row['val_edoc'] == '' )
							echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
						else if ($row['val_edoc'] == 1 )
							echo '<tr><td class="first-colum form-group bg-success text-white">'.$row['full_jenis_edoc'].'</td><td></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"").' <span class=\'badge badge-success\'><i class="fa fa-check"></i> edoc terverifikasi</span></td></tr>';
						else if ($row['val_edoc'] == 0 )
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"")." <span class='badge badge-danger'>Perlu diperbaiki Karena : ".$row['catatan_pta'].'</span></td></tr>';
							
					}
					// jika ada sdh ada file, tampilkan saja beserta tombol lock
					else{
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td  colspan=2> <i class="fa fa-lock"></i> max.'.$row['limit_lock'].' hari setelah '.$ref_lock.' '.$html_unlock.'</td></tr>';		
					}
				}
				else{
					if ($row['val_edoc'] == '' )
							echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
					else if ($row['val_edoc'] == 1 )
							echo '<tr><td class="first-colum form-group bg-success text-white">'.$row['full_jenis_edoc'].'</td><td></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"").' <span class=\'badge badge-success\'><i class="fa fa-check"></i> edoc terverifikasi</span></td></tr>';
					else if ($row['val_edoc'] == 0 )
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"")." <span class='badge badge-danger'>Perlu diperbaiki Karena : ".$row['catatan_pta'].'</span></td></tr>';
					
				#	echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
					
				}	
			
			} // else jenis_edoc = bas

			endforeach;

			if ( $lock_bundle_a == TRUE ) {
				echo '<tr><td colspan=3><a class="btn btn-warning" href="javascript:popup_unlock(\''.$perkara_id.'\',\''.$this->session->userdata('idpn').'\',\'bundle_a\')"><i class="fa fa-unlock"></i> Request Buka Kunci Bundle A ke PTA</a></td></tr>';		
			}
			?>
			
</table>


</div>


<div class="tab-pane fade" id="bundle-b" role="tabpanel" aria-labelledby="bundle-b">

<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
		<col width="250">
		<col width="250">
		<col>
		</colgroup><tbody>
			<tr>
			<input type="hidden" name="perkara_id" value="<?php echo $perkara_id;?>">
			<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>E-Doc Bundle B</strong></td>
			</tr>
			<tr><td colspan="4"><strong>Anda dapat mengupload file secara bertahap/parsial, misal hari ini Surat Pengantar dan besok e-doc sisanya. file edoc masih dapat diubah/diupload ulang selama belum divalidasi di tingkat Banding  </strong></td></tr>
			<?php
			$lengkap = true;
			
			foreach ($ref_jenis_edoc_b as $row):

		
				if ($row['file_edoc'] == '' && $row['is_required'] == 1)
					$lengkap = false;
					$nama_field = $row['jenis_edoc'];
					$ref_lock = $row['ref_column_lock'];
					$html_unlock = '<a class="btn btn-warning" href="javascript:popup_unlock(\''.$perkara_id.'\',\''.$this->session->userdata('idpn').'\',\''.$nama_field.'\')"><i class="fa fa-unlock"></i> Request Buka Kunci ke PTA</a>';
					
			
				$selisih_hari_setelah_req_unlock = 100;	
				
				if ( $lock_bundle_b == TRUE && $row['file_edoc'] == '')
				{
						echo '<tr><td class="first-colum form-group bg-danger text-white col-md-3">'.$row['full_jenis_edoc'].'</td><td  colspan=2> <i class="fa fa-lock"></i> BUNDLE B TERLOCK, Max. 30 hari dari Penerimaan Banding</td></tr>';		
					
				}

				else if ($lock_bundle_b == TRUE && $row['file_edoc'] != '' ) {
					echo '<tr><td class="first-colum form-group bg-success text-white col-md-3">'.$row['full_jenis_edoc'].'</td>'."<td><a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a></td>".'<td> <i class="fa fa-lock"></i> BUNDLE B TERLOCK, Max. 30 hari dari Penerimaan Banding</td></tr>';		
				}

				else if (isset($banding_detil[0][$ref_lock])){					
					$selisih_hari =  _hitung_hari($banding_detil[0][$ref_lock], $tgl_hari_ini,$arr_hari_libur);
					if (isset($arr_req_unlock[$nama_field]['tgl_unlock']))
					{
						$selisih_hari_setelah_req_unlock =  _hitung_hari($arr_req_unlock[$nama_field]['tgl_unlock'], $tgl_hari_ini,$arr_hari_libur);
					}
					if ( $selisih_hari <= $row['limit_lock'] || $selisih_hari_setelah_req_unlock <= 4 || $row['file_edoc'] != '' ){	
						$unlock_icon = ($selisih_hari_setelah_req_unlock <= 4 ) ? '<i class="fa fa-unlock"></i>':"";
						if ($row['val_edoc'] == '' )
							echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
						else if ($row['val_edoc'] == 1 )
							echo '<tr><td class="first-colum form-group bg-success text-white">'.$row['full_jenis_edoc'].'</td><td></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"").' <span class=\'badge badge-success\'><i class="fa fa-check"></i> edoc terverifikasi</span></td></tr>';
						else if ($row['val_edoc'] == 0 )
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"")." <span class='badge badge-danger'>Perlu diperbaiki Karena : ".$row['catatan_pta'].'</span></td></tr>';
							
					}
					else{
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td  colspan=2> <i class="fa fa-lock"></i> max.'.$row['limit_lock'].' hari setelah '.$ref_lock.' '.$html_unlock.'</td></tr>';		
					}
				}
				else{
					if ($row['val_edoc'] == '' )
							echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" id="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
						else if ($row['val_edoc'] == 1 )
							echo '<tr><td class="first-colum form-group bg-success text-white">'.$row['full_jenis_edoc'].'</td><td></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"").' <span class=\'badge badge-success\'><i class="fa fa-check"></i> edoc terverifikasi</span></td></tr>';
						else if ($row['val_edoc'] == 0 )
						echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"")." <span class='badge badge-danger'>Perlu diperbaiki Karena : ".$row['catatan_pta'].'</span></td></tr>';
					
				}	

			endforeach;

			if ( $lock_bundle_b == TRUE ) {
				echo '<tr><td colspan=3><a class="btn btn-warning" href="javascript:popup_unlock(\''.$perkara_id.'\',\''.$this->session->userdata('idpn').'\',\'bundle_b\')"><i class="fa fa-unlock"></i> Request Buka Kunci Bundle B ke PTA</a></td></tr>';		
			}
			?>
			
</table>


</div>



<div class="tab-pane fade" id="sidang" role="tabpanel" aria-labelledby="sidang">

<?php
$url_salput ="";
$url_tt_banding ="";
#print_r($ref_jenis_edoc_c);
foreach($ref_jenis_edoc_c as $dc){
 if ( $dc['jenis_edoc'] == 'salput_banding' && $dc['file_edoc'] != ''){
	$url_salput = $dc['file_edoc'];
 }
 if ( $dc['jenis_edoc'] == 'tt_daftar_banding' && $dc['file_edoc'] != ''){
	$url_tt_banding = $dc['file_edoc'];
 }
}
?>
<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
		<col width="250">
		<col>
	
		</colgroup><tbody>
			<tr>
			<input type="hidden" name="perkara_id" value="<?php echo $perkara_id;?>">
			<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>Proses di Tingkat Banding </strong></td>
			</tr>
			<tr><td colspan="3"><strong>Sumber Data : Sinkronisasi Data SIPP</strong></td></tr>
	
		
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Nomor Perkara Banding</td>
				<td><strong><?php echo $banding_detil[0]['nomor_perkara_banding'];?></strong>
			
				<?php 
					if ( $url_tt_banding != '' )
					echo "<a class='preview-edoc' href=\"javascript:popup_edoc('".$url_tt_banding."')\"><i class='fas fa-fw fa-file-pdf'></i> Lihat Tanda Terima </a>";
				?></td>
		</tr>
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Majelis Hakim Banding</td>
				<td><?php echo '';?></td>
		</tr>
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Status Banding</td>
				<td><?php echo '';?></td>
		</tr>
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Status Putusan Banding</td>
				<td><span class="badge badge-success"><?php echo '';?></span></td>
		</tr>
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Catatan Amar Banding</td>
				<td><?php echo '';?></td>
		</tr>

		<?php
		if ( $url_salput != '' ) :
		?>
		<tr class="">
			<td class="first-colum form-group bg-success text-white">	
				Salinan Putusan Banding</td>
				<td>
					<?php echo "<a class='preview-edoc' href=\"javascript:popup_edoc('".$url_salput."')\"><i class='fas fa-fw fa-file-pdf'></i> Lihat Salinan Putusan</a>";
				?>
		</tr>
		<?php endif; ?>

			
			
</table>


</div>


<div class="tab-pane fade" id="pbtbanding" role="tabpanel" aria-labelledby="sidang">


<table class="table_form" style="font-size:14px;width:100%;" border="0">
		<colgroup>
		<col width="250">
		<col>
	
		</colgroup><tbody>
			<tr>
			<input type="hidden" name="perkara_id" value="<?php echo $perkara_id;?>">
			<td class= "bg-gradien-blue" style="padding:5px;;color:white" colspan="3"><strong>E-Doc Pemberitahuan Putusan Banding </strong></td>
			</tr>
			
		
			<?php
			$lengkap = true;
			foreach ($ref_jenis_edoc_d as $row):
			if ( $row['file_edoc'] == '' && $row['is_required'] == 1 )
				$lengkap = false;
			
			if ($row['val_edoc'] == '' )
				echo '<tr><td class="first-colum form-group '.( $row['file_edoc'] == '' ? "bg-warning text-white":"bg-success text-white" ).'">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\">".$row['org_name']."</a> telah diupload":"").'</td></tr>';
			else if ($row['val_edoc'] == 1 )
				echo '<tr><td class="first-colum form-group bg-success text-white">'.$row['full_jenis_edoc'].'</td><td></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a>":"").' <span class=\'badge badge-success\'>edoc terverifikasi</span></td></tr>';
			else if ($row['val_edoc'] == 0 )
			echo '<tr><td class="first-colum form-group bg-danger text-white">'.$row['full_jenis_edoc'].'</td><td><input type="file" class="file_edoc" name="'.$row['jenis_edoc'].'"></td><td class="status">'.($row['org_name'] <> '' ? "<a class='preview-edoc' href=\"javascript:popup_edoc('".$row['file_edoc']."')\"><i class='fas fa-fw fa-file-pdf'></i>".$row['org_name']."</a> <span class='badge badge-danger'>Perlu diperbaiki Karena : ".$row['catatan_pta']:"").'</span></td></tr>';
			

			endforeach;
			?>
			
			
</table>


</div>

</form>
<hr>


	
	
	
