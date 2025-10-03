<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors',1);
error_reporting(E_ALL);
class Penetapan extends CI_Controller {

	var $arr_hari_libur = array();
	
	var $tgl_hari_ini = '';
	var $allow_salput = false;
	var $allow_skum = false;
	var $allow_pbt_banding = false;
    public function __construct()
    {
        parent::__construct();
			$this->load->model('m_akses','admin');
			$this->load->model('banding_model','banding');
			$this->banding->init();
			$this->load->helper('my');
			$this->keyword = "";
			$this->tgl_hari_ini = date('Y-m-d');
			#$this->tgl_hari_ini = "2022-07-12";
			$this->session->set_userdata('tgl_server',$this->tgl_hari_ini);
			// generate array hari libur
			$this->db->where('YEAR(tanggal)>YEAR(NOW()) - 1');
			$libur = $this->db->get('ref_hari_libur')->result_array();
			foreach( $libur as $row ):
				$this->arr_hari_libur[] = 	$row['tanggal'];	
			endforeach;


		
    }

	public function index()
	{
		
		if(!$this->admin->logged_id())
		{
			redirect("login");
		}
	
	
	}
	

	public function validasi($satkersing,$perkara_id)
	{
		
		// cadangkan data current satkersing ke session agar ditangkap di do_upload
		$this->session->set_userdata('satkersing_1',$satkersing);
		$perkara_id_enc = $perkara_id;
		$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
		$this->banding->switch_db($satkersing);
		if(!$this->admin->is_login())
		{
			redirect("login");
		}
		
		if ( $this->session->userdata('role') == 'op_satker' )
		{
			die('Tidak diizinkan');
		}
	
		$data['content'] = "Welcome";
		$data['title'] = "Validasi e-doc Banding";
		$data_content['html_sidang_1'] = $this->_html_sidang_validasi($perkara_id);
		$data_content['ref_jenis_edoc_a'] = $this->banding->fetch_ref_edoc($perkara_id,$satkersing,'a');
		$data_content['ref_jenis_edoc_b'] = $this->banding->fetch_ref_edoc($perkara_id,$satkersing,'b');
		$data_content['ref_jenis_edoc_c'] = $this->banding->fetch_ref_edoc($perkara_id,$satkersing,'c');
		$data_content['ref_jenis_edoc_d'] = $this->banding->fetch_ref_edoc($perkara_id,$satkersing,'d');
		$data_content['banding_detil'] = $this->banding->banding_detil($perkara_id);
		$data_content['perkara_detil'] = $this->banding->perkara_detil($perkara_id);
		$data_content['perkara_id'] = $perkara_id_enc;
		
		$data_content['satkersing'] = $satkersing;
		$data['content'] = $this->load->view("f_validasi",$data_content,true);
		$this->load->view("template",$data);
	}
   
   
   

    

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
				$satkersing = $this->session->userdata('satkersing');

				// klo satkersing PTA MEDAN, berarti yang satkersingnya dari session
				$satkersing = ( $satkersing == 'ptamedan' ) ? $this->session->userdata('satkersing_1'):$satkersing;
				
				$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$_FILES["file_edoc"]['name'];
				
                //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc/';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']			= '10000';
                $config['max_width']  		= '3000';
                $config['max_height'] 		= '3000';
				//$config['encrypt_name'] = TRUE;
				$config['file_name']= $new_name;
				if ($_FILES["file_edoc"]['type'] == 'application/msword') {
					$config['allowed_types'] 	= 'doc|rtf|docx|rtf';
				}

				#print_r($config);
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file_edoc')) {
                        $up_data	 	= $this->upload->data();
						
						$data['perkara_id'] = $perkara_id;
						$data['jenis_edoc'] = $jenis_edoc;
						$data['satkersing'] = $satkersing;
						$data['file_edoc'] = $up_data['file_name'];
						$data['file_size'] = $up_data['file_size'];
						$data['file_type'] = $up_data['file_type'];
						$data['org_name'] = $up_data['client_name'];
						$data['val_edoc'] = null;
						#echo $data['file_type'];
						
