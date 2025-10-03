<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chpwd extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('m_akses','admin');
		$this->load->model('profil_model','profil');
		
    }

	public function index()
	{
		# print_r($this->session->userdata());exit;
		if(!$this->admin->is_login())
		{
		redirect("login");
		}
		
		#print_r($this->session->userdata('satkersing'));
		// klo op_satker
		$this->load->view('f_password');
		

	}

	

}
