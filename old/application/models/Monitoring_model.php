<?php
class Monitoring_model extends CI_Model {

	function init(){
		$this->db_pa = $this->session->userdata('idpn');
		$this->db_pa = "sipp"; // xampp mode
        
		$this->db_edoc = "dev_dakung_banding";
	}
	

    function banding_berjalan(){
		$sql_filter = "";
        // kalau bukan verifikator atau bukan KPTA,
        // jika hakim
        if (  $this->session->userdata('is_verifikator') != 1 ) {

            if ( $this->session->userdata('grup') == '1'   ){
                $sql_filter = " AND majelis_hakim_banding LIKE '%".($this->session->userdata('nama'))."%'";
            }
            
            else if ( $this->session->userdata('grup') == '2' ){
                $sql_filter = " AND panitera_pengganti_banding =".$this->session->userdata('pp_banding_id');
            }
        }    
        $sql = "SELECT a.*,blm_validasi jml
		FROM sipp_perkara_banding a LEFT JOIN
		(
            SELECT idpn,perkara_id,COUNT(1) blm_validasi  FROM(
            SELECT idpn,perkara_id,jenis_edoc FROM dok_perkara_banding
            WHERE val_edoc IS NULL
			AND jenis_edoc NOT IN (SELECT jenis_edoc FROM ref_jenis_edoc WHERE bundel='c')
            AND file_edoc IS NOT NULL
            UNION 
            SELECT idpn,perkara_id,jenis_edoc FROM dok_perkara_banding_sidang
            WHERE val_edoc IS NULL
            AND file_edoc IS NOT NULL
            ) z
            GROUP BY idpn,perkara_id
		) b 
		ON (a.`perkara_id`=b.perkara_id AND a.idpn=b.idpn)        
		WHERE ( nomor_perkara_banding <> '' ) AND putusan_banding IS NULL
        
		ORDER BY blm_validasi DESC
		";
       # echo  $sql;
		$q = $this->db->query($sql);
		return $q->result_array();	
	}


    function menunggu_revisi(){
        $sql_filter = "";
        // kalau bukan verifikator atau bukan KPTA,
        // jika hakim
        if (  $this->session->userdata('is_verifikator') != 1 ) {

            if ( $this->session->userdata('grup') == '1'   ){
                $sql_filter = " AND majelis_hakim_banding LIKE '%".($this->session->userdata('nama'))."%'";
            }
            
            else if ( $this->session->userdata('grup') == '2' ){
                $sql_filter = " AND panitera_pengganti_banding =".$this->session->userdata('pp_banding_id');
            }
        }    
        
        $sql = "SELECT a.*,perbaikan jml
                    FROM sipp_perkara_banding a,
                    (
                    SELECT idpn,perkara_id,SUM(perbaikan) perbaikan FROM
                    (
                    SELECT idpn,perkara_id,COUNT(1) perbaikan FROM dok_perkara_banding
                    WHERE val_edoc=0
                    AND file_edoc IS NOT NULL
					AND jenis_edoc NOT IN (SELECT jenis_edoc FROM ref_jenis_edoc WHERE bundel='c')
                    GROUP BY idpn,perkara_id
                    UNION
                    SELECT idpn,perkara_id,COUNT(1) perbaikan FROM dok_perkara_banding_sidang
                    WHERE val_edoc=0
                    AND file_edoc IS NOT NULL
                    GROUP BY idpn,perkara_id
                    ) z
                    GROUP BY idpn,perkara_id		
                ) b 
                WHERE (a.`perkara_id`=b.perkara_id AND a.idpn=b.idpn)
                and a.putusan_banding is null
                
       ";
       #echo  $sql;
       $q = $this->db->query($sql);
       return $q->result_array();	
   }

