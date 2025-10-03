<?php
class SMS_model extends CI_Model {


	function send($data){
		//$sql = "insert into outbox(DestinationNumber,TextDecoded) values('$data[no_hp]','$data[pesan]')";
		$this->db->insert("sms.outbox",$data);

	}

	function sendWA($data){
		#print_r($data);
		$no_tujuan_new = preg_replace('/^0/', "62", trim($data['no_hp']));
		$sql = "insert into wabot.outbox(tujuan,pesan,waktu_insert) values('$no_tujuan_new','$data[pesan]','".date('Y-m-d H:i:s')."')";
		
		#$this->db->query($sql);
		
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
