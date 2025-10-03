<?php
class Notif_model extends CI_Model {

	function get_wait_disposisi($user_id){
			$sql = "SELECT COUNT(1) jml FROM
				(
				SELECT id,tgl_diterima last_update, REPLACE(diteruskan_ke,'0,','') id_jab_pelaksana
					FROM t_surat_masuk
					UNION
					SELECT id_surat,waktu_input,`id_jab_pelaksana`
					FROM t_disposisi
					WHERE YEAR(waktu_input)=YEAR(NOW())
				) z1,
				(
				SELECT id,MAX(tgl_diterima) last_update
					FROM
					(
					SELECT id,tgl_diterima, REPLACE(diteruskan_ke,'0,','') id_jab_pelaksana
					FROM t_surat_masuk
					UNION
					SELECT id_surat,waktu_input,`id_jab_pelaksana` FROM t_disposisi
					) AS z
					WHERE YEAR(z.tgl_diterima)=YEAR(NOW())
					GROUP BY id
				) z2 ,t_user u,t_surat_masuk s
				WHERE z1.id=z2.id
				AND  z1.id_jab_pelaksana=u.`kode_jabatan`
				AND z1.last_update=z2.last_update
				AND DATE_ADD(NOW(), INTERVAL 1 HOUR) > z1.last_update
				AND z1.id=s.`id`
				AND z2.id=s.`id`
				AND u.id=$user_id
				AND diarsipkan=0
				";
				return $this->db->query($sql)->row();
	}

	function get_blm_cek_draft($user_id){
			$sql = "SELECT count(1) jml FROM t_draft a, (SELECT id_draft,MAX(id) id_rev
                FROM t_draft_revisi GROUP BY id_draft) b, t_draft_revisi c
                WHERE a.id=b.id_draft
                AND b.id_rev=c.id
                AND
                ((id_konseptor='$user_id' AND STATUS IN (12,22)) OR (id_korektor='$user_id' AND STATUS=1) OR (id_pejabat='$user_id' AND STATUS=11))
				";
       # echo $sql;
				return $this->db->query($sql)->row();
	}


}
?>
