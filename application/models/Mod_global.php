<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_global extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	function getData($query) {
	    $query = $this->db->query($query) ;
			return $query->result_array();
	}

	function getDataOne($query) {
    $query = $this->db->query($query) ;
		return $query->row_array();
	}
	
	public function save($data, $tabel){
		$this->db->insert($tabel, $data);
		return true;
	}

	public function updatedata($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}

	public function select_data($tabel,$where){
		$this->db->select('*');
	  	$this->db->from($tabel);
	  	$this->db->where($where);
	  	$this->db->limit(1);
	 
	  $query = $this->db->get();
	  if($query -> num_rows() == 1){
			return $query->result();
	  }
	}

	public function get_by_id($tabel,$where){
		$this->db->from($tabel);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_by_id_advanced($select=false, $table, $where=false, $join=false, $order=false, $single=false)
	{
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select('*');
		}

		$this->db->from($table);

		if ($join) {
			foreach($join as $j) :
				$this->db->join($j["table"], $j["on"],'left');
			endforeach;
		}
		
		if ($where) {
			$this->db->where($where);
		}
		
		if ($order) {
			$this->db->order_by($order);
		}

		if ($single) {
			return $this->db->get()->row_array();
		}else{
			return $this->db->get()->result_array();
		}
		
	}
}