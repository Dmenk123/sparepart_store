<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_set_produk_adm extends CI_Model
{
	// declare array variable to search datatable
	var $column_search = array(
		'vp.id_produk',
		'gp.nama_gambar',
		'p.nama_produk',
		'sat.nama_satuan',
		'sp.stok_sisa',
		'p.harga',
		'p.status'
	);

	var $column_order = array(
		'vp.id_produk',
		'gp.nama_gambar',
		'p.nama_produk',
		'sat.nama_satuan',
		'sp.stok_sisa',
		'p.harga',
		'p.status'
	);

	var $order = array('p.nama_produk' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//for all data
	private function _get_data_query($term='', $id_vendor, $id_kategori) //term is value of $_REQUEST['search']
	{
		$column = array(
			'vp.id_produk',
			'gp.nama_gambar',
			'p.nama_produk',
			'sat.nama_satuan',
			'sp.stok_sisa',
			'p.harga',
			'p.status',
			null,
		);

		$this->db->select('
			vp.id_produk,
			gp.nama_gambar,
			p.nama_produk,
			sat.nama_satuan,
			sp.stok_sisa,
			p.harga,
			p.status
		');

		$this->db->from('tbl_vendor_produk as vp');
		$this->db->join('tbl_produk as p', 'vp.id_produk = p.id_produk','left');
		$this->db->join("tbl_gambar_produk as gp", "vp.id_produk = gp.id_produk AND (gp.jenis_gambar = 'display')","left");
		$this->db->join('tbl_stok as sp', 'vp.id_produk = sp.id_produk','left');
		$this->db->join('tbl_satuan as sat', 'p.id_satuan = sat.id_satuan','left');
		$this->db->where([
			'vp.id_vendor ' => $id_vendor,
			'p.id_kategori' => $id_kategori
		]);
		//$this->db->group_by('kp.nama_kategori');
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

	function get_datatable($id_vendor, $id_kategori)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_query($term, $id_vendor, $id_kategori);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_vendor, $id_kategori)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_query($term, $id_vendor, $id_kategori);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_vendor, $id_kategori)
	{
		$this->db->select('
			vp.id_produk,
			gp.nama_gambar,
			p.nama_produk,
			sat.nama_satuan,
			sp.stok_sisa,
			p.harga,
			p.status
		');

		$this->db->from('tbl_vendor_produk as vp');
		$this->db->join('tbl_produk as p', 'vp.id_produk = p.id_produk','left');
		$this->db->join("tbl_gambar_produk as gp", "vp.id_produk = gp.id_produk AND (gp.jenis_gambar = 'display')","left");
		$this->db->join('tbl_stok as sp', 'vp.id_produk = sp.id_produk','left');
		$this->db->join('tbl_satuan as sat', 'p.id_satuan = sat.id_satuan','left');
		$this->db->where([
			'vp.id_vendor ' => $id_vendor,
			'p.id_kategori' => $id_kategori
		]);
		return $this->db->count_all_results();		
	}
	//end datatable query
	
	public function get_by_id($select=false, $table, $where=false, $join=false, $order=false, $single=false)
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

	public function get_max_id($table, $column_id)
	{
		$q = $this->db->query("SELECT MAX($column_id) as kode_max from $table");
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

	public function update_data($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
	}
	
	public function get_data_kategori()
	{
		$query = $this->db->get('tbl_kategori_produk');
		return $query->result();
	}

	public function get_akronim_kategori($id_kategori)
	{
		$this->db->select('akronim');
		$this->db->from('tbl_kategori_produk');
		$this->db->where('id_kategori', $id_kategori);
		$query = $this->db->get();

		$hasil = $query->row();
		return $hasil->akronim;
	}

	public function get_kode_produk($akronim)
    {
		$q = $this->db->query("select MAX(RIGHT(id_produk,5)) as kode_max from tbl_produk where id_produk like '%$akronim%'");
		$kd = "";
		if($q->num_rows()>0){
			foreach($q->result() as $hasil){
				$tmp = ((int)$hasil->kode_max)+1;
				$kd = sprintf("%05s", $tmp);
			}
		}else{
			$kd = "00001";
		}
		return "$akronim".$kd;
    }
}