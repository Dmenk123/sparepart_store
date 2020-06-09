<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_monitoring_sewa extends CI_Model
{
	var $column_search = array(
			'ckt.id_checkout',
			'CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap',
			'ckt.alamat_kirim',
			'ckt.tgl_checkout',
			'jas.id_produk',
			'jas.nama_produk',
			'jas.durasi',
			'jas.qty_produk',
			'jas.harga_produk',
			'DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired',
			'user.email',
			'user.no_telp_user'
		);

	var $column_order = array(
			'ckt.id_checkout',
			'CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap',
			'ckt.alamat_kirim',
			'ckt.tgl_checkout',
			'jas.id_produk',
			'jas.nama_produk',
			'jas.durasi',
			'jas.qty_produk',
			'jas.harga_produk',
			'DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired',
			'user.email',
			'user.no_telp_user',
			null,
		);

	var $order = array('ckt.tgl_checkout' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// ================================================================================================

	private function _get_datatable_sewa_query($term='', $id_vendor="") //term is value of $_REQUEST['search']
	{
		$column = array(
				'ckt.id_checkout',
				'CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap',
				'ckt.alamat_kirim',
				'ckt.tgl_checkout',
				'jas.id_produk',
				'jas.nama_produk',
				'jas.durasi',
				'jas.qty_produk',
				'jas.harga_produk',
				'DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired',
				'user.email',
				'user.no_telp_user',
				null,
			);
		
		$this->db->select('
			ckt.id_checkout,
			CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap,
			ckt.alamat_kirim,
			ckt.tgl_checkout,
			ckt.kode_ref,
			jas.id as id_jasa,
			jas.id_produk,
			jas.nama_produk,
			jas.durasi,
			jas.qty_produk,
			jas.harga_produk,
			DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired,
			user.email,
			user.no_telp_user,
		');
		
		$this->db->from('tbl_checkout as ckt');
		$this->db->join('tbl_checkout_detail as cktd', 'ckt.id_checkout = cktd.id_checkout and ckt.is_jasa = 1', 'left');
		$this->db->join('tbl_temp_jasa as jas', 'cktd.id_temp_jasa = jas.id', 'left');
		$this->db->join('tbl_user as user', 'ckt.id_user = user.id_user','left');
		$this->db->where('jas.id_vendor', $id_vendor);
		$this->db->where('jas.tgl_selesai', null);
		$this->db->group_by('ckt.id_checkout');

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

	function get_list_sewa($id_vendor)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_sewa_query($term, $id_vendor);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_sewa($id_vendor="")
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_sewa_query($term, $id_vendor);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_sewa($id_vendor="")
	{
		$this->db->select('
			ckt.id_checkout,
			CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap,
			ckt.alamat_kirim,
			ckt.tgl_checkout,
			ckt.kode_ref,
			jas.id_produk,
			jas.nama_produk,
			jas.durasi,
			jas.qty_produk,
			jas.harga_produk,
			ckt.ongkos_total,
			DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired,
			user.email,
			user.no_telp_user,
		');
		
		$this->db->from('tbl_checkout as ckt');
		$this->db->join('tbl_checkout_detail as cktd', 'ckt.id_checkout = cktd.id_checkout and ckt.is_jasa = 1', 'left');
		$this->db->join('tbl_temp_jasa as jas', 'cktd.id_temp_jasa = jas.id', 'left');
		$this->db->join('tbl_user as user', 'ckt.id_user = user.id_user','left');
		$this->db->where('jas.id_vendor', $id_vendor);
		$this->db->where('jas.tgl_selesai', null);
		$this->db->group_by('ckt.id_checkout');
		return $this->db->count_all_results();
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

	public function cek_bisa_sewa($id_vendor, $id_user)
	{
		$this->db->select('
			ckt.id_checkout,
			CONCAT(ckt.fname_kirim," ",ckt.lname_kirim) as nama_lengkap,
			ckt.alamat_kirim,
			ckt.tgl_checkout,
			ckt.kode_ref,
			jas.id_produk,
			jas.nama_produk,
			jas.durasi,
			jas.qty_produk,
			jas.harga_produk,
			DATE_ADD(ckt.tgl_checkout,INTERVAL jas.durasi DAY) as tgl_expired,
			user.email,
			user.no_telp_user,
		');
		
		$this->db->from('tbl_checkout as ckt');
		$this->db->join('tbl_checkout_detail as cktd', 'ckt.id_checkout = cktd.id_checkout and ckt.is_jasa = 1', 'left');
		$this->db->join('tbl_temp_jasa as jas', 'cktd.id_temp_jasa = jas.id', 'left');
		$this->db->join('tbl_user as user', 'ckt.id_user = user.id_user','left');
		$this->db->where('jas.id_vendor', $id_vendor);
		$this->db->where('ckt.id_user', $id_user);
		$this->db->where('jas.tgl_selesai', null);
		$this->db->group_by('ckt.id_checkout');
		return $this->db->count_all_results();
	}

}