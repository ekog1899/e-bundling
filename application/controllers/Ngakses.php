<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Ngakses extends CI_Controller {

    private $secretkey = '';

    function __construct() {
        parent::__construct();
      }


    public function index() {
       // redirect(base_url());
    }

    //method untuk melihat token pada user
    public function validasiuser(){
		
        $this->load->model('m_akses');
        // check dulu PTA
        if ( $this->input->post('username') == 'ptasemarang' && $this->input->post('password') == 'bismillahxx' ){
            $data_login=array(
                'satkersing'=>'ptasemarang',
                'satker'=>"PTA Semarang",
                'nama'=>"Admin PTA Semarang",
                'role' => 'op_pta',                        
                'login_time' => date('Y-m-d H:i:s')
              );

            $this->session->set_userdata($data_login);
            redirect(base_url('main'));

        }
        else if ($this->m_akses->chek_userpass()=='wedhus') {
            
		     #print_r($this->session->userdata());exit;
			 redirect(base_url('main'));
			
        }
        
        else {
            //exit;
            $this->session->set_userdata('logine','oraoke');
			$this->session->set_flashdata('k','Login Salah');
            redirect(base_url('login'));
        }
    }


    public function tokencek(){

        $token=base64_encode($this->secretkey);
        $jwt = $this->session->userdata('tokencuk');

        try {

            $decode = JWT::decode($jwt,$this->secretkey,array('HS256'));

            if ($decode->idtoken==$token) {
                return true;
            } else {
                redirect(base_url());
            }

        } catch (Exception $e) {

            redirect(base_url());
            exit;
        }
    }
}