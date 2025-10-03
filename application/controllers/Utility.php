<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utility extends CI_Controller {

    public function index() {
        if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
        $data['content'] = $this->load->view('f_backup','',true);
        $this->load->view('template',$data);
	}


    public function backup() {
		$this->load->dbutil();
		$this->load->helper('file');

		$config = array(
			'format'	=> 'zip',
			'filename'	=> 'database.sql'
		);

		$backup = $this->dbutil->backup($config);
		$fname = 'backup-'.date("ymdH").'-db.zip';
		$save = FCPATH.'backup/'.$fname;

		write_file($save, $backup);

        $this->load->helper('download');
        force_download($fname, $backup);
	}
}
