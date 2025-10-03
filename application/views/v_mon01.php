<div class="clearfix">
<div class="row">
  <div class="col-lg-12">

	

  </div>
</div>

<?php echo $this->session->flashdata("info");?>
<?php

?>


<div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Tahun</label>
    <div class="col-sm-10">
	<form method="POST" action="<?php echo site_url('monitoring/mon_01');?>">
	<select id="switch_tahun" class="form-control col-md-2">
        <?php
		$skrg = date('Y');
		for($i = $skrg ; $i>=($skrg-2); $i--){
			echo '<option '.( $i == $tahun ? 'selected':'') .' value='.$i.'>'.$i.'</option>';
		}
		?>
      </select>
    </div>
	</form>
  </div>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th rowspan=2 width="2%">No.</th>
			<th rowspan=2>Satuan Kerja</th>	
            <th colspan=3>TAHUN BANDING <?php echo $tahun; ?></th>	
        </tr>  
        <tr>  		
			<th width="15%">Banding</th>
			<th width="15%">Kasasi Tahun Berjalan</th>
			<th width="15%">Kasasi Tahun Berikutnya</th>
	
		</tr>
	</thead>

	<tbody>
		<?php
        $no = 0;
		$j_banding1 = 0;
		$j_kasasi1 = 0;
		$j_banding2 = 0;
		$j_kasasi2 = 0;
		if (empty($data_mon)) {
			echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
		} else {
			foreach ($data_mon as $b) {
			$no++;
			$j_banding1+= $b['banding'];
			$j_kasasi1+= $b['kasasi_skrg'];
			$j_banding2+= $b['kasasi_depan'];
			
			
			?>
			<td><?php echo $no;?></td>
			<td><?php echo $b['satnama'];?></td>
			<td><a href='<?php echo site_url('monitoring/mon_01/'.$tahun.'/banding/'.$b['satsing']);?>'><?php echo $b['banding'];?></a></td>
			<td><a href='<?php echo site_url('monitoring/mon_01/'.$tahun.'/kasasi_skrg/'.$b['satsing']);?>'><?php echo $b['kasasi_skrg'];?></a></td>
			<td><a href='<?php echo site_url('monitoring/mon_01/'.$tahun.'/kasasi_depan/'.$b['satsing']);?>'><?php echo $b['kasasi_depan'];?></a></td>
			
			<tr>
			
			
			
		</tr>
		<?php
			}

			?>
			<td colspan=2>JUMLAH </td>
			<td><?php echo $j_banding1;?></td>
			<td><?php echo $j_kasasi1;?></td>
			<td><?php echo $j_banding2;?></td>
			<td><?php echo $j_banding1+$j_kasasi1+$j_banding2;?></td>
			
			
			<tr> 
		<?php }
		?>
	</tbody>
	
</table>



</div>
