<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal extends CI_Model
{

	//fungsi check login
    function list_kegiatan()
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

    function add($data)
    {
        if ($this->db->insert('tbl_jadwal',$data) )
            return true;
        else return false;
    }

    function update($data)
    {
        $this->db->set('kegiatan', $data['kegiatan']);
        $this->db->set('dihadiri', $data['dihadiri']);
        $this->db->set('lokasi', $data['lokasi']);
        $this->db->set('waktu', $data['waktu']);
        $this->db->where('id', $data['id']);
         if ($this->db->update('tbl_jadwal') )
            return true;
        else return false;
    }

    function get_kegiatan($id){
        $this->db->where('id', $id);
        $q = $this->db->get('tbl_jadwal');
        return $q->row();
    }
    function delete($id){
        $this->db->delete('tbl_jadwal', array('id' => $id));
    }
}
