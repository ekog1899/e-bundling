<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_login','admin');
        $this->load->model('m_jadwal','jadwal');
    }

	public function index()
	{
		if($this->admin->logged_id())
		{

			// $this->load->view("dashboard");
             $data_view['data'] = $this->jadwal->list_kegiatan();

             $data['content'] = $this->load->view("v_jadwal_list",$data_view,true);
             $data['title'] = "Jadwal Kegiatan";
             $data['subtitle'] = "List Jadwal Kegiatan";
             $this->load->view("v_template_admin",$data);

		}else{

			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}

	public function add($id="")
	{
             if ($this->input->post('submit')=='Simpan' && $this->input->post('kegiatan_id')==''){
                 // save data baru
                 $data['kegiatan'] = $this->input->post('kegiatan');
                 $data['dihadiri'] = $this->input->post('dihadiri');
                 $data['lokasi'] = $this->input->post('lokasi');
                 // echo $this->input->post('waktu');
                 $data['waktu'] = date_format(DateTime::createFromFormat('d-m-Y', $this->input->post('waktu')),"Y-m-d") ;
                 // print_r($data);
                 if ( $this->jadwal->add($data) ){
                     $this->session->set_flashdata('info', 'Data Jadwal Kegiatan berhasil disimpan');
                     // redirect
                    // echo "add";
                     redirect("jadwal");
                 }

             }
             else if ($this->input->post('submit')=='Simpan' && $this->input->post('kegiatan_id')<>''){
                 $data['id'] = $this->input->post('kegiatan_id');
                 $data['kegiatan'] = $this->input->post('kegiatan');
                 $data['dihadiri'] = $this->input->post('dihadiri');
                 $data['lokasi'] = $this->input->post('lokasi');
                 $data['waktu'] = date_format(DateTime::createFromFormat('d-m-Y', $this->input->post('waktu')),"Y-m-d") ;
                 if ( $this->jadwal->update($data) ){
                     $this->session->set_flashdata('info', 'Data Jadwal Kegiatan berhasil diupdate');
                     // redirect
                    // echo "add";
                     redirect("jadwal");
                 }

             } else {
                 $data_form['kegiatan_id'] = $id;
                 $data_form['kegiatan'] = "";
                 $data_form['dihadiri'] = "";
                 $data_form['lokasi'] = "";
                 $data_form['waktu'] = "";

                 $keg = $this->jadwal->get_kegiatan($id);
                 if ($keg) {
                     $data_form['kegiatan'] = $keg->kegiatan;
                     $data_form['dihadiri'] = $keg->dihadiri;
                     $data_form['lokasi'] = $keg->lokasi;
                     $data_form['waktu'] = date_format(DateTime::createFromFormat('Y-m-d', $keg->waktu),"d-m-Y") ;;
                 }
                 $data['content'] = $this->load->view("frm_jadwal",$data_form,true);
                 $data['title'] = "Jadwal Kegiatan";
                 $data['subtitle'] = "List Jadwal Kegiatan";
                 $this->load->view("v_template_admin",$data);
             }
	}

    public function update($id){
        $this->add($id);
    }

    public function delete($id){

        $this->jadwal->delete($id);
        $this->session->set_flashdata('info', 'Data Jadwal Kegiatan berhasil dihapus');
                     // redirect
                    // echo "add";
         redirect("jadwal");
    }

}
