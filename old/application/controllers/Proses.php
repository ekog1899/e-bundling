<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
			$this->load->model('m_akses','admin');
			$this->load->model('banding_model','banding');
			$this->banding->init();
			$this->load->helper('my');
			$this->keyword = "";
    }

	public function index()
	{
		
		if(!$this->admin->logged_id())
		{
			redirect("login");
		}
	
	
		$data['content'] = "Welcome";
		$data['title'] = "Banding Belum Kirim";
		$data['profile'] = $this->session->userdata();
		$data_content['banding_blm_kirim'] = $this->banding->belum_kirim();
		
		$data['content'] = $this->load->view("dashboard",$data_content,true);
		$this->load->view("template",$data);
	}
	
	

	
	public function validasi($satkersing,$perkara_id)
	{
		
		
		if(!$this->admin->is_login())
		{
			redirect("login");
		}
	
	
		$data['content'] = "Welcome";
		$data['title'] = "Proses Banding";
		$data_content['ref_jenis_edoc_a'] = $this->banding->fetch_ref_edoc($perkara_id,$this->session->userdata('satkersing'),'a');
		$data_content['ref_jenis_edoc_b'] = $this->banding->fetch_ref_edoc($perkara_id,$this->session->userdata('satkersing'),'b');
		
		$data_content['banding_detil'] = $this->banding->banding_detil($perkara_id);
		$data_content['perkara_detil'] = $this->banding->perkara_detil($perkara_id);
		$data_content['perkara_id'] = $perkara_id;
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
				$jenis_edoc				= addslashes($this->input->post('jenis_edoc')); 
				$satkersing = $this->session->userdata('satkersing');
				
				$new_name = $satkersing.'_'.$perkara_id.'_'.$jenis_edoc.'_'.$_FILES["file_edoc"]['name'];
				
                //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc';
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
						$data['file_edoc'] = $up_data['file_name'];
						$data['file_size'] = $up_data['file_size'];
						$data['file_type'] = $up_data['file_type'];
						$data['org_name'] = $up_data['client_name'];
						$data['val_edoc'] = null;
						
						if  ($data['file_type'] == 'application/pdf')
						{	
							// hapus file lama 
							$this->banding->insert_edoc($data);
							echo "<i class='fas fa-fw fa-check'></i> <span class='text-success'>file <a href=\"javascript:popup_edoc('".$up_data['file_name']."')\">".$up_data['client_name']."</a> berhasil diupload</span>";
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
				if ($this->banding->validasi_edoc($data))
					echo "<i class='fas fa-fw fa-check'></i> <span class='text-success'>Validasi Tersimpan</span>";
				else 
					echo "<i class='fas fa-fw fa-check'></i> <span class='text-danger'>Validasi Gagal</span>";
	
		} 
	  
    }
