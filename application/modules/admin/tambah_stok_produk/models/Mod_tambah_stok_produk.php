<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_tambah_stok_produk extends CI_Model
{
	// declare array variable to search datatable
	var $column_search = array(
		'concat(tbl_stok.id_produk, " - ", tbl_produk.nama_produk)', 'tbl_satuan.nama_satuan', 'tbl_kategori_produk.nama_kategori', 'tbl_sub_kategori_produk.nama_sub_kategori', 'tbl_stok.stok_sisa' 
	);

	var $column_order = array(
		'concat(tbl_stok.id_produk, " - ", tbl_produk.nama_produk)', 'tbl_satuan.nama_satuan', 'tbl_kategori_produk.nama_kategori', 'tbl_sub_kategori_produk.nama_sub_kategori', 'tbl_stok.stok_sisa', null 
	);

	var $order = array('tbl_stok.id_produk' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//for all data
	private function _get_datatable_query($term='', $id_vendor) //term is value of $_REQUEST['search']
	{
		$column = array(
			'concat(tbl_stok.id_produk, " - ", tbl_produk.nama_produk)', 'tbl_satuan.nama_satuan', 'tbl_kategori_produk.nama_kategori', 'tbl_sub_kategori_produk.nama_sub_kategori', 'tbl_stok.stok_sisa', null
		);

		$this->db->select("
			concat(tbl_stok.id_produk, ' - ', tbl_produk.nama_produk) as nama_lkp,
			tbl_stok.id_produk,
			tbl_stok.id_stok,
			tbl_satuan.nama_satuan,
			tbl_stok.stok_sisa,
			tbl_stok.stok_minimum,
			tbl_stok.STATUS,
			tbl_produk.nama_produk,
			tbl_kategori_produk.nama_kategori,
			tbl_sub_kategori_produk.nama_sub_kategori
		");

		$this->db->from('tbl_produk');
		$this->db->join('tbl_stok', 'tbl_stok.id_produk = tbl_produk.id_produk','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_produk.id_satuan','left');
		$this->db->join('tbl_vendor_produk', 'tbl_produk.id_produk = tbl_vendor_produk.id_produk','left');
		$this->db->join('tbl_kategori_produk', 'tbl_produk.id_kategori = tbl_kategori_produk.id_kategori','left');
		$this->db->join('tbl_sub_kategori_produk', 'tbl_produk.id_sub_kategori = tbl_sub_kategori_produk.id_sub_kategori','left');
		$this->db->where('tbl_vendor_produk.id_vendor', $id_vendor);
		$this->db->where('tbl_stok.status', '1');
		// $this->db->order_by('tbl_stok.id_produk', 'asc');
		$i = 0;
		// loop column 
		foreach ($this->column_search as $item) 
		{
			// if datatable send POST for search
			if($_POST['search']['value']) 
			{
				// first loop
				if($i===0) 
				{
					// open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				//last loop
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

	function get_datatable($id_vendor)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_query($term, $id_vendor);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_vendor)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_query($term, $id_vendor);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_vendor)
	{
		$this->db->select("
			concat(tbl_stok.id_produk, ' - ', tbl_produk.nama_produk) as nama_lkp,
			tbl_stok.id_produk,
			tbl_satuan.nama_satuan,
			tbl_stok.stok_sisa,
			tbl_stok.stok_minimum,
			tbl_stok.STATUS,
			tbl_produk.nama_produk,
			tbl_kategori_produk.nama_kategori,
			tbl_sub_kategori_produk.nama_sub_kategori
		");

		$this->db->from('tbl_produk');
		$this->db->join('tbl_stok', 'tbl_stok.id_produk = tbl_produk.id_produk','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_produk.id_satuan','left');
		$this->db->join('tbl_vendor_produk', 'tbl_produk.id_produk = tbl_vendor_produk.id_produk','left');
		$this->db->join('tbl_kategori_produk', 'tbl_produk.id_kategori = tbl_kategori_produk.id_kategori','left');
		$this->db->join('tbl_sub_kategori_produk', 'tbl_produk.id_sub_kategori = tbl_sub_kategori_produk.id_sub_kategori','left');
		$this->db->where('tbl_vendor_produk.id_vendor', $id_vendor);
		$this->db->where('tbl_stok.status', '1');
		return $this->db->count_all_results();		
	}


	//end datatable query
	public function get_by_id($select=false, $table, $where=false, $order=false)
	{
		if ($select) {
			$this->db->select($select);
		}else{
			$this->db->select('*');
		}

		$this->db->from($table);
		
		if ($where) {
			$this->db->where($where);
		}
		if ($order) {
			$this->db->order_by($order);
		}
		return $this->db->get()->row_array();
	}

	public function get_max_id($table, $column)
	{
		$q = $this->db->query("SELECT MAX(".$column.") as kode_max from ".$table."");
		$kd = "";
		if($q->num_rows()>0){
			$kd = $q->row();
			return (int)$kd->kode_max + 1;
		}else{
			return '1';
		}
	}

	public function insert_data($table, $data)
	{
		$this->db->insert($table, $data);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
	
	public function get_data_stok($id_stok)
	{
		$this->db->select("tbl_stok.id_stok, tbl_stok.id_produk, tbl_stok.stok_awal, tbl_stok.stok_sisa, concat(tbl_produk.id_produk, ' - ', tbl_produk.nama_produk) as nama_produk");
		$this->db->from('tbl_stok');
		$this->db->join('tbl_produk', 'tbl_stok.id_produk = tbl_produk.id_produk', 'left');
		$this->db->where('tbl_stok.id_stok', $id_stok);
		return $this->db->get()->row();
	}

	public function update_data($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		return ($this->db->affected_rows() != 1) ? false : true;
	}
	
}