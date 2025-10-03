<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_Masuk extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_surat','surat');
        $this->load->model('m_login','admin');
		$this->load->model('sms_model','sms');
        $this->load->helper('my');
        $this->keyword = "";
    }

	public function index()
	{
		if($this->admin->logged_id())
		{

			#echo $this->keyword;

            $jumlah_data = $this->surat->jml_surat();
            $this->load->library('pagination');
            $config['base_url'] = base_url().'surat_masuk/index/';
            $config['total_rows'] = $jumlah_data;
            $config['per_page'] = 10;
            $config['first_link']       = 'First';
            $config['last_link']        = 'Last';
            $config['next_link']        = 'Next';
            $config['prev_link']        = 'Prev';
            $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
            $config['full_tag_close']   = '</ul></nav></div>';
            $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
            $config['num_tag_close']    = '</span></li>';
            $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
            $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
            $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
            $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
            $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
            $config['prev_tagl_close']  = '</span>Next</li>';
            $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
            $config['first_tagl_close'] = '</span></li>';
            $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
            $config['last_tagl_close']  = '</span></li>';
            $from =  empty ($this->uri->segment(3))?0:$this->uri->segment(3);
            $this->pagination->initialize($config);
            $data_content['data'] = $this->surat->list_surat($config['per_page'],$from,$this->keyword);
            $data_content['pagination'] = $this->pagination->create_links();
            #print_r($data_content['data']);exit;

             $data['content'] = $this->load->view("l_surat_masuk",$data_content,true);

             $this->load->view("template",$data);

		}else{

			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}

    public function cari($offset){
        $this->keyword = $this->input->get('q');
        $this->index();
    }

    public function disposisi($id){

        $data_content['list_disposisi'] = $this->surat->list_disposisi($id);
        #print_r( $data_content['list_disposisi']);
        $det_surat = $this->surat->get_surat($id);
        $data_content['judul_surat'] = $det_surat->isi_ringkas;
        $data_content['tgl_surat'] = $det_surat->tgl_surat;
         $data_content['file'] = $det_surat->file;
        $data_content['l_pejabat_disposisi'] = $this->surat->list_pejabat_disposisi();
        #print_r($data_content['l_pejabat_disposisi']);exit;
         $data['content'] = $this->load->view("l_disposisi",$data_content,true);
         $data['title'] = "Banner Manajemen";
         $data['subtitle'] = "Pengaturan Banner Kiri";
         $this->load->view("template",$data);

    }

	public function add( )
	{
            if ($this->input->post('act')=='act_add' or $this->input->post('act')=='act_edt'){
                 // save data baru
                 //ambil variabel post
                $idp					= addslashes($this->input->post('idp'));
                $no_agenda				= addslashes($this->input->post('no_agenda'));
                $indek_berkas			= addslashes($this->input->post('indek_berkas'));
                $kode					= addslashes($this->input->post('kode'));
                $dari					= addslashes($this->input->post('dari'));
                $no_surat				= addslashes($this->input->post('no_surat'));
                $tgl_surat				= addslashes($this->input->post('tgl_surat'));
                $uraian					= addslashes($this->input->post('uraian'));
                $ket					= addslashes($this->input->post('ket'));
                $diteruskan_ke			= $this->input->post('diteruskan_ke');
                $cari					= addslashes($this->input->post('q'));

                //$d_diteruskan_ke = "0";
                $config['upload_path'] 		= './uploads/surat_masuk';
                $config['allowed_types'] 	= 'pdf';
                $config['max_size']			= '10000';
                $config['max_width']  		= '3000';
                $config['max_height'] 		= '3000';



                $this->load->library('upload', $config);

                if ($this->input->post('act')=='act_add') {
                    if ($this->upload->do_upload('file_surat')) {
                        $up_data	 	= $this->upload->data();
                        $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '".$up_data['file_name']."', '".$this->session->userdata('user_id')."','$diteruskan_ke',false)");
                        $this->session->set_flashdata("k", "<div class=\"alert alert-success\" id=\"alert\">Data Berhasil disimpan. ".$this->upload->display_errors()."</div>");

                    } else {
                        die("Error, file gagal diupload");// $this->db->query("INSERT INTO t_surat_masuk VALUES (NULL, '$kode', '$no_agenda', '$indek_berkas', '$uraian', '$dari', '$no_surat', '$tgl_surat', NOW(), '$ket', '', '".$this->session->userdata('admin_id')."','$d_diteruskan_ke')");
                    }
                    $insert_id = $this->db->insert_id();
                    #Kirim SMS Notifikasi sesuai Jalur
                    /* LOOP */
                            // ambil id berdasarkan id_pejabat
                    $pelaksana = $this->admin->get_user_profile_by_id_jabatan($diteruskan_ke)->row();
                    $sms['DestinationNumber'] = $pelaksana->hp;
                    $sms['CreatorID'] = $this->session->userdata('user_username');
                    $sms['TextDecoded'] = 'Surat Masuk No.'.$no_agenda.' : '.substr($uraian,0,70);
                    $this->sms->send($sms);
                }
                else
                // klo file gak ada, abaikan upload
                {
                    if ($this->upload->do_upload('file_surat')) {
                        $up_data	 	= $this->upload->data();
                        $data['file'] = $up_data['file_name'];


                    }
                     $data['no_agenda'] = $no_agenda;
                     $data['dari'] = $dari;
                    $data['isi_ringkas'] = $uraian;
                     $data['kode'] = $kode;
                     $data['no_surat'] = $no_surat;
                     $data['tgl_surat'] = $tgl_surat;
                     $data['tgl_surat'] = $tgl_surat;
                     $this->db->where("id",$this->input->post('idp'));
                     $this->db->update('t_surat_masuk',$data);

                }
                redirect('surat_masuk');

             } else {
                 $data_content['act'] = "Simpan";
                $data_content['mode'] = "act_add";
                 $data['content'] = $this->load->view("f_surat_masuk",$data_content,true);

                 $this->load->view("template",$data);
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
