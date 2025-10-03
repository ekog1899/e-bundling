<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();
		$this->load->helper('my');
		$this->load->model('Publik_model','publik');
	}


	public function index($short_param=null){
		
		// parse token
		// ski65991x
		$short_param_test = encode_url("pakis","12724");
		
		#echo $short_param_test;
		$parse = (decode_url($short_param));
		$satkersing = $parse['satkersing'];
		$perkara_id = $parse['perkara_id'];
		// check dulu satkersing nya
		#print_r($this->publik->check_satker($satkersing));
		if ($this->publik->check_satker($satkersing)->result_id->num_rows == 0)
		{ 
			 exit('Data Tidak ditemukan'); 
		}
		#echo $perkara_id;

		$satnama = $this->publik->check_satker($satkersing)->row()->satnama;
		$data['info'] = $this->publik->get_info_perkara($satkersing,$perkara_id)[0];
		// $parse = decode_short_url($short_param);
		//$data['satker_list'] = $this->banding->satkerlist();
        $this->load->view("v_publik",$data);
	}





	

	

}
