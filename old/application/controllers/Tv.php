<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tv extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_tv','tv');
    }

	public function index()
	{
		$data = array();
        $data['banners'] = $this->tv->list_banner();
        $data['videos'] = $this->tv->list_video();
        $data['jadwal'] = $this->tv->list_jadwal();
        $data['running_text'] = $this->tv->get_running_text();
        $data['waktu'] = "21:25 WIB";
        $data['tanggal'] = $this->tgl_indo(date('Y-m-d'),true);
        $this->load->view("v_tv",$data);
	}

    private function tgl_indo($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}


}
