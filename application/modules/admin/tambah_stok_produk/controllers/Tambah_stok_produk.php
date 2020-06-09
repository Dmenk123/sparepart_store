<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tambah_stok_produk extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_tambah_stok_produk','m_tbh_stok');
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
			'modal' => 'modalTambahStokProduk',
			'css'	=> false,
			'js'	=> 'tambahStokProdukJs',
			'view'	=> 'view_list_tambah_stok_produk'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_data()
	{
		$id_user = $this->session->userdata('id_user'); 
		$where = ['id_user' => $id_user];
		$d_vendor = $this->m_tbh_stok->get_by_id(false, 'tbl_vendor', $where,  false);
		
		$id_vendor = $d_vendor['id_vendor'];
		$list = $this->m_tbh_stok->get_datatable($id_vendor);
				
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $list->nama_lkp;
			$row[] = $list->nama_satuan;
			$row[] = $list->nama_kategori;
			$row[] = $list->nama_sub_kategori;
			$row[] = $list->stok_sisa;
			//add html for action button
			$row[] =
				'<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Tambah Stok" onclick="tambah_stok('."'".$list->id_stok."'".')"><i class="fa fa-pencil"></i></a>'
			;		
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_tbh_stok->count_all($id_vendor),
						"recordsFiltered" => $this->m_tbh_stok->count_filtered($id_vendor),
						"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}	

	public function get_data($id_stok)
	{
		$data = $this->m_tbh_stok->get_data_stok($id_stok);
		echo json_encode($data);
	}

	public function add_data()
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_tbh_stok->get_by_id(false, 'tbl_vendor', $where_user,  false);
		$id_vendor = $d_vendor['id_vendor'];

		$nama = $this->input->post('nama');
		$id_stok = $this->input->post('id_stok');
		$id_produk = $this->input->post('id_produk');
		$sisa = $this->input->post('sisa');
		$awal = $this->input->post('awal');
		$tambah = $this->input->post('tambah');
		
		$this->db->trans_begin();
		//insert tbl penerimaan
		$input = [
			'id' => $this->m_tbh_stok->get_max_id('tbl_penerimaan', 'id'),
			'id_stok' => $id_stok,
			'id_vendor' => $id_vendor,
			'id_produk' => $id_produk,
			'qty' => $tambah,
			'tanggal' => date('Y-m-d'),
			'created_at' => date("Y-m-d H:i:s")
		];

		$insert_penerimaan = $this->m_tbh_stok->insert_data('tbl_penerimaan', $input);

		//update tbl_stok
		$stok_akhir = (int)$sisa + (int)$tambah;
		$where = ['id_stok' => $id_stok];
		$update_stok = $this->m_tbh_stok->update_data('tbl_stok', $where, ['stok_sisa' => $stok_akhir]);

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
				"pesan" => 'Stok Produk berhasil ditambahkan'
			));
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////	
}
  