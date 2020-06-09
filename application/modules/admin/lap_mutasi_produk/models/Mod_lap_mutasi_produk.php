<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_lap_mutasi_produk extends CI_Model
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

	public function get_detail_mutasi_produk($id_vendor, $tanggal_awal, $tanggal_akhir)
	{
		$query =  $this->db->query("
			SELECT
				s.id_stok,
				s.id_produk,
				p.nama_produk,
				(select(
						s.stok_awal + coalesce(pre_t_msk.qty_masuk_pre, 0) - coalesce(pre_t_keluar.qty_keluar_pre, 0)
					)
				) as stok_awal, 
				coalesce(t_msk.qty_masuk, 0) as masuk,
  				coalesce(t_keluar.qty_keluar, 0) as keluar,
				(select(
						s.stok_awal + coalesce(pre_t_msk.qty_masuk_pre, 0) - coalesce(pre_t_keluar.qty_keluar_pre, 0) + coalesce(t_msk.qty_masuk, 0) - coalesce(t_keluar.qty_keluar, 0)
					)
				) as stok_sisa,
				vp.id_vendor
			FROM
				tbl_stok s
			LEFT JOIN tbl_vendor_produk vp ON s.id_produk = vp.id_produk 
			LEFT JOIN tbl_produk p on s.id_produk = p.id_produk
			LEFT JOIN (
				select 
					sum(qty) as qty_masuk_pre,
					id_produk 
				from tbl_penerimaan 
				where id_vendor = '".$id_vendor."' and tanggal <= '1990-01-01' and tanggal < '".$tanggal_awal."'
				GROUP BY id_produk
			) as pre_t_msk on s.id_produk = pre_t_msk.id_produk
			LEFT JOIN (
				SELECT
					cd.id_vendor,
					cd.id_produk,
					sum(qty) as qty_keluar_pre
				FROM
					tbl_checkout_detail cd
					JOIN tbl_checkout c ON cd.id_checkout = c.id_checkout 
					JOIN tbl_pembelian beli ON cd.id_checkout = beli.id_checkout 
				WHERE
					cd.id_vendor = '".$id_vendor."' and c.tgl_checkout <= '1990-01-01' and c.tgl_checkout < '".$tanggal_awal."'
				GROUP BY id_produk
			) as pre_t_keluar on s.id_produk = pre_t_keluar.id_produk
			LEFT JOIN (
				select 
					sum(qty) as qty_masuk,
					id_produk 
				from tbl_penerimaan 
				where id_vendor = '".$id_vendor."' and tanggal BETWEEN '".$tanggal_awal."' and '".$tanggal_akhir."'
				GROUP BY id_produk
			) as t_msk on s.id_produk = t_msk.id_produk
			LEFT JOIN (
				SELECT
					cd.id_vendor,
					cd.id_produk,
					sum(qty) as qty_keluar
				FROM
					tbl_checkout_detail cd
					JOIN tbl_checkout c ON cd.id_checkout = c.id_checkout 
					JOIN tbl_pembelian beli ON cd.id_checkout = beli.id_checkout 
				WHERE
					cd.id_vendor = '".$id_vendor."' and c.tgl_checkout BETWEEN '".$tanggal_awal."' and '".$tanggal_akhir."'
				GROUP BY id_produk
			) as t_keluar on s.id_produk = t_keluar.id_produk
			WHERE
				vp.id_vendor = '".$id_vendor."'
		");
        return $query->result();
	}

}