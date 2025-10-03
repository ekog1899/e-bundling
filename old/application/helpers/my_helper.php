<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/* ambil database */
	function gli($tabel, $field_kunci, $pad) {
		$CI 	=& get_instance();
		#echo "SELECT max($field_kunci*1) AS last FROM $tabel WHERE YEAR(tgl_surat)=YEAR(NOW())";
		$nama	= $CI->db->query("SELECT max($field_kunci*1) AS last FROM $tabel WHERE YEAR(tgl_surat)=YEAR(NOW())")->row();
		$data	= (intval($nama->last)) + 1;
		$last	= str_pad($data, $pad, '0', STR_PAD_LEFT);
		return $last;
	}
	function gval($tabel, $field_kunci, $diambil, $where) {
		$CI =& get_instance();
		$nama	= $CI->db->query("SELECT $diambil FROM $tabel WHERE $field_kunci = '$where'")->row();
		$data	= empty($nama) ? "-" : $nama->$diambil;
		return $data;
	}
	
	
	function _hitung_hari_kerja($awal,$akhir,$arr_libur){
		$awal = date_create_from_format('Y-m-d', $awal);
		$awal = date_format($awal, 'Y-m-d');
		$awal = strtotime($awal);
		
		$akhir = date_create_from_format('Y-m-d', $akhir);
		$akhir = date_format($akhir, 'Y-m-d');
		$akhir = strtotime($akhir);
		
		$harikerja = array();
		
		for ($i=$awal; $i < $akhir; $i += (60 * 60 * 24)) {
			if (date('w', $i) !== '0' && date('w', $i) !== '6' && !in_array(date('Y-m-d', $i),$arr_libur)) {
				$harikerja[] = $i;
			}
			
		}
		return count($harikerja);
	}
	
	
	
	function _hitung_hari($awal,$akhir,$arr_libur){
		$awal = date_create_from_format('Y-m-d', $awal);
		$awal = date_format($awal, 'Y-m-d');
		$awal = strtotime($awal);
		
		$akhir = date_create_from_format('Y-m-d', $akhir);
		$akhir = date_format($akhir, 'Y-m-d');
		$akhir = strtotime($akhir);
		
		$harikerja = array();
		for ($i=$awal; $i < $akhir; $i += (60 * 60 * 24)) {
			if ( !in_array(date('Y-m-d', $i),$arr_libur)) {
				$harikerja[] = $i;
			}
			
		}
		return count($harikerja);
	}
	
	function hari_tanggal ($tgl_var) {
		$tgl1 = explode('-',$tgl_var);
		$tgl = $tgl1[0];
		$bln = $tgl1[1];
		$thn = $tgl1[2];
		$date = date_create($tgl_var);
		$hari = date_format($date,"D");
		
		$ar_hari = array(
		'Sun'=>'Minggu',
		'Mon'=>'Senin',
		'Tue'=>'Selasa',
		'Wed'=>'Rabu',
		'Thu'=>'Kamis',
		'Fri'=>'Jum\'at',
		'Sat'=>'Sabtu');
		
		$ar_bulan = array(
		'01'=>'Januari',
		'02'=>'Februari',
		'03'=>'Maret',
		'04'=>'April',
		'05'=>'Mei',
		'06'=>'Juni',
		'07'=>'Juli',
		'08'=>'Agustus',
		'09'=>'September',
		'10'=>'Oktober',
		'11'=>'Nopember',
		'12'=>'Desember');
		
		
		return $ar_hari[$hari].", ".$tgl." ".$ar_bulan[$bln]." ".$thn; //."  ".$jam1;
	}
	
	function tanggal_bulan($tgl_var) {
		$tgl1 = explode('-',$tgl_var);
		$tgl = $tgl1[0];
		$bln = $tgl1[1];
		$thn = $tgl1[2];
		$date = date_create($tgl_var);
		$hari = date_format($date,"D");
		
		
		$ar_bulan = array(
		'01'=>'Januari',
		'02'=>'Februari',
		'03'=>'Maret',
		'04'=>'April',
		'05'=>'Mei',
		'06'=>'Juni',
		'07'=>'Juli',
		'08'=>'Agustus',
		'09'=>'September',
		'10'=>'Oktober',
		'11'=>'Nopember',
		'12'=>'Desember');
		
		
		return $tgl." ".$ar_bulan[$bln]." ".$thn; //."  ".$jam1;
	}
	function tgl_indo($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		
		return $tanggal.' '.$bulan.' '.$tahun;       
	} 
	function getBulan($bln){
		switch ($bln){
			case 1: 
			return "Januari";
			break;
			case 2:
			return "Februari";
			break;
			case 3:
			return "Maret";
			break;
			case 4:
			return "April";
			break;
			case 5:
			return "Mei";
			break;
			case 6:
			return "Juni";
			break;
			case 7:
			return "Juli";
			break;
			case 8:
			return "Agustus";
			break;
			case 9:
			return "September";
			break;
			case 10:
			return "Oktober";
			break;
			case 11:
			return "November";
			break;
			case 12:
			return "Desember";
			break;
		}
	} 
	function encode_url($satkersing,$perkara_id){
		// pasbga, 12345
		//$satkersing = "pakis";
		
		$number = ( $perkara_id*5 ) + 1;
		$char_1 = substr($satkersing,2,1);
		$char_2 = substr($satkersing,3,1);
		$char_3 = substr($satkersing,4,1);
		$char_4 = substr($satkersing,5,1);
		$char_4 = ($char_4 == '') ?"x":$char_4;
		#echo $char_1."-".$char_2."-".$char_3."-".$char_4;
		$token = $char_3.$char_1.$char_2.$number.$char_4;
		return $token;
	}
	
	
	function decode_url($token){
		// pasbga, 12345
		// ski61726x
		$char_1 = substr($token,1,1);
		$char_2 = substr($token,2,1);
		$char_3 = substr($token,0,1);
		$char_4 = substr($token,-1);
		$char_4 = ($char_4 == 'x') ?"":$char_4;
		
		preg_match('!\d+!', $token, $matches); 
		$r['satkersing'] = "pa".$char_1.$char_2.$char_3.$char_4;
		$r['perkara_id'] =($matches[0]-1)/5;
		return $r ;
	}
	
	/* penyederhanaan fungsi */
	function _page($total_row, $per_page, $uri_segment, $url) {
		$CI 	=& get_instance();
		$CI->load->library('pagination');
		$config['base_url'] 	= $url;
		$config['total_rows'] 	= $total_row;
		$config['uri_segment'] 	= $uri_segment;
		$config['per_page'] 	= $per_page;
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close']= '</li>';
		$config['prev_link'] 	= '&lt;';
		$config['prev_tag_open']='<li>';
		$config['prev_tag_close']='</li>';
		$config['next_link'] 	= '&gt;';
		$config['next_tag_open']='<li>';
		$config['next_tag_close']='</li>';
		$config['cur_tag_open']='<li class="active disabled"><a href="#"  style="background: #e3e3e3">';
		$config['cur_tag_close']='</a></li>';
		$config['first_tag_open']='<li>';
		$config['first_tag_close']='</li>';
		$config['last_tag_open']='<li>';
		$config['last_tag_close']='</li>';
		
		$CI->pagination->initialize($config);
		return $CI->pagination->create_links();
	}
	
	function _print_pdf($file, $data) {
		require_once('h2p/html2fpdf.php');          // agar dapat menggunakan fungsi-fungsi html2pdf
		ob_start();                            		// memulai buffer
		error_reporting(1);                     	// turn off warning for deprecated functions
		$pdf= new HTML2FPDF();                  	// membuat objek HTML2PDF
		$pdf->DisplayPreferences('Fullscreen');
		
		$html = $data;               		// mengambil data dengan format html, dan disimpan di variabel
		ob_end_clean();                         	// mengakhiri buffer dan tidak menampilkan data dalam format html
		$pdf->addPage();                        	// menambah halaman di file pdf
		$pdf->WriteHTML($html);                 	// menuliskan data dengan format html ke file pdf
		return $pdf->Output($file,'D');
	}
