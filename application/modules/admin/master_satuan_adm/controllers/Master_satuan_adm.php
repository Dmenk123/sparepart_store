<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_satuan_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_master_satuan_adm','m_sat');
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
			'modal' => 'modalMasterSatuanAdm',
			'css'	=> false,
			'js'	=> 'masterSatuanAdmJs',
			'view'	=> 'view_list_master_satuan'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_satuan()
	{
		$list = $this->m_sat->get_datatable();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $sat) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $sat->nama_satuan;
			if ($sat->status == '1') {
				$row[] =
				'<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="edit_satuan('."'".$sat->id_satuan."'".')"><i class="fa fa-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-success btn_edit_status" href="javascript:void(0)" title="aktif" id="'.$sat->id_satuan.'" data-id="aktif"><i class="fa fa-check"></i> Aktif</a>';
			}else{
				$row[] =
				'<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="edit_satuan('."'".$sat->id_satuan."'".')"><i class="fa fa-pencil"></i></a>
				<a class="btn btn-sm btn-danger btn_edit_status" href="javascript:void(0)" title="nonaktif" id="'.$sat->id_satuan.'"><i class="fa fa-times" data-id="nonaktif"></i> Nonaktif</a>';
			}
			$data[] = $row;
		}//end loop
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_sat->count_all(),
						"recordsFiltered" => $this->m_sat->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function edit_data_satuan($id)
	{
		$data = $this->m_sat->get_by_id($id);
		echo json_encode($data);
	}

	public function add_data_satuan()
	{
		$nama = $this->input->post('sat');
		$input = ['nama_satuan' => $nama];
		$this->m_sat->insert_data('tbl_satuan', $input);
		echo json_encode($data = [
			'status'=> true,
			'pesan' => 'Satuan sukses disimpan'
		]);
	}

	public function update_data_satuan()
	{
		$id = $this->input->post('satId');
		$nama = $this->input->post('sat');
		$input = ['nama_satuan' => $nama];
		$this->m_sat->update_data('tbl_satuan', ['id_satuan' => $id], $input);
		echo json_encode($data = [
			'status'=> true,
			'pesan' => 'Satuan sukses diupdate'
		]);
	}

	public function edit_status_satuan()
	{
		$id = $this->input->post('id');
		if ($this->input->post('status') == "aktif"){
			$status = '0';
			$pesan = 'Berhasil di non-aktifkan';
		}else{
			$status = '1';
			$pesan = 'Berhasil di aktifkan';
		}
		
		$input = array(
			'status' => $status 
		);

		$this->m_sat->update_data('tbl_satuan', ['id_satuan' => $id], $input);

		echo json_encode($data = [
			'status'=> true,
			'pesan' => $pesan
		]);
	}

	
}
