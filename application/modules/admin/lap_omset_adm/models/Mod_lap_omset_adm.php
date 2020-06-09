<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_lap_omset_adm extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_report($tanggal_awal, $tanggal_akhir)
	{
		$this->db->select('
			lb.id_pembelian,
			CAST( lb.created_at AS DATE ) AS tgl_bayar,
			lb.created_at,
			lb.id_checkout,
			lb.nominal,
			lb.bea_layanan,
			lb.bea_vendor,
			lb.omset_bruto,
			lb.omset_nett
		');
		$this->db->from('tbl_laba_adm as lb');
		$this->db->where('CAST( lb.created_at AS DATE ) >=', $tanggal_awal);
		$this->db->where('CAST( lb.created_at AS DATE ) <=', $tanggal_akhir);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_detail_footer($id_user)
	{
		$this->db->select('fname_user, lname_user');
		$this->db->from('tbl_user');
        $this->db->where('id_user', $id_user);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
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