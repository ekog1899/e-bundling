<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_akses extends CI_Model
{

    public $database = '';
    public $kodepa = '';

    function __construct()
    {
        parent::__construct();
        $this->config->load('sms_config', TRUE);
        // $this->database=$this->config->item('db_dakung_banding');

    }



    // check user PTA

    function check_user_pta()
    {
        $this->db->select('nama, case when id = 85 then TRUE else FALSE end is_verifikator,kode_jabatan');
        $this->db->from("t_user_pta");
        $this->db->where("username", $this->input->post('username'));
        $this->db->where("password", md5($this->input->post('password')));
        $this->db->limit(1);
        $sql = "select nama,nip, grup, case when kode_jabatan in (41) then TRUE else FALSE end is_verifikator,kode_jabatan from t_user_pta where username='" . $this->input->post('username') . "' and password='" . md5($this->input->post('password')) . "' limit 1";
        $query =  $this->db->query($sql);
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result_array();
        }
    }


    public function satkerlist()
    {
        $this->db->select('idpn,satkername');
        $data = $this->db->get('satkerlist');
        return $data->result_array();
    }



    function get_pp_banding_id_by_nip($nip)
    {
        $res = $this->db->query("select id from panitera_pt where nip ='" . $nip . "'")->result_array();
        return $res[0]['id'];
    }

    function get_hakim_banding_id_by_nip($nip)
    {
        $res = $this->db->query("select id from hakim_pt where nip ='" . $nip . "'")->result_array();
        return $res[0]['id'];
    }

    function chek_userpass()
    {
        // 1. login SIPP

        $kweri_password = $this->db->query("select password,username from sipp_user where nip='" . $this->input->post('username') . "'")->row();
        if (!empty($kweri_password->password)) {

            $password = $kweri_password->password;
            $username = $kweri_password->username;
        } else {

            $password = '';
            $username = '';
        }

        $pass = $this->getPassword();



        if ($pass != false and !empty($password)) {
            if ($pass === $password) {

                $kweri_user = $this->db->query("
                                               select a.idpn,username,fullname,satkername from  sipp_user a, satkerlist b
                                               where a.username='$username'
                                               and a.idpn=b.idpn
                                            
                                             ")->row();
                #echo $this->db->last_query();exit;
                if ($kweri_user->idpn <> '') {

                    $iduser = 0;
                } else {

                    $this->session->set_userdata('logine', 'oraoke');
                    $this->session->set_userdata('hasil', 'gadjah');
                    return 'gajah';
                }

                $data_login = array(
                    'idpn' => $kweri_user->idpn,
                    'satker' => $kweri_user->satkername,
                    'nama' => $kweri_user->fullname,
                    'role' => 'op_satker',
                    'login_time' => date('Y-m-d H:i:s')
                );

                $this->session->set_userdata($data_login);
                return 'wedhus';
            } else {
                $this->session->set_userdata('logine', 'oraoke');
                $this->session->set_userdata('hasil', 'gadjah');
                return 'gajah';
            }
        } else {
            $this->session->set_userdata('logine', 'oraoke');
            $this->session->set_userdata('hasil', 'gadjah');
            return 'gajah';
        }
    }


    function arr2md5($arrinput)
    {
        $hasil = '';
        foreach ($arrinput as $val) {
            if ($hasil == '') {
                $hasil = md5($val);
            } else {
                $code = md5($val);
                for ($hit = 0; $hit < min(array(strlen($code), strlen($hasil))); $hit++) {
                    $hasil[$hit] = chr(ord($hasil[$hit]) ^ ord($code[$hit]));
                }
            }
        }
        return (md5($hasil));
    }


    function getPassword()
    {
        $kweri = $this->db->query("select code_activation from sipp_user where nip='" . $this->input->post('username', TRUE) . "'")->row();
        if (!empty($kweri->code_activation)) {
            $pass = $this->arr2md5(array($kweri->code_activation, $this->input->post('password', TRUE)));

            return $pass;
        } else {
            return false;
        }
    }

    function is_login()
    {
        if ($this->session->userdata('nama') <> '')
            return true;
        else return false;
    }

    function get_hakim_banding()
    {
        $sql = "select * from hakim_pt where aktif ='Y'";
        return $this->db->query($sql)->result_array();
    }

    function get_pp_banding()
    {
        $sql = "select * from panitera_pt where aktif ='Y'";
        return $this->db->query($sql)->result_array();
    }
}
