<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_kat_penjualan_adm extends CI_Model
{
	// declare array variable to search datatable
	var $column_search = array(
		'kp.nama_kategori', 'count(vp.id_produk) as qty' 
	);

	var $column_order = array(
		'kp.nama_kategori', 'count(vp.id_produk) as qty' 
	);

	var $order = array('kp.nama_kategori' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//for all data
	private function _get_data_kat_query($term='', $id_vendor) //term is value of $_REQUEST['search']
	{
		$column = array(
			'kp.nama_kategori', 
			'count(vp.id_produk) as qty',
			null,
		);

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

	function get_datatable_kat($id_vendor)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_kat_query($term, $id_vendor);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_vendor)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_kat_query($term, $id_vendor);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_vendor)
	{
		$this->db->select('
			kp.nama_kategori,
			count( vp.id_produk ) AS qty
		');

		$this->db->from('tbl_vendor_kategori as vk');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join('tbl_vendor_produk as vp', 'vk.id_vendor = vp.id_vendor','left');
		$this->db->join('tbl_kategori_produk as kp', 'vk.id_kategori = kp.id_kategori','left');
		$this->db->where('vk.id_vendor', $id_vendor);
		$this->db->group_by('kp.nama_kategori');
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

	public function get_max_id()
	{
		$q = $this->db->query("SELECT MAX(id) as kode_max from tbl_vendor_kategori");
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
	
	
	
	
	
	
	
	
	public function get_data_vendor_detail($id_user)
	{
		$this->db->select('
			u.*, 
			v.*, 
			prov.nama_provinsi,
			kot.nama_kota,
			kec.nama_kecamatan,
			kel.nama_kelurahan'
		);
		$this->db->from('tbl_user as u');
		$this->db->join('tbl_vendor as v', 'u.id_user = v.id_user', 'left');
		$this->db->join('tbl_provinsi as prov', 'u.id_provinsi = prov.id_provinsi', 'left');
		$this->db->join('tbl_kota as kot', 'u.id_kota = kot.id_kota', 'left');
		$this->db->join('tbl_kecamatan as kec', 'u.id_kecamatan = kec.id_kecamatan', 'left');
		$this->db->join('tbl_kelurahan as kel', 'u.id_kelurahan = kel.id_kelurahan', 'left');
		$this->db->where([
			'u.id_user' => $id_user,
		]);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
	}
	
	public function get_data_foto($id)
	{
		$this->db->select('foto_user');
		$this->db->from('tbl_user');
		$this->db->where('id_user',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_email_vendor($id)
	{
		$this->db->select('email');
		$this->db->from('tbl_user');
		$this->db->where('id_user', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function update_status_user($where, $data)
	{
		$this->db->update("tbl_user", $data, $where);
	}
	
}