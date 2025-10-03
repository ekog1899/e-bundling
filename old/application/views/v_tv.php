<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <head>
	<title> TV Media BKKN Sumut</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
	<style type="text/css">
	@font-face {
	  font-family: 'Cabin';
	  font-style: normal;
	  font-weight: 400;
	  src: local('Cabin Regular'), local('Cabin-Regular'), url(http://sipp-pool.pta-medan.go.id:8081/aset/font/satu.woff) format('woff');
	}
	@font-face {
	  font-family: 'Cabin';
	  font-style: normal;
	  font-weight: 700;
	  src: local('Cabin Bold'), local('Cabin-Bold'), url(http://sipp-pool.pta-medan.go.id:8081/aset/font/dua.woff) format('woff');
	}
	@font-face {
	  font-family: 'Lobster';
	  font-style: normal;
	  font-weight: 400;
	  src: local('Lobster'), url(http://sipp-pool.pta-medan.go.id:8081/aset/font/tiga.woff) format('woff');
	}

	</style>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="assets/css/ionicons.min.css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tv.css" media="screen">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminlte.min.css" media="screen">
    <link href="<?php echo base_url(); ?>assets/css/video-js.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/skitter.css" type="text/css" media="all" rel="stylesheet" />

	</head>



  <body id="tvDisplay">
      <div class="container" style="width:100%">
     <div id="content">
        <div class="row">
         <div class="col-md-4 col-lg-4">
             <div class="small-box">
                 <div class="bg-box1 shadow-box">
                 <div class="inner">
                    <div class="identitas">
                    <img src="<?php echo base_url('assets/img/logo.png'); ?>">
                        </div>

                  <div class="bannerSamping">
                     <!-- <img src="/tvbkkbn2/banners/1575474937.jpeg"> -->

                       <div class="skitter-large-box">
                        <div class="skitter skitter-large">
                          <ul>
                            <?php
                              foreach ( $banners as $banner) : ?>
                              <li>
                                <a href="#cube"><img src="<?php echo base_url('banners/'.$banner->file);?>" class="circles" /></a>
                                </li>
                              <?php
                              endforeach;
                              ?>
                          </ul>
                        </div>
                      </div>
                    </div>

                  </div>
             </div>
            </div>

         </div>
         <div class="col-xs-8">
            <div id="tvBox">
             <!-- <iframe width="100%" height="500px" src="https://www.youtube.com/embed/DXHcexz1RF8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            -->
                <video
                    id="my-video"
                    class="video-js"
                    controls
                    preload="auto"
                    width="878"
                    poster="MY_VIDEO_POSTER.jpg"
                    data-setup="{}"
                  >

                  </video>

             </div>
             <br />


             </div>
        </div>

        <div class="row">
                <div class="col-md-4 col-lg-4">
				   <div class="small-box bg-red inner">
                       <div class="bg-box2 shadow-box waktu" onclick="toggleFullScreen()">
                           <h3 class="jam"></h3>
                           <h4 class="tanggal"><?php echo $tanggal;?></h4>
                        </div>
                    </div>
			     </div>
            <div class="jadwal col-md-8  col-lg-8 quotes">
                     <div class="inner small-box">
                         <div class="header">
								<span class="col-md-2">Waktu</span>
								<span class="col-md-4">Kegiatan</span>
								<span class="col-md-3">Dihadiri</span>
								<span class="col-md-3">Lokasi</span>
				          </div>

                        <div class="inner">
                                 <ul  class="nTicker nTickerJadwal">
                                <?php
                                foreach ($jadwal as $row) :

                                ?>
                                    <li class="row">
                                        <span class="col-md-2"><?php echo date('d-m-Y',strtotime($row->waktu));?></span>
                                        <span class="col-md-4"><?php echo $row->kegiatan;?></span>
                                        <span class="col-md-3"><?php echo $row->dihadiri;?></span>
                                        <span class="col-md-3"><?php echo $row->lokasi;?></span>
                                    </li>
                                <?php endforeach; ?>


                                 </ul>
                         </div>

				    </div>
                 </div>
         </div>

      </div>

           <div id="footer" class="">
             <div class="runningText">
			<marquee scrolldelay="120">
			<?php echo $running_text[0]->running_text;?>
			</marquee>
			</div>
         </div>


	<div class="loading"></div>
     </div>

 <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.newsTicker.min.js"></script>
       <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="<?php echo base_url(); ?>assets/js/video.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/videojs-playlist.min.js"></script>


    <script src="<?php echo base_url(); ?>assets/js/jquery.easing.1.3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.skitter.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/tv.js"></script>
   <script type="text/javascript">

       $(document).ready(function(){

            $('.nTicker').newsTicker({
                row_height: 20,
                max_rows: 5,
                duration: 4000
                });

       })

           options = {
              autoplay: 'play',
              muted:true
            }
            var player = videojs('my-video', options);

            player.playlist([
            <?php
            foreach ($videos as $video):
            ?>
            {
              sources: [
              {
                src: '<?php echo base_url('videos/'.$video->file);?>',
                type: 'video/mp4'
              }]
            },
            <?php
            endforeach;
            ?>
            ]);



       player.playlist.autoadvance(1);
    </script>
</body>
    </html>
