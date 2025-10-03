<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Update_versi extends CI_Model{
		function __construct(){
			$this->load->dbforge();
			//$this->dbsipp = $this->load->database("dbsipp",TRUE);
			$this->run_this();
			echo "<li>Query Database Berhasil dijalankan</li>";
		}
		
		function isColumnExist($table_name,$column_name){
			try {
				$result = $this->db->query('
				SELECT COLUMN_NAME AS colname
			    FROM information_schema.columns 
			    WHERE TABLE_NAME = "'.$table_name.'"
			    AND COLUMN_NAME = "'.$column_name.'" AND TABLE_SCHEMA = "'.$this->db->database.'" 
			    GROUP BY column_name;
				');
				if($result->num_rows()>0){
					return $result->row()->colname;
					}else{
					return '';
				}
				} catch (Exception $e) {
				return FALSE;
			}
		}
			
		function run_this(){
			
			$this->db->where(array('param' => 'sys_version'));
			$res=$this->db->update('sys_config', array('value' => '2.01'));
			return $res;
			
		}
	}
