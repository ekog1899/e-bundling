<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class M_config extends CI_Model
	{
		
		// //fungsi check login
		// function get_config()
		// {
		//     $this->db->select('*');
		//     $this->db->from("sys_config");
		//      $query = $this->db->get();
		//     if ($query->num_rows() == 0) {
		//         return FALSE;
		//     } else {
		//         return $query->result();
		//     }
		// }
		
		
		function save($data)
		{
			$this->db->set($data);
			if ($this->db->update('sys_config') )
            return true;
			else return false;
		}
		
		function list_user_pta(){
			$sql_list = "SELECT a.*,nama_jabatan jabatan FROM t_user_pta a left outer join ref_jabatan b on a.kode_jabatan=b.kode_jabatan order by kode_jabatan asc";
			$query = $this->db->query($sql_list);
			if ($query->num_rows() == 0) {
				return FALSE;
				} else {
				return $query->result();
			}
			
		}
		
		function list_jabatan(){
			$sql_list = "SELECT * from ref_jabatan order by kode_jabatan asc";
			$query = $this->db->query($sql_list);
			if ($query->num_rows() == 0) {
				return FALSE;
				} else {
				return $query->result();
			}
			
		}
		
		function list_pejabat(){
            $this->db->where('kode_jabatan in (1,30,40,50,60,70,80)');
			return $this->db->get("t_user_pta")->result();
		}
		
		
		function get_user_pta($id){
			$this->db->select('*');
			$this->db->from("t_user_pta");
			$this->db->join('ref_jabatan', 't_user_pta.kode_jabatan = ref_jabatan.kode_jabatan', 'left');
			$this->db->where("id",$id);
			$query = $this->db->get();
			
			if ($query->num_rows() == 0) {
				return FALSE;
				} else {
				return $query->row();
			}
			
		}
		
		function get_profile($id){
			$this->db->select('*');
			$this->db->from("t_user");
			$this->db->join('ref_jabatan', 't_user.kode_jabatan = ref_jabatan.kode_jabatan', 'left');
			$this->db->where("id",$id);
			$query = $this->db->get();
			if ($query->num_rows() == 0) {
				return FALSE;
				} else {
				return $query->row();
			}
			
		}
		
		function delete($id){
			$this->db->delete('tbl_banner', array('id' => $id));
		}
		
		function wilayah_list(){
			$this->db->select('id_satker_sipp,nama_satker');
			$this->db->distinct();
			$this->db->where('id_parent_sipp',null);
			$this->db->where('tingkat_satker','PTA');
			$this->db->order_by('urutan','ASC');
			$query = $this->db->get('master_satker');
			return $query->result_array();
		}
		
		function reload_satker($id_satker_pta){
			$this->db->query('truncate table satkerlist');
			$sql = "insert into satkerlist SELECT id_satker_sipp,nama_satker FROM master_satker WHERE id_parent_sipp='".$id_satker_pta."'";
			$this->db->query($sql);
		}
		
		function get_satker_name($id_satker_sipp){
			$this->db->select('nama_satker');
			$this->db->distinct();
			$this->db->where('id_satker_sipp',$id_satker_sipp);
			return $this->db->get('master_satker')->row()->nama_satker;
		}
		
		
		function save_config($param,$value){
			$this->db->where('param',$param);
			$this->db->delete('sys_config');
			$data['param'] = $param;
			$data['value'] = $value;
			$this->db->insert('sys_config',$data);
		}
		
		function get_config($param){
			$this->db->where('param',$param);
			$query = $this->db->get('sys_config');
			$value = ( isset( $query->row()->value ) ? $query->row()->value:"" );
			return $value;
		}
	}