						if  ($data['file_type'] == 'application/pdf' or $data['file_type'] == 'text/rtf' )
						{	
							// hapus file lama 
							#print_r($data);
							$this->banding->insert_edoc($data);
							echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload dan NOTIFIKASI Telah dikirim ke PIHAK</span></div>";
						
							// kalo yang diupload tt_daftar_banding, kirim notif ke pihak
							if ( $jenis_edoc == 'tt_daftar_banding'){
								
								$link = base_url('s/'.encode_url($satkersing,$perkara_id));

								$pesan = "Perkara Banding telah terdaftar. klik ".$link;
								
								$doc = $this->banding->get_dok_by_perkara_id($satkersing,$perkara_id,'hp_pembanding');
								
								if (count($doc[0])){
									$hp_pembanding = $doc[0]['org_name'];
									#$this->banding->sendWA($pesan,$hp_pembanding);
																}
								$doc = $this->banding->get_dok_by_perkara_id($satkersing,$perkara_id,'hp_terbanding');
								if (count($doc[0])){
									$hp_terbanding = $doc[0]['org_name'];
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
				$satkersing = $this->session->userdata('satkersing');
				$tanggal_sidang				= addslashes($this->input->post('tanggal_sidang')); 
				
				$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$tanggal_sidang.'_'.$_FILES["file_edoc"]['name'];
				 //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc/';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']			= '10000';
                $config['max_width']  		= '3000';
                $config['max_height'] 		= '3000';
				//$config['encrypt_name'] = TRUE;
				$config['file_name']= $new_name;
				
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file_edoc')) {
                        $up_data	 	= $this->upload->data();
						
						$data['perkara_id'] = $perkara_id;
						$data['jenis_edoc'] = $jenis_edoc;
						$data['satkersing'] = $satkersing;
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
	
	
	public function do_validasi()
	{
           if(!$this->admin->is_login())
			{
			redirect("login");
			}
		// validasi edoc yang ada,	
		          // save data baru
                 //ambil variabel post
				$id_edoc				= addslashes($this->input->post('id_edoc')); 
				$perkara_id				= addslashes($this->input->post('perkara_id')); 
				$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
				$satkersing				= addslashes($this->input->post('satkersing')); 
				$jenis_edoc				= addslashes($this->input->post('jenis_edoc')); 
				$select_validasi				= addslashes($this->input->post('select_validasi')); 
				$catatan_validasi				= addslashes($this->input->post('catatan_validasi')); 
				
				$data['id'] = $id_edoc;
				$data['perkara_id'] = $perkara_id;
				$data['satkersing'] = $satkersing;
				$data['jenis_edoc'] = $jenis_edoc;
				$data['val_edoc'] = $select_validasi;
				$data['catatan_pta'] = $catatan_validasi;
				$data['waktu_validasi'] = date('Y-m-d h:i:s');

				// send Notif
				if ( $select_validasi == 0 ){
					$pesan = "PTA Medan Menolak edoc ".$jenis_edoc." dengan catatan \n\r".$catatan_validasi;
					$this->notif_satker($satkersing,$pesan);
				}

				if ($this->banding->validasi_edoc($data))
					echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Validasi Tersimpan</span></div>";
				else 
					echo "<i class='fas fa-fw fa-check'></i> <span class='text-danger'>Validasi Gagal</span>";
	
	}


	public function do_validasi_sidang()
	{
           if(!$this->admin->is_login())
			{
			redirect("login");
			}
		// validasi edoc yang ada,	
		          // save data baru
                 //ambil variabel post
				$id_edoc				= addslashes($this->input->post('id_edoc')); 
				$perkara_id				= addslashes($this->input->post('perkara_id')); 
				$perkara_id = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
				$satkersing				= addslashes($this->input->post('satkersing')); 
				$jenis_edoc				= addslashes($this->input->post('jenis_edoc')); 
				$select_validasi		= addslashes($this->input->post('select_validasi')); 
				$catatan_validasi		= addslashes($this->input->post('catatan_validasi')); 
				$tgl_sidang				= addslashes($this->input->post('tgl_sidang')); 
				
				$data['perkara_id'] = $perkara_id;
				$data['satkersing'] = $satkersing;
				$data['jenis_edoc'] = $jenis_edoc;
				$data['val_edoc'] = $select_validasi;
				$data['catatan_pta'] = $catatan_validasi;
				$data['tanggal_sidang'] = $tgl_sidang;
				$data['waktu_validasi'] = date('Y-m-d h:i:s');

				// send Notif
				if ( $select_validasi == 0 ){
					$pesan = "PTA Medan Menolak edoc ".$jenis_edoc." utk Tgl Sidang". $tgl_sidang." dengan catatan \n\r".$catatan_validasi;
					$this->notif_satker($satkersing,$pesan);
				}

				if ($this->banding->validasi_edoc_sidang($data))
					echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Validasi Tersimpan</span></div>";
				else 
					echo "<i class='fas fa-fw fa-check'></i> <span class='text-danger'>Validasi Gagal</span>";
	
	}


	
	
	public function save_text(){
		if(!$this->admin->is_login())
			{
			redirect("login");
			}

		$perkara_id			= addslashes($this->input->post('perkara_id')); 
		$data['perkara_id'] = $this->encryption->decrypt(str_replace('__','/',$perkara_id));
		$data['satkersing']	= $this->session->userdata('satkersing');; 
		$data['jenis_edoc']	= addslashes($this->input->post('jenis_edoc')); 
		$data['org_name']		= addslashes($this->input->post('value')); 
		// save ke db	

		$this->banding->save_text_info($data);
		echo "<div class='highlight_fadeOut'><i class='fas fa-fw fa-check'></i> <span class='text-success'>Data Tersimpan</span></div>";
	}


	public function notif_satker($satkersing,$pesan){
		$this->db->where('satsing',$satkersing);
		$data = $this->db->get('satker')->result_array();
		if (isset($data)){
			$hp_admin = $data[0]['hp_admin'];
		}
		else{
			$hp_admin = "";			
		}
		//$this->banding->sendWA($pesan,$hp_admin);
	}		
}