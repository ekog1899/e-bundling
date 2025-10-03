<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draft extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();
		$this->load->model('draft_model','draft');
		$this->load->model('sms_model','sms');
	#	$this->load->model('notif_model','notif2')
        $this->load->library('encrypt');
        $this->load->library('my_encrypt');
        $this->load->helper('my');
    }


	public function index() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {

			$datacontent['l_draft'] = $this->draft->list_draft();

            $jumlah_data = count($this->draft->list_draft());
            $this->load->library('pagination');
            $config['base_url'] = base_url().'draft/index/';
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
            $datacontent['l_draft'] = $this->draft->list_draft($config['per_page'],$from,$this->keyword);
            $datacontent['pagination'] = $this->pagination->create_links();


			$a['content'] = $this->load->view('l_draft', $datacontent,TRUE);

			$this->load->view('template', $a);
		}

	}

	public function create() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$datacontent['l_template'] = $this->draft->list_template();
            $datacontent['l_pejabat'] = $this->draft->list_pejabat();
			#print_r($datacontent['l_template']);
			$a['content'] = $this->load->view('f_draft2', $datacontent,TRUE);
			$this->load->view('template', $a);
		}

	}

	public function save(){
		$id_draft = $this->input->post('id_draft');
		$id_draft_revisi =  $this->input->post('id_draft_revisi');
		$data['perihal'] = $this->input->post('perihal');
		$data['kepada'] = $this->input->post('kepada');
        $data['id_konseptor'] = $this->session->userdata('user_id');
        $data['id_korektor'] = $this->input->post('id_korektor');
		$data['id_pejabat'] = $this->input->post('id_pejabat');
        $data['kertas'] = $this->input->post('kertas');

		$id_template = $this->input->post('template');

		// if id_draft == ''
		if ( $id_draft == '' ){
			$this->db->insert('t_draft',$data);
			$data_revisi['id_draft'] = $this->db->insert_id();

			// ambil HTML isi dari template
			$data_revisi['isi'] = $this->draft->get_html_template_by_id($id_template);

            // ambil data penanda tangan
            $data_pejabat = $this->draft->get_profile_by_id($data['id_pejabat']);

			$data_revisi['perihal'] = $data['perihal'];
			$data_revisi['kepada'] = "Yth. ".$data['kepada']."<br />di - Tempat";
            $data_revisi['sifat'] = $this->input->post('sifat');
            $data_revisi['lampiran'] = "-";
            $data_revisi['tembusan'] = "Tembusan <ol><li>Arsip<br></li></ol>	";
            $data_revisi['tgl'] = "SEMARANG, ".tanggal_bulan(date('d-m-Y'));
			;
			$data_revisi['nomor_surat'] = ".........../TU-201/J.1/".date('Y');
            $data_revisi['ttd'] = $data_pejabat->nama_jabatan.",<br /><br /><br />".$data_pejabat->nama."<br />NIP.".$data_pejabat->nip;
			// insert ke draft_revisi
			$this->db->insert('t_draft_revisi',$data_revisi);
			// set session
			$id_draft_revisi = $this->db->insert_id();
			$this->session->set_userdata("");
			// return to client
			$ret['id_draft'] = $data_revisi['id_draft'];
			$ret['msg'] = 'Draft berhasil dibuat';
			$ret['html_template'] = $data_revisi['isi'];
			$ret['kepada'] = $data_revisi['kepada'];
			$ret['perihal'] = $data['perihal'];

			$ret['nomor_surat'] = $data_revisi['nomor_surat'] ;
			$ret['id_draft_revisi'] = $id_draft_revisi;
            $this->session->set_flashdata('info','Konsep Baru berhasil disimpan');
			redirect('draft');


		}	else{
			$data['perihal'] = $this->input->post('perihal');
			$data['kepada'] = $this->input->post('kepada');
			$this->db->where('id',$id_draft);
			$this->db->update('t_draft',$data);

			$this->session->set_flashdata('info','Data berhasil disimpan');
			redirect('draft');
		}
		#header('Content-Type: application/json');
		#echo json_encode($ret);

	}

	public function save_rev(){
		$id_draft_revisi =  $this->input->post('id_draft_revisi');
		$data['perihal'] = $this->input->post('perihal');
		$data['kepada'] = $this->input->post('kepada');
        $data['sifat'] = $this->input->post('sifat');
		$id_template = $this->input->post('template');

		$data['lampiran'] = $this->input->post('lampiran');
		$data['kepada'] = $this->input->post('kepada');
		$data['ttd'] = $this->input->post('ttd');
		$data['isi'] = $this->input->post('isi');
        $data['tgl'] = $this->input->post('tgl');
        $data['tembusan'] = $this->input->post('tembusan');
		$data['nomor_surat'] = $this->input->post('nomor_surat');
		$this->db->where('id',$id_draft_revisi);
		if ($this->db->update('t_draft_revisi',$data) )
			$ret['msg'] = "Perubahan Tersimpan";
		else
			$ret['msg'] = "Gagal Tersimpan,silahkan coba lagi";
		header('Content-Type: application/json');
		echo json_encode($ret);

	}


	public function edit($id_draft){
        $id_draft_enc = $id_draft;
        $id_draft = $this->my_encrypt->decode($id_draft);

		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$datacontent['l_template'] = $this->draft->list_template();
            $datacontent['l_pejabat'] = $this->draft->list_pejabat();

			// draft
			$this->db->where('id',$id_draft);
			$datacontent['data_draft'] = $this->db->get('t_draft')->row();
			// draft revisi
			$this->db->where('id_draft',$id_draft);
			$this->db->order_by('id','desc');
			$datacontent['data_draft_revisi'] = $this->db->get('t_draft_revisi')->row();

			#print_r($datacontent['l_template']);
			$a['content'] = $this->load->view('f_draft2', $datacontent,TRUE);
			$this->load->view('template', $a);
		}
	}

	public function edit_rev($id_draft_revisi){
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->db->where('id',$id_draft_revisi);
			$datacontent['data_draft_revisi'] = $this->db->get('t_draft_revisi')->row();

			$a['content'] = $this->load->view('f_draft_revisi', $datacontent,TRUE);
			$this->load->view('template', $a);
		}

	}

	public function send($tujuan = "korektor"){
		if ( $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else{
			$id_draft_rev = $this->input->post('id_draft_revisi');
            $catatan = $this->input->post('catatan');

			// cek status dulu, klo status 0 atau NULL,
			// berarti kirim ke Korektor, update status t_draft_revisi = 1,
			$draft_rev = $this->draft->get_rev_by_id($id_draft_rev);
			$draft = $this->draft->get_draft_by_id($draft_rev->id_draft);
            #print_r($draft);
			if ( $draft_rev->status == '' && $tujuan == 'korektor' ){
				$data['status'] = 1;
				$profil = $this->draft->get_profile_by_id($draft->id_korektor);
				#print_r($draft);
			}

			// klo status = 1 && user_id == id_konseptor
			// berarti diterima Korektor lalu kirim ke pejabat, berarti catatan dari korektor
			else if (  $tujuan == 'pejabat' ){
				$data['status'] = 11;
				$profil = $this->draft->get_profile_by_id($draft->id_pejabat);
			}
            // klo tujuan konseptor dan id=id_korektor, berarti catatan_korektor
            else if (  $tujuan == 'konseptor' && $this->session->userdata('user_id') == $draft->id_korektor ){
				$data['status'] = 12;
				$data['catatan_korektor'] = $this->input->post('catatan');
				$profil = $this->draft->get_profile_by_id($draft->id_korektor);
			}
            // klo tujuan konseptor dan id=id_pejabat, berarti catatan dari pejabat
            else if (  $tujuan == 'konseptor' && $this->session->userdata('user_id') == $draft->id_pejabat){
				$data['status'] = 22;
				$data['catatan_pejabat'] = $this->input->post('catatan');
				$profil = $this->draft->get_profile_by_id($draft->id_konseptor);
			}

            // klo tujuan konseptor dan id=valid, berarti catatan dari pejabat
            else if (  $tujuan == 'valid' && $this->session->userdata('user_id') == $draft->id_pejabat){
				$data['status'] = 21;
				$data['catatan_pejabat'] = "Tidak Ada Koreksi, Silahkan Cetak";
				$profil = $this->draft->get_profile_by_id($draft->id_konseptor);
			}

			$this->db->where("id",$id_draft_rev);
			$ret['status'] = 'NOK';
			if ( $this->db->update("t_draft_revisi",$data))
			{
				// ambil profil korektor by id
				// kirim SMS Notifikasi
				$sms['no_hp'] = $profil->hp;
              #  $ret['data'] = $this->db->last_query();
				$sms['pesan'] = "Draft Surat ".$draft->perihal." Menunggu Koreksi Anda";
				//$this->sms->send();
				$ret['msg'] = 'Draft telah diteruskan ke '.$tujuan.' utk dikoreksi';
				$ret['status'] = 'OK';
				// send notifikasi ke korektor

			}
			header('Content-Type: application/json');
			echo json_encode($ret);

		}
	}


    public function cek($id_draft_revisi){
		$id_draft_revisi = $this->my_encrypt->decode($id_draft_revisi);
        if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->db->where('id',$id_draft_revisi);
			$datacontent['data_draft_revisi'] = $this->db->get('t_draft_revisi')->row();

            $this->db->where('id',$datacontent['data_draft_revisi']->id_draft);
			$datacontent['data_draft'] = $this->db->get('t_draft')->row();
            $datacontent['list_history'] = $this->draft->list_history($datacontent['data_draft_revisi']->id_draft);


			$a['content'] = $this->load->view('f_cek_draft', $datacontent,TRUE);
			$this->load->view('template', $a);
		}

	}

    public function download($id_draft_revisi){
		$id_draft_revisi = $this->my_encrypt->decode($id_draft_revisi);
        if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->db->where('id',$id_draft_revisi);
			$data_draft_revisi = $this->db->get('t_draft_revisi')->row();

            $this->db->where('id',$data_draft_revisi->id_draft);
			$datacontent['data_draft'] = $this->db->get('t_draft')->row();
            $datacontent['list_history'] = $this->draft->list_history($data_draft_revisi->id_draft);


			$template = "aset/templates/tpl_surat_1_A4.rtf";
		$handle = fopen($template, "r+");
		$hasilbaca = fread($handle, filesize($template));
		fclose($handle);


		//tuliskan data dalam template
        $hasilbaca = preg_replace('/<\/(?:p|div|section|article)(?:\s+[^>]*)?>/i', "\n\\par}\n",$hasilbaca);
		$hasilbaca = str_replace('#isi#', $data_draft_revisi->isi, $hasilbaca);

		#echo $data_req->nama_pihak1;exit;
		$file_output = 	"_surat";
		#$file_output = str_replace('/', '_');

		Header("Content-type: application/rtf"); Header("Content-Disposition: attachment;filename=".$file_output.".rtf");

		echo $hasilbaca;



		}

	}

    public function create_new_ver(){
        if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			// cek status dulu
            $id_draft_revisi = $this->input->post('id_draft_revisi');
            $curr_rev = $this->draft->get_rev_by_id($id_draft_revisi);
            if ( $curr_rev->status==12 or $curr_rev->status==22 ){
                // salin dari data_revisi yg lama
                $data_revisi['id_draft'] = $curr_rev->id_draft;

                // ambil HTML isi dari template
                $data_revisi['isi'] = $curr_rev->isi;
                $data_revisi['perihal'] = $curr_rev->perihal;
                $data_revisi['kepada'] = $curr_rev->kepada;
                $data_revisi['tgl'] = $curr_rev->tgl;
                $data_revisi['lampiran'] = $curr_rev->lampiran;
                $data_revisi['tgl'] = $curr_rev->tgl;
                $data_revisi['nomor_surat'] = $curr_rev->nomor_surat;
                $data_revisi['ttd'] = $curr_rev->ttd;
                // insert ke draft_revisi
                $this->db->insert('t_draft_revisi',$data_revisi);
                // set session
                $id_draft_revisi_new = $this->db->insert_id();
                $id_draft_revisi_new = $this->my_encrypt->encode($id_draft_revisi_new);
                $link = base_url('draft/cek/').$id_draft_revisi_new;
                $ret['msg'] = 'Konsep Baru telah dibuat berdasarkan konsep yang lama, silahkan edit lalu ajukan kembali.<br />
                <a type="button" class="btn btn-primary" href='.$link.'>Lanjut Ke Editor Konsep</a>';
            }

            else
                $ret['msg'] = "gagal buat perbaikan. $curr_rev->status Draft ini masih dalam proses Pengecekan";

            header('Content-Type: application/json');
		    echo json_encode($ret);
        }
    }

	public function preview() {
		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->load->view('f_draft2', "");
		}

	}

    public function del($id){
        // cek dulu status,
        $id = $this->my_encrypt->decode($id);
        #echo $id;exit;
        $this->db->where('id',$id);
        $this->db->delete('t_draft');

        $this->db->where('id_draft',$id);
        $this->db->delete('t_draft_revisi');
        redirect('draft');
    }


    public function add_notes(){
        if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			// cek status dulu
            $id_draft_revisi = $this->input->post('id_draft_revisi');
            $curr_rev = $this->draft->get_rev_by_id($id_draft_revisi);
                // salin dari data_revisi yg lama
                $data_note['id_rev'] = $id_draft_revisi;
                $data_note['id_user'] = $this->session->userdata('user_id');
                $data_note['startOffset'] = $this->input->post('startOffset');
                $data_note['endOffset'] = $this->input->post('endOffset');
                $data_note['saveNode'] = $this->input->post('saveNode');
                $data_note['nodeData'] = $this->input->post('nodeData');
                $data_note['nodeHTML'] = $this->input->post('nodeHTML');
                $data_note['koreksi'] = $this->input->post('koreksi');
                $data_note['nodeTagName'] = $this->input->post('nodeTagName');
                $data_note['catatan'] = $this->input->post('catatan');


                // insert ke draft_revisi
                $this->db->insert('t_draft_notes',$data_note);
                // set session
                $id_draft_revisi_new = $this->db->insert_id();
                $link = base_url('draft/cek/').$id_draft_revisi_new;
                $ret['msg'] = 'Catatan Berhasil ditambah<br />
                <a type="button" class="btn btn-primary" href='.$link.'>Lanjut Ke Editor Konsep</a>';


            header('Content-Type: application/json');
		    echo json_encode($ret);
        }
    }

    public function list_notes($id_rev){

		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->db->where('id_rev',$id_rev);
            $this->db->order_by('id',"ASC");
			$ret['notes'] = $this->db->get('t_draft_notes')->result_array();

            header('Content-Type: application/json');
		    echo json_encode($ret);
        }

    }

    public function del_note($id){

		if ($this->session->userdata('admin_valid') == FALSE && $this->session->userdata('user_id') == "") {
			redirect("login");
		}
		else {
			$this->db->where('id',$id);
			$ret['notes'] = $this->db->delete('t_draft_notes');

            header('Content-Type: application/json');
		    echo json_encode($ret);
        }

    }





}
