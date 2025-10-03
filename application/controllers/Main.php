<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_akses','admin');
		$this->load->model('banding_model','banding');
		$this->banding->init();
		$this->banding->set_satker($this->session->userdata('idpn'));
    }

	public function index()
	{
		# print_r($this->session->userdata());exit;
		if(!$this->admin->is_login())
		{
		redirect("login");
		}
		
		// klo op_satker
		if ( $this->session->userdata('role') == 'op_satker') {
			$this->banding->set_satker($this->session->userdata('idpn'));
			$this->_dash_satker();
		}
		else{
			$this->_dash_pta();
			// if ( $this->session->userdata('is_verifikator') == 1 )
			// 	$this->_dash_pta();
			// else
			// 	redirect('monitoring/mon_02');	
		}
		     
	}

	private function _dash_pta(){
		$data['content'] = "Welcome";
		$data['title'] = "DASHBOARD";
		$data['profile'] = $this->session->userdata();
		$data_content['banding_blm_daftar'] = $this->banding->belum_berjalan();
		$data_content['banding_stat'] = $this->banding->stat_pta();
		$data_content['piechat_data'] = $this->banding->get_piechart_data();
		$data_content['linechat_data'] = $this->banding->get_linechart_data();	
		$data['content'] = $this->load->view("dashboard_pta",$data_content,true);
		$this->load->view("template",$data);
	}

	private function _dash_satker(){
			$data['content'] = "Welcome";
			$data['title'] = "DASHBOARD";
			$data['profile'] = $this->session->userdata();
			$data_content['banding_blm_kirim'] = $this->banding->belum_kirim();
			$data_content['banding_stat'] = $this->banding->stat_satker();
			$data_content['piechat_data'] = $this->banding->get_piechart_data();
			$data_content['linechat_data'] = $this->banding->get_linechart_data();		
			$data['content'] = $this->load->view("dashboard_pa",$data_content,true);
			$this->load->view("template",$data);
	}

	public function berjalan(){
		$data['content'] = "Welcome";
		$data['title'] = "Perkara Banding Masih Berjalan";
		$data['profile'] = $this->session->userdata();
		$data_content['banding_blm_kirim'] = $this->banding->berjalan_pa();		
		$data['content'] = $this->load->view("list_perkara",$data_content,true);
		$this->load->view("template",$data);
	}

	// use untuk banding kasasi per satker
    public function kasasi_satker($tahun=null,$type=null,$satker=null){
        $satker = ($satker == '') ? $this->session->userdata('idpn'):  $satker;
        $tahun = ($tahun == '' ) ? date('Y') : $tahun;
		if( $type == '' ){
			$data_content['data_mon'] = $this->banding->banding_kasasi_satker_tahun($tahun, $satker);
			$data['title'] = "Monitoring Banding yang melakukan Kasasi ".$this->session->userdata('satker');
			$data_content['tahun'] =  $tahun;
			$data['content'] = $this->load->view("v_mon01_pa",$data_content,true);
		}else if ( $type == 'banding' ){
            $data_content['data_mon'] = $this->banding->list_banding($satker,$tahun);
            $data['title'] = "Monitoring Banding ".$this->session->userdata('satker');
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil_pa",$data_content,true); 
        } 
        else if ( $type == 'kasasi_skrg' ){
            $data_content['data_mon'] = $this->banding->list_kasasi_skrg($satker,$tahun);
            $data['title'] = "Monitoring Banding yang Kasasi di tahun berjalan ".$this->session->userdata('satker');
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil_pa",$data_content,true); 
        } 
        else if ( $type == 'kasasi_depan' ){
            $data_content['data_mon'] = $this->banding->list_kasasi_depan($satker,$tahun);
            $data['title'] = "Monitoring Banding yang Kasasi di tahun berikutnya ".$this->session->userdata('satker');
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil_pa",$data_content,true); 
        } 
		$this->load->view("template",$data);
    }

		

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	public function get_hari_ini(){
		return $this->format_hari_tanggal(date('Y-m-d'));
	}
	
	function format_hari_tanggal($waktu)
	{
		$hari_array = array(
			'Minggu',
			'Senin',
			'Selasa',
			'Rabu',
			'Kamis',
			'Jumat',
			'Sabtu'
		);
		$hr = date('w', strtotime($waktu));
		$hari = $hari_array[$hr];
		$tanggal = date('j', strtotime($waktu));
		$bulan_array = array(
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember',
		);
		$bl = date('n', strtotime($waktu));
		$bulan = $bulan_array[$bl];
		$tahun = date('Y', strtotime($waktu));
		$jam = date( 'H:i:s', strtotime($waktu));
		
		//untuk menampilkan hari, tanggal bulan tahun jam
		//return "$hari, $tanggal $bulan $tahun $jam";

		//untuk menampilkan hari, tanggal bulan tahun
		return "$hari, $tanggal $bulan $tahun";
	}

}
