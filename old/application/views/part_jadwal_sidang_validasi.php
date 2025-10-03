<table class="table_form table-striped" style="font-size:14px;width:100%;">
<tbody>
<tr><td width="10%">Tgl. Sidang</td><td width="25%">Agenda</td><td>Edoc</td></tr>

<?php
 foreach ($list_sidang as $row){
   
   $html_validasi_p = '<input type="hidden" name="tgl_sidang" value="'.$row['tanggal_sidang'].'" ><select class="form-control col-md-3 '.($row['val_edoc_p'] == '' ? "bg_blink text-white" : "" ).'" name="select_validasi_sidang">
                              <option> -- Pilih Validasi --</option>
                              <option value="1"> Valid </option>
                           <option value="0"> Tidak Valid </option>
                        </select>
                        <input type="text" class="col-md-4 catatan_validasi form-control" name="catatan_validasi" value="">
                        <a class="btn btn-validasi-sidang btn-success col-md-2" href="javascript:;">Simpan</a>';  

   $html_validasi_t = '<input type="hidden" name="tgl_sidang" value="'.$row['tanggal_sidang'].'" ><select class="form-control col-md-3 '.($row['val_edoc_t'] == '' ? "bg_blink text-white" : "" ).'" name="select_validasi_sidang">
                        <option> -- Pilih Validasi --</option>
                        <option value="1"> Valid </option>
                     <option value="0"> Tidak Valid </option>
                  </select>
                  <input type="text" class="col-md-4 catatan_validasi form-control" name="catatan_validasi" value="">
                  <a class="btn btn-validasi-sidang btn-success col-md-2" href="javascript:;">Simpan</a>';     
                  
   $html_validasi_bas = '<input type="hidden" name="tgl_sidang" value="'.$row['tanggal_sidang'].'" ><select class="form-control col-md-3 '.($row['val_edoc_bas'] == '' ? "bg_blink text-white" : "" ).'" name="select_validasi_sidang">
                  <option> -- Pilih Validasi --</option>
                  <option value="1"> Valid </option>
               <option value="0"> Tidak Valid </option>
            </select>
            <input type="text" class="col-md-4 catatan_validasi form-control" name="catatan_validasi" value="">
            <a class="btn btn-validasi-sidang btn-success col-md-2" href="javascript:;">Simpan</a>';                  
   
    $status_edoc_p = ( $row['val_edoc_p'] == 1 ) ? "<span class='badge badge-success'><i class='fa fa-check'></i> edoc sudah valid</span>" :"<span class='badge badge-danger'><i class='fa fa-times'></i> menunggu perbaikan satker</span>";
    $status_edoc_t = ( $row['val_edoc_t'] == 1 ) ? "<span class='badge badge-success'><i class='fa fa-check'></i> edoc sudah valid</span>" :"<span class='badge badge-danger'><i class='fa fa-times'></i> menunggu perbaikan satker</span>";
    $status_edoc_bas = ( $row['val_edoc_bas'] == 1 ) ? "<span class='badge badge-success'><i class='fa fa-check'></i> edoc sudah valid</span>" :"<span class='badge badge-danger'><i class='fa fa-times'></i> menunggu perbaikan satker</span>";
    
    $html_validasi_p = ($row['val_edoc_p'] <> ''  ) ? $status_edoc_p : $html_validasi_p;
    $html_validasi_t = ($row['val_edoc_t'] <> ''  ) ? $status_edoc_t : $html_validasi_t;
    $html_validasi_bas = ($row['val_edoc_bas'] <> '' ) ? $status_edoc_bas : $html_validasi_bas;

    // finally, check is_verifikator
   $html_validasi_p = (  $this->session->userdata('is_verifikator') == FALSE  && $row['val_edoc_p'] == '' ) ? "<span class='badge badge-warning'><i class='fa fa-hourglass-half'></i> menunggu validasi PTA</span>" : $html_validasi_p;
   $html_validasi_t = (  $this->session->userdata('is_verifikator') == FALSE  && $row['val_edoc_t'] == '' ) ? "<span class='badge badge-warning'><i class='fa fa-hourglass-half'></i> menunggu validasi PTA</span>" : $html_validasi_t;
   $html_validasi_bas = (  $this->session->userdata('is_verifikator') == FALSE && $row['val_edoc_bas'] == '' ) ? "<span class='badge badge-warning'><i class='fa fa-hourglass-half'></i> menunggu validasi PTA</span>" : $html_validasi_bas;
   
    $edoc_relaas_p= ( $row['edoc_relaas_p'] != '' ) ?  '<a class="btn btn-secondary col-md-2" href="javascript:popup_edoc(\''.$row['edoc_relaas_p'].'\')"><i class="fa fa-file-pdf"></i> Relaas P </a> '.$html_validasi_p : '- Relaas P Blm diupload-';
    $edoc_relaas_t= ( $row['edoc_relaas_t'] != '' ) ? '<a class="btn btn-secondary col-md-2" href="javascript:popup_edoc(\''.$row['edoc_relaas_t'].'\')"><i class="fa fa-file-pdf"></i> Relaas T </a> '.$html_validasi_t : '- Relaas T Blm diupload-';
    $edoc_bas = ( $row['edoc_bas'] != '' ) ? '<a class="btn btn-secondary col-md-2" href="javascript:popup_edoc(\''.$row['edoc_bas'].'\')"><i class="fa fa-file-pdf"></i> BAS </a> '.$html_validasi_bas : '- BAS Blm diupload-';
    
    
    
    
    echo '<tr><td class="row_tgl_sidang">'.$row['tanggal_sidang'].'</td><td>'.$row['agenda'].'</td><td class="">
      <div class="'.( $row['val_edoc_p'] == '' && $this->session->userdata('is_verifikator') == TRUE ? "row":"" ).' border-bottom pb-1 pt-1"><input type="hidden" name="jenis_edoc" value="relaas_p" >'.$edoc_relaas_p.'</div>
      <div class="'.( $row['val_edoc_t'] == '' && $this->session->userdata('is_verifikator') == TRUE  ? "row":"" ).' border-bottom pb-1 pt-1"><input type="hidden" name="jenis_edoc" value="relaas_t" >'.$edoc_relaas_t.'</div>
      <div class="'.( $row['val_edoc_bas'] == '' && $this->session->userdata('is_verifikator') == TRUE  ? "row":"" ).' border-bottom pb-1 pt-1"><input type="hidden" name="jenis_edoc" value="bas" >'.$edoc_bas.'</div>
    </td></tr>';
 }
?>
</tbody>    
</table>

