<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_video extends CI_Model
{

	//fungsi check login
    function list_video()
    {
        $this->db->select('*');
        $this->db->from("tbl_video");
         $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function add($data)
    {
        if ($this->db->insert('tbl_video',$data) )
            return true;
        else return false;
    }

    function update($data)
    {
        $this->db->set('banner', $data['banner']);
        $this->db->set('file', $data['nama_file']);

        $this->db->where('id', $data['id']);
         if ($this->db->update('tbl_video') )
            return true;
        else return false;
    }

    function get_banner($id){
        $this->db->where('id', $id);
        $q = $this->db->get('tbl_video');
        return $q->row();
    }
    function delete($id){
        $this->db->delete('tbl_video', array('id' => $id));
    }
}
