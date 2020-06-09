<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_sewa extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email'); // untuk kirim email
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_monitoring_sewa','m_sewa');
		$this->load->model('Order_produk_adm/mod_order_produk_adm','m_order');
		$this->load->model('tambah_stok_produk/mod_tambah_stok_produk','m_tbh_stok');
		$this->load->library('flashdata_stokmin');
	}

	public function index()
	{
		$cek_sess = $this->flashdata_stokmin->stok_min_notif();
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);
		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		
		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif
		);

		$content = [
			'modal' => 'modalMonitoringSewa',
			'css'	=> 'cssMonitoringSewa',
			'js'	=> 'jsMonitoringSewa',
			'view'	=> 'view_list_monitoring_sewa'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_sewa()
	{
		$id_vendor = $this->get_id_vendor();
		$list = $this->m_sewa->get_list_sewa($id_vendor);
		// echo $this->db->last_query();exit;
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $list) {
			// $link_detail = site_url('penjualan_adm/penjualan_detail/').$list->id_pembelian;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $list->id_checkout;
			$row[] = $list->nama_lengkap;
			$row[] = $list->alamat_kirim;
			$row[] = $list->email;
			$row[] = $list->no_telp_user;
			$row[] = date('d-m-Y', strtotime($list->tgl_checkout));
			$row[] = $list->nama_produk;
			$row[] = $list->qty_produk;
			$row[] = $list->durasi;
			if (strtotime(date('Y-m-d')) > strtotime($list->tgl_expired)) {
				$row[] = "<span style='color: red;'>".date('d-m-Y', strtotime($list->tgl_expired))."</span>";
			}else{
				$row[] = date('d-m-Y', strtotime($list->tgl_expired));
			}
			
			$row[] = '<a class="btn btn-sm btn-success btn_edit_status" href="javascript:void(0)" title="Selesai" id="'.$list->id_jasa.'"><i class="fa fa-check"></i> Selesai</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_sewa->count_all_sewa($id_vendor),
						"recordsFiltered" => $this->m_sewa->count_filtered_sewa($id_vendor),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_sewa->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];
		return $id_vendor;
	}

	public function konfirmasi_sewa_selesai()
	{
		$id_temp_jasa = $this->input->post('id');
		$this->db->trans_begin();
		$where = ['id' => $id_temp_jasa];
		$data_sewa = $this->m_sewa->get_by_id_advanced(false, 'tbl_temp_jasa', $where, false, false, true);
		if ($data_sewa) {
			$data_stok = $this->m_sewa->get_by_id_advanced(false, 'tbl_stok', ['id_stok'=>$data_sewa['id_stok']], false, false, true);

			//insert tbl_penerimaan
			$input = [
				'id' => $this->m_tbh_stok->get_max_id('tbl_penerimaan', 'id'),
				'id_stok' => $data_sewa['id_stok'],
				'id_vendor' => $data_sewa['id_vendor'],
				'id_produk' => $data_sewa['id_produk'],
				'qty' => $data_sewa['qty_produk'],
				'tanggal' => date('Y-m-d'),
				'created_at' => date("Y-m-d H:i:s")
			];
			$insert_penerimaan = $this->m_tbh_stok->insert_data('tbl_penerimaan', $input);
			//end insert penerimaan
			
			//update tbl_stok
			$stok_akhir = (int)$data_stok['stok_sisa'] + (int)$data_sewa['qty_produk'];
			$where_stok = ['id_stok' => $data_sewa['id_stok']];
			$update_stok = $this->m_tbh_stok->update_data('tbl_stok', $where_stok, ['stok_sisa' => $stok_akhir]);
			//end update tbl_stok

			//update tbl temp jasa
			$this->db->update("tbl_temp_jasa", ['tgl_selesai' => date('Y-m-d')], ['id' => $data_sewa['id']]);	
		}else{
			$this->db->trans_rollback();
	        echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Terjadi Kesalahan'
			));
			return;
		}
		

		if ($this->db->trans_status() === FALSE){
	        $this->db->trans_rollback();
	        echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Terjadi Kesalahan'
			));
		}else{
	        $this->db->trans_commit();
	        echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Transaksi Peminjaman berhasil ditandai'
			));
		}
	}
}