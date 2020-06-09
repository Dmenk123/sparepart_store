<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	protected $_ci;
	public function __construct()
	{
		//parent::__construct();
		$this->_ci = &get_instance();
	}

  	function stok_min_notif() 
  	{
    	$this->_ci->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
    	$id_vendor = $this->get_id_vendor();
    	//$produk = $this->_ci->m_dasbor->get_produk_vendor($id_vendor);
    	$produk = $this->_ci->m_dasbor->get_produk();
		$link_notif = site_url('laporan_stok');
		$retval = [];
		foreach ($produk as $val) {
			if ($val->stok_sisa < $val->stok_minimum) {
				$retval[] = true;
			}else{
				$retval[] = false;
			}
		}

		if (in_array(TRUE, $retval)){
			return $this->session->set_flashdata('cek_stok', 'Terdapat Stok produk dibawah nilai minimum, Mohon di cek ulang <a href="'.$link_notif.'">disini</a>');
		}else{
			return false;
		}
 	}

 	public function get_id_vendor()
	{
		$this->_ci->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->_ci->load->model('Mod_penjualan_adm','m_jual');
		$id_user = $this->_ci->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->_ci->m_jual->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];
		return $id_vendor;
	}
