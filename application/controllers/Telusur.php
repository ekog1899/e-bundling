<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telusur extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();
		$this->load->model('banding_model','banding');
	}


	public function index(){
		$data['satker_list'] = $this->banding->satkerlist();
        $this->load->view("v_telusur",$data);
	}


	

	

}
