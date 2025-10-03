

<!-- Page Heading -->
<div class="row">

  <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-success shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
						  Banding di Tk. I</div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['terima_pa']); ?></div>
				  </div>
				  <div class="col-auto">
					  <i class="fas fa-calendar fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
				termasuk  yang belum dikirim ke PTA 
			</div>
	  </div>
  </div>

  <!-- Earnings (Monthly) Card Example -->
  <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-primary shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1"">
						Terdaftar di Tk. Banding</div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['terima_pta_1'] + $banding_stat[0]['terima_pta_2'] ); ?></div>
					  
				  </div>
				  <div class="col-auto">
					  <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
			termasuk didaftar tahun lalu di Tk. 1
		  </div>

		  	

	  </div>
  </div>



  <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-info shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
					  Putus</div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['putus']); ?></div>
				  </div>
				  <div class="col-auto">
				  <i class="fas fa-check-circle fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
				termasuk banding tahun lalu
			</div>
	  </div>
  </div>


  <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-danger shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
						  Kasasi </div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['kasasi_terima_pa']); ?></div>
				  </div>
				  <div class="col-auto">
					  
					  <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
				kasasi yang terdaftar di Tk. 1
			</div>
	  </div>
  </div>
</div>

<div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                         Statistik Perkara Banding dan Kasasi
                                    </div>
                                    <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div>
									<div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
									<canvas id="myLineChart" width="769" height="384" class="chartjs-render-monitor" style="display: block; width: 769px; height: 384px;"></canvas>
								<span>Sumber Data : SIPP Satker sehingga perkara banding/kasasi harus terupdate di SIPP satker</span></div>
                                   
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                       Status Putusan Banding Tahun ini
                                    </div>
                                    <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
									<canvas id="myPieChart" width="769" height="384" class="chartjs-render-monitor" style="display: block; width: 769px; height: 384px;"></canvas>
									<span>Sumber Data : SIPP Satker</span>
								</div>
                                   
                                </div>
                            </div>
                        </div>


<div class="row">
	<div class="col-md-12 col-xl-12">
		<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">BELUM TERDAFTAR</h6>
		</div>
		<div class="card-body">
<table class="table table-hover dataTable stripe">
	<thead>
		<tr>
			<th width="2%">Nomor</th>
			<th width="100">Nomor Perkara</th>
			
			<th width="10%">Tgl Putusan</th>
			<th width="">Pemohon Banding</th>
			<th width="10%">Tgl Permohonan Banding</th>
			<th width="20%">Status Perkara Banding</th>
			<th width="50">Aksi</th>
		</tr>
	</thead>

	<tbody>
		<?php
        $no = 0;
		if (empty($banding_blm_daftar)) {
			echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
		} else {
			foreach ($banding_blm_daftar as $b) {
			$no++;
			$perkara_id_enc = str_replace('/','__',$this->encryption->encrypt($b['perkara_id']));
         
			?>
			<tr>
			<td><?php echo $no;?></td>
			<td><?php echo $b['nomor_perkara_pa'];?>
			<?php
			if ( $b['blm_validasi'] > 0 ) {
				echo "<span class='badge badge-danger'>".$b['blm_validasi']." Edoc Menunggu Validasi</span>";
			} ?>

		</td>
			<td><?php echo $b['tgl_putusan_pa'];?></td>
			<td><?php echo $b['pemohon_banding'];?></td>
			<td><?php echo $b['permohonan_banding'];?>
			<?php 
			if ( $b['jml_hari'] > 20 and $b['status_perkara_banding']== 'Permohonan Banding' ){
				echo "<span class='badge badge-danger'>".$b['jml_hari']." hari belum dikirim</span>";
			}
			?>
			</td>
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
		</div>
	</div>	
</div>

<?php 
#print_r($piechat_data);
foreach($piechat_data as $r){
	$pieLabel[]=$r['label'];
	$pieData[]=$r['jml'];
}

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
	Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels:<?php echo json_encode($pieLabel);?>,
    datasets: [{
      data: <?php echo json_encode($pieData);?>,
      backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#58a745'],
    }],
  },
});


var xValues = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sept","Okt","Nov","Des"];

new Chart("myLineChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      data: [<?php echo $linechat_data[0]['jan'];?>,<?php echo $linechat_data[0]['feb'];?>,<?php echo $linechat_data[0]['mar'];?>,<?php echo $linechat_data[0]['apr'];?>,
	  		<?php echo $linechat_data[0]['mei'];?>,<?php echo $linechat_data[0]['jun'];?>,<?php echo $linechat_data[0]['jul'];?>,<?php echo $linechat_data[0]['ags'];?>,
			  <?php echo $linechat_data[0]['sep'];?>,<?php echo $linechat_data[0]['okt'];?>,<?php echo $linechat_data[0]['nop'];?>,<?php echo $linechat_data[0]['des'];?>],
      borderColor: "blue",
	  label:"Terima",
      fill: true
    },{
	 data: [<?php echo $linechat_data[1]['jan'];?>,<?php echo $linechat_data[1]['feb'];?>,<?php echo $linechat_data[1]['mar'];?>,<?php echo $linechat_data[1]['apr'];?>,
	  		<?php echo $linechat_data[1]['mei'];?>,<?php echo $linechat_data[1]['jun'];?>,<?php echo $linechat_data[1]['jul'];?>,<?php echo $linechat_data[1]['ags'];?>,
			  <?php echo $linechat_data[1]['sep'];?>,<?php echo $linechat_data[1]['okt'];?>,<?php echo $linechat_data[1]['nop'];?>,<?php echo $linechat_data[1]['des'];?>],
     borderColor: "green",
	  label:"Putus",
      fill: false
    },{
	data: [<?php echo $linechat_data[2]['jan'];?>,<?php echo $linechat_data[2]['feb'];?>,<?php echo $linechat_data[2]['mar'];?>,<?php echo $linechat_data[2]['apr'];?>,
	  		<?php echo $linechat_data[2]['mei'];?>,<?php echo $linechat_data[2]['jun'];?>,<?php echo $linechat_data[2]['jul'];?>,<?php echo $linechat_data[2]['ags'];?>,
			  <?php echo $linechat_data[2]['sep'];?>,<?php echo $linechat_data[2]['okt'];?>,<?php echo $linechat_data[2]['nop'];?>,<?php echo $linechat_data[2]['des'];?>],
     borderColor: "red",
	  label:"Kasasi",
      fill: false
    }]
  },
  options: {
    legend: {display: true}
  }
});
</script>