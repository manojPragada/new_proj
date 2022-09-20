<?php

class U_model extends CI_Model {
	function get_data($table){
		$data = $this->db->get($table)->result();
		return $data;
	}
	function get_data_with_condition($where, $table){
		$data = $this->db->where($where)->get($table)->result();
		return $data;
	}
	function get_data_with_limit($limit, $table){
		$data = $this->db->limit($limit, 0)->get($table)->result();
		return $data;
	}
	function post_message($table,$data){
		$res = $this->db->insert($table, $data);
		return $res;
	}
}