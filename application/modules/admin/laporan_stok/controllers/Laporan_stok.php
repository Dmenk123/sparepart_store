<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_stok extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_laporan_stok','m_stok');
		$this->load->library('flashdata_stokmin');
	}

	public function index()
	{
		$cek_sess = $this->flashdata_stokmin->stok_min_notif();
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);
		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		$data_vendor = $this->get_id_vendor();
		$query = $this->m_stok->get_report_stok($data_vendor['id_vendor']);

		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			'hasil_data' => $query,
			'nama_vendor' => $data_vendor['nama_vendor']
		);

		$content = [
			'css' 	=> 'cssLapStokAdm',
			'modal' => null,
			'js'	=> 'jsLapStokAdm',
			'view'	=> 'view_lap_stok_detail'
		];

		$this->template_view->load_view($content, $data);
	}

	public function cetak_report_stok()
	{
		$this->load->library('Pdf_gen');
		$id_user = $this->session->userdata('id_user');
		$query_footer = $this->m_stok->get_detail_footer($id_user);
		$data_vendor = $this->get_id_vendor();
		$query = $this->m_stok->get_report_stok($data_vendor['id_vendor']);

		$data = array(
			'title' => 'Laporan Stok Produk',
			'hasil_data' => $query,
			'hasil_footer' => $query_footer,
			'nama_vendor' => $data_vendor['nama_vendor']
		);

	    $html = $this->load->view('view_lap_order_cetak', $data, true);
	    
	    $filename = 'laporan_stok_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_stok->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$data['id_vendor'] = $d_vendor['id_vendor'];
		$data['nama_vendor'] = $d_vendor['nama_vendor'];
		return $data;
	}

}