	function banding_kasasi_2_tahun($tahun2){
		$tahun_lalu = $tahun2 - 1;
        $sql = "SELECT satnama,satsing,banding1,kasasi1,banding2,kasasi2 FROM satker aa LEFT JOIN
        (
        SELECT a.idpn,COUNT(1) banding1,SUM(CASE WHEN b.`nomor_perkara_pn` IS NOT NULL THEN 1 ELSE 0 END) kasasi1
        FROM sipp_perkara_banding a LEFT OUTER JOIN sipp_perkara_kasasi b
        ON a.`nomor_perkara_pa`=b.`nomor_perkara_pn`
        -- and nomor_perkara_banding='3/Pdt.G/2022/PTA.Mdn'
        WHERE YEAR(permohonan_banding)=$tahun_lalu
        AND nomor_perkara_kasasi IS NULL
        GROUP BY a.idpn) z ON (aa.satsing=z.idpn)
        LEFT OUTER JOIN
        (
        SELECT a.idpn,COUNT(1) banding2,SUM(CASE WHEN b.`nomor_perkara_pn` IS NOT NULL THEN 1 ELSE 0 END) kasasi2
        FROM sipp_perkara_banding a LEFT OUTER JOIN sipp_perkara_kasasi b
        ON a.`nomor_perkara_pa`=b.`nomor_perkara_pn`
        -- and nomor_perkara_banding='3/Pdt.G/2022/PTA.Mdn'
        WHERE YEAR(permohonan_banding)=$tahun2
        AND nomor_perkara_kasasi IS NULL
        GROUP BY a.idpn) z1
        ON (aa.satsing=z1.idpn)
        order by kode2 asc";
        return $this->db->query($sql)->result_array();
	}

    /** DASH PTA */
    function banding_kasasi_tahun($tahun){
	 $sql = "SELECT satnama,satsing,banding,kasasi_skrg,kasasi_depan FROM satker aa LEFT JOIN
     (
     SELECT a.idpn,COUNT(1) banding,
     SUM(CASE WHEN b.`nomor_perkara_pn` IS NOT NULL AND YEAR(permohonan_kasasi) = $tahun THEN 1 ELSE 0 END) kasasi_skrg,
     SUM(CASE WHEN b.`nomor_perkara_pn` IS NOT NULL AND YEAR(permohonan_kasasi) <> $tahun THEN 1 ELSE 0 END) kasasi_depan
     FROM sipp_perkara_banding a LEFT OUTER JOIN sipp_perkara_kasasi b
     ON a.`nomor_perkara_pa`=b.`nomor_perkara_pn`
     -- and nomor_perkara_banding='3/Pdt.G/2022/PTA.Mdn'
     WHERE YEAR(tanggal_pendaftaran_banding)=$tahun
     -- AND nomor_perkara_kasasi IS NULL
     GROUP BY a.idpn) z ON (aa.satsing=z.idpn)
     
     ORDER BY kode2 ASC";
        return $this->db->query($sql)->result_array();
	}

    function list_banding($satker,$tahun){
        $sql = "SELECT * FROM sipp_perkara_kasasi a RIGHT OUTER JOIN sipp_perkara_banding b 
        ON (a.idpn=b.idpn AND a.nomor_perkara_pn=b.`nomor_perkara_pa`) 
        WHERE b.idpn='$satker' 
        AND YEAR(tanggal_pendaftaran_banding)=$tahun";
       # echo $sql;
        return $this->db->query($sql)->result_array();
   }

    function list_kasasi_skrg($satker,$tahun){
        $sql = "SELECT * FROM sipp_perkara_kasasi a RIGHT OUTER JOIN sipp_perkara_banding b  ON (a.idpn=b.idpn AND a.nomor_perkara_pn=b.`nomor_perkara_pa`) 
         WHERE a.idpn='$satker' 
        AND YEAR(tanggal_pendaftaran_banding)=$tahun 
        AND YEAR(permohonan_kasasi)=$tahun 
        and status_kasasi_text is not null";
       # echo $sql;
        return $this->db->query($sql)->result_array();
    }

    function list_kasasi_depan($satker,$tahun){
        $sql = "SELECT * FROM sipp_perkara_kasasi a RIGHT OUTER JOIN sipp_perkara_banding b  ON (a.idpn=b.idpn AND a.nomor_perkara_pn=b.`nomor_perkara_pa`) 
         WHERE a.idpn='$satker' 
        AND YEAR(tanggal_pendaftaran_banding)=$tahun 
        AND YEAR(permohonan_kasasi)>$tahun 
        and status_kasasi_text is not null";
       # echo $sql;
        return $this->db->query($sql)->result_array();
    }


    function list_req_unlock(){
       $sql = "SELECT a.id, a.perkara_id, b.nomor_perkara_pa,jenis_edoc,tgl_request,req_alasan,permohonan_banding, pemohon_banding FROM req_unlock a,sipp_perkara_banding b
       WHERE a.`perkara_id`=b.`perkara_id`
       AND a.`idpn`=b.`idpn`
       AND  tgl_unlock IS null       ";
      return $this->db->query($sql)->result_array(); 
    }
	
}
?>
