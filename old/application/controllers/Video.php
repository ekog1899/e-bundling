<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_login','admin');
        $this->load->model('m_video','video');
    }

	public function index()
	{
		if($this->admin->logged_id())
		{

			// $this->load->view("dashboard");
             $data_view['data'] = $this->video->list_video();

             $data['content'] = $this->load->view("list_video",$data_view,true);
             $data['title'] = "Video Manajemen";
             $data['subtitle'] = "Pengaturan Video";
             $this->load->view("v_template_admin",$data);

		}else{

			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}

	public function add($id="")
	{
             if ($this->input->post('submit')=='Simpan'){
                 // save data baru
                $data['ket'] = $this->input->post('ket');
                $config['upload_path']          = './videos/';
                $config['allowed_types']        = 'mp4';
                $config['max_size']             = 50000000;
                $newfilename =  time().".mp4";
                 $config['file_name']            = $newfilename;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('file')){
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                     $this->session->set_flashdata('info', 'Video Gagal diupload');
                   //redirect("banner/add");
                }else{
                    //$data = array('upload_data' => $this->upload->data());
                    $data['file'] = $newfilename;
                    $this->video->add($data);
                    $this->session->set_flashdata('info', 'Video berhasil disimpan');
                    redirect("video");
                }



             } else {

                 $data['content'] = $this->load->view("frm_video","",true);
                 $data['title'] = "Banner Manajemen";
                 $data['subtitle'] = "Form Tambah Banner";
                 $this->load->view("v_template_admin",$data);
             }
	}


    public function delete($id){

        //hapus file dulu
        $banner = $this->video->get_banner($id);
        $file = './videos/'.$banner->file;
        if(is_file($file)) {
            unlink($file); // delete file
            $this->video->delete($id);
            $this->session->set_flashdata('info', 'Data Video Kegiatan berhasil dihapus');
        }

                     // redirect
                    // echo "add";
         redirect("video");
    }

}
