<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_dashboard_adm extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_produk()
	{
		$this->db->select('
			tbl_produk.id_produk,
			tbl_produk.nama_produk,
			tbl_stok.stok_sisa,
			tbl_stok.stok_minimum,
			tbl_stok.status
		');
		$this->db->from('tbl_produk');
		$this->db->join('tbl_stok', 'tbl_produk.id_produk = tbl_stok.id_produk', 'left');
		$this->db->where('tbl_stok.status', '1');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_produk_vendor_flashdata($id_vendor)
	{
		$this->db->select('
			tbl_produk.id_produk,
			tbl_produk.nama_produk,
			tbl_stok.stok_sisa,
			tbl_stok.stok_minimum,
			tbl_stok.status
		');
		$this->db->from('tbl_produk');
		$this->db->join('tbl_stok', 'tbl_produk.id_produk = tbl_stok.id_produk', 'left');
		$this->db->join('tbl_vendor_produk', 'tbl_produk.id_produk = tbl_vendor_produk.id_produk', 'left');
		$this->db->where('tbl_stok.status', '1');
		$this->db->where('tbl_vendor_produk.id_vendor', $id_vendor);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_user($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_user');
		$this->db->where('id_user', $id);
		
		$query = $this->db->get();
		return $query->result();
	}

	public function email_notif_count($id_user) 
	{
        $this->db->from('tbl_pesan');
        $this->db->where('dt_read', null);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_email_notif($id_user) 
    {
    	$this->db->select('*');
        $this->db->from('tbl_pesan');
        $this->db->where('dt_read', null);
        $this->db->order_by('id_pesan', 'DESC');
 
        $query = $this->db->get();
 
        if ($query->num_rows() >0) {
            return $query->result();
        }
    }

    public function get_count_produk()
	{
		$this->db->select('id_produk, COUNT(id_produk) AS jumlah_produk');
		$this->db->from('tbl_produk');
		$this->db->where('status', '1');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_count_stok()
	{
		$this->db->select('tbl_stok.id_produk, tbl_produk.nama_produk, tbl_stok.stok_sisa');
		$this->db->from('tbl_stok');
		$this->db->join('tbl_produk', 'tbl_stok.id_produk = tbl_produk.id_produk', 'left');
		$this->db->where('tbl_stok.status', '1');
		$this->db->order_by('tbl_stok.stok_sisa', 'desc');
		$this->db->limit(5);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_count_user()
	{
		$this->db->select('id_user, COUNT(id_user) AS jumlah_user');
		$this->db->from('tbl_user');
		$this->db->where('status', '1');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_count_user_level()
	{
		$this->db->select('tbl_user.id_user, tbl_level_user.nama_level_user , count(tbl_level_user.id_level_user) AS jumlah_level');
		$this->db->from('tbl_user');
		$this->db->join('tbl_level_user', 'tbl_user.id_level_user = tbl_level_user.id_level_user', 'left');
		$this->db->where('tbl_user.status', '1');
		$this->db->group_by('tbl_level_user.id_level_user');

		$query = $this->db->get();
		return $query->result();
	}

	public function get_count_new_vendor()
	{
		$this->db->select('count(id_user) as jml_vendor');
		$this->db->from('tbl_user');
		$this->db->where([
			'id_level_user' => 4,
			'status' => 0,
			'last_login' => null
		]);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_count_new_penjualan($id_vendor)
	{
		$this->db->select('count(id) as jml_trans');
		$this->db->from('tbl_checkout_detail');
		$this->db->join('tbl_pembelian', 'tbl_checkout_detail.id_checkout = tbl_pembelian.id_checkout', 'left');
		$this->db->where([
			'tbl_checkout_detail.id_vendor' => $id_vendor,
			'tbl_checkout_detail.status' => 'nonaktif',
			'tbl_pembelian.status_confirm_vendor' => 0
		]);
		$this->db->group_by('tbl_checkout_detail.id_vendor');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_count_kat_vendor($id_vendor, $count=FALSE)
	{
		$this->db->select('
			vk.id_kategori, 
			kp.nama_kategori,
			count( vp.id_produk ) AS qty
		');

		$this->db->from('tbl_vendor_kategori as vk');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join('tbl_vendor_produk as vp', 'vk.id_kategori = vp.id_kategori and vp.id_vendor = "'.$id_vendor.'"','left');
		$this->db->join('tbl_kategori_produk as kp', 'vk.id_kategori = kp.id_kategori','left');
		$this->db->where('vk.id_vendor', $id_vendor);
		$this->db->group_by('kp.nama_kategori');
		$query = $this->db->get();
		if ($count == TRUE) {
			return $query->num_rows();
		}else{
			return $query->result();
		}
	}

	public function get_count_omset($id_vendor, $count=FALSE)
	{
		$this->db->select('
			vk.id_kategori, 
			kp.nama_kategori,
			count( vp.id_produk ) AS qty
		');

		$this->db->from('tbl_vendor_kategori as vk');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join('tbl_vendor_produk as vp', 'vk.id_kategori = vp.id_kategori and vp.id_vendor = "'.$id_vendor.'"','left');
		$this->db->join('tbl_kategori_produk as kp', 'vk.id_kategori = kp.id_kategori','left');
		$this->db->where('vk.id_vendor', $id_vendor);
		$this->db->group_by('kp.nama_kategori');
		$query = $this->db->get();
		if ($count == TRUE) {
			return $query->num_rows();
		}else{
			return $query->result();
		}
	}

	public function get_count_produk_vendor($id_vendor, $count=FALSE)
	{
		$this->db->select('
			vp.id_produk,
			p.nama_produk,
			s.stok_sisa
		');

		$this->db->from('tbl_produk as p');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join('tbl_vendor_produk as vp', 'p.id_produk = vp.id_produk and vp.id_vendor = "'.$id_vendor.'"','left');
		$this->db->join('tbl_stok as s', 'p.id_produk = s.id_produk', 'left');
		$this->db->where('vp.id_vendor', $id_vendor);
		$this->db->group_by('p.nama_produk');
		if ($count == TRUE) {
			$query = $this->db->get();
			return $query->num_rows();
		}else{
			$this->db->order_by('p.created', 'desc');
			$this->db->limit(5);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_count_omset_dashboard($count=FALSE)
	{
		$this->db->select("
			tr.id,
			tr.id_pembelian,
			tr.id_rekber,
			tr.nominal,
			tr.nilai_ongkir,
			tr.harga_pembelian,
			tr.status_penarikan,
			CONCAT(r.nama,' - ',r.no_rek) as rekening,
			r.penyedia,
			r.biaya_adm,
			p.id_checkout
		");
		
		$this->db->from('tbl_trans_rekber as tr');
		$this->db->join('tbl_m_rekber as r', 'tr.id_rekber = r.id', 'left');
		$this->db->join('tbl_pembelian as p', 'tr.id_pembelian = p.id_pembelian', 'left');
		$this->db->where('tr.status_penarikan', 1);

		if ($count) {
			$query = $this->db->get();
			return $query->num_rows();
		}else{
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_count_penjualan_adm($count=FALSE)
	{
		$this->db->select('
			tbl_pembelian.id_pembelian,
			tbl_user.fname_user,
			tbl_user.lname_user,
			tbl_checkout.fname_kirim,
			tbl_checkout.lname_kirim,
			tbl_checkout.alamat_kirim,
			tbl_checkout.kode_ref,
			tbl_pembelian.status_confirm_adm,
			tbl_pembelian.status_confirm_customer,
			tbl_pembelian.status_confirm_vendor,
			COUNT(tbl_checkout_detail.id_checkout) AS jml
		');

		$this->db->from('tbl_pembelian');
		$this->db->join('tbl_checkout', 'tbl_pembelian.id_checkout = tbl_checkout.id_checkout', 'left');
		$this->db->join('tbl_user', 'tbl_pembelian.id_user = tbl_user.id_user', 'left');
		$this->db->join('tbl_checkout_detail', 'tbl_checkout.id_checkout = tbl_checkout_detail.id_checkout','left');
		$this->db->join('tbl_trans_vendor', 'tbl_pembelian.id_pembelian = tbl_trans_vendor.id_pembelian','left');
		$this->db->where('tbl_pembelian.status_confirm_adm', 0);
		$this->db->group_by('tbl_checkout_detail.id_checkout');
		if ($count) {
			$query = $this->db->get();
			return $query->num_rows();
		}else{
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_count_data_user($count=FALSE, $id_level_user)
	{
		$this->db->select('*');
		$this->db->from('tbl_user');
		$this->db->where('id_level_user', $id_level_user);
		if ($count) {
			$query = $this->db->get();
			return $query->num_rows();
		}else{
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_report_omset_adm($tanggal_awal, $tanggal_akhir)
	{
		$this->db->select('
			CAST( lb.created_at AS DATE ) AS tgl_bayar,
			sum(lb.omset_nett) as total_omset_nett
		');
		$this->db->from('tbl_laba_adm as lb');
		$this->db->where('CAST( lb.created_at AS DATE ) >=', $tanggal_awal);
		$this->db->where('CAST( lb.created_at AS DATE ) <=', $tanggal_akhir);
		$this->db->group_by('tgl_bayar');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_report_omset_vendor($id_vendor, $tanggal_awal, $tanggal_akhir)
	{
		$this->db->select('
			CAST( lb.created_at AS DATE ) AS tgl_bayar,
			sum( lb.omset_nett ) as total_omset_nett
		');
		$this->db->from('tbl_laba_vendor as lb');
		$this->db->join('tbl_vendor as v', 'lb.id_vendor = v.id_vendor', 'left');
		$this->db->where('lb.id_vendor', $id_vendor);
		$this->db->where('CAST( lb.created_at AS DATE ) >=', $tanggal_awal);
		$this->db->where('CAST( lb.created_at AS DATE ) <=', $tanggal_akhir);
		$this->db->group_by('tgl_bayar');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_report_mutasi_produk($id_vendor, $tanggal_awal, $tanggal_akhir)
	{
		$query =  $this->db->query("
			SELECT
				concat(s.id_produk,' - ',p.nama_produk) as nama_produk,
				COALESCE ( t_keluar.qty_keluar, 0 ) AS total_keluar
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