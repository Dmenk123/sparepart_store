<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_kelola_omset_adm extends CI_Model
{
	var $column_search = array(
			"tr.id_pembelian",
			"CONCAT(r.nama,' - ',r.no_rek)",
			"r.penyedia",
			"r.biaya_adm",
			"tr.nominal",
			"tr.status_penarikan"
		);

	var $column_order = array(
			"tr.id_pembelian",
			"rekening",
			"r.penyedia",
			"r.biaya_adm",
			"tr.nominal",
			"tr.status_penarikan",
			null,
		);

	var $order = array('tr.id_pembelian' => 'desc'); // default order

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// ================================================================================================

	private function _get_datatable_query($term='') //term is value of $_REQUEST['search']
	{
		$column = array(
					"tr.id_pembelian",
					"rekening",
					"r.penyedia",
					"r.biaya_adm",
					"tr.nominal",
					"tr.status_penarikan",
					null,
				);
		
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
		//$this->db->where('tr.status_penarikan', '1');
		
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

	function get_datatable()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_query($term);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatable_query($term);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_vendor="")
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
		//$this->db->where('tr.status_penarikan', '1');
		return $this->db->count_all_results();
	}

	// ================================================================================================
	

	public function get_data_id_checkout($id_penjualan)
	{
		$this->db->select('id_checkout');
		$this->db->from('tbl_pembelian');
		$this->db->where('id_pembelian', $id_penjualan);
		$query = $this->db->get('');
		$hasil = $query->row();
		return $hasil->id_checkout;
	}

	public function get_data_omset_header($id_omset)
	{
		$this->db->select("
			tr.id,
			tr.id_pembelian,
			tr.id_rekber,
			tr.nominal,
			tr.nilai_ongkir,
			tr.harga_pembelian,
			tr.status_penarikan,
			tr.tanggal,
			CONCAT(r.nama,' - ',r.no_rek) as rekening,
			r.penyedia,
			r.biaya_adm,
			p.id_checkout
		");
		
		$this->db->from('tbl_trans_rekber as tr');
		$this->db->join('tbl_m_rekber as r', 'tr.id_rekber = r.id', 'left');
		$this->db->join('tbl_pembelian as p', 'tr.id_pembelian = p.id_pembelian', 'left');
		//$this->db->where('tr.status_penarikan', '1');
		$this->db->where('tr.id', $id_omset);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_data_omset_detail($id_omset)
	{
		$this->db->select('
			tr.id,
			tr.id_pembelian,
			tr.id_rekber,
			tr.tanggal,
			tr.nominal,
			tr.nilai_ongkir,
			tr.harga_pembelian,
			tr.status_penarikan,
			p.id_checkout,
			sum(cd.harga_total) as h_total,
			sum(cd.harga_ongkir) as h_ongkir,
			v.nama_vendor,
			v.id_vendor
		');
		$this->db->from('tbl_trans_rekber as tr');
		$this->db->join('tbl_pembelian as p', 'tr.id_pembelian = p.id_pembelian','left');
		$this->db->join('tbl_checkout_detail as cd', 'p.id_checkout = cd.id_checkout','left');
		$this->db->join('tbl_vendor as v', 'cd.id_vendor = v.id_vendor', 'left');
        $this->db->where('tr.id', $id_omset);
        $this->db->group_by('cd.id_vendor');
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

	public function get_max_id($column, $table)
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

}