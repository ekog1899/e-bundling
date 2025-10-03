<table class="table_form table-striped" style="font-size:14px;width:100%;">
<tbody>
<tr><td width="10%">Tgl. Sidang</td><td width="30%">Agenda</td><td width="20%">Relaas P</td><td  width="20%">Relaas T</td><td width="20%">BAS</td></tr>

<?php
#print_r($list_sidang );
 foreach ($list_sidang as $row){

   $edoc_valid = '<span class="badge badge-success"><i class="fa fa-check"></i> edoc terverifikasi</span>';
   $catatan_p =  ( $row['catatan_p'] != '' ) ? "<br /><span class='badge badge-danger'><i class='fa  fa-comment'></i> : ".$row['catatan_p']."</span>"  :"";
   $catatan_t =  ( $row['catatan_t'] != '' ) ? "<br /><span class='badge badge-danger'><i class='fa  fa-comment'></i> : ".$row['catatan_t']."</span>"  :"";
   $catatan_bas =  ( $row['catatan_bas'] != '' ) ? "<br /><span class='badge badge-danger'><i class='fa  fa-comment'></i>  :  ".$row['catatan_bas']."</span>"  :"";

   $edoc_relaas_p= ( $row['edoc_relaas_p'] != '' ) ? '<br /><a href="javascript:popup_edoc(\''.$row['edoc_relaas_p'].'\')">Lihat Edoc</a>'.$catatan_p :'<br /><span class="text-danger">Blm upload</span>';
   $edoc_relaas_t= ( $row['edoc_relaas_t'] != '' ) ? '<br /><a href="javascript:popup_edoc(\''.$row['edoc_relaas_t'].'\')">Lihat Edoc</a>'.$catatan_t :'<br /><span class="text-danger">Blm upload</span>';
   $edoc_bas= ( $row['edoc_bas'] != '' ) ? '<br /><a href="javascript:popup_edoc(\''.$row['edoc_bas'].'\')">Lihat Edoc</a>'.$catatan_bas :'<br /><span class="text-danger">Blm upload</span>';
   // check apakah sudah valid
   $frm_upload_p = ( $row['val_edoc_p'] == 1 ) ? $edoc_valid:'<input type="file" name="relaas_p" class="file_edoc_sidang" style="width:150px"><span class="status">';
   $frm_upload_t = ( $row['val_edoc_t'] == 1 ) ? $edoc_valid:'<input type="file" name="relaas_t" class="file_edoc_sidang" style="width:150px"><span class="status">';
   $frm_upload_bas = ( $row['val_edoc_bas'] == 1 ) ? $edoc_valid:'<input type="file" name="bas" class="file_edoc_sidang" style="width:150px"><span class="status">';
    echo '<tr><td class="row_tgl_sidang">'.$row['tanggal_sidang'].'</td><td>'.$row['agenda'].'</td><td class="">'.$frm_upload_p.$edoc_relaas_p.'</span></td><td>'.$frm_upload_t.$edoc_relaas_t.'</span></td><td>'.$frm_upload_bas.$edoc_bas.'</span></td></tr>';
 }
?>
</tbody>    
</table>

