<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('absen_model','absen');
        $this->load->model('m_login','admin');
		  $this->load->helper('my');
        $this->keyword = "";
		
		
		
    }

	public function index( $tgl = null )
	{
		if($this->admin->logged_id())
		{
			
			$this->presensi($tgl);

		}else{
			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}
	
	public function presensi(){
		if(!$this->admin->logged_id())
		{
			redirect("login");
		}

		$tgl = @($_GET['tgl']);
		if ( $tgl == null ){ $tgl = date('d/m/Y'); }
		$data_content['lap'] = $this->absen->fetch_presensi_all($tgl);
		$data['content'] = $this->load->view("lap_absen",$data_content,true);
		$data['title'] = "Laporan Absen";
		$this->load->view("template",$data);
	}
	
	
	public function print_to($type,$grup){
		$tgl = @($_GET['tgl']);
		if ( $tgl == null ){ $tgl = date('d/m/Y'); }
		
		// petugas absen
		
		$this->db->where('kode_jabatan',52);
		$petugas = $this->db->get('intra.t_user')->row();
		$data['lap'] = $this->absen->fetch_presensi_all($tgl,$type,$grup);
		
		$data['type'] = $type;
		$data['kepeg'] = $petugas->nama;
		$data['nip_kepeg'] = $petugas->nip;
		
		#print_r($data['lap']);exit;
		
		$content = $this->load->view('lap_absen_type',$data,TRUE);
		echo $content; exit;
		
		
	}	
	
   

    public function detil($user_id){
		if(!$this->admin->logged_id())
		{
			redirect("login");
		}
		
		$data_content['data'] = $this->edoc->dataumum($user_id);
		$data_content['edoc_pendidikan'] = $this->edoc->list_edoc($user_id,'pendidikan');
		$data_content['edoc_sk'] = $this->edoc->list_edoc($user_id,'sk');
		$data_content['edoc_penghargaan'] = $this->edoc->list_edoc($user_id,'penghargaan');
		$data_content['mode'] = 'edt';
		$data['content'] = $this->load->view("f_edoc",$data_content,true);
        $data['title'] = "Edoc";
        $this->load->view("template",$data);

    }

	public function save_edoc( )
	{
        if(!$this->admin->logged_id())
		{
			redirect("login");
		}

		if ($this->input->post('act')=='act_add' or $this->input->post('act')=='act_edt'){
                 // save data baru
                 //ambil variabel post
				$user_id				= addslashes($this->input->post('user_id')); 
                $edoc_col1				= addslashes($this->input->post('edoc_col1'));
                $edoc_col2					= addslashes($this->input->post('edoc_col2'));
                $edoc_col3					= addslashes($this->input->post('edoc_col3'));
				$kategori					= addslashes($this->input->post('kategori'));
               
                //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/edoc';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']			= '10000';
                $config['max_width']  		= '3000';
                $config['max_height'] 		= '3000';
				echo "here";


                $this->load->library('upload', $config);

                if ($this->input->post('edoc_id')=='') {
                    if ($this->upload->do_upload('edoc_file')) {
                        $up_data	 	= $this->upload->data();
                        $this->db->query("INSERT INTO t_edoc VALUES (NULL, '$user_id', '$kategori','$edoc_col1', '$edoc_col2', '$edoc_col3', '".$up_data['file_name']."')");
                        $this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data Berhasil disimpan. ".$this->upload->display_errors()."</div>");

                    } else {
                        die("Error, file gagal diupload");// $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
                    }
                   
                }
                else
                // klo file gak ada, abaikan upload
                {
                    if ($this->upload->do_upload('edoc_file')) {
                        $up_data = $this->upload->data();
                        $data['file_edoc'] = $up_data['file_name'];
                    }
                     $data['edoc_col1'] = $edoc_col1;
                     $data['edoc_col2'] = $edoc_col2;
					 $data['edoc_col3'] = $edoc_col3;
					 
                     $this->db->where("id",$this->input->post('edoc_id'));
                     $this->db->update('t_edoc',$data);

                }
                //redirect('surat_masuk');

             } else {
                 $data_content['act'] = "Simpan";
                $data_content['mode'] = "act_add";
                 $data['content'] = $this->load->view("f_surat_masuk",$data_content,true);

               //  $this->load->view("template",$data);
             }
	}
	
	/** upload foto profile **/
	
	public function save_dataumum(){
				
				$user_id				= addslashes($this->input->post('user_id')); 
                 
                //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/photo';
                $config['allowed_types'] 	= 'jpeg|jpg';
                $config['max_size']			= '1000';
                $config['max_width']  		= '500';
                $config['max_height'] 		= '800';
				echo "here";


                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo_file')) {
                   $up_data	 	= $this->upload->data();
				   $data['photo_src'] = $up_data['file_name'];
                   $this->db->where("id",$this->input->post('user_id'));
                   $this->db->update('t_user',$data);
				   $this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data Berhasil disimpan. ".$this->upload->display_errors()."</div>");
					redirect(base_url('edoc/detil/'.$user_id));
				   
                } else {
                        die("Error, file gagal diupload");// $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
                }
            
	}

    public function edt($id){

        $this->db->where('id',$id);
        $data_content['data']= $this->db->get('t_surat_masuk')->row();
        $data_content['mode'] = "edt";
        $data_content['act'] = "Update";
        $data['content'] = $this->load->view("f_surat_masuk",$data_content,true);
        $this->load->view("template",$data);
     }

    public function del($id){

        //hapus file dulu
        $this->db->where('id',$id);
        $this->db->delete('t_surat_masuk');
        $this->session->set_flashdata('info', 'Data Surat Masuk berhasil dihapus');


                     // redirect
                    // echo "add";
         redirect("surat_masuk");
    }

    public function add_disposisi($id_surat)
	{
            if ( $this->input->post('act')=='act_add' ){
                 // iklo tujuan == arsipkan
                if ( $this->input->post('id_jabatan_tujuan') == 'ARSIPKAN' ){
                    $id_pelaksana = 0;
                    $id_jab_pelaksana = 0;
                    // update diarsipkan =1
                    $this->db->where('id',$id_surat);
                    $this->db->update('t_surat_masuk',array(diarsipkan=>1));


                }
                else {
                 $pelaksana = $this->admin->get_user_profile_by_id_jabatan($this->input->post('id_jabatan_tujuan'))->row();
			     $id_pelaksana = $this->input->post('id_jabatan_tujuan');
			     $id_jab_pelaksana = $pelaksana->kode_jabatan;
			    }
                $pengirim = $this->admin->get_user_profile_by_id($this->session->userdata('user_id'))->row();
			     $id_pengirim = $pengirim->id;
			     $id_jab_pengirim = $pengirim->kode_jabatan;

                #$data['kpd_yth'] = $pelaksana->nama;
                $data['id_surat'] = $id_surat;
                $data['isi_disposisi'] = $this->input->post('isi_disposisi');
                $data['id_pelaksana'] = $id_pelaksana;
                $data['id_pengirim'] = $id_pengirim;
                $data['id_jab_pelaksana'] = $id_jab_pelaksana;
                $data['id_jab_pengirim'] = $id_jab_pengirim;
                $data['waktu_input'] = date('Y-m-d H:i:s');
				
				// send SMS
				$det_surat = $this->surat->get_surat($id_surat);
				$this->surat->save_disposisi($data);
                #print_r($pelaksana);exit;
				if ( $pelaksana->hp )
                {
                    $dataSMS['DestinationNumber']=$pelaksana->hp;
				    $dataSMS['TextDecoded']='Disposisi Surat: '.substr($det_surat->isi_ringkas,0,30);
				    $this->sms->send($dataSMS);
                }
                redirect("surat_masuk/disposisi/".$id_surat);
            }
    }
    }
