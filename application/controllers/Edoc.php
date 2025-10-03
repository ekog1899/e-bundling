<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', 1);
error_reporting(E_ALL);
class Edoc extends CI_Controller
{

	var $arr_hari_libur = array();

	var $tgl_hari_ini = '';
	var $allow_salput = false;
	var $allow_skum = false;
	var $allow_pbt_banding = false;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_akses', 'admin');
		$this->load->model('banding_model', 'banding');
		$this->load->model('m_config', 'ModelConfig');
		$this->banding->init();
		$this->load->helper('my');
		$this->keyword = "";
		$this->tgl_hari_ini = date('Y-m-d');
		#$this->tgl_hari_ini = "2022-07-12";
		$this->session->set_userdata('tgl_server', $this->tgl_hari_ini);
		// generate array hari libur
		$this->db->where('YEAR(tanggal)>YEAR(NOW()) - 1');
		$libur = $this->db->get('ref_hari_libur')->result_array();
		foreach ($libur as $row) :
			$this->arr_hari_libur[] = 	$row['tanggal'];
		endforeach;

		if ($this->session->userdata('nama') == '') {
			sleep(1);
			redirect("login");
		}
	}

	public function index()
	{

		if (!$this->admin->logged_id()) {
			redirect("login");
		}


		$data['content'] = "Welcome";
		$data['title'] = "Banding Belum Kirim";
		$data['profile'] = $this->session->userdata();
		$data_content['banding_blm_kirim'] = $this->banding->belum_kirim();

		$data['content'] = $this->load->view("dashboard", $data_content, true);
		$this->load->view("template", $data);
	}


	private function check_uploading_allowance()
	{
	}

	public function upload_kasasi($perkara_id){
		$perkara_id_enc = $perkara_id;
		$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
		$this->banding->set_satker($this->session->userdata('idpn'));
		if (!$this->admin->is_login()) {
			redirect("login");
		}
		$data['content'] = "Welcome";
		$data['title'] = "Formulir Upload e-doc Putusan Kasasi";
		
		// $data_content['ref_jenis_edoc_a'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'a');
		// $data_content['ref_jenis_edoc_b'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'b');
		// $data_content['ref_jenis_edoc_c'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'c');
		// $data_content['ref_jenis_edoc_t'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 't');
		// // $data_content['ref_jenis_edoc_d'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'd');
		$data_content['kasasi_detil'] = $this->banding->kasasi_detil($perkara_id);

		$data['content'] = $this->load->view("f_edoc_kasasi", $data_content, true);
		$this->load->view("template", $data);
	}

	function do_upload_kasasi() {
		if (!$this->admin->is_login()) {
			redirect("login");
		}

		if ($this->input->post('perkara_id') <> '') {
			// ambil data hidden form
			$perkara_id = addslashes($this->input->post('perkara_id'));
			//$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
			$jenis_edoc = addslashes($this->input->post('jenis_edoc'));
			$idpn       = addslashes($this->input->post('idpn'));
			$satkersing = $idpn;

			// generate nama file baru
			$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$_FILES["file_doc"]['name'];

			// config upload
			$config['upload_path']   = './uploads/edoc/';
			$config['allowed_types'] = 'pdf|doc|docx|rtf';
			$config['max_size']      = 100000; // dalam KB (100 MB)
			$config['encrypt_name']  = FALSE;  // kalau mau pakai nama custom
			$config['file_name']     = $new_name;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file_doc')) {
				$up_data = $this->upload->data();

				$data['idpn']       = $idpn;
				$data['perkara_id'] = $perkara_id;
				$data['jenis_edoc'] = $jenis_edoc;
				$data['satkersing'] = $satkersing;
				$data['file_doc']   = $up_data['file_name'];   //pakai file_name
				$data['file_size']  = $up_data['file_size'];
				$data['file_type']  = $up_data['file_type'];

				// validasi tipe file
				if (in_array($data['file_type'], ['application/pdf', 'application/rtf', 'text/rtf'])) {
					// hapus lama + insert baru
					$this->banding->insert_edoc_kasasi($data);

					//echo "<div class='highlight_fadeOut'>
							//<i class='fas fa-fw fa-check'></i> 
							//<span class='text-success'>
							//file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload
							//</span>
						//</div>";
					 $this->session->set_flashdata('message', 
								"<div class='highlight_fadeOut'>
									<i class='fas fa-fw fa-check'></i> 
									<span class='text-success'>
										file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload
									</span>
								</div>");
					
				} else {
					//die('Format File harus PDF atau RTF');
					$this->session->set_flashdata('message', "<div class='text-danger'>Format File harus PDF atau RTF</div>");
				}}
				if (!$this->upload->do_upload('file_doc')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('message', "<div class='text-danger'>Error upload: ".$error."</div>");
				}

				redirect("edoc/upload_kasasi/".str_replace('/', '__', $this->encryption->encrypt($perkara_id)));

			}
	}


	public function upload($perkara_id)
	{
		$perkara_id_enc = $perkara_id;

		$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
		$this->banding->set_satker($this->session->userdata('idpn'));
		if (!$this->admin->is_login()) {
			redirect("login");
		}

		//echo $this->session->userdata('idpn'); exit;
		$data['content'] = "Welcome";
		$data['title'] = "Formulir Upload e-doc Banding";

		$data_content['ref_jenis_edoc_a'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'a');
		$data_content['ref_jenis_edoc_b'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'b');
		$data_content['ref_jenis_edoc_c'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'c');
		$data_content['ref_jenis_edoc_t'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 't');
		$data_content['ref_jenis_edoc_d'] = $this->banding->fetch_ref_edoc($perkara_id, $this->session->userdata('idpn'), 'd');
		$data_content['banding_detil'] = $this->banding->banding_detil($perkara_id);
		//$data_content['perkara_detil'] = $this->banding->perkara_detil($perkara_id);


		// form upload relas dan bas digenerate khusus

		$data_content['html_sidang_1'] = $this->_html_sidang($perkara_id);


		$data_content['perkara_id'] = $perkara_id_enc;
		$data_content['arr_hari_libur'] = $this->arr_hari_libur;

		// ambil data request unlock by perkara_id
		$this->db->select("jenis_edoc,DATE_FORMAT(tgl_unlock,'%Y-%m-%d') tgl_unlock");
		$this->db->where('perkara_id', $perkara_id);
		$this->db->where('idpn', $this->session->userdata('idpn'));
		$unlock = $this->db->get('req_unlock')->result_array();
		$req_unlock = array();
		foreach ($unlock as $row) :
			$req_unlock[$row['jenis_edoc']]['tgl_unlock'] = $row['tgl_unlock'];
		endforeach;


		// check izin upload edoc
		// upload salput, max 1 hari dari penerimaan
		$arr_banding_detil = $data_content['banding_detil'];
		$tgl_permohonan_banding = $arr_banding_detil[0]['permohonan_banding'];

		$lock_upload = array();
		/*** 
				if ( _hitung_hari($tgl_permohonan_banding, $this->tgl_hari_ini,$this->arr_hari_libur) > 2 ){
				$lock_upload['salput_pdf']['lock']	= TRUE;
				$lock_upload['salput_pdf']['desc']	= '<i class="fas fa-fw fa-lock"></i>'." Terkunci! Max. 2 Hari Kerja setelah Penerimaan Banding";
				$lock_upload['salput_rtf']['lock']	= TRUE;
				$lock_upload['salput_rtf']['desc']	= '<i class="fas fa-fw fa-lock"></i>'." Terkunci! Max. 2 Hari Kerja setelah Penerimaan Banding";
				$lock_upload['akta_banding']['lock'] = TRUE;
				$lock_upload['akta_banding']['desc'] = '<i class="fas fa-fw fa-lock"></i>'." Terkunci! Max. 2 Hari Kerja setelah Penerimaan Banding";
				$lock_upload['skum']['lock']	= TRUE;
				$lock_upload['skum']['desc']	= '<i class="fas fa-fw fa-lock"></i>'." Terkunci! Max. 2 Hari Kerja setelah Penerimaan Banding";
				}
				
		 **/

		/*** cek auto lock bundle by tanggal 
		 * Bundle A 7 hari setelah permohonan banding 
		 * Bundle B 30 hari setelah permohonan banding **/

		$lock_bundle_a = FALSE;
		$lock_bundle_b = FALSE;
		# echo $this->tgl_hari_ini."---"._hitung_hari($tgl_permohonan_banding, $this->tgl_hari_ini,$this->arr_hari_libur);exit;
		if (_hitung_hari($tgl_permohonan_banding, $this->tgl_hari_ini, $this->arr_hari_libur) > 7 && !isset($req_unlock['bundle_a']['tgl_unlock'])) {
			$lock_bundle_a = TRUE;
		}
		if (_hitung_hari($tgl_permohonan_banding, $this->tgl_hari_ini, $this->arr_hari_libur) > 30 && !isset($req_unlock['bundle_b']['tgl_unlock'])) {
			$lock_bundle_b = TRUE;
		}


		$data_content['lock_bundle_a'] = $lock_bundle_a;
		$data_content['lock_bundle_b'] = $lock_bundle_b;

		$data_content['arr_req_unlock'] = $req_unlock;
		$data_content['arr_lock_upload'] = $lock_upload;
		$data_content['arr_hari_libur'] = $this->arr_hari_libur;
		$data_content['tgl_hari_ini'] = $this->tgl_hari_ini;
		$data_content['idpn'] = $this->session->userdata('idpn');

		$data['content'] = $this->load->view("f_edoc", $data_content, true);
		$this->load->view("template", $data);
	}

	//tambahan pindahan dari penetapan
	public function do_upload()
	{
		
		if(!$this->admin->is_login())
			{
			redirect("login");
			}
		if ($this->input->post('perkara_id') <> '' ){
                 // save data baru
                 //ambil variabel post
				$perkara_id				= addslashes($this->input->post('perkara_id')); 
				$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
				$jenis_edoc				= addslashes($this->input->post('jenis_edoc')); 
			//	$satkersing = $this->session->userdata('satkersing'); yg salah
			
				$idpn				= addslashes($this->input->post('idpn'));
				$satkersing = $idpn;
				//echo 'idpnnya='.$idpn;exit;
				// klo satkersing PTA MEDAN, berarti yang satkersingnya dari session
				//$satkersing = ( $satkersing == 'ptamedan' ) ? $this->session->userdata('satkersing_1'):$satkersing;
				
				$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$_FILES["file_edoc"]['name'];
				
                $d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc/';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']		= '100000';
                $config['max_width']  		= '30000';
                $config['max_height'] 		= '30000';
				$config['encrypt_name'] = TRUE;
				$config['file_name']= $new_name;
				if ($_FILES["file_edoc"]['type'] == 'application/msword') {
					$config['allowed_types'] 	= 'doc|rtf|docx|rtf|pdf';
				}

				#print_r($config);
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file_edoc')) {
                        $up_data	 	= $this->upload->data();
						//$data['idpn']	= $this->session->userdata('idpn');;
						$data['idpn']	= $idpn;;
						$data['perkara_id'] = $perkara_id;
						$data['jenis_edoc'] = $jenis_edoc;
						$data['satkersing'] = $satkersing;
						
						$data['file_edoc'] = $up_data['file_name'];
						$data['file_size'] = $up_data['file_size'];
						$data['file_type'] = $up_data['file_type'];
						$data['org_name'] = $up_data['client_name'];
						$data['val_edoc'] = null;
						#echo $data['file_type'];
						
						if  ($data['file_type'] == 'application/pdf' or $data['file_type'] == 'text/rtf')
						{	
							// hapus file lama 
							//print_r($data); exit;
							$this->banding->insert_edoc($data);
							echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload dan NOTIFIKASI Telah dikirim ke PIHAK</span></div>";
						
							// kalo yang diupload tt_daftar_banding, kirim notif ke pihak
							if ( $jenis_edoc == 'tt_daftar_banding'){
								
								$link = base_url('s/'.encode_url($satkersing,$perkara_id));

								$pesan = "Perkara Banding telah terdaftar. klik ".$link;
								
								///doc = $this->banding->get_dok_by_perkara_id($satkersing,$perkara_id,'hp_pembanding'); salah
								$doc = $this->banding->get_dok_by_perkara_id($satkersing,$perkara_id,'hp_pembanding');
								
								if (@count($doc[0])){
									$hp_pembanding = $doc[0]['org_name'];

								//	echo $hp_pembanding;
									#$this->banding->sendWA($pesan,$hp_pembanding);
																}
								$doc = $this->banding->get_dok_by_perkara_id($satkersing,$perkara_id,'hp_terbanding');
								//print_r($doc); exit;
								
								if (@count($doc[0])){
									$hp_terbanding = $doc[0]['org_name'];
									//echo $hp_terbanding; exit;
									#$this->banding->sendWA($pesan,$hp_terbanding);
								}
								
							}
	
						}	
						else {
							die ('Format File harus PDF atau RTF');
						}

                    } else {
                        die("Error, file gagal diupload");// $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
                    }
                   
                }
               
	}

	public function do_upload_sidang()
	{
           if(!$this->admin->is_login())
			{
			redirect("login");
			}
		if ($this->input->post('perkara_id') <> '' ){
                 // save data baru
                 //ambil variabel post
				$perkara_id				= addslashes($this->input->post('perkara_id')); 
				$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
				$jenis_edoc				= addslashes($this->input->post('jenis_edoc')); 
				$satkersing = $this->session->userdata('idpn');
				$tanggal_sidang				= addslashes($this->input->post('tanggal_sidang')); 
				$idpn = $this->session->userdata('idpn');
				$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$tanggal_sidang.'_'.$_FILES["file_edoc"]['name'];
				 //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc/';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']		= '100000';
                $config['max_width']  		= '30000';
                $config['max_height'] 		= '30000';
				//$config['encrypt_name'] = TRUE;
				$config['file_name']= $new_name;
				
				
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file_edoc')) {
                        $up_data	 	= $this->upload->data();
						
						$data['perkara_id'] = $perkara_id;
						$data['jenis_edoc'] = $jenis_edoc;
						$data['idpn'] = $idpn;
						//$data['satkersing'] = $satkersing;
						$data['tanggal_sidang'] = $tanggal_sidang;
						$data['file_edoc'] = $up_data['file_name'];
						$data['file_size'] = $up_data['file_size'];
						$data['file_type'] = $up_data['file_type'];
						$data['org_name'] = $up_data['client_name'];
						$data['val_edoc'] = null;
						
						if  ($data['file_type'] == 'application/pdf')
						{	
							// hapus file lama 
							$this->banding->insert_edoc_sidang($data);
							echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload</span></div>";
						}	

                } else {
                       die("Error, file gagal diupload");// $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
                }
                   
            }
               
	}
	
	
	private function _html_sidang($perkara_id)
	{

		$content['list_sidang'] = $this->banding->fetch_jadwal_sidang_and_edoc($perkara_id);
		$html_sidang = $this->load->view("part_jadwal_sidang", $content, true);
		return $html_sidang;
	}

	private function _html_sidang_validasi($perkara_id)
	{

		$content['list_sidang'] = $this->banding->fetch_jadwal_sidang_and_edoc($perkara_id);
		$html_sidang = $this->load->view("part_jadwal_sidang_validasi", $content, true);
		return $html_sidang;
	}


	public function validasi($idpn, $perkara_id)
	{
		$perkara_id_enc = $perkara_id;
		$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));

		$this->banding->set_satker($idpn);
		$this->session->set_userdata('idpn_1', $idpn);

		//echo $idpn; exit;
		// cari Nomor Perkara nya
		$this->session->set_userdata('sess_nomor_perkara_pn', '');
		$banding_detil = $this->banding->banding_detil($perkara_id);
		$nomor_perkara_pa = $banding_detil[0]['nomor_perkara_pa'];
		$this->session->set_userdata('sess_nomor_perkara_pn', $nomor_perkara_pa);

		if (!$this->admin->is_login()) {
			redirect("login");
		}

		if ($this->session->userdata('role') == 'op_satker') {
			die('Tidak diizinkan');
		}

		$data['content'] = "Welcome";
		$data['title'] = "Validasi e-doc Banding";
		$data_content['html_sidang_1'] = $this->_html_sidang_validasi($perkara_id);
		$data_content['ref_jenis_edoc_a'] = $this->banding->fetch_ref_edoc($perkara_id, $idpn, 'a');
		$data_content['ref_jenis_edoc_b'] = $this->banding->fetch_ref_edoc($perkara_id, $idpn, 'b');
		$data_content['ref_jenis_edoc_c'] = $this->banding->fetch_ref_edoc($perkara_id, $idpn, 'c');
		$data_content['ref_jenis_edoc_d'] = $this->banding->fetch_ref_edoc($perkara_id, $idpn, 'd');
		$data_content['banding_detil'] = $this->banding->banding_detil($perkara_id);
		$data_content['cektim'] = $this->banding->view_where_cetak($perkara_id, $idpn)->row_array();
		$data_content['catatan'] = $this->banding->view_catatan($perkara_id, $idpn)->result_array();
		//$data_content['perkara_detil'] = $this->banding->perkara_detil($perkara_id);
		$data_content['perkara_id'] = $perkara_id_enc;

		$data_content['hakim'] = $this->admin->get_hakim_banding();
		$data_content['pp'] = $this->admin->get_pp_banding();

		//$nama = $this->session->userdata('nama');
		//$data_content['ceknama'] = $this->banding->view_where_all('t_user_pta',array('nama'=>$nama))->row_array();
		//$query = "select username FROM  t_user_pta WHERE nama=$nama";
		//$data['username'] = $this->db->query($query)->row_array();
		//$data_content['username'] =$this->session->userdata('nama');
		$data_content['idpn'] = $idpn;
		$data['content'] = $this->load->view("f_validasi", $data_content, true);
		$this->load->view("template", $data);
	}
	public function hapus_timpemeriksa()
	{
		$get_encIdperkara = $this->uri->segment(3);
		$get_satker = $this->uri->segment(4);
		$perkara_idasli = $this->encryption->decrypt(str_replace('__', '/', $get_encIdperkara));
		$this->banding->hapusData('tim_pemeriksa_pradaftar', array('idpn' => $get_satker, 'perkara_id' => $perkara_idasli));
		$this->session->set_flashdata("k", "<div class=\"alert alert-info\">Data Tim Pemeriksa Pra Daftar berhasil di Hapus </div>");
		redirect('Edoc/validasi/' . $get_satker . '/' . $get_encIdperkara);
	}
	public function cetak_instrumen_timpmh()
	{
		$get_encIdperkara = $this->uri->segment(3);
		$get_satker = $this->uri->segment(4);
		$perkara_idasli = $this->encryption->decrypt(str_replace('__', '/', $get_encIdperkara));
		$ceksatker = $this->banding->view_where_all('master_satker', array('id_satker_sipp' => $get_satker))->row_array();
		$cekperkara = $this->banding->view_where_all('sipp_perkara_banding', array('perkara_id' => $perkara_idasli, 'idpn' => $get_satker))->row_array();
		$nomorperkara = $cekperkara['nomor_perkara_pa'];
		$cektim = $this->banding->view_where_cetak($perkara_idasli, $get_satker)->row_array();
		$ketuamajelis = $cektim['nama'];
		$datanama_kpta = $this->ModelConfig->get_user_wakil(2);
		// $datanama_kpta = $this->ModelConfig->get_config('nama_kpta');
		$nama_pta = $this->ModelConfig->get_config('pta_name');
		$datenow = date('Y-m-d');
		$tanggalttd =  tgl_indo($datenow);
		//print_r($nomorperkara);exit;
		$namafile = 'template_form_intrumen';
		$url = base_url("template/" . $namafile . ".rtf");

		$Templatecetak = file_get_contents($url);

		$Templatecetak = str_replace("#nomorperkara#", '' . $nomorperkara . '', $Templatecetak);
		$Templatecetak = str_replace("#ketuamajelis#", '' . $ketuamajelis . '', $Templatecetak);
		$Templatecetak = str_replace("#anggota_1#", '' . $cektim['nama_tim1'] . '', $Templatecetak);
		$Templatecetak = str_replace("#anggota_2#", '' . $cektim['nama_tim2'] . '', $Templatecetak);
		$Templatecetak = str_replace("#panitera#", '' . $cektim['nama_panitera'] . '', $Templatecetak);
		$Templatecetak = str_replace("#jenisperkara#", '' . $cekperkara['jenis_perkara_text'] . '', $Templatecetak);
		$Templatecetak = str_replace("#satuankerja_pa#", '' . $ceksatker['nama_satker'] . '', $Templatecetak);
		$Templatecetak = str_replace("#nama_kpta#", '' . $datanama_kpta . '', $Templatecetak);
		$Templatecetak = str_replace("#nama_pta#", '' . $nama_pta . '', $Templatecetak);
		$Templatecetak = str_replace("#tanggal#", '' . $tanggalttd . '', $Templatecetak);
		$datenow = date('Y-m-d');
		header("Content-type: application/rtf");
		header("Content-disposition: inline; filename=" . $namafile . "-" . $datenow . ".rtf");
		header("Content-length: " . strlen($Templatecetak));
		echo $Templatecetak;
	}




	public function do_upload_x()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}
		$nama_pta = $this->ModelConfig->get_config('pta_name');
		if ($this->input->post('perkara_id') <> '') {
			// save data baru
			//ambil variabel post
			$perkara_id				= addslashes($this->input->post('perkara_id'));
			$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
			$jenis_edoc				= addslashes($this->input->post('jenis_edoc'));
			$idpn = $this->session->userdata('idpn');

			// request pak syaifudding
			if ($jenis_edoc == 'pengantar') {
				// check mediasi
				$edoc_jadwal_mediasi = $this->banding->get_dok_by_perkara_id($idpn, $perkara_id, 'jadwal_mediasi');
				if (count($edoc_jadwal_mediasi) == 0) {
					die('<strong class="text-danger">Mohon Upload Jadwal Mediasi terlebih dahulu</strong>');
				}
			}


			// klo idpn PTA MEDAN, berarti yang idpnnya dari session
			$idpn = ($idpn == 'ptamedan') ? $this->session->userdata('idpn_1') : $idpn;


			$new_name = $idpn . '_' . $perkara_id . '_' . $jenis_edoc . '_' . $_FILES["file_edoc"]['name'];

			//$d_diteruskan_ke = "0";
			$config['upload_path'] 		= './uploads/edoc/';
			$config['allowed_types'] 	= 'pdf';
			$config['max_size']		= '1000000';
			$config['max_width']  		= '300000';
			$config['max_height'] 		= '300000';
			//$config['encrypt_name'] = TRUE;
			$config['file_name'] = $new_name;
			if ($_FILES["file_edoc"]['type'] == 'application/msword') {
				$config['allowed_types'] 	= 'doc|rtf|docx|rtf|pdf';
			}

			#print_r($config);
			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file_edoc')) {
				$up_data	 	= $this->upload->data();

				$data['perkara_id'] = $perkara_id;
				$data['jenis_edoc'] = $jenis_edoc;
				$data['idpn'] = $idpn;
				$data['file_edoc'] = $up_data['file_name'];
				$data['file_size'] = $up_data['file_size'];
				$data['file_type'] = $up_data['file_type'];
				$data['org_name'] = $up_data['client_name'];
				$data['val_edoc'] = null;
				#echo $data['file_type'];

				if ($data['file_type'] == 'application/pdf' or $data['file_type'] == 'text/rtf') {
					// hapus file lama 
					#print_r($data);
					$this->banding->insert_edoc($data);
					echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('" . $up_data['file_name'] . "')\">" . $up_data['client_name'] . "</a> berhasil diupload dan NOTIFIKASI Telah dikirim ke PIHAK</span></div>";

					// sending SMS Notif
					if ($jenis_edoc == 'tt_daftar_banding') {

						$link = base_url('s/' . encode_url($idpn, $perkara_id));

						$nomor_perkara_pa = $this->session->userdata('sess_nomor_perkara_pn');
						$pesan = "Upaya hukum Banding $nomor_perkara_pa telah terdaftar di PTA. klik " . $link;
						$doc = $this->banding->get_dok_by_perkara_id($idpn, $perkara_id, 'hp_pembanding');

						if (count($doc) > 0) {
							$hp_pembanding = $doc[0]['org_name'];

							echo "<br />Notif  " . $this->banding->sendSMS($pesan, $hp_pembanding) . " ke " . $hp_pembanding;
						}
						$doc = $this->banding->get_dok_by_perkara_id($idpn, $perkara_id, 'hp_terbanding');
						if (count($doc) > 0) {
							$hp_terbanding = $doc[0]['org_name'];
							//$this->banding->sendSMS($pesan,$hp_terbanding);
							echo "<br />Notif  " . $this->banding->sendSMS($pesan, $hp_terbanding) . " ke " . $hp_terbanding;
						}
					}

					if ($jenis_edoc == 'salput_banding') {

						$link = base_url('s/' . encode_url($idpn, $perkara_id));

						$nomor_perkara_pa = $this->session->userdata('sess_nomor_perkara_pn');
						$pesan = "Upaya hukum Banding $nomor_perkara_pa telah Putus. klik " . $link;
						$doc = $this->banding->get_dok_by_perkara_id($idpn, $perkara_id, 'hp_pembanding');

						if (count($doc) > 0) {
							$hp_pembanding = $doc[0]['org_name'];

							echo "<br />Notif  " . $this->banding->sendSMS($pesan, $hp_pembanding) . " ke " . $hp_pembanding;
						}
						$doc = $this->banding->get_dok_by_perkara_id($idpn, $perkara_id, 'hp_terbanding');
						if (count($doc) > 0) {
							$hp_terbanding = $doc[0]['org_name'];
							//$this->banding->sendSMS($pesan,$hp_terbanding);
							echo "<br />Notif  " . $this->banding->sendSMS($pesan, $hp_terbanding) . " ke " . $hp_terbanding;
						}
					}
				} else {
					die('Format File harus PDF atau RTF');
				}
			} else {
				die("Error, file gagal diupload"); // $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
			}
		}
	}

	public function do_upload_sidang_xxx()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}
		if ($this->input->post('perkara_id') <> '') {
			// save data baru
			//ambil variabel post
			$perkara_id				= addslashes($this->input->post('perkara_id'));
			$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
			$jenis_edoc				= addslashes($this->input->post('jenis_edoc'));
			$idpn = $this->session->userdata('idpn');
			$tanggal_sidang				= addslashes($this->input->post('tanggal_sidang'));

			$new_name = $idpn . '_' . $perkara_id . '_' . $jenis_edoc . '_' . $tanggal_sidang . '_' . $_FILES["file_edoc"]['name'];
			//$d_diteruskan_ke = "0";
			$config['upload_path'] 		= './uploads/edoc/';
			$config['allowed_types'] 	= 'pdf';
			$config['max_size']		= '1000000';
			$config['max_width']  		= '300000';
			$config['max_height'] 		= '300000';
			//$config['encrypt_name'] = TRUE;
			$config['file_name'] = $new_name;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file_edoc')) {
				$up_data	 	= $this->upload->data();

				$data['perkara_id'] = $perkara_id;
				$data['jenis_edoc'] = $jenis_edoc;
				$data['idpn'] = $idpn;
				$data['tanggal_sidang'] = $tanggal_sidang;
				$data['file_edoc'] = $up_data['file_name'];
				$data['file_size'] = $up_data['file_size'];
				$data['file_type'] = $up_data['file_type'];
				$data['org_name'] = $up_data['client_name'];
				$data['val_edoc'] = null;

				if ($data['file_type'] == 'application/pdf') {
					// hapus file lama 
					$this->banding->insert_edoc_sidang($data);
					echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('" . $up_data['file_name'] . "')\">" . $up_data['client_name'] . "</a> berhasil diupload</span></div>";
				}
			} else {
				die("Error, file gagal diupload"); // $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
			}
		}
	}


	public function do_validasi()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}
		// validasi edoc yang ada,	
		// save data baru
		//ambil variabel post
		$id_edoc				= addslashes($this->input->post('id_edoc'));
		$perkara_id				= addslashes($this->input->post('perkara_id'));
		$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
		$idpn				= addslashes($this->input->post('idpn'));
		$jenis_edoc				= addslashes($this->input->post('jenis_edoc'));
		$select_validasi				= addslashes($this->input->post('select_validasi'));
		$catatan_validasi				= addslashes($this->input->post('catatan_validasi'));

		$data['id'] = $id_edoc;
		$data['perkara_id'] = $perkara_id;
		$data['idpn'] = $idpn;
		$data['jenis_edoc'] = $jenis_edoc;
		$data['val_edoc'] = $select_validasi;
		$data['catatan_pta'] = $catatan_validasi;
		$data['waktu_validasi'] = date('Y-m-d h:i:s');
		$data['validator']=$this->session->userdata('nama');
		$data['satkersing']=$idpn; 
		//echo $idpn;
		//echo $perkara_id.'<br/>';
		//echo $id_edoc.'<br/>';
		//exit; 
		// send Notif
		if ($select_validasi == 0) {
			$pesan = "PTA Menolak edoc " . $jenis_edoc . " dengan catatan \n\r" . $catatan_validasi;
			$this->notif_satker($idpn, $pesan);
		}

		if ($this->banding->validasi_edoc($data))
			echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Validasi Tersimpan</span></div>";
		else
			echo "<i class='fas fa-fw fa-check'></i> <span class='text-danger'>Validasi Gagal</span>";
	}


	public function do_validasi_sidang()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}
		// validasi edoc yang ada,	
		// save data baru
		//ambil variabel post
		$id_edoc				= addslashes($this->input->post('id_edoc'));
		$perkara_id				= addslashes($this->input->post('perkara_id'));
		$perkara_id = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
		$idpn				= addslashes($this->input->post('idpn'));
		$jenis_edoc				= addslashes($this->input->post('jenis_edoc'));
		$select_validasi		= addslashes($this->input->post('select_validasi'));
		$catatan_validasi		= addslashes($this->input->post('catatan_validasi'));
		$tgl_sidang				= addslashes($this->input->post('tgl_sidang'));

		$data['perkara_id'] = $perkara_id;
		$data['idpn'] = $idpn;
		
		$data['jenis_edoc'] = $jenis_edoc;
		$data['val_edoc'] = $select_validasi;
		$data['catatan_pta'] = $catatan_validasi;
		$data['tanggal_sidang'] = $tgl_sidang;
		$data['waktu_validasi'] = date('Y-m-d h:i:s');

		// send Notif
		if ($select_validasi == 0) {
			$pesan = "PTA Menolak edoc " . $jenis_edoc . " utk Tgl Sidang" . $tgl_sidang . " dengan catatan \n\r" . $catatan_validasi;
			$this->notif_satker($idpn, $pesan);
		}

		if ($this->banding->validasi_edoc_sidang($data))
			echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Validasi Tersimpan</span></div>";
		else
			echo "<i class='fas fa-fw fa-check'></i> <span class='text-danger'>Validasi Gagal</span>";
	}




	public function save_text()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}

		$perkara_id			= addslashes($this->input->post('perkara_id'));
		$data['perkara_id'] = $this->encryption->decrypt(str_replace('__', '/', $perkara_id));
		$data['idpn']	= $this->session->userdata('idpn');;
		$data['jenis_edoc']	= addslashes($this->input->post('jenis_edoc'));
		$data['org_name']		= addslashes($this->input->post('value'));
		// save ke db	

		$this->banding->save_text_info($data);
		echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Data Tersimpan</span></div>";
	}

	public function save_timpemeriksa()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}

		$dataidpn		= addslashes($this->input->post('idpn'));
		$perkara_id_enc		= addslashes($this->input->post('perkara_id'));
		//$perkara_idasli = $this->encryption->decrypt(str_replace('__','/',addslashes($this->input->post('perkara_id'))));
		$data['idpn']		= addslashes($this->input->post('idpn'));
		$data['perkara_id']		= $this->encryption->decrypt(str_replace('__', '/', addslashes($this->input->post('perkara_id'))));
		$data['id_ketua_tim']		= addslashes($this->input->post('id_ketua_tim'));
		$data['id_anggota_tim_1']	= addslashes($this->input->post('id_anggota_tim_1'));
		$data['id_anggota_tim_2']	= addslashes($this->input->post('id_anggota_tim_2'));
		$data['id_panitera_tim']	= addslashes($this->input->post('id_panitera_tim'));
		// save ke db	

		$this->banding->save_text_tim_pemeriksa($data);
		$this->session->set_flashdata("k", "<div class=\"alert alert-info\">Data Tim Pemeriksa Pra Daftar berhasil di tambahkan </div>");
		redirect('Edoc/validasi/' . $dataidpn . '/' . $perkara_id_enc);
	}

	public function save_catatan()
	{
		if (!$this->admin->is_login()) {
			redirect("login");
		}

		$dataidpn		= addslashes($this->input->post('idpn'));
		$perkara_id_enc		= addslashes($this->input->post('perkara_id'));
		//$perkara_idasli = $this->encryption->decrypt(str_replace('__','/',addslashes($this->input->post('perkara_id'))));
		$data['idpn']		= addslashes($this->input->post('idpn'));
		$data['perkara_id']		= $this->encryption->decrypt(str_replace('__', '/', addslashes($this->input->post('perkara_id'))));
		$data['nama_pengguna']		= addslashes($this->input->post('username'));
		$data['catatan']	= addslashes($this->input->post('catatan'));
		$data['tanggal_insert']	= date('Y-m-d h:i:s');
		//$nama		= addslashes($this->input->post('username'));
		//$cekusername = "select username from t_user_pta where nama='$nama'";
		//return $this->db->query($cekusername)->row_array();
		//$data['username']		= $cekusername;

		// save ke db	

		$this->banding->save_text_catatan($data);
		$this->session->set_flashdata("k", "<div class=\"alert alert-info\">Catatan Pemeriksa Pra Daftar berhasil di tambahkan </div>");
		redirect('Edoc/validasi/' . $dataidpn . '/' . $perkara_id_enc);
	}


	public function notif_satker($idpn, $pesan)
	{
		$this->db->where('idpn', $idpn);
		$data = $this->db->get('satkerlist')->result_array();
		if (isset($data)) {
			//	$hp_admin = $data[0]['hp_admin'];
		} else {
			//	$hp_admin = "";			
		}
		//$this->banding->sendWA($pesan,$hp_admin);
	}

	public function delete_catatan($id)
	{
		$get_encIdperkara = $this->uri->segment(5);
		$get_satker = $this->uri->segment(4);
		//$perkara_idasli = $this->encryption->decrypt(str_replace('__', '/', $get_encIdperkara));
		$this->banding->hapuscatatan('catatan_pemeriksa', array('id' => $id));
		$this->session->set_flashdata("k", "<div class=\"alert alert-info\">Data Tim Pemeriksa Pra Daftar berhasil di Hapus </div>");
		redirect('Edoc/validasi/' . $get_satker . '/' . $get_encIdperkara);
	}
}
