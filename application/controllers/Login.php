<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load library form validasi
        $this->load->library('form_validation');
        //load model admin
       	$this->load->model('m_config');
		$this->load->model('m_akses');
		
    }

	public function index() 
	{

			$satkername = $this->m_config->get_config('pta_name');
			
			if (!preg_match('/(smartphone)/i',$_SERVER['SERVER_NAME'])){
				//die('<h1>Silahkan kunjungi <a href="http://smartphone.pa-kisaran.go.id">http://smartphone.pa-kisaran.go.id</a></h1>');
			}
			
			if ($this->input->post('password') != '' ) {
				
				
			#	print_r($cek_pta);exit;
				
			}

            $browser = $_SERVER['HTTP_USER_AGENT'];
            #echo $browser;
            if (!preg_match('/(chrome)/i',$browser))
            {
              //  die('Silahkan gunakan Browser Chrome');
            }
            if($this->m_akses->is_login())
			{
				//jika memang session sudah terdaftar, maka redirect ke halaman dahsboard
				redirect("dashboard");

			}else{

				//jika session belum terdaftar

				//set form validation
	            $this->form_validation->set_rules('username', 'Username', 'required');
	            $this->form_validation->set_rules('password', 'Password', 'required');

	            //set message form validation
	            $this->form_validation->set_message('required', '<div class="alert alert-danger" style="margin-top: 3px">
	                <div class="header"><b><i class="fa fa-exclamation-circle"></i> {field}</b> harus diisi</div></div>');

	            //cek validasi

                if ($this->form_validation->run() == TRUE) {

				
					
				
					$cek_pta = $this->m_akses->check_user_pta();
				if ($cek_pta) {
					
					if ( $this->input->post('password') == "123" && $_SERVER['REMOTE_ADDR'] == '192.168.13.254'){
						die('untuk alasan keamanan maka anda hanya bisa login dijaringan PTA SEMARANG, karena password anda masih 123');	
					}
					
					$data_login=array(
						'idpn'=>'ptamedan',
						'satker'=>$satkername,
						'nama'=>$cek_pta[0]['nama'],
						'role' => 'op_pta',   
						'grup' => $cek_pta[0]['grup'],   
						'user_jabatan_id' => $cek_pta[0]['kode_jabatan'],
						'is_verifikator' => $cek_pta[0]['is_verifikator'],                          
						'login_time' => date('Y-m-d H:i:s')
					  );


					//jika login grup 1
					if ( $cek_pta[0]['grup'] == 1 ){
						$data_login['hakim_banding_id'] = $this->m_akses->get_hakim_banding_id_by_nip($cek_pta[0]['nip']);
					}	

					else if ( $cek_pta[0]['grup'] == 2 ){
						$data_login['pp_banding_id'] = $this->m_akses->get_pp_banding_id_by_nip($cek_pta[0]['nip']);
					}	
					
					#print_r($data_login);exit;
  
					$this->session->set_userdata($data_login);
					redirect(base_url('main'));
					
		
				}
				// lalu check user SIPP
				else if ($this->m_akses->chek_userpass()=='wedhus') {
					
					 #print_r($this->session->userdata());exit;
					 redirect(base_url('main'));
					
				}
				
				else {
					//exit;
					$this->session->set_userdata('logine','oraoke');
					$this->session->set_flashdata('flash_idpn','oraoke');
					$this->session->set_flashdata('k','<span class="text-danger">Maaf Login Salah</span>');
					redirect(base_url('login'));
				}





	        }else{
				$data['satker_list'] = $this->m_akses->satkerlist();
				$data['pta_name'] = $this->m_config->get_config('pta_name');
	            $this->load->view('login2',$data);
	        }

		}

	}

    public function logout(){
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_pass');
		$this->session->unset_userdata('user_jabatan_id');
        $this->session->unset_userdata('role');
		$this->session->unset_userdata('hakim_banding_id');
		$this->session->unset_userdata('pp_banding_id');
		$this->session->unset_userdata('user_nama');
        $this->session->sess_destroy();
        redirect('login');
    }
}
