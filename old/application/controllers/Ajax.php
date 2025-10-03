<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public $tipe = 'P';
	public $keyword = '';
	function __construct() {
		parent::__construct();
		$this->load->model('banding_model','banding');
	}


	public function get_notification(){
		// cek surat masuk yang belum didisposisi Ketua
		$not = $this->notification->get_unread();

		if ($not)
		{
			$this->notification->mark_push($not[0]['not_id']);
			$this->output->cache(0);
			echo json_encode($not[0]);
		}

		#$this->load->view('template', $a);
	}

	public function get_notif_atas(){
			$notif['wait_disp'] = $this->notif2->get_wait_disposisi($this->session->userdata('user_id'))->jml;
			$notif['wait_cuti'] = 0;
			$notif['wait_cek_draft'] = $this->notif2->get_blm_cek_draft($this->session->userdata('user_id'))->jml;


			echo json_encode($notif);
	}

    public function get_jam_server() {
		$ret['jam'] = date('H:i:s');
		echo json_encode($ret);
	}
	
	public function get_jsidang($satkersing,$enc_perkara_id){
		$perkara_id = $this->encryption->decrypt(str_replace('__','/',$enc_perkara_id));
		$data['jadwal_sidang'] = $this->banding->jadwal_sidang($satkersing,$perkara_id);

		$this->load->view('tab_jadwal_sidang',$data);
	}

	public function save_jsidang(){
		
		$timestamp = strtotime($this->input->post('tanggal'));
		
		// Create the new format from the timestamp
		$data['tanggal'] = date("Y-m-d", $timestamp);
		$data['agenda'] = $this->input->post('agenda');
		$data['alasan_tunda'] = $this->input->post('alasan_tunda');
		
		// klo enc_id == null, berarti data baru
		
		if ($this->input->post('enc_id') == ''){
			$perkara_id = $this->encryption->decrypt(str_replace('__','/',$this->input->post('enc_perkara_id')));
			$data['perkara_id'] = $perkara_id;
			$data['satkersing'] = $this->input->post('satkersing');		
			#echo "perkara_id = $perkara_id";	
			$this->db->insert('banding_jadwal_sidang',$data);
		}
		else{
			$id = $this->input->post('enc_id');
			$this->db->where('id',$id);
			$this->db->update('banding_jadwal_sidang',$data);
			echo "Data Berhasil diupdate";
		}
	}

	public function save_req_unlock(){
		$timestamp = strtotime($this->input->post('tanggal'));
		
		// Create the new format from the timestamp
		
		
		
		if ($this->input->post('enc_id') == ''){
			$perkara_id = $this->encryption->decrypt(str_replace('__','/',$this->input->post('enc_perkara_id')));
			$data['perkara_id'] = $perkara_id;
			$data['idpn'] = $this->input->post('satkercode');	
			$data['jenis_edoc'] = $this->input->post('jenis_edoc');		
			$data['tgl_request'] = date("Y-m-d h:i:s");
			$data['req_alasan'] = $this->input->post('req_alasan');

			// check apakah ada pending request ?
			$this->db->select("max(tgl_request) tgl_request");
			$this->db->where('perkara_id',$data['perkara_id']);
			$this->db->where('idpn',$data['idpn']);
			$this->db->where('jenis_edoc',$data['jenis_edoc']);
			$this->db->where('tgl_unlock',null);
			$result = $this->db->get('req_unlock');
			#print_r($this->db->last_query());
			if ( $result->row()->tgl_request == '' ){
			
				$this->db->insert('req_unlock',$data);
				#header('error', true, 500);
				echo '<span class="text-success">Permintaan Buka Kunci berhasil terkirim, mohon tunggu Approval dari PTA Medan</a>';
			}
			else{
				header('error', true, 500);
				
				echo '<span class="text-danger">Gagal karena permintaan Buka Kunci sebelumnya '.$result->row()->tgl_request.' masih menunggu approve PTA</a>';	
			}	

			
		    
		}
	}


	public function process_unlock(){
		if ($this->input->post('id') <> ''){
			$this->db->where('id',$this->input->post('id'));
			$this->db->where('perkara_id',$this->input->post('perkara_id'));
			$data['tgl_unlock'] = date("Y-m-d h:i:s");
			if ( $this->db->update('req_unlock',$data) )
				echo "<span class='text-success'>edoc Berhasil dibuka</span>";
		
		}	

	}

	public function del_jsidang(){
		if ( $this->session->userdata('role') != 'op_pta' )
		{
			die('Tidak diizinkan');
		}
		else{
			$this->db->where('id',$this->input->post('id'));
			$this->db->delete('banding_jadwal_sidang');
		}
	}

	public function telusur(){
	//	sleep(3);
		$np_nomor = $this->input->post('np_nomor');
		$satkersing = $this->input->post('satkersing');
		$np_tahun = $this->input->post('np_tahun');
		$np_pg = $this->input->post('np_pg');
		$nomor_perkara = ($np_nomor.'/'.$np_pg.'/'.$np_tahun.'/'.$satkersing);
		$perkara_id_data = $this->banding->get_perkara_id_banding($nomor_perkara);
		
		if ( count($perkara_id_data) == 0 ){
			echo "<span class='text-danger'>Data Banding untuk $nomor_perkara tidak ditemukan, pastikan Nomor Perkara sudah benar</span>";
			exit;
		}
		
		$perkara_id = $perkara_id_data[0]['perkara_id'];
		$db_pa = strtolower(str_replace('.','',$satkersing));
		$this->banding->switch_db($db_pa);
		$datatk1 = $this->banding->perkara_detil($perkara_id)[0];
		$databanding =  $this->banding->banding_detil($perkara_id)[0];
		$datasidang =  $this->banding->jadwal_sidang($db_pa,$perkara_id);
		#print_r($databanding);

		$html_sidang = '<table class="table table-bordered table-hover">
				<thead>
				
					<tr>  		
						<th width="26%">Tanggal</th>
						<th>Agenda</th>
						<th>Alasan Tunda</th>
						</tr>
				</thead>


				<tbody>';
		foreach ($datasidang as $row):
			$html_sidang.= "<tr><td>".$row['tanggal']."</td><td>".$row['agenda']."</td><td>".$row['alasan_tunda']."</td></tr>";
		endforeach;	
		
		$html_sidang.="</tbody></table></html>";	
		
		if (count($datasidang) == 0) {
			$html_sidang = "untuk sementara data persidangan baru tersedia untuk perkara tahun 2022";
		}

		$status_banding = $databanding['status_banding_text'];

		// klo  $status_banding = masih pengiriman, 
		if ( $status_banding =='Pengiriman Berkas Banding' && $databanding['nomor_perkara_banding'] !='' ){
			$status_banding = "Sedang diproses di Tingkat Banding";
		}


		//echo json_encode($res);
		$html = '<div classs="datatk1">    
		<table class="table table-bordered table-hover">
			<tbody>
			<tr><td colspan="2" class="bg-success text-white">Data Tingkat Pertama</td></tr>
			<tr><td class="col-md-3  col-sm-6">Nomor Perkara Tk.1</td><td>'.$nomor_perkara.' <span class="badge bg-info">'.$datatk1['jenis_perkara_nama'].'</span></td></tr>
				<tr><td>Majelis Hakim</td><td>'.$datatk1['majelis_hakim_text'].'</td></tr>
				<tr><td>Panitera Pengganti</td><td>'.str_replace('Panitera Pengganti: ','',$datatk1['panitera_pengganti_text']).'</td></tr>  
				<tr><td>Tanggal Putusan</td><td>'.$datatk1['tanggal_putusan'].'</td></tr>  
				<tr><td>Pengiriman Berkas Banding</td><td>'.$databanding['pengiriman_berkas_banding'].'</td></tr>               
			</tbody>
		</table>    
	</div>
				
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <li class="nav-item">
		<a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-a" role="tab" aria-controls="tab-a" aria-selected="true">Data Banding</a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" id="profile-tab" data-toggle="tab" href="#tab-b" role="tab" aria-controls="tab-b" aria-selected="false">Persidangan Banding</a>
	  </li>
	
	  <li class="nav-item">
		<a class="nav-link" id="contact-tab" data-toggle="tab" href="#tab-c" role="tab" aria-controls="tab-c" aria-selected="false">Putusan Banding</a>
	  </li>
	</ul>


	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade active show" id="tab-a" role="tabpanel" aria-labelledby="tab-a">
			<table class="table table-bordered table-hover">
				<tbody>
				<tr><td class="col-md-5 col-sm-6">Nomor Banding</td><td>'.$databanding['nomor_perkara_banding'].'</td></tr>
					<tr><td>Status Perkara Banding</td><td>'.$status_banding.'</td></tr>  
					<tr><td>Majelis Hakim</td><td>'.$databanding['majelis_hakim_banding'].'</td></tr>  
					<tr><td>Panitera Pengganti</td><td>'.$databanding['panitera_pengganti_banding'].'</td></tr>  
					  
				</tbody>
			</table> 
		</div>        
		<div class="tab-pane fade" id="tab-b" role="tabpanel" aria-labelledby="tab-b">
		   '.$html_sidang.'
		</div>
		<div class="tab-pane fade" id="tab-c" role="tabpanel" aria-labelledby="tab-c">
		<table class="table table-bordered table-hover">
		<tbody>
			<tr><td class="col-md-5 col-sm-6">Tanggal Putusan Banding</td><td>'.$databanding['putusan_banding'].'</td></tr>  
			<tr><td>Status Putusan Banding</td><td>'.$databanding['status_putusan_banding_text'].'</td></tr>       			 
			  
		</tbody>
	</table> 
		</div>
	</div>   ';
	echo ($html);


	}

}
