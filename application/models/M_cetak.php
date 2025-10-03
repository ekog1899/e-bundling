<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class M_cetak extends CI_Model{
	function __construct(){
		parent::__construct(); 
	}
	function get_data($select, $table){
		$query = $this->db->get($table);
		return $query->result();
	}
	function get_all_data($table){
		$query = $this->db->get($table);
		return $query->result();
	}

	function insert_data($tableName, $data){
		$res = $this->db->insert($tableName, $data);
		return $res;
	}

	function update_data($whereconditon, $tableName, $data){
		$this->db->where($whereconditon);
		$res = $this->db->update($tableName, $data); 
		return $res;
	}
	function replace_data($tableName, $data){
		$this->db->replace($tableName, $data);
	}
	
	function delete_data($whereconditon, $tableName){
		$this->db ->where($whereconditon); 
		$res = $this->db->delete($tableName);
		return $res;
	}

	function get_data_where($whereconditon, $table){
		$this->db->where($whereconditon);
		$query = $this->db->get($table);
		return $query->result();
	}
	function serah_terima_berkas($idpn, $perkara_id){
		$sql="SELECT 
				sipp_perkara_banding.nomor_perkara_pa
				,sipp_perkara_banding.nomor_perkara_banding
				,sipp_perkara_banding.jenis_perkara_text
				,satkerlist.satkername AS nama_satker
				,t_user_pta.nama AS nama_km
				,convert_tanggal_indonesia(tim_pemeriksa_pradaftar.tgl_serah_terima_berkas) AS tanggal
				,nama_hari(tim_pemeriksa_pradaftar.tgl_serah_terima_berkas) AS hari

				FROM tim_pemeriksa_pradaftar
				LEFT JOIN sipp_perkara_banding on sipp_perkara_banding.perkara_id=tim_pemeriksa_pradaftar.perkara_id AND sipp_perkara_banding.idpn=tim_pemeriksa_pradaftar.idpn
				LEFT JOIN t_user_pta on t_user_pta.id =tim_pemeriksa_pradaftar.id_ketua_tim
				LEFT JOIN satkerlist on satkerlist.idpn =tim_pemeriksa_pradaftar.idpn
				WHERE tim_pemeriksa_pradaftar.perkara_id=$perkara_id AND tim_pemeriksa_pradaftar.idpn=$idpn ";
		$query = $this->db->query($sql); 
		return $query->result();
	}

}

