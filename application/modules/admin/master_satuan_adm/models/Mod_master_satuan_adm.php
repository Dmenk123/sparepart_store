<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_master_satuan_adm extends CI_Model
{
	// declare array variable to search datatable
	var $column_search = array(
		'nama_satuan'
	);

	var $column_order = array(
		'nama_satuan',null
	);

	var $order = array('nama_satuan' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	//for all data
	private function _get_data_satuan_query($term='') //term is value of $_REQUEST['search']
	{
		$column = array(
				'nama_satuan',
				null,
			);

		$this->db->select('
				id_satuan,
				nama_satuan,
				status
			');

		$this->db->from('tbl_satuan');
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

	function get_datatable()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_satuan_query($term);
		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_data_satuan_query($term);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('tbl_satuan');
		return $this->db->count_all_results();		
	}
	//end datatable query

	public function get_by_id($id)
	{
		$this->db->from('tbl_satuan');
		$this->db->where('id_satuan',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function insert_data($table, $input)
	{
		//insert into tbl_satuan 
		$this->db->insert($table, $input);
	}

	public function update_data($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
	}	
}