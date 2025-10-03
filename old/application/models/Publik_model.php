<?php
class Publik_model extends CI_Model {

	function get_info_perkara($satkersing,$perkara_id){
		$sql = "SELECT satkersing,jenis_edoc,nomor_perkara_pn,pemohon_banding,nomor_perkara_banding,status_putusan_banding_text,putusan_banding,amar_putusan_banding,b.file_edoc 
		FROM $satkersing.perkara_banding a, dok_perkara_banding b 
		WHERE a.perkara_id='$perkara_id'
		AND b.perkara_id='$perkara_id'
		AND satkersing='$satkersing'
		AND jenis_edoc='tt_daftar_banding'";
		#echo $sql;		
		$q = $this->db->query($sql);
		return $q->result_array();	
	}
	
	function check_satker($satkersing){
		$this->db->select('satnama');
		$this->db->where('satsing',$satkersing);
		return $this->db->get('satker');
	}
	

	

	
}
?>
