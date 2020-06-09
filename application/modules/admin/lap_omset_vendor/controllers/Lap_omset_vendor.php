<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_omset_vendor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_lap_omset_vendor','m_vendor');
		$this->load->library('flashdata_stokmin');
	}

	public function index()
	{
		$cek_sess = $this->flashdata_stokmin->stok_min_notif();
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);
		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		if ($this->input->get('tgl_awal') != '' || $this->input->get('tgl_akhir') != '') {
			$tgl_awal = date('Y-m-d', strtotime($this->input->get('tgl_awal')));
			$tgl_akhir = date('Y-m-d', strtotime($this->input->get('tgl_akhir')));
			$data_vendor = $this->get_id_vendor();
			$query = $this->m_vendor->get_report($data_vendor['id_vendor'], $tgl_awal, $tgl_akhir);
			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'hasil_data' => $query,
				'nama_vendor' => $data_vendor['nama_vendor'],
				'tgl_awal' => $this->input->get('tgl_awal'),
				'tgl_akhir' => $this->input->get('tgl_akhir')
			);
		}else{
			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'hasil_data' => false,
				'nama_vendor' => false,
				'tgl_awal' => false,
				'tgl_akhir' => false
			);
		}

		$content = [
			'css' 	=> 'cssLapOmsetVendor',
			'modal' => null,
			'js'	=> 'jsLapOmsetVendor',
			'view'	=> 'view_lap_omset_vendor_detail'
		];

		$this->template_view->load_view($content, $data);
	}

	public function cetak_report($tanggal_awal, $tanggal_akhir)
	{
		$this->load->library('Pdf_gen');
		$tgl_awal = date('Y-m-d', strtotime($tanggal_awal));
		$tgl_akhir = date('Y-m-d', strtotime($tanggal_akhir));
		$data_vendor = $this->get_id_vendor();
		$query = $this->m_vendor->get_report($data_vendor['id_vendor'], $tgl_awal, $tgl_akhir);

		$data = array(
			'title' => 'Laporan Omset Vendor',
			'hasil_data' => $query,
			'nama_vendor' => $data_vendor['nama_vendor'],
			'tgl_awal' => $tanggal_awal,
			'tgl_akhir' => $tanggal_akhir
		);

	    $html = $this->load->view('view_lap_omset_vendor_cetak', $data, true);
	    
	    $filename = 'laporan_omset_vendor_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_vendor->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$data['id_vendor'] = $d_vendor['id_vendor'];
		$data['nama_vendor'] = $d_vendor['nama_vendor'];
		return $data;
	}

}
