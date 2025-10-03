<?php
class AJAX_model extends CI_Model {


	function insert_notification($data){
			#$data['user_id'] = $user_id;
			$data['diinput'] = date('Y-m-d H:i:s');
			$this->db->insert("t_notifikasi",$data);
	}
	function send($data){
		// $sql = "insert into outbox(DestinationNumber,TextDecoded) values('$to','$msg')";
		$this->db->insert("dbsms.outbox",$data);

	}

	function get_unread(){
			return $this->db->query("SELECT * FROM t_notifikasi  WHERE dikirim IS NULL AND TIMESTAMPDIFF(HOUR,diinput,NOW()) <= 1 AND user_id=".$this->session->userdata('user_id')." limit 1")->result_array();
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
