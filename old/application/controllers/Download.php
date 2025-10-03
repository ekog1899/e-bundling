<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();
		$this->load->helper('my');
		//$this->load->model('Publik_model','publik');
	}


	public function index($satkersing,$perkara_id){
		
		
	
	}

	public function generate_zip($satkersing,$perkara_id){
		$this->load->library('zip');
		
		$perkara_id_enc = $perkara_id;
		$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
		
		$sql = "select org_name,file_edoc,bundel from dok_perkara_banding a,ref_jenis_edoc b 
		where a.jenis_edoc=b.jenis_edoc 
		and bundel in ('a','b')
		and satkersing='$satkersing' 
		and perkara_id='$perkara_id' 
		and file_edoc is not null
		order by bundel asc, urut asc,b.id ASC
			";
		$files = $this->db->query($sql);
		$urut=0;
		 foreach ($files->result_array() as $row){
			$urut++;
			echo $row['file_edoc']."<br />";
				$file_path = "./uploads/edoc/".$row['file_edoc'];
		

				if (file_exists($file_path)){
					$this->zip->read_file($file_path,"bundel_".$row['bundel']."/".$urut."_".$row['file_edoc']);
				}
		}


		$sql = "select tanggal_sidang,org_name,file_edoc from dok_perkara_banding_sidang where satkersing='$satkersing' and perkara_id='$perkara_id'  and file_edoc is not null order by tanggal_sidang asc";
		echo $sql ;
		$files = $this->db->query($sql);
		$urut=0;
		
		 foreach ($files->result_array() as $row){
			$urut++;
			echo $row['file_edoc']."<br />";
				$file_path = "./uploads/edoc/".$row['file_edoc'];
				$data = $row['org_name'];
				
				if (file_exists($file_path)){
					$this->zip->read_file($file_path,"relaas_bas/".$row['tanggal_sidang']."/".$row['file_edoc']);
				}
		}

		
		$this->zip->download($satkersing.$perkara_id.'.zip');
	}
	
		

	





	

	

}
