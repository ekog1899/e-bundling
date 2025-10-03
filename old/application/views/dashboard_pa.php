<!-- Page Heading -->
<div class="row">

<div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-success shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
						  Terdaftar di TK. 1</div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['terima']); ?></div>
				  </div>
				  <div class="col-auto">
					  <i class="fas fa-calendar fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
				termasuk yang belum dikirim ke PTA 
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
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo ($banding_stat[0]['terima_pta']); ?></div>
					  
				  </div>
				  <div class="col-auto">
					  <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
			termasuk yang dimohonkan akhir tahun lalu
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
		  	termasuk yang dikirimkan tahun lalu
		   </div>
	  </div>
  </div>


  <div class="col-xl-3 col-md-6 mb-4">
	  <div class="card bg-danger shadow h-100">
		  <div class="card-body">
			  <div class="row no-gutters align-items-center">
				  <div class="col mr-2">
					  <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
						  berjalan di Tk. Banding </div>
					  <div class="h5 mb-0 font-weight-bold text-white"><?php echo number_format($banding_stat[0]['berjalan']); ?></div>
				  </div>
				  <div class="col-auto">
					  
					  <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
				  </div>
			  </div>
		  </div>

		  <div class="card-footer d-flex align-items-center justify-content-between small text-white">
				terdaftar di Tk. banding dan belum putus
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
								<span>Sumber Data : perkara di SIPP yang sudah mendapatkan nomor perkara Banding</span></div>
                                   
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
			<h6 class="m-0 font-weight-bold text-primary">BANDING BELUM KIRIM</h6>
		</div>
		<div class="card-body">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="2%">Nomor</th>
					<th>Nomor Perkara</th>
					<th>Majelis</th>
					
					<th width="10%">Tgl Putusan</th>
					<th width="20%">Pemohon Banding</th>
					<th width="10%">Tgl Permohonan Banding</th>
					<th width="10%">Aksi</th>
				</tr>
			</thead>

			<tbody>
				<?php
				$no = 0;
				if (empty($banding_blm_kirim)) {
					echo "<tr><td colspan='5'  style='text-align: center; font-weight: bold'>--Data tidak ditemukan--</td></tr>";
				} else {
					foreach ($banding_blm_kirim as $b) {
					$no++;
					$perkara_id_enc = str_replace('/','__',$this->encryption->encrypt($b['perkara_id']));
				
					?>
					<td><?php echo $no;?></td>
					<td><?php echo $b['nomor_perkara_pn'];?><br />
					<?php
			if ( $b['perbaikan'] > 0 ) {
				echo "<span class='badge badge-danger'>".$b['perbaikan']." Edoc Menunggu Perbaikan</span>";
			} ?></td>

					<td><?php echo $b['majelis_hakim_text'];?>	
					</td>
				
					<td><?php echo $b['putusan_pn'];?></td>
					<td><?php echo $b['pemohon_banding'];?></td>
					<td><?php echo $b['permohonan_banding'];?></td>
					<td><a class="btn btn-success noprint" href="<?php echo base_url('edoc/upload/'.$perkara_id_enc);?>">edoc</a> </td>
				<tr>
					
					
					
				</tr>
				<?php
					}
				}
				?>
			</tbody>
			
		</table>
			
		<span class=""><ul>
			<li><em>Data diatas adalah data Banding yang belum diinput tanggal Pengirimannya di SIPP. Jika sudah dikirim fisiknya dan diinput tanggal pengiriman, maka akan hilang dari daftar</em></li>
			<li><em>Update bersifat harian. Jika ada perubahan data di SIPP, mohon segera sinkron MANUAL ke PTA Medan dan tunggu 1 jam utk perubahan data</em></li>
			<ul>
		</span>

		</div>
		</div>
	</div>	
</div>
		
<div class="clearfix">


<?php echo $this->session->flashdata("info");?>






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
