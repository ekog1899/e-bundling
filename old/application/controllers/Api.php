<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model('api_model','api');
	}

	/** 
	 * method	: POST 
	 * PARAM	: user_id,password,token_device
	 * return 	: boolean
	 * */

	public function index(){
		// terima php json
		$postdata = json_decode(file_get_contents('php://input'), true);
		$kode_satker_enc = $postdata['token'];
		$idpn =  $this->encryption->decrypt(str_replace('__','/',$kode_satker_enc));
		if ( $idpn == '') {
			die('Kode Satker tidak ditemukan di server PTA');
		}

		if ( isset($postdata['data']) ){
			
			switch($postdata['type']){
				case "data_user":
					$table = "sipp_user";	
					$desc = "Data User Panitera di SIPP";				
					break;
				
				case "data_banding":
					$table = "sipp_perkara_banding";	
					$desc = "Data Perkara Banding sejak 2021";				
					break;
				case "data_sidang":
					$table = "sipp_perkara_jadwal_sidang";	
					$desc = "Data Persidangan";				
					break;	
				
				case "data_kasasi":
						$table = "sipp_perkara_kasasi";		
						$desc = "Data Kasasi sejak 2021";			
						break;	
		

			}
			
			$this->api->delete_old_data($idpn,$table);
		
			$data = array();
			$i = 0;
			foreach($postdata['data'] as $row){
				$i++;
				$data = $row ;
				$data['idpn'] = $idpn;
				$this->api->insert_new_data($idpn,$data,$table);
			}

			echo "\n\r - $desc : ".$i. " rows";

		}
		
		
	}



	

	

}

