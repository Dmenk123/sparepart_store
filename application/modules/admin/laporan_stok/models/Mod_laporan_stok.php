<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_laporan_stok extends CI_Model
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

	public function get_report_stok($id_vendor)
	{
		$this->db->select('tbl_stok.id_produk,
						   tbl_satuan.nama_satuan,
						   tbl_stok.berat_satuan,
						   tbl_stok.stok_sisa,
						   tbl_stok.stok_minimum,
						   tbl_stok.status,
						   tbl_produk.nama_produk');
		$this->db->from('tbl_produk');
		$this->db->join('tbl_stok', 'tbl_stok.id_produk = tbl_produk.id_produk', 'left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_produk.id_satuan', 'left');
		$this->db->join('tbl_vendor_produk', 'tbl_produk.id_produk = tbl_vendor_produk.id_produk', 'left');
		$this->db->where('tbl_vendor_produk.id_vendor', $id_vendor);
		$this->db->where('tbl_stok.status', '1');
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