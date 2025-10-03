<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_login','admin');
        $this->load->model('m_config');
		if(!$this->admin->logged_id())
		{
			redirect("login");
		}	
    }

	public function index()
	{
		if($this->admin->logged_id())
		{
			// $this->load->view("dashboard");
             $data_view['data'] = $this->m_config->get_config();

             $data['content'] = $this->load->view("frm_config",$data_view,true);
             $data['title'] = "Konfigurasi";
             $data['subtitle'] = "Pengaturan Aplikasi";
             $this->load->view("v_template_admin",$data);

		}else{

			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}


    public function users(){
        if ( $this->session->userdata('user_jabatan_id') <> 52 ) {
			die ('Akses ditolak');
		}
		$data_content['title'] = "Pengaturan User";
        $data_content['list_user'] = $this->m_config->list_user();
        $data['content'] = $this->load->view("l_user",$data_content,true);
        $this->load->view("template",$data);

    }

    public function user_edt($id){
        $data_content['l_jabatan'] = $this->m_config->list_jabatan();
        $data_content['l_atasan_langsung'] = $this->m_config->list_pejabat();
        $data_content['data'] = $this->m_config->get_user($id);
        $data_content['mode'] = "edt";
        if ($this->session->userdata('user_id') == 1 or ($this->session->userdata('user_jabatan_id') == 52 ))
        {
             $data['title'] = "Edit Data User";
			 $data['content'] = $this->load->view("f_user",$data_content,true);
            $this->load->view("template",$data);
        }
        else {
            echo "denied";exit;
        }

    }

 

    public function user_del($id){
		
        if ($this->session->userdata('user_id') == 1 or $this->session->userdata('user_id') == 5 )
        {
            $this->db->where("id",$id);
            $this->db->delete('t_user');
        }
        else {
            echo "denied";exit;
        }

         $this->session->set_flashdata('info', '<div class="alert alert-dismissable alert-success">
  <button type="button" class="close" data-dismiss="alert">x</button>
  Data Berhasil dihapus
</div>');
                     // redirect
                    // echo "add";
         redirect("config/users");

    }

    public function user_add(){
		$data_content['l_jabatan'] = $this->m_config->list_jabatan();
		 $data_content['l_atasan_langsung'] = $this->m_config->list_pejabat();
       
        $data_content['mode'] = "add";
        //echo $this->session->userdata('user_id');
		if ($this->session->userdata('user_jabatan_id') == 52 )
        {
            $data['title'] ="Penambahan Data";
			$data['content'] = $this->load->view("f_user",$data_content,true);
            $this->load->view("template",$data);
        }
        else {
            echo "denied";exit;
        }

    }

    public function user_save($act){
        $data['username'] = $this->input->post("username");
        $data['nama'] = $this->input->post("nama");
        $data['nip'] = $this->input->post("nip");
		$data['hp'] = $this->input->post("hp");
		$data['no_ktp'] = $this->input->post("no_ktp");
        $data['kode_jabatan'] = $this->input->post("kode_jabatan");
        $data['level'] = "user";
        $data['id_atasan'] = $this->input->post("id_atasan");
        $data['tempat_lahir'] = $this->input->post("tempat_lahir");
		$data['tgl_lahir'] = $this->input->post("tgl_lahir");
        $data['aktif'] = $this->input->post("aktif");
        if ( $this->input->post("password") != '-' && $this->input->post("password") == $this->input->post("password2") )
        {
            $data['password'] = md5($this->input->post("password"));
        }
        // klo act_edit
        if ($act == 'act_edt') {
         $this->db->where("id",$this->input->post("idp"));
		 #print_r($data);
         #echo "edit ".$this->input->post("idp");exit;
		 $this->db->update("t_user",$data);
        }
        else{
        $this->db->insert("t_user",$data);
        }

         $this->session->set_flashdata('info', '<div class="alert alert-dismissable alert-success">
  <button type="button" class="close" data-dismiss="alert">x</button>
  Data Berhasil disimpan
</div>');
                     // redirect
                    // echo "add";
         redirect("config/users");



    }

    public function delete($id){

        $this->jadwal->delete($id);
        $this->session->set_flashdata('info', 'Data Jadwal Kegiatan berhasil dihapus');
                     // redirect
                    // echo "add";
         redirect("jadwal");
    }

}
