<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_omset_adm extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_lap_omset_adm','m_adm');
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
			$query = $this->m_adm->get_report($tgl_awal, $tgl_akhir);
			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'hasil_data' => $query,
				'tgl_awal' => $this->input->get('tgl_awal'),
				'tgl_akhir' => $this->input->get('tgl_akhir')
			);
		}else{
			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'hasil_data' => false,
				'tgl_awal' => false,
				'tgl_akhir' => false
			);
		}

		$content = [
			'css' 	=> 'cssLapOmsetAdm',
			'modal' => null,
			'js'	=> 'jsLapOmsetAdm',
			'view'	=> 'view_lap_omset_adm_detail'
		];

		$this->template_view->load_view($content, $data);
	}

	public function cetak_report($tanggal_awal, $tanggal_akhir)
	{
		$this->load->library('Pdf_gen');
		$tgl_awal = date('Y-m-d', strtotime($tanggal_awal));
		$tgl_akhir = date('Y-m-d', strtotime($tanggal_akhir));
		$query = $this->m_adm->get_report($tgl_awal, $tgl_akhir);

		$data = array(
			'title' => 'Laporan Pendapatan',
			'hasil_data' => $query,
			'tgl_awal' => $tanggal_awal,
			'tgl_akhir' => $tanggal_akhir
		);

	    $html = $this->load->view('view_lap_omset_adm_cetak', $data, true);
	    
	    $filename = 'laporan_omset_adm_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_adm->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$data['id_vendor'] = $d_vendor['id_vendor'];
		$data['nama_vendor'] = $d_vendor['nama_vendor'];
		return $data;
	}

}
