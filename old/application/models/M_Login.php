<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Login extends CI_Model
{
	//fungsi cek session
    function logged_id()
    {
        return ($this->session->userdata('user_id')=='' ? FALSE:TRUE);
    }
	public function squrity(){
			$userName = $this->session->userdata('idpn');
			if(empty($userName)){
				$this->session->sess_destroy();
				redirect('login');
			}
		}
	public function get_config(){
			$q=$this->db->query("SELECT * FROM sys_config");
			$data=array();
			foreach ($q->result_array() as $d){
				$kd = $d['param'];
				$data[$kd] = $d['value'];
			}
			return $data;
		}
		public function sys_config(){
			
			$q=$this->db->query("SELECT param, value FROM sys_config");
			$data=array();
			foreach ($q->result_array() as $d){
				
					$data['id_satker_pta']=$d['value'];
					$data['prop_id']=$d['value'];
					$data['pta_name']=$d['value'];
					$data['nama_kpta']=$d['value'];
					$data['sys_version']=$d['value'];
				
			}
			return $data;
		}
	//fungsi check login
    function check_login($table, $field1, $field2)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($field1);
        $this->db->where($field2);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }


    function get_user_profile_by_id($uid){

		$this->db->where('id',$uid);
		return $this->db->get('t_user');

	}

	function get_user_profile_by_id_jabatan($uid){

		$this->db->where('kode_jabatan',$uid);
		return $this->db->get('t_user');

	}

    public function satkerlist(){
		$this->db->select('satker_code,satker_name');
		$data = $this->db->get('satkerlist');
		return $data->result_array();
	}
}
