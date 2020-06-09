<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_produk_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_set_produk_adm','m_set');
		$this->load->library('flashdata_stokmin');
	}

	public function index()
	{
		$cek_sess = $this->flashdata_stokmin->stok_min_notif();
		$id_vendor = $this->input->get('id_vendor');
		$id_kategori = $this->input->get('id_kategori');

		if ($id_vendor == '' || $id_kategori == '') {
			//get id vendor
			$id_user = $this->session->userdata('id_user'); 
			$where_user = ['id_user' => $id_user];
			$d_vendor = $this->m_set->get_by_id(false, 'tbl_vendor', $where_user, false, false, true);
			$id_vendor = $d_vendor['id_vendor'];

			//get data kategori by vendor
			$select = "vk.id_kategori, kp.nama_kategori";
			$table = 'tbl_vendor_kategori as vk';
			$join = array(
				array(
					"table" => "tbl_kategori_produk as kp",
					"on"    => "vk.id_kategori = kp.id_kategori"
				)
			);
			$where = ['vk.id_vendor' => $id_vendor];
			$data_kat = $this->m_set->get_by_id($select, $table, $where, $join, false, false);
			//var_dump($data_kat);exit;
		}else{
			//get data kategori by vendor
			$select = "vk.id_kategori, kp.nama_kategori";
			$table = 'tbl_vendor_kategori as vk';
			$join = array(
				array(
					"table" => "tbl_kategori_produk as kp",
					"on"    => "vk.id_kategori = kp.id_kategori"
				)
			);
			$where = ['vk.id_vendor' => $id_vendor];
			$data_kat = $this->m_set->get_by_id($select, $table, $where, $join, false, false);
		}

		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);
		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		
		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			'data_kat'	=> $data_kat,
			'id_vendor'	=> $id_vendor
		);

		$content = [
			'modal' => 'modalSetProdukAdm',
			'css'	=> false,
			'js'	=> 'setProdukAdmJs',
			'view'	=> 'view_list_set_produk_adm'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_set_produk()
	{
		$id_user = $this->session->userdata('id_user'); 
		$id_vendor = $this->input->post('idVendor');
		$id_kategori = $this->input->post('idKategori');
		$list = $this->m_set->get_datatable($id_vendor, $id_kategori);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = '<img src="./assets/img/produk/'.$list->nama_gambar.'" alt="Gambar Produk" class="img_produk" height="50" width="50">';
			$row[] = $list->id_produk;
			$row[] = $list->nama_produk;
			$row[] = $list->nama_satuan;
			$row[] = $list->stok_sisa;
			$row[] = 
				"<span class='pull-left'>Rp.</span>
				<span class='pull-right'>".number_format($list->harga,2,',','.')."</span>";
			$row[] = ($list->status == '1') ? "Aktif" : "Nonaktif";
			//add html for action button
			// $row[] =
			// 	'<a class="btn btn-sm btn-primary" href="'.base_url('').'" title="Set Produk"><i class="fa fa-pencil"></i> Set Produk</a>'
			// ;
			if ($list->status == '1') {
				$row[] =
				'<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="edit_produk('."'".$list->id_produk."'".')"><i class="fa fa-pencil"></i></a>
				<a class="btn btn-sm btn-success btn_edit_status" href="javascript:void(0)" title="aktif" id="'.$list->id_produk.'" data-id="aktif"><i class="fa fa-check"></i></a>';
			}else{
				$row[] =
				'<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Edit" onclick="edit_produk('."'".$list->id_produk."'".')"><i class="fa fa-pencil"></i></a>
				<a class="btn btn-sm btn-danger btn_edit_status" href="javascript:void(0)" title="nonaktif" id="'.$list->id_produk.'"><i class="fa fa-times" data-id="nonaktif"></i></a>';
			}
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_set->count_all($id_vendor, $id_kategori),
						"recordsFiltered" => $this->m_set->count_filtered($id_vendor, $id_kategori),
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

	public function add_produk()
	{
		$timestamp = date('Y-m-d H:i:s');
		$id_kategori = $this->input->post('idKategori');
		$id_vendor = $this->input->post('idVendor');
		$akronim = $this->m_set->get_akronim_kategori($id_kategori);
		$id_produk = $this->m_set->get_kode_produk($akronim);
		//get extension
		$path = $_FILES['gambarDisplay']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		//replace space with dash
		$nmfile = str_replace(" ", "-", strtolower(trim($this->input->post('namaProduk'))));

		$input = array(
			'id_produk' => $id_produk,
			'id_kategori' => $id_kategori,
			'id_sub_kategori' => $this->input->post('subKategoriProduk'),
			'nama_produk' => trim($this->input->post('namaProduk')),
			'harga' => trim($this->input->post('hargaProduk')),
			'id_satuan' => $this->input->post('satuanProduk'),
			'keterangan_produk' => trim($this->input->post('keteranganProduk')),
			'varian_produk' => trim($this->input->post('varianProduk')),
			'created' => $timestamp,
			'status' => '1'
		);

		$this->db->trans_begin();
		//save data (PROSES PERTAMA)
		$insert1 = $this->m_set->insert_data('tbl_produk', $input);
		
		//insert data tabel gambar
		//load konfig upload
		$this->konfigurasi_upload_produk($nmfile);
		if (!$this->gbr_produk->do_upload('gambarDisplay')) {
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => 'gbrDetailKosong',
				'pesan' => 'Gambar display tidak boleh kosong. Terima kasih'
			));
			return;
		}

		//jika melakukan upload foto
		if ($this->gbr_produk->do_upload('gambarDisplay')) 
		{
			$gbrDisplay = $this->gbr_produk->data();
			//inisiasi variabel u/ digunakan pada fungsi config img produk
			$nama_file_produk = $gbrDisplay['file_name'];
		    //load config img produk
		    $this->konfigurasi_image_produk($nama_file_produk);
	        //data input array
			$input_display = array(			
				'id_produk' => $id_produk,
				'jenis_gambar' => 'display',
				'nama_gambar' => $gbrDisplay['file_name'],
			);
			//clear img lib after resize
			$this->image_lib->clear();
			//save data (PROSES KEDUA)
			$insert2 = $this->m_set->insert_data('tbl_gambar_produk', $input_display);
		} //end 

		//flag status upload
		$status_upload_detail = false;
		$this->konfigurasi_upload_produk_detail();
		if ($this->gbr_detail->do_upload('gambarDetail1')){
			$status_upload_detail = true;
		}
		//insert data tabel gambar detail
		//load config image detail
		if ($status_upload_detail) {
			for ($i=1; $i<=2 ; $i++) 
			{
				//jika melakukan upload foto
				if ($this->gbr_detail->do_upload('gambarDetail'.$i))
				{
					$gbrDetail = $this->gbr_detail->data();//get file upload data
					$config['image_library'] = 'gd2';
					$config['source_image'] = './assets/img/produk/img_detail/'.$gbrDetail['file_name'];
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['width'] = 450; //resize
					$config['height'] = 500; //resize
					$config['new_image'] = './assets/img/produk/img_detail/'.$nmfile."-det".$i.".".$ext;
					$this->load->library('image_lib',$config);
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					$input_detail = array(			
						'id_produk' => $id_produk,
						'jenis_gambar' => 'detail',
						'nama_gambar' => $nmfile."-det".$i.".".$ext,
					);
					//save data (PROSES KETIGA)
					$this->m_set->insert_data('tbl_gambar_produk', $input_detail);
					$this->image_lib->clear();//clear img lib after resize

					//unlink file upload, just image processed file only saved in server
					$ifile = '/marketplace/assets/img/produk/img_detail/'.$gbrDetail['file_name'];
					unlink($_SERVER['DOCUMENT_ROOT'] .$ifile); // use server document root
				}
				else
				{
					//jika tidak ada file diupload, maka duplicate upload file pertama tiap loop
					$this->gbr_detail->do_upload('gambarDetail1');
					$gbrDetail = $this->gbr_detail->data();
					//konfigurasi image lib
					$config['image_library'] = 'gd2';
					$config['source_image'] = './assets/img/produk/img_detail/'.$gbrDetail['file_name'];
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['width'] = 450; //resize
					$config['height'] = 500; //resize
					$config['new_image'] = './assets/img/produk/img_detail/'.$nmfile."-det".$i.".".$ext;
					$this->load->library('image_lib',$config);
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					
					$input_detail = array(			
						'id_produk' => $id_produk,
						'jenis_gambar' => 'detail',
						'nama_gambar' => $nmfile."-det".$i.".".$ext,
					);
					//save data (PROSES KETIGA)
					$this->m_set->insert_data('tbl_gambar_produk', $input_detail);
					$this->image_lib->clear();//clear img lib after resize
					$ifile = '/marketplace/assets/img/produk/img_detail/'.$gbrDetail['file_name']; 
					unlink($_SERVER['DOCUMENT_ROOT'] .$ifile); // use server document root
				}
			}//end loop
		}else{
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => 'gbrDetailKosong',
				'pesan' => 'Anda Harus Mengisi gambar detail minimal 1 gambar. Terima kasih'
			));
			return;
		}
		
		//insert into tbl stok
		$inputStok = array(
			'id_stok' => $this->m_set->get_max_id('tbl_stok', 'id_stok'),
			'id_produk' => $id_produk,
			'stok_awal' => (int)$this->input->post('stokAwal'),
			'stok_sisa' => (int)$this->input->post('stokAwal'),
			'stok_minimum' => (int)$this->input->post('stokMin'),
			'berat_satuan' => (int)$this->input->post('berat'),
			'status' => '1'
		);

		$insert3 = $this->m_set->insert_data('tbl_stok', $inputStok);
		//end insert into tbl stok

		//insert into tbl vendorproduk
		$inputVenpro = array(
			'id' => $this->m_set->get_max_id('tbl_vendor_produk', 'id'),
			'id_vendor' => $id_vendor,
			'id_produk' => $id_produk,
			'id_sub_kategori' => $this->input->post('subKategoriProduk'),
			'id_kategori' => $id_kategori,
			'created_at' => $timestamp
		);

		$insert4 = $this->m_set->insert_data('tbl_vendor_produk', $inputVenpro);
		//end insert into tbl vendorproduk

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => FALSE,
				'pesan' => 'Terjadi kesalahan, mohon hubungi pada kontak kami. Terima kasih'
			));
		}else {
			$this->db->trans_commit();
			echo json_encode(array(
				'status' => TRUE,
				'pesan' => "Data Produk ".$id_produk." Berhasil ditambahkan" 
			));
		}
	}

	public function edit_produk($id)
	{
		//get id vendor
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_set->get_by_id(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];

		$select =  "p.id_produk, p.nama_produk, p.harga, p.keterangan_produk, p.varian_produk, gp.id_gambar, gp.nama_gambar, k.id_kategori,
		k.nama_kategori, s.id_stok, s.stok_awal, s.stok_minimum, s.berat_satuan, sk.id_sub_kategori, sk.nama_sub_kategori, sat.id_satuan, sat.nama_satuan, vp.id as id_vendor_produk";
		$join = array(
			array(
				"table" => "tbl_satuan as sat",
				"on"    => "p.id_satuan = sat.id_satuan"
			),
			array(
				"table" => "tbl_kategori_produk k",
				"on"    => "p.id_kategori = k.id_kategori"
			),
			array(
				"table" => "tbl_sub_kategori_produk sk",
				"on"    => "p.id_sub_kategori = sk.id_sub_kategori"
			),
			array(
				"table" => "tbl_stok as s",
				"on"    => "p.id_produk = s.id_produk"
			),
			array(
				"table" => "tbl_gambar_produk as gp",
				"on"    => "p.id_produk = gp.id_produk AND gp.jenis_gambar = 'display'"
			),
			array(
				"table" => "tbl_vendor_produk as vp",
				"on"    => "p.id_produk = vp.id_produk AND vp.id_vendor = '".$id_vendor."'"
			)
		);
		$where = ['p.id_produk' => $id];
		$data = $this->m_set->get_by_id($select, 'tbl_produk as p', $where, $join, false, false);
		
		//get id vendor
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_set->get_by_id(false, 'tbl_vendor', $where_user, false, false, true);
		$data[0]['id_vendor'] = $d_vendor['id_vendor'];

		//get gambar detail
		$select_det =  "id_gambar, nama_gambar";
		$where_det = "id_produk = '".$id."' and jenis_gambar = 'detail'";
		$d_gbr_det = $this->m_set->get_by_id($select_det, 'tbl_gambar_produk', $where_det, false, false, false);
		//assign to data array
		for ($i=0; $i <count($d_gbr_det); $i++) { 
			$count = $i+1;
			$data[0]['id_gambar_detail'.$count] = $d_gbr_det[$i]['id_gambar'];
			$data[0]['nama_gambar_detail'.$count] = $d_gbr_det[$i]['nama_gambar'];
		}

		echo json_encode ($data[0]);
	}

	public function update_produk()
	{
		$timestamp = date('Y-m-d H:i:s');
		$id_produk = $this->input->post('idProduk');
		$id_vendor = $this->input->post('idVendor');
		$id_kategori = $this->input->post('idKategori');
		//get extension
		$path = $_FILES['gambarDisplay']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		//replace space with dash
		$nmfile = str_replace(" ", "-", strtolower(trim($this->input->post('namaProduk'))));

		$input = array(
			'id_sub_kategori' => $this->input->post('subKategoriProduk'),
			'nama_produk' => trim($this->input->post('namaProduk')),
			'harga' => trim($this->input->post('hargaProduk')),
			'id_satuan' => $this->input->post('satuanProduk'),
			'keterangan_produk' => trim($this->input->post('keteranganProduk')),
			'varian_produk' => trim($this->input->post('varianProduk')),
			'modified' => $timestamp
		);

		$this->db->trans_begin();
		//update data (PROSES PERTAMA)
		$this->m_set->update_data('tbl_produk', ['id_produk' => $id_produk], $input);

		//update data tabel gambar
		//load konfig upload
		$this->konfigurasi_upload_produk($nmfile);
		//jika gbr display tdk kosong
		if(!empty($_FILES['gambarDisplay']['name']))
		{
			//jika melakukan upload foto
			if ($this->gbr_produk->do_upload('gambarDisplay')) 
			{
				//$this->gbr_produk->do_upload('gambarDisplay');
				$gbrDisplay = $this->gbr_produk->data();
				//inisiasi variabel u/ digunakan pada fungsi config img produk
				$nama_file_produk = $gbrDisplay['file_name'];
			    //load config img produk
			    $this->konfigurasi_image_produk($nama_file_produk);
		        //data input array
				$input_display = array(			
					'jenis_gambar' => 'display',
					'nama_gambar' => $gbrDisplay['file_name'],
				);
				//clear img lib after resize
				$this->image_lib->clear();
				//save data (PROSES KEDUA)
				$this->m_set->update_data('tbl_gambar_produk', ['id_gambar' => $this->input->post('idGbrDisplay')], $input_display);
			} //end 
		}
		//insert data tabel gambar detail
		
		$this->konfigurasi_upload_produk_detail(); //load config image detail
		for ($i=1; $i<=2 ; $i++) 
		{
			if(!empty($_FILES['gambarDetail'.$i]['name']))
			{
				//get detail extension
				$pathDet = $_FILES['gambarDetail'.$i]['name'];
				$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);

				if ($this->gbr_detail->do_upload('gambarDetail'.$i)) 
				{
			        $gbrDetail = $this->gbr_detail->data();//get file upload data
		            $config['image_library'] = 'gd2';
		            $config['source_image'] = './assets/img/produk/img_detail/'.$gbrDetail['file_name'];
		            $config['create_thumb'] = FALSE;
		            $config['overwrite'] = TRUE;
		            $config['maintain_ratio'] = FALSE;
		            $config['width'] = 450; //resize
		            $config['height'] = 500; //resize
		            $config['new_image'] = './assets/img/produk/img_detail/'.$nmfile."-det".$i.".".$extDet;
		            $this->load->library('image_lib',$config);
		            $this->image_lib->initialize($config);
		            $this->image_lib->resize();
		            $input_detail = array(			
						'jenis_gambar' => 'detail',
						'nama_gambar' => $nmfile."-det".$i.".".$extDet,
					);
					//save data (PROSES KETIGA)
					$this->m_set->update_data('tbl_gambar_produk', ['id_gambar' => $this->input->post('idGbrDet'.$i)], $input_detail);
	                $this->image_lib->clear();//clear img lib after resize
	                $ifile = '/marketplace/assets/img/produk/img_detail/'.$gbrDetail['file_name']; 
					unlink($_SERVER['DOCUMENT_ROOT'] .$ifile); // use server document root
	            }
			} //end isset files
		}//end loop

		//update stok
		$inputStok = array(
			'id_produk' => $id_produk,
			'stok_awal' => (int)$this->input->post('stokAwal'),
			'stok_sisa' => (int)$this->input->post('stokAwal'),
			'stok_minimum' => (int)$this->input->post('stokMin'),
			'berat_satuan' => (int)$this->input->post('berat'),
			'status' => '1'
		);

		$this->m_set->update_data('tbl_stok', ['id_stok' => $this->input->post('idStok')], $inputStok);
		//end insert into tbl stok

		//insert into tbl vendorproduk
		$inputVenpro = array(
			'id_vendor' => $id_vendor,
			'id_produk' => $id_produk,
			'id_sub_kategori' => $this->input->post('subKategoriProduk'),
			'id_kategori' => $id_kategori,
			'updated_at' => $timestamp
		);

		$this->m_set->update_data('tbl_vendor_produk', ['id' => $this->input->post('idVendorProduk')], $inputVenpro);
		//end insert into tbl vendorproduk

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => FALSE,
				'pesan' => 'Terjadi kesalahan, mohon hubungi pada kontak kami. Terima kasih'
			));
		}else {
			$this->db->trans_commit();
			echo json_encode(array(
				'status' => TRUE,
				'pesan' => "Data Produk ".$id_produk." Berhasil diupdate"
			));
		}
	}

	public function get_master_kategori()
	{
		$data = $this->m_set->get_data_kategori();
		foreach ($data as $row) {
			$kategori[] = array(
				'id' => $row->id_kategori,
				'text' => $row->nama_kategori,
			);
		}
		echo json_encode ($kategori);
	}

	public function get_master_sub_kategori($idKategori)
	{
		$where = ['id_kategori' => $idKategori];
		$data = $this->m_set->get_by_id(false, 'tbl_sub_kategori_produk', $where, false, false, false);
		foreach ($data as $row) {
			$sub_kategori[] = array(
						'id' => $row['id_sub_kategori'],
						'text' => $row['nama_sub_kategori'],
					);
		}
		echo json_encode ($sub_kategori);
	}

	public function get_satuan()
	{
		$where = ['status' => 1];
		$data = $this->m_set->get_by_id(false, 'tbl_satuan', $where, false, false, false);
		foreach ($data as $row) {
			$satuan[] = array(
				'id' => $row['id_satuan'],
				'text' => $row['nama_satuan'], 
			);
		}
		echo json_encode($satuan);
	}

	public function konfigurasi_upload_produk($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './assets/img/produk/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		$config['file_name'] = $nmfile;
		//load library with custom object name alias
		$this->load->library('upload', $config, 'gbr_produk');
		$this->gbr_produk->initialize($config);
	}

	public function konfigurasi_upload_produk_detail()
	{
		//konfigurasi upload img detail
		$config['upload_path'] = './assets/img/produk/img_detail/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		//load library with custom object name alias
		$this->load->library('upload', $config, 'gbr_detail');
		$this->gbr_detail->initialize($config);
	}

	public function konfigurasi_image_produk($filename)
	{
		//konfigurasi image lib
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = './assets/img/produk/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './assets/img/produk/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 450; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	public function edit_status_produk()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		if ($status == 'aktif') {
			$input = ['status' => 0 ];
			$pesan = 'Berhasil di Non-aktifkan';
		}else{
			$input = ['status' => 1 ];
			$pesan = 'Berhasil di Aktifkan';
		}
		$this->m_set->update_data('tbl_produk', ['id_produk' => $id], $input);
		echo json_encode($data = [
			'status'=> true,
			'pesan' => $pesan
		]);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////	
}
