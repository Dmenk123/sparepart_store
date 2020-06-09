<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_penjualan_adm extends CI_Model
{
	var $column_search = array(
			'tbl_pembelian.id_pembelian',
			'tbl_user.fname_user',
			'tbl_checkout.fname_kirim',
			'tbl_checkout.alamat_kirim',
			'tbl_checkout.kode_ref',
			'tbl_checkout_detail.qty',
			'tbl_pembelian.status_confirm_adm'
		);

	var $column_order = array(
			'tbl_pembelian.id_pembelian',
			'tbl_user.fname_user',
			'tbl_checkout.fname_kirim',
			'tbl_checkout.alamat_kirim',
			'tbl_checkout.kode_ref',
			'tbl_checkout_detail.qty',
			'tbl_pembelian.status_confirm_adm',
			null,
		);
	var $order = array('tbl_checkout.id_checkout' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// ================================================================================================

	private function _get_datatable_penjualan_query($term='', $id_vendor="") //term is value of $_REQUEST['search']
	{
		$column = array(
				'tbl_pembelian.id_pembelian',
				'tbl_user.fname_user',
				'tbl_checkout.fname_kirim',
				'tbl_checkout.alamat_kirim',
				'tbl_checkout.kode_ref',
				'tbl_checkout_detail.qty',
				'tbl_pembelian.status_confirm_adm',
				null,
			);
		if ($id_vendor != "") {
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
				btv.is_confirm,
				COUNT(tbl_checkout_detail.id_checkout) AS jml
			');
		}else{
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
		}

		$this->db->from('tbl_pembelian');
		$this->db->join('tbl_checkout', 'tbl_pembelian.id_checkout = tbl_checkout.id_checkout', 'left');
		$this->db->join('tbl_user', 'tbl_pembelian.id_user = tbl_user.id_user', 'left');
		$this->db->join('tbl_checkout_detail', 'tbl_checkout.id_checkout = tbl_checkout_detail.id_checkout','left');
		$this->db->join('tbl_trans_vendor', 'tbl_pembelian.id_pembelian = tbl_trans_vendor.id_pembelian','left');
		if ($id_vendor != "") {
			$this->db->join("(SELECT * from tbl_bukti_transfer_vendor) btv","tbl_pembelian.id_pembelian = btv.id_pembelian AND (btv.id_vendor = '".$id_vendor."')", "left");
			$this->db->where('tbl_trans_vendor.id_vendor', $id_vendor);
		}
		$this->db->group_by('tbl_checkout_detail.id_checkout');
		$i = 0;
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
			{
				if($i===0) 
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i) 
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatable_penjualan($id_vendor="")
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_penjualan_query($term, $id_vendor);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_penjualan($id_vendor="")
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_penjualan_query($term, $id_vendor);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_penjualan($id_vendor="")
	{
		if ($id_vendor != "") {
			$this->db->select('
				tbl_pembelian.id_pembelian,
				tbl_user.fname_user,
				tbl_user.lname_user,
				tbl_checkout.fname_kirim,
				tbl_checkout.lname_kirim,
				tbl_checkout.alamat_kirim,
				tbl_checkout.ongkos_total,
				tbl_checkout.kode_ref,
				tbl_pembelian.status_confirm_adm,
				tbl_pembelian.status_confirm_vendor,
				btv.is_confirm,
				COUNT(tbl_checkout_detail.id_checkout) AS jml
			');
		}else{
			$this->db->select('
				tbl_pembelian.id_pembelian,
				tbl_user.fname_user,
				tbl_user.lname_user,
				tbl_checkout.fname_kirim,
				tbl_checkout.lname_kirim,
				tbl_checkout.alamat_kirim,
				tbl_checkout.ongkos_total,
				tbl_checkout.kode_ref,
				tbl_pembelian.status_confirm_adm,
				tbl_pembelian.status_confirm_vendor,
				COUNT(tbl_checkout_detail.id_checkout) AS jml
			');
		}
		

		$this->db->from('tbl_pembelian');
		$this->db->join('tbl_checkout', 'tbl_pembelian.id_checkout = tbl_checkout.id_checkout', 'left');
		$this->db->join('tbl_user', 'tbl_pembelian.id_user = tbl_user.id_user', 'left');
		$this->db->join('tbl_checkout_detail', 'tbl_checkout.id_checkout = tbl_checkout_detail.id_checkout','left');
		$this->db->join('tbl_trans_vendor', 'tbl_pembelian.id_pembelian = tbl_trans_vendor.id_pembelian','left');
		if ($id_vendor != "") {
			$this->db->join("(SELECT * from tbl_bukti_transfer_vendor) btv","tbl_pembelian.id_pembelian = btv.id_pembelian AND (btv.id_vendor = '".$id_vendor."')", "left");
			$this->db->where('tbl_trans_vendor.id_vendor', $id_vendor);
		}
		$this->db->group_by('tbl_checkout_detail.id_checkout');
		return $this->db->count_all_results();
	}

	// ================================================================================================
	public function cek_tipe_pembelian($id_pembelian)
	{
		$this->db->select('beli.id_pembelian, beli.id_checkout, beli.id_user, beli.tgl_pembelian, ckt.is_jasa');
		$this->db->from('tbl_pembelian as beli');
		$this->db->join('tbl_checkout as ckt', 'beli.id_checkout = ckt.id_checkout', 'left');
		$this->db->where(['beli.id_pembelian' => $id_pembelian]);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_data_id_checkout($id_penjualan)
	{
		$this->db->select('id_checkout');
		$this->db->from('tbl_pembelian');
		$this->db->where('id_pembelian', $id_penjualan);
		$query = $this->db->get('');
		$hasil = $query->row();
		return $hasil->id_checkout;
	}

	public function get_data_penjualan_header($id_penjualan, $id_vendor = "")
	{
		$this->db->select('
			tbl_pembelian.id_pembelian,
			tbl_pembelian.id_user,
			tbl_user.fname_user,
			tbl_user.lname_user,
			tbl_user.email,
			tbl_checkout.method_checkout,
			tbl_checkout.jasa_ekspedisi,
			tbl_checkout.pilihan_paket,
			tbl_checkout.estimasi_datang,
			tbl_checkout.ongkos_kirim,
			tbl_checkout.fname_kirim,
			tbl_checkout.lname_kirim,
			tbl_checkout.alamat_kirim,
			tbl_pembelian.tgl_pembelian,
			tbl_pembelian.id_checkout,
			tbl_pembelian.btransfer_1,
			tbl_pembelian.btransfer_2,
			tbl_pembelian.btransfer_3,
			tbl_pembelian.status_confirm_vendor,
			tbl_pembelian.status_confirm_customer,
			tbl_pembelian.status_confirm_adm,
			tbl_bukti_transfer_vendor.bukti_transfer_vendor
		');
		$this->db->from('tbl_pembelian');
		$this->db->join('tbl_user', 'tbl_pembelian.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_checkout', 'tbl_pembelian.id_checkout = tbl_checkout.id_checkout', 'left');
		$this->db->join('tbl_bukti_transfer_vendor', 'tbl_pembelian.id_pembelian = tbl_bukti_transfer_vendor.id_pembelian', 'left');
        $this->db->where('tbl_pembelian.id_pembelian', $id_penjualan);
        if ($id_vendor != '') {
        	$this->db->where('tbl_bukti_transfer_vendor.id_vendor', $id_vendor);
        }
        $this->db->group_by('tbl_pembelian.id_pembelian');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_data_penjualan_detail($id_checkout, $id_vendor="")
	{
		$this->db->select('tbl_checkout.fname_kirim,
						   tbl_checkout.lname_kirim,
						   tbl_produk.nama_produk,
						   tbl_produk.varian_produk,
						   tbl_satuan.nama_satuan,
						   tbl_checkout_detail.qty,
						   tbl_produk.harga,
						   tbl_checkout_detail.harga_ongkir,
						   tbl_checkout_detail.harga_total
						');
		$this->db->from('tbl_checkout_detail');
		$this->db->join('tbl_checkout', 'tbl_checkout_detail.id_checkout = tbl_checkout.id_checkout','left');
		$this->db->join('tbl_produk', 'tbl_checkout_detail.id_produk = tbl_produk.id_produk','left');
		$this->db->join('tbl_satuan', 'tbl_checkout_detail.id_satuan = tbl_satuan.id_satuan', 'left');
		$this->db->join('tbl_stok', 'tbl_checkout_detail.id_stok = tbl_stok.id_stok', 'left');
        $this->db->where('tbl_checkout_detail.id_checkout', $id_checkout);
        if ($id_vendor != "") {
        	 $this->db->where('tbl_checkout_detail.id_vendor', $id_vendor);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function update_data_konfirmasi($where, $data)
	{
		$this->db->update("tbl_pembelian", $data, $where);
	}

	public function tambah_datalog_konfirmasi($data)
	{
		$this->db->insert('tbl_log', $data);
	}

	public function update_status_penjualan($where, $data)
	{
		$this->db->update("tbl_pembelian", $data, $where);
	}

	public function update_status_checkout($where, $data)
	{
		$this->db->update("tbl_checkout", $data, $where);
	}

	public function cek_token($id_checkout)
	{
		$this->db->select('token_confirm');
		$this->db->from('tbl_checkout');
		$this->db->where('id_checkout', $id_checkout);
		$query = $this->db->get()->row();
		if ($query->token_confirm != null) {
			return ['status' => TRUE, 'token_confirm' => $query->token_confirm];
		}else{
			return ['status' => FALSE, 'token_confirm' => ''];
		}
	}

	public function get_kode_terima_produk(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_trans_masuk,5)) as kode_max from tbl_trans_masuk WHERE DATE_FORMAT(tgl_trans_masuk, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "MSK".date('my').$kd;
    }

    public function get_kode_po()
    {
    	$this->db->select('id_trans_order'); 
        $this->db->from('tbl_trans_order_detail');
        $this->db->where('status_masuk', '0');
        $this->db->group_by('id_trans_order');
        $query = $this->db->get();
        return $query->result();
    }

    /*public function get_qty_order_masuk(){
		 $query = $this->db->query("SELECT id_trans_order FROM tbl_trans_order_detail WHERE status_masuk='0' OR qty > ANY (SELECT qty FROM tbl_trans_masuk_detail)");
		 return $query->result();
	}*/

	public function get_data_order_details($id_trans_order)
	{
		$this->db->select('tbl_trans_order_detail.id_trans_order,
						   tbl_produk.nama_produk,
						   tbl_trans_order_detail.id_trans_order_detail,
						   tbl_trans_order_detail.id_produk,
						   tbl_satuan.nama_satuan,
						   tbl_trans_order_detail.id_satuan,
						   tbl_trans_order_detail.id_stok,
						   tbl_trans_order_detail.ukuran,
						   tbl_trans_order_detail.qty,
						   tbl_trans_order_detail.harga_prev_beli,
						   tbl_trans_order_detail.harga_satuan_beli,
						   tbl_trans_order_detail.harga_total_beli');
		$this->db->from('tbl_trans_order_detail');
		$this->db->join('tbl_produk', 'tbl_trans_order_detail.id_produk = tbl_produk.id_produk','left');
		$this->db->join('tbl_satuan', 'tbl_trans_order_detail.id_satuan = tbl_satuan.id_satuan','left');
        $this->db->where('tbl_trans_order_detail.id_trans_order', $id_trans_order);
        $this->db->where('tbl_trans_order_detail.status_masuk', '0');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_masuk');
		$this->db->where('id_trans_masuk',$id);
		$query = $this->db->get();

		return $query->row();
	}

	

	public function lookup_id_supplier($idOrder)
	{
		$this->db->select('tbl_trans_order.id_supplier, tbl_supplier.nama_supplier');
		$this->db->from('tbl_trans_order');
		$this->db->join('tbl_supplier', 'tbl_trans_order.id_supplier = tbl_supplier.id_supplier', 'left');
		$this->db->where('tbl_trans_order.id_trans_order', $idOrder);
			
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
            return $query->row();
        }
	}

	public function simpan_data_masuk($data_masuk, $data_masuk_detail)
	{
		//insert into tbl_trans_masuk
		$this->db->insert('tbl_trans_masuk',$data_masuk);
		//insert into tbl_trans_masuk_detail 
		$this->db->insert_batch('tbl_trans_masuk_detail',$data_masuk_detail);
	}

	public function hapus_data_masuk_detail($id)
	{
		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk_detail');
	}

	public function update_data_header_masuk($where, $data_header)
	{
		$this->db->update('tbl_trans_masuk', $data_header, $where);
	}

	public function insert_update_masuk($data_masuk_detail)
	{
		$this->db->insert_batch('tbl_trans_masuk_detail',$data_masuk_detail);
	}

	public function delete_data_terima_produk($id)
	{
		//tbl header
		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk');
		//tbl detail
		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk_detail');
	}
  
	public function lookup_produk($keyword)
	{
		$this->db->select('tbl_produk.nama_produk, tbl_produk.id_produk, tbl_produk.harga, tbl_produk.id_satuan, tbl_satuan.nama_satuan');
		$this->db->from('tbl_produk');
		$this->db->join('tbl_satuan', 'tbl_produk.id_satuan = tbl_satuan.id_satuan', 'left');;
		$this->db->like('nama_produk',$keyword);
		$this->db->where('status', '1');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_data_status_masuk($where, $nilai)
	{
		$this->db->update('tbl_trans_order_detail', $nilai, $where);
	}

	public function get_id_trans_order($id_trans_masuk)
	{
	 	$this->db->select('id_trans_order');
	 	$this->db->from('tbl_trans_masuk_detail');
	 	$this->db->where('id_trans_masuk', $id_trans_masuk);
	 	$this->db->group_by('id_trans_order');
	 	$query = $this->db->get();
	 	$hasil = $query->row();
	 	return $hasil->id_trans_order;
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