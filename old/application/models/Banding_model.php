<?php
class Banding_model extends CI_Model
{

	function init()
	{
		$this->idpn = $this->session->userdata('idpn');
		$this->logged_user_name = $this->session->userdata('username');
		$this->idpn = "sipp"; // xampp mode
	}

	function set_satker($idpn)
	{
		$this->idpn = $idpn;
		// force on localhost
		if (ENVIRONMENT == 'development') {
			//$this->idpn = "sipp";
		}
	}

	public function satkerlist()
	{
		$this->db->select('satker_code,satker_name');
		$data = $this->db->get('satkerlist');
		return $data->result_array();
	}
	public function view_where_all($table, $data)
	{
		$this->db->where($data);
		return $this->db->get($table);
	}
	public function hapusData($table, $where)
	{
		return $this->db->delete($table, $where);
	}

	function view_where_cetak($perkara_id, $idpn)
	{


		$sql = "SELECT A.*,B.`nip`,B.`nama_gelar`,
			C.`nip` AS nip_tim1,C.`nama_gelar` AS nama_tim1,
			D.`nip` AS nip_tim2,D.`nama_gelar` AS nama_tim2,
			E.`nip` AS nip_tim_panitera,E.`nama_gelar` AS nama_panitera
			FROM `tim_pemeriksa_pradaftar` A
			LEFT JOIN `hakim_pt` B ON A.`id_ketua_tim` = B.`id`
			LEFT JOIN `hakim_pt` C ON A.`id_anggota_tim_1` = C.`id`
			LEFT JOIN `hakim_pt` D ON A.`id_anggota_tim_2` = D.`id`
			LEFT JOIN `panitera_pt` E ON A.`id_panitera_tim` = E.`id`
			WHERE idpn='$idpn' AND perkara_id='$perkara_id'";
		#echo $sql;	
		return $this->db->query($sql);
	}
	function fetch_ref_edoc($perkara_id, $idpn, $bundle)
	{


		$sql = "SELECT a.*,b.file_edoc,org_name,b.id id_edoc,catatan_pta,val_edoc,waktu_validasi FROM ref_jenis_edoc a LEFT OUTER JOIN (
			SELECT * FROM dok_perkara_banding 
			WHERE perkara_id='$perkara_id'
			AND idpn='$idpn' 
			) b
			ON(a.jenis_edoc=b.jenis_edoc)
			where bundel='$bundle'
			order by urut asc,id asc";
		#echo $sql;	
		return $this->db->query($sql)->result_array();
	}
	//Get TIm Pemeriksa Pra daftar
	function fetch_timpemeriksa($perkara_id, $idpn)
	{


		$sql = "SELECT a.*,b.file_edoc,org_name,b.id id_edoc,catatan_pta,val_edoc,waktu_validasi FROM ref_jenis_edoc a LEFT OUTER JOIN (
			SELECT * FROM dok_perkara_banding 
			WHERE perkara_id='$perkara_id'
			AND idpn='$idpn' 
			) b
			ON(a.jenis_edoc=b.jenis_edoc)
			where bundel='$bundle'
			order by urut asc,id asc";
		#echo $sql;	
		return $this->db->query($sql)->result_array();
	}
	// get data persidangan
	function fetch_jadwal_sidang_and_edoc($perkara_id)
	{
		$sql = "SELECT a.tanggal_sidang,a.agenda, b.file_edoc edoc_relaas_p, c.file_edoc edoc_relaas_t, d.file_edoc edoc_bas,
			b.val_edoc val_edoc_p, c.val_edoc val_edoc_t, d.val_edoc val_edoc_bas,
			b.catatan_pta catatan_p, c.catatan_pta catatan_t, d.catatan_pta catatan_bas
			
			FROM sipp_perkara_jadwal_sidang a 
			LEFT OUTER JOIN dok_perkara_banding_sidang b ON (a.perkara_id=b.perkara_id AND b.idpn='" . $this->idpn . "' AND a.tanggal_sidang=b.tanggal_sidang AND b.jenis_edoc='relaas_p')
			LEFT OUTER JOIN dok_perkara_banding_sidang c ON (a.perkara_id=c.perkara_id AND c.idpn='" . $this->idpn . "' AND a.tanggal_sidang=c.tanggal_sidang AND c.jenis_edoc='relaas_t')
			LEFT OUTER JOIN dok_perkara_banding_sidang d ON (a.perkara_id=d.perkara_id AND d.idpn='" . $this->idpn . "' AND a.tanggal_sidang=d.tanggal_sidang AND d.jenis_edoc='bas')
			WHERE a.perkara_id=$perkara_id
			and a.idpn='$this->idpn'
			ORDER BY a.tanggal_sidang ASC";
		#echo $sql;
		$q = $this->db->query($sql);
		return $q->result_array();
	}



	// dashboard PA
	function belum_kirim()
	{
		$sql = "SELECT a.perkara_id,a.pemohon_banding,a.nomor_perkara_pa nomor_perkara_pn,a.tgl_putusan_pa putusan_pn,permohonan_banding,majelis_hakim_pa majelis_hakim_text,z1.perbaikan
			FROM sipp_perkara_banding a
			LEFT OUTER JOIN
			(
			SELECT idpn,perkara_id,SUM(perbaikan) perbaikan FROM
			(
			SELECT idpn,perkara_id,COUNT(1) perbaikan FROM dok_perkara_banding
			WHERE val_edoc=0
			AND file_edoc IS NOT NULL
			GROUP BY idpn,perkara_id
			UNION
			SELECT idpn,perkara_id,COUNT(1) perbaikan FROM dok_perkara_banding_sidang
			WHERE val_edoc=0
			AND file_edoc IS NOT NULL
			GROUP BY idpn,perkara_id
			) z
			GROUP BY idpn,perkara_id
			) z1
			ON (a.perkara_id=z1.perkara_id)
			WHERE
			YEAR(permohonan_banding)=YEAR(NOW())
			AND a.idpn='$this->idpn'
			AND pengiriman_berkas_banding IS NULL";
		# echo $sql ;
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function stat_satker()
	{
		$sql = "SELECT 
			SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima,
			SUM(CASE WHEN YEAR(tanggal_pendaftaran_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima_pta,
			SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) AND pengiriman_berkas_banding IS NULL AND status_banding_id <> 391 THEN 1 ELSE 0 END ) blm_kirim,
			SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) AND minutasi_banding IS NULL THEN 1 ELSE 0 END ) berjalan,
			SUM(CASE WHEN YEAR(putusan_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) putus
			FROM sipp_perkara_banding
			where idpn='" . $this->idpn . "'";
		#echo $this->idpn;exit;
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function get_piechart_data()
	{
		#echo $this->idpn;
		$sql_filter_satker = ($this->session->userdata('idpn') == 'ptamedan') ? "" : "and idpn='" . $this->session->userdata('idpn') . "'";

		$sql = "SELECT status_putusan_banding_text label,COUNT(1) jml FROM sipp_perkara_banding
			WHERE YEAR(tanggal_pendaftaran_banding)=YEAR(NOW())
			AND status_putusan_banding_text <> ''
			$sql_filter_satker
			GROUP BY status_putusan_banding_text";
		$q = $this->db->query($sql);
		return $q->result_array();
	}


	function get_linechart_data()
	{
		$sql_filter_satker = ($this->session->userdata('idpn') == 'ptamedan') ? "" : "and idpn='" . $this->session->userdata('idpn') . "'";
		$sql = "SELECT 'jenis',
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='01' THEN 1 ELSE 0 END) jan,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='02' THEN 1 ELSE 0 END) feb,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='03' THEN 1 ELSE 0 END) mar,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='04' THEN 1 ELSE 0 END) apr,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='05' THEN 1 ELSE 0 END) mei,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='06' THEN 1 ELSE 0 END) jun,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='07' THEN 1 ELSE 0 END) jul,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='08' THEN 1 ELSE 0 END) ags,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='09' THEN 1 ELSE 0 END) sep,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='10' THEN 1 ELSE 0 END) okt,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='11' THEN 1 ELSE 0 END) nop,
			SUM(CASE WHEN MONTH(tanggal_pendaftaran_banding)='12' THEN 1 ELSE 0 END) des  
			FROM sipp_perkara_banding
			WHERE YEAR(tanggal_pendaftaran_banding)=YEAR(NOW())
			$sql_filter_satker
			GROUP BY 'terima'
			UNION
			SELECT 'putus',
			SUM(CASE WHEN MONTH(putusan_banding)='01' THEN 1 ELSE 0 END) jan,
			SUM(CASE WHEN MONTH(putusan_banding)='02' THEN 1 ELSE 0 END) feb,
			SUM(CASE WHEN MONTH(putusan_banding)='03' THEN 1 ELSE 0 END) mar,
			SUM(CASE WHEN MONTH(putusan_banding)='04' THEN 1 ELSE 0 END) apr,
			SUM(CASE WHEN MONTH(putusan_banding)='05' THEN 1 ELSE 0 END) mei,
			SUM(CASE WHEN MONTH(putusan_banding)='06' THEN 1 ELSE 0 END) jun,
			SUM(CASE WHEN MONTH(putusan_banding)='07' THEN 1 ELSE 0 END) jul,
			SUM(CASE WHEN MONTH(putusan_banding)='08' THEN 1 ELSE 0 END) ags,
			SUM(CASE WHEN MONTH(putusan_banding)='09' THEN 1 ELSE 0 END) sep,
			SUM(CASE WHEN MONTH(putusan_banding)='10' THEN 1 ELSE 0 END) okt,
			SUM(CASE WHEN MONTH(putusan_banding)='11' THEN 1 ELSE 0 END) nop,
			SUM(CASE WHEN MONTH(putusan_banding)='12' THEN 1 ELSE 0 END) des  
			FROM sipp_perkara_banding
			WHERE YEAR(tanggal_pendaftaran_banding)=YEAR(NOW())
			$sql_filter_satker
			UNION
			SELECT 'kasasi',
			SUM(CASE WHEN MONTH(permohonan_kasasi)='01' THEN 1 ELSE 0 END) jan,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='02' THEN 1 ELSE 0 END) feb,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='03' THEN 1 ELSE 0 END) mar,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='04' THEN 1 ELSE 0 END) apr,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='05' THEN 1 ELSE 0 END) mei,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='06' THEN 1 ELSE 0 END) jun,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='07' THEN 1 ELSE 0 END) jul,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='08' THEN 1 ELSE 0 END) ags,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='09' THEN 1 ELSE 0 END) sep,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='10' THEN 1 ELSE 0 END) okt,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='11' THEN 1 ELSE 0 END) nop,
			SUM(CASE WHEN MONTH(permohonan_kasasi)='12' THEN 1 ELSE 0 END) des  
			FROM sipp_perkara_kasasi
			WHERE YEAR(permohonan_kasasi)=YEAR(NOW())
			$sql_filter_satker
			";
		#echo $sql;
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function stat_pta()
	{
		$sql = "SELECT * FROM (
			SELECT SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima_pa,
			SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima_pta,
			SUM(CASE WHEN YEAR(permohonan_banding)<YEAR(NOW()) 
			AND YEAR(tanggal_pendaftaran_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima_pta_1,
			SUM(CASE WHEN YEAR(permohonan_banding)=YEAR(NOW()) 
			AND YEAR(tanggal_pendaftaran_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) terima_pta_2,
			SUM(CASE WHEN YEAR(putusan_banding)=YEAR(NOW()) THEN 1 ELSE 0 END ) putus
			FROM sipp_perkara_banding
			) aa,
			(SELECT SUM(CASE WHEN YEAR(permohonan_kasasi)=YEAR(NOW()) THEN 1 ELSE 0 END ) kasasi_terima_pa
			FROM sipp_perkara_kasasi ) bb		";
		$q = $this->db->query($sql);
		#echo $sql;
		return $q->result_array();
	}


	function berjalan_pa()
	{
		$sql = "SELECT *
			FROM sipp_perkara_banding
			where minutasi_banding is null
			and idpn='$this->idpn'";
		# echo $sql ;
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	// dash PTA
	function belum_berjalan()
	{
		$sql = "SELECT a.*,b.blm_validasi,DATEDIFF(NOW(),permohonan_banding) jml_hari
			FROM sipp_perkara_banding a LEFT JOIN
			(
			SELECT idpn,perkara_id,SUM(blm_validasi) blm_validasi FROM
			(
			SELECT idpn,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding
			WHERE val_edoc IS NULL
			AND jenis_edoc NOT IN (SELECT jenis_edoc FROM ref_jenis_edoc WHERE bundel='c')
			AND file_edoc IS NOT NULL
			GROUP BY idpn,perkara_id
			UNION
			SELECT idpn,perkara_id,COUNT(1) blm_validasi FROM dok_perkara_banding_sidang
			WHERE val_edoc IS NULL
			AND file_edoc IS NOT NULL
			GROUP BY idpn,perkara_id
			) z
			GROUP BY idpn,perkara_id
			) b 
			ON (a.`perkara_id`=b.perkara_id AND a.idpn=b.idpn)
			WHERE nomor_perkara_banding IS NULL OR nomor_perkara_banding = ''
			AND status_perkara_banding NOT LIKE '%cabut%'
			ORDER BY blm_validasi DESC
			";

		#	echo "<pre>$sql</pre>";

		$q = $this->db->query($sql);
		return $q->result_array();
	}




	function banding_detil($perkara_id)
	{
		$sql = "SELECT *
			FROM sipp_perkara_banding
			where perkara_id='$perkara_id'
			and idpn='$this->idpn'";
		#echo $sql;		
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function perkara_detil($perkara_id)
	{
		$sql = "SELECT *
			FROM $this->idpn.v_perkara
			where perkara_id='$perkara_id'";
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function get_perkara_id_banding($nomor_perkara)
	{
		$sql = "SELECT perkara_id
			FROM sipp_perkara_banding
			where upper(nomor_perkara_pa)=upper('$nomor_perkara')";
		$q = $this->db->query($sql);
		return $q->result_array();
	}

	function get_lock_info($perkara_id, $idpn)
	{

		$this->db->where('perkara_id', $perkara_id);
		$this->db->where('idpn', $this->idpn);
		return $this->db->get("lock_perkara_banding");
	}



	function insert_edoc($data)
	{

		// cek dulu udah ada data keterangan?
		$this->db->where('idpn', $data['idpn']);
		$this->db->where('perkara_id', $data['perkara_id']);
		$this->db->where('jenis_edoc', $data['jenis_edoc']);
		$this->db->select('file_edoc');
		$old_file = $this->db->get('dok_perkara_banding')->row();
		if (count($old_file) > 0)
			if (@file_exists('./uploads/edoc/' . $old_file->file_edoc)) {
				unlink('./uploads/edoc/' . $old_file->file_edoc);
			}
		$this->db->where('idpn', $data['idpn']);
		$this->db->where('perkara_id', $data['perkara_id']);
		$this->db->where('jenis_edoc', $data['jenis_edoc']);
		$this->db->delete('dok_perkara_banding');

		if ($data['file_size'] > 0) {
			#echo "inserted";
			$this->db->insert('dok_perkara_banding', $data);
		}
	}

	function insert_edoc_sidang($data)
	{

		// cek dulu udah ada data keterangan?
		$perkara_id = $data['perkara_id'];
		$jenis_edoc = $data['jenis_edoc'];
		$this->db->where('idpn', $data['idpn']);
		$this->db->where('perkara_id', $data['perkara_id']);
		$this->db->where('jenis_edoc', $data['jenis_edoc']);
		$this->db->where('tanggal_sidang', $data['tanggal_sidang']);
		$this->db->select('file_edoc');
		$old_file = $this->db->get('dok_perkara_banding_sidang')->row();
		if (count($old_file) > 0)
			if (@file_exists('./uploads/edoc/' . $old_file->file_edoc)) {
				unlink('./uploads/edoc/' . $old_file->file_edoc);
			}
		$this->db->where('idpn', $data['idpn']);
		$this->db->where('perkara_id', $data['perkara_id']);
		$this->db->where('jenis_edoc', $data['jenis_edoc']);
		$this->db->where('tanggal_sidang', $data['tanggal_sidang']);
		$this->db->delete('dok_perkara_banding_sidang');

		if ($data['file_size'] > 0) {
			$this->db->insert('dok_perkara_banding_sidang', $data);
		}

		// sys_log
		$this->insert_log($perkara_id, "Upload edoc " . $jenis_edoc);
	}

	function validasi_edoc($data)
	{

		if ($data['id'] != '') {
			#print_r($data);
			$this->db->where('id', $data['id']);
			$this->db->update('dok_perkara_banding', $data);
		} else {
			// klo edoc belum ada, insert
			$this->db->insert('dok_perkara_banding', $data);
			#	echo $this->db->last_query();
		}
		return true;
	}


	function validasi_edoc_sidang($data)
	{

		if ($data['tanggal_sidang'] != '') {
			print_r($data);
			$this->db->where('tanggal_sidang', $data['tanggal_sidang']);
			$this->db->where('jenis_edoc', $data['jenis_edoc']);
			$this->db->where('perkara_id', $data['perkara_id']);
			$this->db->where('idpn', $data['idpn']);
			$this->db->update('dok_perkara_banding_sidang', $data);
		} else {
			// klo edoc belum ada, insert
			$this->db->insert('dok_perkara_banding_sidang', $data);
			#echo $this->db->last_query();
		}
		return true;
	}

	function save_text_info($data)
	{
		// 
		$this->db->where('perkara_id', $data['perkara_id']);
		$this->db->where('idpn', $data['idpn']);
		$this->db->where('jenis_edoc', $data['jenis_edoc']);
		$this->db->delete('dok_perkara_banding');
		$this->db->insert('dok_perkara_banding', $data);
		return true;
	}
	function save_text_tim_pemeriksa($data)
	{
		// 
		$this->db->insert('tim_pemeriksa_pradaftar', $data);
		return true;
	}

	function save_text_catatan($data)
	{
		// 
		$this->db->insert('catatan_pemeriksa', $data);
		return true;
	}

	/*** JADWAL SIDANG BANDING**/
	function jadwal_sidang($idpn, $perkara_id)
	{
		$this->db->select('*');
		$this->db->where('perkara_id', $perkara_id);
		$this->db->where('idpn', $idpn);
		return $this->db->get('banding_jadwal_sidang')->result_array();
	}


	function insert_log($perkara_id, $aktivitas)
	{
		$data['waktu'] = date('Y-m-d H:i:s');
		$data['aktivitas'] = $aktivitas;
		$data['perkara_id'] = $perkara_id;
		$data['idpn'] = $this->idpn;
		$data['user_name'] = $this->logged_user_name;

		$this->db->insert('sys_log', $data);
	}

	function get_dok_by_perkara_id($idpn, $perkara_id, $jenis_edoc)
	{
		$this->db->select('*');
		$this->db->where('perkara_id', $perkara_id);
		$this->db->where('idpn', $idpn);
		$this->db->where('jenis_edoc', $jenis_edoc);
		return $this->db->get('dok_perkara_banding')->result_array();
	}

	function sendWA($pesan, $tujuan)
	{
		$ch = curl_init();
		if (ENVIRONMENT == 'development') {
			$tujuan = "081376493629";
		}
		curl_setopt($ch, CURLOPT_URL, "http://api.pa-kisaran.go.id/webhook/hook/api_send");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt(
			$ch,
			CURLOPT_POSTFIELDS,
			"phone=$tujuan&message=$pesan&template=pariban_temp&key=xkurnx123"
		);

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);

		curl_close($ch);
	}

	function view_catatan($perkara_id, $idpn)
	{
		$sql = "SELECT * FROM `catatan_pemeriksa` WHERE perkara_id='$perkara_id'";
		return $this->db->query($sql);
	}

	public function hapuscatatan($table, $where)
	{
		return $this->db->delete($table, $where);
	}
}
