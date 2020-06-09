<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_kategori_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_master_kategori_adm','m_kat');
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
			'modal' => 'modalMasterKategoriAdm',
			'css'	=> false,
			'js'	=> 'masterKategoriAdmJs',
			'view'	=> 'view_list_master_kategori'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_kategori()
	{
		$list = $this->m_kat->get_datatable_kategori();
		$data = array();
		$angka = 1;
		$no = $_POST['start'];
		foreach ($list as $listKategori) {
			$no++;
			$link_detail = site_url('master_kategori_adm/master_kategori_detail/').$listKategori->id_kategori;
			$row = array();
			//loop value tabel db
			$row[] = $angka;
			$row[] = $listKategori->nama_kategori;
			$row[] = $listKategori->ket_kategori;
			$row[] = $listKategori->akronim;
			//add html for action button
			$row[] =
				'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Detail" id="btn_detail"><i class="glyphicon glyphicon-search"></i> Detail</a>
				 <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_kategori('."'".$listKategori->id_kategori."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_kategori('."'".$listKategori->id_kategori."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			$angka++;				 
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kat->count_all(),
						"recordsFiltered" => $this->m_kat->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_sub_kategori($id_kategori)
	{
		$list = $this->m_kat->get_datatable_subkategori($id_kategori);
		$data = array();
		$angka = 1;
		$no = $_POST['start'];
		foreach ($list as $listSubKategori) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $angka;
			$row[] = $listSubKategori->nama_sub_kategori;
			$row[] = $listSubKategori->ket_sub_kategori;
			//add html for action button
			$row[] =
				'<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_sub_kategori('."'".$listSubKategori->id_sub_kategori."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_sub_kategori('."'".$listSubKategori->id_sub_kategori."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			$angka++;				 
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_kat->count_all_subkategori($id_kategori),
						"recordsFiltered" => $this->m_kat->count_filtered_subkategori($id_kategori),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function add_master_kategori()
	{
		$data = array(
			'nama_kategori' => trim($this->input->post('namaKategori')),
			'ket_kategori' => trim($this->input->post('keteranganKategori')),
			'akronim' => strtoupper(trim($this->input->post('akronimKategori'))),
		);

		$this->m_kat->insert_data_kategori($data);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => "Data kategori berhasil ditambahkan", 
		));
	}

	public function add_master_subkategori()
	{
		$data = array(
			'nama_sub_kategori' => trim($this->input->post('namaSubKategori')),
			'id_kategori' => trim($this->input->post('idKategori')),
			'ket_sub_kategori' => trim($this->input->post('keteranganSubKategori')),
		);

		$this->m_kat->insert_data_subkategori($data);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => "Data Sub-kategori berhasil ditambahkan", 
		));
	}

	public function edit_data_kategori($id)
	{
		$data = $this->m_kat->get_data_kategori_byid($id);
		echo json_encode($data);
	}

	public function edit_data_subkategori($id)
	{
		$data = $this->m_kat->get_data_subkategori_byid($id);
		echo json_encode($data);
	}

	public function update_master_kategori()
	{
		$data = array(
			'nama_kategori' => trim($this->input->post('namaKategori')),
			'ket_kategori' => trim($this->input->post('keteranganKategori')),
			'akronim' => strtoupper(trim($this->input->post('akronimKategori'))),
		);

		$this->m_kat->update_data_kategori(array('id_kategori' => $this->input->post('idKategori')), $data);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => "Data kategori berhasil diupdate", 
		));
	}

	public function update_master_subkategori()
	{
		$data = array(
			'nama_sub_kategori' => trim($this->input->post('namaSubKategori')),
			'id_kategori' => trim($this->input->post('idKategori')),
			'ket_sub_kategori' => trim($this->input->post('keteranganSubKategori'))
		);

		$this->m_kat->update_data_subkategori(array('id_sub_kategori' => $this->input->post('idSubKategori')), $data);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => "Data Sub-kategori berhasil diupdate", 
		));
	}

	public function master_kategori_detail($id_kategori)
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);

		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);
		$notif = $this->m_dasbor->get_email_notif($id_user);
		$query_header = $this->m_kat->get_data_kategori_byid($id_kategori);

		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			'hasil_header' => $query_header
		);

		$content = [
			'modal' => 'modalDetailKategoriAdm',
			'css'	=> false,
			'js'	=> 'MasterKategoriAdmJs',
			'view'	=> 'view_detail_master_kategori'
		];

		$this->template_view->load_view($content, $data);
	}

	public function delete_master_kategori($id_kategori)
	{
		$this->m_kat->delete_data_kategori($id_kategori);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => 'Master Kategori Berhasil dihapus'
		));
	}

	public function delete_master_subkategori($id_subkategori)
	{
		$this->m_kat->delete_data_subkategori($id_subkategori);
		echo json_encode(array(
			'status' => TRUE,
			'pesan' => 'Master Sub-kategori Berhasil dihapus'
		));
	}
	
}
