<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();

		$this->load->model('monitoring_model','monitoring');
        $this->load->model('m_akses','admin');
        if(!$this->admin->is_login())
		{
			redirect("login");
		}
      
	}



	public function mon_01($tahun=null,$type=null,$satker=null){
		
        $tahun = ($tahun == '' ) ? date('Y') : $tahun;
        if( $type == '' ){
            $data_content['data_mon'] = $this->monitoring->banding_kasasi_tahun( $tahun);
            $data['title'] = "Monitoring Banding yang melakukan Kasasi";
            $data_content['tahun'] =  $tahun;
            $data['content'] = $this->load->view("v_mon01",$data_content,true);
        }

        else if ( $type == 'banding' ){
            $data_content['data_mon'] = $this->monitoring->list_banding($satker,$tahun);
            $data['title'] = "Monitoring Banding";
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil",$data_content,true); 
        } 
        else if ( $type == 'kasasi_skrg' ){
            $data_content['data_mon'] = $this->monitoring->list_kasasi_skrg($satker,$tahun);
            $data['title'] = "Monitoring Banding yang Kasasi di tahun berjalan";
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil",$data_content,true); 
        } 
        else if ( $type == 'kasasi_depan' ){
            $data_content['data_mon'] = $this->monitoring->list_kasasi_depan($satker,$tahun);
            $data['title'] = "Monitoring Banding yang Kasasi di tahun berikutnya";
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil",$data_content,true); 
        } 
         $this->load->view("template",$data);
        
	}



    public function mon_02($tahun=null,$type=null,$satker=null){
		
        $tahun = ($tahun == '' ) ? date('Y') : $tahun;
        if( $type == '' ){
            $data_content['data'] = $this->monitoring->banding_berjalan($tahun);
            $data['title'] = "Monitoring Banding belum Putus";
            // if ( $this->session->userdata('is_verifikator') == 1 ){
            //     $data_content['sub_title'] = "<span class='text-danger'>Anda adalah Verifikator sehingga dapat melihat semua perkara</span>"; 
            // }
            // else {
            //     $data_content['sub_title'] = "<span class='text-danger'>hanya menampilkan perkara berjalan yang anda tangani</span><br />"; 
            // }
            $data_content['tahun'] =  $tahun;
            $data_content['badge_text'] = " menunggu validasi";
            $data['content'] = $this->load->view("list_banding_pta",$data_content,true);
        }

        else if( $type == 'menunggu_revisi' ){
            $data['title'] = "Monitoring Banding Menunggu Perbaikan Edoc";
            $data_content['data'] = $this->monitoring->menunggu_revisi();            
            $data_content['badge_text'] = " menunggu revisi";
            $data_content['tahun'] =  $tahun;
            
            $data['content'] = $this->load->view("list_banding_pta",$data_content,true);
        }

        else if ( $type == 'banding' ){
            $data_content['data_mon'] = $this->monitoring->list_banding_berjalan($satker,$tahun);
            $data['title'] = "Monitoring Banding Masih Berjalan (belum putus)";
            $data_content['tahun'] = date('Y');
            $data['content'] = $this->load->view("v_mon01_detil",$data_content,true); 
        } 
       
         $this->load->view("template",$data);
        
	}

    public function req_unlock(){
        $data_content['data'] = $this->monitoring->list_req_unlock();
        $data['title'] = "Permintaan unlock upload edoc";
        $data['content'] = $this->load->view("list_req_unlock",$data_content,true); 
        $this->load->view("template",$data);

    }

	

}
