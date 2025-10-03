<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	ini_set('max_execution_time', 0); 
	ini_set('memory_limit','2048M');
	class Home extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->mymodel->squrity();
			$this->load->model('m_config');
		}
		public function index()
		{
			$this->load->view('welcome_message');
		}
		public function update_system(){
			$data['title'] = "Update System";	
			//$sys2 = $this->mymodel->get_config();
			$sys_version = $this->m_config->get_config('sys_version');
			
			$data['sys_version'] = $sys_version;
			$data['url_server_update'] = $this->config->item('server_badilag');
			$data_content['url_server_update'] = $this->config->item('server_badilag');
			$source_path = $data['url_server_update']."Update_ebundling?versi=$sys_version";
			//echo 'XX '.$source_path;
			$ver_updates='';
			$st_updates='';
			$pesan='';
			$update=true;
			$sock = false;
			$error = '';
			if($source_path!=''){
				$arr_versions = $this->get_data($source_path);
				if ($arr_versions){
					foreach ($arr_versions as $k=>$v) {
						if (is_numeric($k) and $sys_version<$k){
							$st_updates='Versi Baru Ditemukan';
							$ver_updates.='versi : '.$k.' # Penting #<i>'.$v[1].'</i></br>';
						}
					}
					if($ver_updates==''){
						$ver_updates='';
						$st_updates='<i>Versi Terbaru Terinstall</i>';
						$update=false;
					}
					}else{
					$ver_updates = '<font color="red"> Server tidak dapat dihubungi <br/></font>';
					$st_updates='';
					$update=false;    
				}
				}else{
				$ver_updates = '<font color="red">Kesalahan pada file config<br/></font>';
				$st_updates='';
				$update=false;    
			}
			$data_content['path_updates'] = $source_path;
			$data_content['ver_updates'] = $ver_updates;
			$data_content['st_updates'] = $st_updates;
			$data_content['update'] = $update;
			$sys = $this->mymodel->sys_config();
			$data_content['nm_satker'] = $sys['pta_name'];
			$data_content['namaketua'] = $sys['nama_kpta'];
			$data_content['id_satker'] = $sys['id_satker_pta'];
			$data_content['version'] = $sys['sys_version'];
			// ambil config
			$data_content['id_satker_pta'] = $this->m_config->get_config('id_satker_pta');
			$data_content['nama_kpta'] = $this->m_config->get_config('nama_kpta');
			$data_content['pta_name'] = $this->m_config->get_config('pta_name');
			$data_content['sys_version'] = $this->m_config->get_config('sys_version');
			// end ambil config
			$data_content['content'] = "Welcome";
		$data_content['title'] = "DASHBOARD";
		$data_content['profile'] = $this->session->userdata();
		//$data_content['banding_blm_daftar'] = $this->banding->belum_berjalan();
		//$data_content['banding_stat'] = $this->banding->stat_pta();
		///$data_content['piechat_data'] = $this->banding->get_piechart_data();
		//$data_content['linechat_data'] = $this->banding->get_linechart_data();
		
		$data['content'] = $this->load->view("f_pta_update",$data_content,true);
		$this->load->view("template",$data);
		
		
			//$this->load->view('keuangan/page/header');
			//$this->load->view('keuangan/v_update_system',$data);
			//$this->load->view('keuangan/page/footer');	
		}
		function sukses_update(){
			$sys = $this->mymodel->sys_config();
			$data['nm_satker'] = $sys['NamaPN'];
			$data['id_satker'] = $sys['id_satker'];
			$data['nm_pta'] = $sys['NamaPT'];
			$this->load->view('keuangan/page/header');
			$this->load->view('keuangan/v_sukses_update',$data);
		}
		function download_update(){
			//$sys2 = $this->mymodel->get_config();
			$sys_version = $this->m_config->get_config('sys_version');
			$sys = $this->mymodel->sys_config();
			$sys_sat = $this->m_config->get_config('id_satker_pta');
			$sys_sat =md5(base64_encode($sys_sat));
			$url_server = $this->config->item('server_badilag');		
			$source_path = $url_server."Update_ebundling?versi=$sys_version&sat=$sys_sat";
			

			$arr_versions['status'] = true;
			if($source_path!=''){
				$ver_updates='';
				$arr_versions = $this->get_data($source_path);
				// print_r($arr_versions['status']);die;
				
				if ($arr_versions == ''){
					echo 'Checking versions gagal';
					return;
				}
				$extractPath= getcwd()."/";//use this for real
				// $extractPath= getcwd()."/TESTING/";//testing
				// $arr_versions=json_decode($getVersions, true);
				
				foreach ($arr_versions as $update_avail=>$arr_val) {
					if ($sys_version<$update_avail){
						echo 'Download Pacth <strong>'.$update_avail.'</strong> <br>';
						$url = $url_server."download_ebundling/".$arr_val[0];
						$file_versi=$arr_val[0];
						// print_r($file_versi);die;
						
						if(!is_file('UPDATE/'.$file_versi)){
							echo '<p>Download Update baru... Silahkan tunggu sebentar..</p>';
							$newUpdate=$this->get_file($url);
							if(!is_dir('UPDATE/'))mkdir('UPDATE/');
							$dlHandler=fopen('UPDATE/'.$file_versi,'w');
							if(!fwrite($dlHandler,$newUpdate)){
								echo '<p>Tidak dapat menyimpan file Update. Operasi dibatalkan.</p>';exit();
							}
							fclose($dlHandler);
							echo '<p>Update baru sudah didownload dan tersimpan</p>';
						}
						else{
							$hapus_file_lama=unlink('UPDATE/'.$file_versi);
							echo '<p>Download Update baru... Silahkan tunggu sebentar..</p>';
							$newUpdate=$this->get_file($url);
							if(!is_dir('UPDATE/'))mkdir('UPDATE/');
							$dlHandler=fopen('UPDATE/'.$file_versi,'w');
							if(!fwrite($dlHandler,$newUpdate)){
								echo '<p>Tidak dapat menyimpan file Update. Operasi dibatalkan.(code error 112)</p>';exit();
							}
							fclose($dlHandler);
							echo '<p>Update baru sudah didownload dan tersimpan..</p>';
						}
						$zipFile = ('UPDATE/'.$file_versi);// Local Zip File Path
						$this->ekstrak($zipFile, $extractPath);
						//update database disini
						$this->load->model('Update_versi');
						unlink('UPDATE/'.$file_versi);
						
					}
				}
			}
		}
		
		function ekstrak($zipFile,$extractPath=''){
			//Open The File And Do Stuff
			$zipHandle = zip_open($zipFile);
			if ($zipHandle){
				echo '<ul>';
				while ($aF = zip_read($zipHandle) ) {
					$thisFileName = zip_entry_name($aF);
					$thisFileDir = dirname($thisFileName);
					//Continue if its not a file
					echo '<br><li>Working on '.$thisFileName.'</li>';
					if ( substr($thisFileName,-1,1) == '/') continue;
					
					//Make the directory if we need to...
					if ( !is_dir ( $extractPath.$thisFileDir ) ){
						echo '<li>Creating Directory '.$extractPath.$thisFileDir.'</li>';
						@mkdir($extractPath.$thisFileDir,0750,true);
						echo '<li>Directory '.$extractPath.$thisFileDir.'........... <strong>CREATED</strong></li>';
						}else{
						echo '<li>Directory '.$extractPath.$thisFileDir.' already exist</li>';
					}
					
					//Overwrite the file
					if ( !is_dir($extractPath.$thisFileName) ) {
						echo '<li>File '.$extractPath.$thisFileName.'...........';
						$contents = zip_entry_read($aF, zip_entry_filesize($aF));					
						$updateThis = '';
						
						//If we need to run commands, then do it.
						if ( $thisFileName == 'upgrade.php' ){
							$upgradeExec = fopen ('upgrade.php','w');
							$contents = str_replace("\r\n", "\n", $contents);
							fwrite($upgradeExec, $contents);
							fclose($upgradeExec);
							include ('upgrade.php');
							unlink('upgrade.php');
							echo' <strong>EXECUTED</strong></li>';
						}
						else
						{
							$updateThis = fopen($extractPath.$thisFileName, 'w');
							fwrite($updateThis, $contents);
							fclose($updateThis);
							unset($contents);
							echo' <strong>UPDATED</strong></li>';
						}
					}
				}
				echo '</ul>';
				$updated = true;
				}else{
				return;
			}
		}
		public function update_db(){
			//echo filenya;
			$this->load->model('update/Update_database_207');
		}
		function get_data($url){
			$st = array();
			$ch = curl_init($url);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
			$output = curl_exec($ch);
			$_err = curl_errno($ch);
			$_errmsg = curl_error($ch);
			if($value = json_decode($output,true))
			{
				return (array) $value;
				// var_dump($value); die;
			}
			else
			{
				$st['status'] = false;
				$st['txt'] = $_errmsg;
				return $st;
			}
		}
		function get_file($url){
			$st = array();
			$ch = curl_init($url);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
			$output = curl_exec($ch);
			$_err = curl_errno($ch);
			$_errmsg = curl_error($ch);
			curl_close($ch);
       		return($output);
		}
	}
