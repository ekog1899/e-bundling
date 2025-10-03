<?php
class Api_model extends CI_Model {


	function insert_db($data){
			#$data['user_id'] = $user_id;
			$data['diinput'] = date('Y-m-d H:i:s');
			//$this->db->insert("t_notifikasi",$data);
	}

	function get_satkersing($kode_satker){
		$this->db->select('satsing');
		$this->db->where('kode2',$kode_satker);
		return $this->db->get('satkerlist')->row()->satsing;
	}

	function delete_old_data($idpn,$table){
		$this->db->where('idpn',$idpn);
		$this->db->delete($table);
	}

	function insert_new_data($idpn,$data,$table){
		$this->db->insert($table,$data);
	#	echo $this->db->last_query();
	
	}


	function delete_sidang($satkersing){
		$this->db->where('idpn',$idpn);
		$this->db->delete('sipp_perkara_jadwal_sidang');
	}

	function add_sidang($idpn,$data){
		$this->db->insert('sipp_perkara_jadwal_sidang',$data);
		echo $this->db->last_query();
	
	}
	

}
?>
