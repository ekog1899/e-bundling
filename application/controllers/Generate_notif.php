<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_notif extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
		#$this->banding->init();
		$this->load->model('banding_model','banding');
	
    }

	public function index()
	{
		// notif PTA ada permintaan validasi

		$sql = "SELECT kontak, CONCAT('edoc Nomor Perkara Berikut ini menunggu validasi di PARIBAN \n\r -', GROUP_CONCAT(nomor_perkara_pa  SEPARATOR '\n\r -')) pesan FROM
		(
		SELECT a.*,b.blm_validasi,DATEDIFF(NOW(),permohonan_banding) jml_hari
		FROM sipp_perkara_banding a LEFT JOIN
		(
		SELECT satkersing,perkara_id,SUM(blm_validasi) blm_validasi FROM
		(
		SELECT satkersing,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding
		WHERE val_edoc IS NULL
		AND file_edoc IS NOT NULL
		GROUP BY satkersing,perkara_id
		UNION
		SELECT satkersing,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding_sidang
		WHERE val_edoc IS NULL
		AND file_edoc IS NOT NULL
		GROUP BY satkersing,perkara_id
		) z
		GROUP BY satkersing,perkara_id
		) b 
		ON (a.`perkara_id`=b.perkara_id AND a.satkersing=b.satkersing)
		WHERE nomor_perkara_banding IS NULL OR nomor_perkara_banding = ''
		AND status_perkara_banding NOT LIKE '%cabut%'
		AND blm_validasi IS NOT NULL
		ORDER BY blm_validasi DESC
		) a1, t_user_pta a2
		WHERE a2.`grup`=2
		AND a2.kontak IS NOT NULL
		AND a2.id IN (109,85)
		GROUP BY kontak";
		$query = $this->db->query($sql);
		foreach ($query->result_array() as $row ){
			$hp = $row['kontak'];
			$pesan = $row['pesan'];
			#$hp ='6281376493629';
			#echo "$hp -- $pesan";
			$this->banding->sendWA($pesan,$hp);
		}


		// notif ke admin SATKER
		$this->butuh_perbaikan();

		//


	}


	public function req_unlock()
	{
		// notif PTA ada permintaan validasi

		$sql = "SELECT kontak, CONCAT('Nomor Perkara Berikut ini *MENUNGGU UNLOCK* di PARIBAN \n\r -', GROUP_CONCAT(nomor_perkara_pa  SEPARATOR '\n\r -')) pesan FROM
		(
		SELECT a.`nomor_perkara_pa`
		FROM sipp_perkara_banding a,req_unlock b
		WHERE (a.`perkara_id`=b.perkara_id AND a.satkersing=b.satkersing)
		AND b.tgl_unlock IS NULL
		) a1, t_user_pta a2
		WHERE a2.`grup`=2
		AND a2.kontak IS NOT NULL
		AND a2.id IN (109,85)
		GROUP BY kontak";
		$query = $this->db->query($sql);
		foreach ($query->result_array() as $row ){
			$hp = $row['kontak'];
			$pesan = $row['pesan'];
			#$hp ='6281376493629';
			#echo "$hp -- $pesan";
			$this->banding->sendWA($pesan,$hp);
		}

		//


	}


	public function butuh_perbaikan()
	{
		// notif PTA ada permintaan validasi

		$sql = "SELECT CONCAT(' Nomor Perkara berikut ini *menunggu perbaikan edoc* di PARIBAN \n\r -',GROUP_CONCAT(b.nomor_perkara_pa SEPARATOR '\n\r -')) pesan, s.`hp_admin` FROM
		(
		SELECT satkersing,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding
		WHERE val_edoc = 0
		AND file_edoc IS NOT NULL
		GROUP BY satkersing,perkara_id
		UNION
		SELECT satkersing,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding_sidang
		WHERE val_edoc = 0
		AND file_edoc IS NOT NULL
		GROUP BY satkersing,perkara_id
		) z, satker s,sipp_perkara_banding b
		WHERE s.satsing=z.satkersing
		AND z.satkersing=b.`satkersing`
		AND hp_admin IS NOT NULL
		AND b.putusan_banding IS NULL
		AND z.perkara_id=b.`perkara_id`
		GROUP BY z.satkersing,hp_admin";
		$query = $this->db->query($sql);
		foreach ($query->result_array() as $row ){
			$hp = $row['hp_admin'];
			$pesan = $row['pesan'];
			#$hp ='6281376493629';
			#echo "$hp -- $pesan";
			$this->banding->sendWA($pesan,$hp);
		}

		//


	}

}
