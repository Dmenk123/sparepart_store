<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_new_vendor_adm extends CI_Model
{
	// declare array variable to search datatable
	var $column_search = array(
			'CONCAT(u.fname_user, " ", u.lname_user) AS nama_user',
			'u.email',
			'u.status',
			'u.timestamp',
			'v.nama_vendor',
			'v.jenis_usaha_vendor' 
		);

	var $column_order = array(
			'CONCAT(u.fname_user, " ", u.lname_user) AS nama_user',
			'u.email',
			'u.status',
			'u.timestamp',
			'v.nama_vendor',
			'v.jenis_usaha_vendor' 
		);

	var $order = array('v.nama_vendor' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	//for all data
	private function _get_data_vendor_query($term='') //term is value of $_REQUEST['search']
	{
		$column = array(
			'CONCAT(u.fname_user, " ", u.lname_user) AS nama_user',
			'u.email',
			'u.status',
			'u.timestamp',
			'v.nama_vendor',
			'v.jenis_usaha_vendor',
			null,
		);

		$this->db->select('
			CONCAT(u.fname_user, " ", u.lname_user) AS nama_user,
			u.id_user,
			u.email,
			u.status,
			u.timestamp,
			v.nama_vendor,
			v.jenis_usaha_vendor
		');

		$this->db->from('tbl_user as u');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join('tbl_vendor as v', 'u.id_user = v.id_user','left');
		$this->db->where('u.status', 0);
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

	function get_datatable_vendor()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_vendor_query($term);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_vendor_query($term);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('tbl_user');
		$this->db->join('tbl_vendor', 'tbl_user.id_user = tbl_vendor.id_user', 'left');
		$this->db->where(['tbl_user.status' => 0]);
		return $this->db->count_all_results();		
	}
	//end datatable query

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