<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tv extends CI_Model
{

	//fungsi check login
    function list_video()
    {
        $this->db->select('*');
        $this->db->from("tbl_video");
        $this->db->order_by('id','desc');
         $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function list_banner()
    {
        $this->db->select('*');
        $this->db->from("tbl_banner");
        $this->db->order_by('id','desc');
         $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function list_jadwal()
    {
        $this->db->select('*');
        $this->db->from("tbl_jadwal");
         $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function get_running_text()
    {
        $this->db->select('*');
        $this->db->from("sys_config");
         $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

}
