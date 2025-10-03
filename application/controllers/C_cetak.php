<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_cetak extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('M_cetak');
    }
    function update_tgl_serah_terima_berkas(){
		$perkara_id=trim($this->input->post('perkara_id'));
    	$idpn=trim($this->input->post('idpn'));
    	$tgl_serah_terima_berkas=trim($this->input->post('tgl_serah_terima_berkas'));
    	$where="perkara_id=$perkara_id AND idpn=$idpn";
    	$data = array(
	        'tgl_serah_terima_berkas' => $tgl_serah_terima_berkas
		);
     	$this->M_cetak->update_data($where, 'tim_pemeriksa_pradaftar', $data);

	}
	function cetak_serah_terima_berkas($idpn,$perkara_id){
		$this->load->helper('file');
		$rtf="";
		$source_file="template/berita_acara_serah_terima_berkas_.rtf";
		$rtf=file_get_contents($source_file);
		$data=$this->M_cetak->serah_terima_berkas($idpn,$perkara_id);
		if(count($data<>0)){
			$rtf= str_replace("#hari#",$data[0]->hari,$rtf) ;
			$rtf= str_replace("#tanggal#",$data[0]->tanggal,$rtf) ;
			$rtf= str_replace("#nama_km#",$data[0]->nama_km,$rtf) ;
			$rtf= str_replace("#nomor_perkara_pa#",$data[0]->nomor_perkara_pa,$rtf) ;
			$rtf= str_replace("#nama_satker#",$data[0]->nama_satker,$rtf) ;
			$rtf= str_replace("#nomor_perkara_banding#",$data[0]->nomor_perkara_banding,$rtf) ;
			$rtf= str_replace("#jenis_perkara_text#",$data[0]->jenis_perkara_text,$rtf) ;
		}
		header("Content-type: application/rtf");
		header('Content-Disposition: attachment; filename=berita_acara_serah_terima_berkas.rtf');
		header("Content-length: ".strlen($rtf));
		echo $rtf;
	}
	function terbilang_rupiah($bilangan)
		{
			  $bilangan=str_replace(".00", "", $bilangan);	
			   $angka = array('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',
        '0', '0', '0');
			    $kata = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh',
			        'delapan', 'sembilan');
			    $tingkat = array('', 'ribu', 'juta', 'milyar', 'triliun');

			    $panjang_bilangan = strlen($bilangan);

			    /* pengujian panjang bilangan */
			    if ($panjang_bilangan > 15)
			    {
			        $kalimat = "Diluar Batas";
			        return $kalimat;
			    }

			    /* mengambil angka-angka yang ada dalam bilangan,
			    dimasukkan ke dalam array */
			    for ($i = 1; $i <= $panjang_bilangan; $i++)
			    {
			        $angka[$i] = substr($bilangan, -($i), 1);
			    }

			    $i = 1;
			    $j = 0;
			    $kalimat = "";


			    /* mulai proses iterasi terhadap array angka */
			    while ($i <= $panjang_bilangan)
			    {
			        $subkalimat = "";
			        $kata1 = "";
			        $kata2 = "";
			        $kata3 = "";

			        /* untuk ratusan */
			        if ($angka[$i + 2] != "0")
			        {
			            if ($angka[$i + 2] == "1")
			            {
			                $kata1 = "seratus";
			            }
			            else
			            {
			                $kata1 = $kata[$angka[$i + 2]] . " ratus";
			            }
			        }

			        /* untuk puluhan atau belasan */
			        if ($angka[$i + 1] != "0")
			        {
			            if ($angka[$i + 1] == "1")
			            {
			                if ($angka[$i] == "0")
			                {
			                    $kata2 = "sepuluh";
			                }
			                elseif ($angka[$i] == "1")
			                {
			                    $kata2 = "sebelas";
			                }
			                else
			                {
			                    $kata2 = $kata[$angka[$i]] . " belas";
			                }
			            }
			            else
			            {
			                $kata2 = $kata[$angka[$i + 1]] . " puluh";
			            }
			        }

			        /* untuk satuan */
			        if ($angka[$i] != "0")
			        {
			            if ($angka[$i + 1] != "1")
			            {
			                $kata3 = $kata[$angka[$i]];
			            }
			        }

			        /* pengujian angka apakah tidak nol semua,
			        lalu ditambahkan tingkat */
			        if (($angka[$i] != "0") or ($angka[$i + 1] != "0") or ($angka[$i + 2] != "0"))
			        {
			            $subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . " ";
			        }

			        /* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
			        ke variabel kalimat */
			        $kalimat = $subkalimat . $kalimat;
			        $i = $i + 3;
			        $j = $j + 1;

			    }

			    /* mengganti satu ribu jadi seribu jika diperlukan */
			    if (($angka[5] == "0") and ($angka[6] == "0"))
			    {
			        $kalimat = str_replace("satu ribu", "seribu", $kalimat);
			    }

			    return str_replace("  "," ",str_replace("  "," ",trim($kalimat . " rupiah")));
			} 
	function cetak_eksekusi(){
		$this->load->helper('file');

		$perkara_id= $this->input->post('perkara_id');
		$template_id= $this->input->post('template_id');
		$where='id='.$template_id;
		$data_blangko=$this->Template_m->get_data_where($where,"template_dokumen_eksekusi");
		$rtf="";
		//echo "Menggunakan Blangko ".$data_blangko[0]->kode.".rtf<br>";
		$source_file="template_eksekusi/".$data_blangko[0]->kode.".rtf";
		$rtf=file_get_contents($source_file);
		//$pos=$this->input->post();
		//var_dump($pos);exit;
			foreach($this->input->post() as $key=>$value){
				$where="var_nomor='".$key."'";
				$data_variabel=$this->Template_m->get_data_where($where,"master_variabel");
				if(count($data_variabel<>0)){
					if($data_variabel[0]->var_fungsi_nama=="tanggal_indonesia"){
						$value=$this->convertKeTglIndo($value);
					}
				}
				//echo $key. "-" .$value."<br>";
				//var_dump($h);exit;
				//echo $h["key"];exit;
				if($key==5058 OR $key==5059 OR $key==5060 OR $key==5061 OR $key==8100 OR $key==8101 OR $key==5062 OR $key==5063 OR $key==5064 OR $key==5065 OR $key==20000 OR $key==20001 OR $key==20002 OR $key==20003){
						//lama
						//$value=str_replace("&nbsp;"," ", $value);
						//$value=str_replace(";;",";", $value);
						//$value=str_replace("^"," \par \pard\li3254\sa200\sl360\slmult1\qj ", $value);
						//$value=str_replace("|"," \par \pard\sa200\sl360\slmult1\qj\lang33 ", $value);
						//$rtf= str_replace("#".$key."#",$value,$rtf) ; 
						//lama
						
						//Baru
						$value=str_replace("&nbsp;"," ", $value);
						$value=str_replace("   "," ", $value);
						$value=str_replace("  "," ", $value);
						$isinya=explode("|",$value);
						$jml_tanya_jawab=count($isinya);
						$tabelnya="";
						for ($tanya_jawab_posisi = 0; $tanya_jawab_posisi < $jml_tanya_jawab-1; $tanya_jawab_posisi++) 
						{
							$data_baris=$isinya[$tanya_jawab_posisi];
							$pecah_tanya_jawab=explode("^",$data_baris);
							$tabelnya.='\trowd\cellx3800\cellx8500\intbl '.trim($pecah_tanya_jawab[0]);
							$tabelnya.='\cell\intbl \cell\row \trowd\cellx3800\cellx8500\intbl\cell\intbl '.trim($pecah_tanya_jawab[1]).'\cell\row'; 
						}
						$tabelnya.='\pard\par';
						$rtf= str_replace("#".$key."#",$tabelnya,$rtf) ;
					//Baru 
				}else
				{

					$value=str_replace(";;",";", $value);
					$value=str_replace(chr(13),";", $value);
					$value=str_replace(chr(10),";", $value);
					//$value=str_replace(chr(9),"\tab ", $value);
				//	$value=str_replace('\t',"\tab ", $value);
					$value=str_replace('\n',";", $value);
					$value=str_replace('; ;',";", $value);
					$value=str_replace(';;',";", $value);
					$value=str_replace(';;',";", $value);
					$value=str_replace('.;',";", $value);
					$value=str_replace(';;',";", $value);
					$value=str_replace('-;',";", $value);
					$value=str_replace(';',";\par ", $value);
					$value=str_replace("ï¿½","'", $value);
					$value=str_replace(" ,","", $value);
					$value=str_replace("\'ef\'bf\'bd\'ef\'bf\'bd\'ef\'bf\'bd\loch\f1","", $value);
					$rtf= str_replace("#".$key."#",$value,$rtf) ;
					//echo "Merubah #$key# menjadi  $value<br>";
				}
			}
			//$nama_file_hasil=str_replace("/","_",@$nomor_perkara)."_".@$jenis_blangko_nama."_".date("Y-m-d").".rtf";
			$nama_file_hasil=$perkara_id."vpreview.rtf";
			//replace karakter khusus
			//$rtf= str_replace("\'ef\'bf\'bd\loch\f1","",$rtf) ;
			//$rtf= str_replace("\'ef\'bf\'bd","",$rtf) ;
			if ( ! write_file("./hasil/$nama_file_hasil", $rtf)){
			    // echo 'Berhasil';
			}else{
			    // echo 'Gagal';
			}
			//$tulis=write_file('hasil/'.$nama_file_hasil, $rtf, 'r+');
			//var_dump($tulis);
			//replace karakter khusus
			//echo $rtf;
			//exit;
			$hasil_lokasi="hasil/".$nama_file_hasil;
			//$hasil=file_put_contents($hasil_lokasi,$rtf);
			//echo '<br><center><a href="'.$hasil_lokasi.'" class="w3-btn  w3-small w3-green">.:: Unduh Ulang ::.</a><center>';
			echo '^'.$hasil_lokasi;
		
	}
    public function convertKeTglIndo($tgl){
    	# contoh: 21 April 2014
	    if (!$this->validateDate($tgl)) return $tgl; 
	    $tanggal_ = substr($tgl,8,2);
	    if($tanggal_>=10){
	    	$tanggal = $tanggal_;
	    }elseif($tanggal_<10){
	    	$tanggal = substr($tgl,9,2);
	    }
	    $bulan_ =  $this->getBulanFull(substr($tgl,5,2));
	    $tahun_ =  substr($tgl,0,4);
	    return  $tanggal.' '.$bulan_.' '.$tahun_;

	}
	function validateDate($date){
	    if (empty($date)) return false;
	    $date = str_replace('/', '-', $date);
		$d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') == $date;
	}
	public function  getBulanFull($bln){
	    switch  ($bln){
	        case 1: return  "Januari"; break;
	        case 2: return  "Februari"; break;
	        case 3: return  "Maret"; break;
	        case 4: return  "April"; break;
	        case 5: return  "Mei"; break;
	        case 6: return  "Juni"; break;
	        case 7: return  "Juli"; break;
	        case 8: return  "Agustus"; break;
	        case 9: return  "September"; break;
	        case 10: return "Oktober"; break;
	        case 11: return "November"; break;
	        case 12: return "Desember"; break;
	    }
	}
	function format_uang($nilai){
			if((int)$nilai==0)
			{
				$nilai=0;
			}else
			{
				$nilai=number_format($nilai, 0, ',', '.');
			}
			return $nilai.",00";
	} 
    
}

