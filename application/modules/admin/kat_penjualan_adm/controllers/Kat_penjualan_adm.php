<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kat_penjualan_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_kat_penjualan_adm','m_kat');
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
			'modal' => 'modalKatPenjualanAdm',
			'css'	=> false,
			'js'	=> 'katPenjualanAdmJs',
			'view'	=> 'view_list_kat_penjualan'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_kat()
	{
		$id_user = $this->session->userdata('id_user'); 
		$where = ['id_user' => $id_user];
		$d_vendor = $this->m_kat->get_by_id(false, 'tbl_vendor', $where,  false);
		
		$id_vendor = $d_vendor['id_vendor'];
		$list = $this->m_kat->get_datatable_kat($id_vendor);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list) {
			$id_kategori = $list->id_kategori;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $list->nama_kategori;
			$row[] = $list->qty;
			//add html for action button
			$row[] =
				'<a class="btn btn-sm btn-primary" href="'.base_url('set_produk_adm?id_vendor=').$id_vendor.'&id_kategori='.$id_kategori.'" title="Set Produk"><i class="fa fa-pencil"></i> Set Produk</a>'
			;		
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kat->count_all($id_vendor),
						"recordsFiltered" => $this->m_kat->count_filtered($id_vendor),
						"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}	

	public function get_vendor()
	{
		$q = $this->db->get('tbl_kategori_produk')->result();
		echo json_encode($q);
	}

	public function add_kat()
	{
		//get data vendor
		$id_user = $this->session->userdata('id_user'); 
		$id_kategori = $this->input->post('kat');
		$id = $this->m_kat->get_max_id();
		$where = ['id_user' => $id_user];
		$d_vendor = $this->m_kat->get_by_id(false, 'tbl_vendor', $where,  false);

		//cek data exist or not
		$where2 = ['id_vendor' => $d_vendor['id_vendor'], 'id_kategori' => $id_kategori];
		$exist = $this->m_kat->get_by_id(false, 'tbl_vendor_kategori', $where2);
		if ($exist) {
			echo json_encode([
				'status' => false,
				'pesan'	=> 'Kategori sudah pernah ada'
			]);
			return;
		}

		$input = [
			'id' 			=> $id,
			'id_vendor'		=> $d_vendor['id_vendor'],
			'id_kategori' 	=> $id_kategori,
			'created_at'	=> date('Y-m-d H:i:s')
		];

		$insert = $this->m_kat->insert_data('tbl_vendor_kategori', $input);
		if ($insert) {
			echo json_encode([
				'status' => true,
				'pesan'	=> 'kategori sukses ditambahkan'
			]);
		}else{
			echo json_encode([
				'status' => false,
				'pesan'	=> 'kategori gagal ditambahkan'
			]);
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////	
}
