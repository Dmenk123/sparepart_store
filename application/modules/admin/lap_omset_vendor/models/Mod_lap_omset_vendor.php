<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_lap_omset_vendor extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_detail($tanggal_awal, $tanggal_akhir)
	{
		$query =  $this->db->query(
		"SELECT tbl_trans_order_detail.tgl_trans_order_detail, tbl_barang.nama_barang, tbl_satuan.nama_satuan, tbl_trans_order_detail.qty_order, tbl_trans_order_detail.keterangan_order
		 FROM tbl_trans_order_detail
		 LEFT JOIN tbl_barang ON tbl_trans_order_detail.id_barang = tbl_barang.id_barang 
		 LEFT JOIN tbl_satuan ON tbl_trans_order_detail.id_satuan = tbl_satuan.id_satuan 
		 WHERE tbl_trans_order_detail.tgl_trans_order_detail 
		 BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' ORDER BY tbl_trans_order_detail.tgl_trans_order_detail ASC");

         return $query->result();
	}

	public function get_report($id_vendor, $tanggal_awal, $tanggal_akhir)
	{
		$this->db->select('
			v.nama_vendor,
			lb.id_pembelian,
			CAST( lb.created_at AS DATE ) AS tgl_bayar,
			lb.created_at,
			lb.id_checkout,
			lb.ongkir,
			lb.omset_bruto,
			lb.potongan,
			lb.omset_nett
		');
		$this->db->from('tbl_laba_vendor as lb');
		$this->db->join('tbl_vendor as v', 'lb.id_vendor = v.id_vendor', 'left');
		$this->db->where('lb.id_vendor', $id_vendor);
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