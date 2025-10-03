<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_surat extends CI_Model
{

	function jml_surat()
    {
        #$this->db->select('*');
        $ta = $this->session->userdata('user_ta');

        $sql_strict = "";
       $sql_cari = ( $this->keyword == '' )?"": " and lower(isi_ringkas) like '%".strtolower($this->keyword)."%'";

			// klo yg akses selain kasub umum, munculnya sesuai disposisi
        if ($this->session->userdata('user_jabatan_id') <> 51 && $this->session->userdata('user_jabatan_id') <> 90 )
			$sql_strict = "JOIN ( SELECT DISTINCT id FROM t_surat_masuk  WHERE REPLACE(diteruskan_ke,'0,','')='".$this->session->userdata('user_jabatan_id')."'
								UNION
								SELECT DISTINCT id_surat
								FROM t_disposisi
								WHERE id_jab_pelaksana='".$this->session->userdata('user_jabatan_id')."'
								) c  ON a.id=c.id ";

			$sql_list = "SELECT a.*,b.jml_disp FROM t_surat_masuk a
						LEFT OUTER JOIN (SELECT id_surat,COUNT(1) jml_disp
						FROM t_disposisi GROUP BY id_surat) b  ON a.id=b.id_surat
						$sql_strict
						WHERE YEAR(tgl_diterima) = '$ta'
                        $sql_cari
						ORDER BY diarsipkan ASC,id DESC";
         $query = $this->db->query($sql_list);
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->num_rows();
        }
    }

    function list_surat($perpage,$offset,$keyword)
    {
        #$this->db->select('*');
        $ta = $this->session->userdata('user_ta');

        $sql_cari = ( $keyword == '' )?"": " and lower(isi_ringkas) like '%".strtolower($keyword)."%'";
        $sql_strict = "";
			// klo yg akses selain kasub umum, munculnya sesuai disposisi
        if ($this->session->userdata('user_jabatan_id') <> 51 && $this->session->userdata('user_jabatan_id') <> 90 )
			$sql_strict = "JOIN ( SELECT DISTINCT id FROM t_surat_masuk  WHERE REPLACE(diteruskan_ke,'0,','')='".$this->session->userdata('user_jabatan_id')."'
								UNION
								SELECT DISTINCT id_surat
								FROM t_disposisi
								WHERE id_jab_pelaksana='".$this->session->userdata('user_jabatan_id')."'
								) c  ON a.id=c.id ";

			$sql_list = "SELECT a.*,b.jml_disp FROM t_surat_masuk a
						LEFT OUTER JOIN (SELECT id_surat,COUNT(1) jml_disp
						FROM t_disposisi GROUP BY id_surat) b  ON a.id=b.id_surat
						$sql_strict
						WHERE YEAR(tgl_diterima) = '$ta'
                        $sql_cari
						ORDER BY diarsipkan ASC,id DESC
                        limit $perpage offset $offset";
        #echo $sql_list;exit;
         $query = $this->db->query($sql_list);
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function get_surat($id){
        $sql_list = "SELECT a.*,b.jml_disp FROM t_surat_masuk a
						LEFT OUTER JOIN (SELECT id_surat,COUNT(1) jml_disp
						FROM t_disposisi GROUP BY id_surat) b  ON a.id=b.id_surat
						WHERE a.id = '$id'
						ORDER BY diarsipkan ASC,id DESC";
        #echo $sql_list;
        $query = $this->db->query($sql_list);
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->row();
        }
    }

    function list_pejabat_disposisi(){
        $sql_filter = "";
        $user_jabatan_id = $this->session->userdata('user_jabatan_id');
        // klo login umum tujuan ketua $user_jabatan_id == 32
        if ($user_jabatan_id == 32 )
        {
            $sql_filter = " and u.kode_jabatan = 1";
        }

        else if ($user_jabatan_id == 1 )
        {
            $sql_filter = " and u.kode_jabatan in (30,40,50)";
        }

        else if ($user_jabatan_id == 30 or $user_jabatan_id == 40 or $user_jabatan_id == 50)
        {
            $in = ($user_jabatan_id+1).",".($user_jabatan_id+2).",".($user_jabatan_id+3).",".($user_jabatan_id+4);
            $sql_filter = " and u.kode_jabatan in ($in)";
        }

        else if ($user_jabatan_id == 32) {
		//	$sql_filter = "";
		}
		else	
        {
            $in = ($user_jabatan_id+1).",".($user_jabatan_id+2).",".($user_jabatan_id+3).",".($user_jabatan_id+4);
            $sql_filter = " and 1=0";
        }


        $sql_list = "SELECT u.kode_jabatan,j.nama_jabatan
        FROM t_user u, ref_jabatan j WHERE u.kode_jabatan=j.`kode_jabatan`
        $sql_filter
        ORDER BY u.kode_jabatan ASC";
        #echo $user_jabatan_id;
         $query = $this->db->query($sql_list);
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }

    }

    function list_disposisi($id){
        	$sql = "SELECT a.*,b.`nama_jabatan` sing_jab_pelaksana,c.`nama_jabatan` sing_jab_pengirim FROM t_disposisi a
					LEFT JOIN ref_jabatan b
					ON a.`id_jab_pelaksana`= b.kode_jabatan
					LEFT JOIN ref_jabatan c
					ON a.`id_jab_pengirim`= c.kode_jabatan
					WHERE id_surat ='$id'
                    order by a.id asc";
            return $this->db->query($sql)->result();
    }

    function save_disposisi($data)
    {
        if ($this->db->insert('t_disposisi',$data) )
            return true;
        else return false;
    }

    function update($data)
    {
        $this->db->set('banner', $data['banner']);
        $this->db->set('file', $data['nama_file']);

        $this->db->where('id', $data['id']);
         if ($this->db->update('tbl_banner') )
            return true;
        else return false;
    }

    function get_banner($id){
        $this->db->where('id', $id);
        $q = $this->db->get('tbl_banner');
        return $q->row();
    }
    function delete($id){
        $this->db->delete('tbl_banner', array('id' => $id));
    }
	
}
