<?php
class Profil_model extends CI_Model {

	function check_satker($satkersing){
		$this->db->select('satnama');
		$this->db->where('satsing',$satkersing);
		return $this->db->get('satker');
	}
	

	

	
}
?>
