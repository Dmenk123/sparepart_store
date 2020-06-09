<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_adm extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Mod_dashboard_adm','m_dasbor');
		$this->load->model('penjualan_adm/mod_penjualan_adm','m_jual');
		$this->load->library('flashdata_stokmin');
		//cek sudah login apa tidak
		if ($this->session->userdata('logged_in') != true) {
			redirect('home/error_404');
		}
		//cek level user
		if ($this->session->userdata('id_level_user') == "2") {
			redirect('home/error_404');
		}
	}

	public function index()
	{
		$bln_now = date('m');
		$thn_now = date('Y');
		$tanggal_awal = $thn_now.'-'.$bln_now.'-01';
		$tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));
		
		$cek_sess = $this->flashdata_stokmin->stok_min_notif();
		$id_user = $this->session->userdata('id_user');
		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		$data_user = $this->m_dasbor->get_data_user($id_user);
		$new_vendor_reg = $this->m_dasbor->get_data_user($id_user);

		//array untuk label dan qty grafik
		$qty_grafik = array();
		$lbl_grafik = array();
		$qty_grafik2 = array();
		$lbl_grafik2 = array();

		if ($this->session->userdata('id_level_user') == '4') {
			$id_vendor = $this->get_id_vendor();
			$d_vendor = $this->db->query("select * from tbl_vendor where id_vendor = '".$id_vendor."'")->row();
			$nama_vendor = $d_vendor->nama_vendor;

			$periode_label  = 'Pendapatan '.$nama_vendor.' Periode '.$this->bulan_indo($bln_now).' '.$thn_now;
			$periode_label2 = 'Mutasi Produk '.$nama_vendor.' Periode '.$this->bulan_indo($bln_now).' '.$thn_now;
			
			$count_new_penjualan = $this->m_dasbor->get_count_new_penjualan($id_vendor);
			// echo $this->db->last_query();exit;
			$count_kategori_vendor = $this->m_dasbor->get_count_kat_vendor($id_vendor, TRUE);
			$list_kategori_vendor = $this->m_dasbor->get_count_kat_vendor($id_vendor, FALSE);
			$count_produk_vendor = $this->m_dasbor->get_count_produk_vendor($id_vendor, TRUE);
			$list_produk_vendor = $this->m_dasbor->get_count_produk_vendor($id_vendor, FALSE);
			$chart_data = $this->m_dasbor->get_report_omset_vendor($id_vendor, $tanggal_awal, $tanggal_akhir);
			
			foreach ($chart_data as $val) {
				$qty_grafik[] = $val->total_omset_nett;
			}

			foreach ($chart_data as $val) {
				$lbl_grafik[] = date('d-m-Y', strtotime($val->tgl_bayar));
			}

			$mutasi_chart_data = $this->m_dasbor->get_report_mutasi_produk($id_vendor, $tanggal_awal, $tanggal_akhir);

			foreach ($mutasi_chart_data as $val) {
				$qty_grafik2[] = $val->total_keluar;
			}

			foreach ($mutasi_chart_data as $val) {
				$lbl_grafik2[] = $val->nama_produk;
			}

			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'counter_new_penjualan' => $count_new_penjualan,
				'counter_kategori_vendor' => $count_kategori_vendor,
				'list_kategori_vendor' => $list_kategori_vendor,
				'counter_produk_vendor' => $count_produk_vendor,
				'list_produk_vendor' => $list_produk_vendor,
				'qty_grafik' => json_encode($qty_grafik),
				'lbl_grafik' => json_encode($lbl_grafik),
				'periode_label' => $periode_label,
				'qty_grafik2' => json_encode($qty_grafik2),
				'lbl_grafik2' => json_encode($lbl_grafik2),
				'periode_label2' => $periode_label2
			);
		}else{
			$periode_label = 'Pendapatan Onpets Periode '.$this->bulan_indo($bln_now).' '.$thn_now;
			$count_omset = $this->m_dasbor->get_count_omset_dashboard(TRUE);
			$count_penjualan_adm = $this->m_dasbor->get_count_penjualan_adm(TRUE);
			$count_data_vendor = $this->m_dasbor->get_count_data_user(TRUE, 4);
			$count_data_user = $this->m_dasbor->get_count_data_user(TRUE, 2);
			$count_user = $this->m_dasbor->get_count_user();
			$count_user_level = $this->m_dasbor->get_count_user_level();
			$count_new_vendor = $this->m_dasbor->get_count_new_vendor();
			$chart_data = $this->m_dasbor->get_report_omset_adm($tanggal_awal, $tanggal_akhir);

			foreach ($chart_data as $val) {
				$qty_grafik[] = $val->total_omset_nett;
			}

			foreach ($chart_data as $val) {
				$lbl_grafik[] = date('d-m-Y', strtotime($val->tgl_bayar));
			}

			$data = array(
				'data_user' => $data_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'counter_omset' => $count_omset,
				'counter_penjualan_adm' => $count_penjualan_adm,
				'counter_user' => $count_user,
				'counter_user_level' => $count_user_level,
				'counter_new_vendor' => $count_new_vendor,
				'counter_data_vendor' => $count_data_vendor,
				'counter_data_user' => $count_data_user,
				'qty_grafik' => json_encode($qty_grafik),
				'lbl_grafik' => json_encode($lbl_grafik),
				'periode_label' => $periode_label
			);
		}

		$content = [
			'modal' => false,
			'js'	=> 'jsDashboardAdm',
			'css'	=> false,
			'view'	=> 'dashboard_adm/view_list_dashboard_adm'
		];
		// $this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);

        //$this->load->view('temp_adm',$data);
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_jual->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];
		return $id_vendor;
	}

	public function bulan_indo($bulan)
	{
		$arr = [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		];

		return $arr[$bulan];
	}

}
