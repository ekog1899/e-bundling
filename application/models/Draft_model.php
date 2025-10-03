<?php
class Draft_model extends CI_Model {


	function list_template(){
			return $this->db->get("ref_draft_template")->result_array();
	}

    function list_pejabat(){
            $this->db->where('kode_jabatan in (1,30)');
			return $this->db->get("t_user")->result_array();
	}

	function list_draft($perpage=null,$offset=null,$keyword=null){

            $sql_limit = "";
            $my_uid = $this->session->userdata('user_id');
            if ( $perpage != null ){
                $sql_limit = "limit $perpage offset $offset";
            }
            $sql = "SELECT a.*,b.id_rev,c.status,k.nama nama_korektor, p.nama nama_pejabat FROM t_draft a
LEFT OUTER JOIN t_user k ON (a.`id_korektor`=k.`id`)
LEFT OUTER JOIN t_user p ON (a.`id_pejabat`=p.`id`),
 (SELECT id_draft,MAX(id) id_rev
                FROM t_draft_revisi GROUP BY id_draft) b, t_draft_revisi c
                WHERE a.id=b.id_draft
                AND b.id_rev=c.id
                and (a.`id_korektor`=$my_uid or a.`id_konseptor`=$my_uid or a.`id_pejabat`=$my_uid)
                ORDER BY id DESC
                $sql_limit";
         #   echo $sql;
			return $this->db->query($sql)->result();
	}



    function list_history($id_draft){
            $sql = "SELECT dn.id_rev,
            CASE
            WHEN id_user=id_korektor THEN 'korektor'
            WHEN id_user=id_pejabat THEN 'pejabat'
            END pemberi_catatan, GROUP_CONCAT(CONCAT(koreksi,' --> ', catatan) SEPARATOR '###') catatan
            FROM t_draft d, t_draft_revisi dr,t_draft_notes dn WHERE d.id=dr.`id_draft`
            AND dr.`id`= dn.`id_rev`
            AND STATUS IN (12,22) AND id_draft=$id_draft
            GROUP BY id_rev
            ORDER BY id_rev  DESC";
			return $this->db->query($sql)->result();
	}

	function get_html_template_by_id($id){
		$this->db->where("id",$id);
		return $this->db->get('ref_draft_template')->row()->isi;
	}

	function get_rev_by_id($id) {
		$this->db->where("id",$id);
		return $this->db->get('t_draft_revisi')->row();
	}


	function get_draft_by_id($id) {
		$this->db->where("id",$id);
		return $this->db->get('t_draft')->row();
	}

	function get_profile_by_id($id) {
		$sql = "SELECT a.*,b.`nama_jabatan` FROM t_user a LEFT OUTER JOIN ref_jabatan b ON a.`kode_jabatan`=b.`kode_jabatan`
        WHERE id=$id";
		return $this->db->query($sql)->row();
	}



	function mark_push($id){
		return $this->db->query("update t_notifikasi set dikirim=now() where not_id=$id");
	}

	function mark_read($current_uri){
		$this->db->set(array('dilihat'=>date('Y-m-d h:i:s')));
		$this->db->where(
				array(
				'url'=>$current_uri,
				'user_id'=>$this->session->userdata('admin_id')
				)
			)	;
		$this->db->update("t_notifikasi");
	}


	function del($idp){

			$this->db->where('idp',$idp);
			$this->db->delete("t_program",$data);

	}

	function close($idp){
			$this->db->set(array('tgl_selesai'=>date('Y-m-d h:i:s')));
			$this->db->where('idp',$idp);
			$this->db->update("t_program",$data);

	}

	/****** Program Comment **************/
	function add_comment($data){
		if ( $data['idp'] != '' )
		{
		//	echo "Insert";
			$this->db->insert("t_komentar",$data);
		}
	}




}
?>
