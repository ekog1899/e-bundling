<table class="table table-bordered table-hover">
	<thead>
	
        <tr>  		
			<th width="6%">No</th>
			<th width="26%">Tanggal</th>
            <th>Agenda</th>
			<th>Alasan Tunda</th>
            <th>Aksi</th>	
		</tr>
	</thead>


	<tbody>
    <?php
    $i = 0;
   // $CI =& get_instance();
   // $CI->load->library('encryption');
    if ( count($jadwal_sidang) == 0 ){
        echo "<tr><td colspan='3'>-- DATA BELUM TERSEDIA -- </td></tr>";
    }
    else
    foreach ($jadwal_sidang as $row):
        $i++;
        $timestamp = strtotime( $row['tanggal']);
   
		// Create the new format from the timestamp
		$new_tanggal = date("d-m-Y", $timestamp);
       ?>
    <tr>  		
			<td><?php echo $i;?></td>
			<td><?php echo $row['tanggal'];?></td>
            <td><?php echo $row['agenda'];?></td>
			<td><?php echo $row['alasan_tunda'];?></td>
            <td><a href="javascript:popup_tunda('<?php echo $row['id'];?>','','','<?php echo $new_tanggal;?>','<?php echo $row['agenda'];?>','<?php echo $row['alasan_tunda'];?>')">Edit</a> 
            | <a href="javascript:del_tunda('<?php echo $row['id'];?>')">Hapus</a>
        </td>	
		</tr>
    <?php endforeach; ?>    
    </tbody>   
    </table>    