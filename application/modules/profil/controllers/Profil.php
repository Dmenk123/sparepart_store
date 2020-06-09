<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('homepage/mod_homepage','mod_hpg');
		$this->load->model('mod_profil');
		$this->load->model('mod_global');
		$this->load->model('checkout/mod_checkout','mod_ckt');
		if ($this->session->userdata('logged_in') != true) {
			redirect('home/error_404');
		}
	}

	public function index()
	{
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$id_user = $this->session->userdata('id_user');
		$checkout_notif = $this->mod_ckt->notif_count($id_user);
		$data_user = $this->mod_ckt->get_data_user($id_user);

		if ($this->session->userdata('id_level_user') == '4' || $this->session->userdata('id_level_user') == '1') {
			redirect('dashboard_adm','refresh');
			return;
		}

		$data = array(
			'content' => 'profil/view_profil',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
			'notif_count' => $checkout_notif,
		);

        $this->load->view('temp',$data);
	}

	public function edit_profil($id)
	{
		$this->load->library('Enkripsi');
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$data_user = $this->mod_profil->get_data_profil($id);
		//decrypt password
		$pass_string = $data_user->password;
		$hasil_password = $this->enkripsi->decrypt($pass_string);
		// replace array value
		$data_user->password = $hasil_password;

		$data = array(
			'content' => 'profil/form_edit_profil',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
		);

		$this->load->view('temp', $data);
	}

	public function edit_profil_vendor($id)
	{
		$this->load->library('Enkripsi');
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$data_user = $this->mod_profil->get_data_profil_vendor($id);
		//decrypt password
		$pass_string = $data_user->password;
		$hasil_password = $this->enkripsi->decrypt($pass_string);
		// replace array value
		$data_user->password = $hasil_password;

		$data = array(
			'content' => 'profil/form_edit_profil_vendor',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
		);

		$this->load->view('temp', $data);
	}

	public function konfirmasi_kedatangan($token)
	{
		$id = $this->session->userdata('id_user');
		$this->load->library('Enkripsi');
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		
		//cek tipe pembelian apakah reguler / jasa
		$cek_tipe_pembelian = $this->mod_global->get_by_id('tbl_checkout',['token_confirm' => $token]);
		if ($cek_tipe_pembelian) {
			$tipe_pembelian = ($cek_tipe_pembelian->is_jasa == '1') ? 'jasa' : 'reguler';
		}else{
			$tipe_pembelian = null;
		}
		
		$d_id_ckt = $this->mod_profil->get_by_id_advanced(
			'id_checkout, status_confirm_datang', 'tbl_checkout', ['token_confirm' => $token], false, false, true
		);
		$id_checkout = $d_id_ckt['id_checkout'];
		$status_datang = $d_id_ckt['status_confirm_datang'];

		$data_ckt_header = $this->mod_profil->get_detail_checkout_header($id_checkout);
		$data_ckt_detail = $this->mod_profil->get_detail_checkout($id_checkout);

		//var_dump($data_ckt_header, $data_ckt_detail);exit;

		$data = array(
			'content' => 'profil/form_konfirmasi_kedatangan',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_header' => $data_ckt_header,
			'data_detail' => $data_ckt_detail,
			'status_datang' => $status_datang,
			'tipe' => $tipe_pembelian
		);

		$this->load->view('temp', $data);
	}

	public function konfirmasi_kedatangan_proses()
	{
		$id_checkout = $this->input->post('idCheckout');
		//var_dump($id_checkout);exit;
		$this->db->trans_begin();
		$this->db->update("tbl_checkout", ['status_confirm_datang' => 1], ['id_checkout' => $id_checkout]);
		$this->db->update("tbl_pembelian", ['status_confirm_customer' => 1], ['id_checkout' => $id_checkout]);

		if ($this->db->trans_status() === FALSE) {
        	$this->db->trans_rollback();
        	echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Terjadi Kesalahan, Mohon hubungi kami pada nomor yg ada dikontak. Terima Kasih'
			));
		}else{
        	$this->db->trans_commit();
        	echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Transaksi berhasil dikonfirmasi, Terima kasih telah berbelanja di OnPets E-Marketplace.'
			));
		}
	}

	public function get_select_edit()
	{
		$id = $this->session->userdata('id_user');
		$data = $this->mod_profil->get_data_profil($id);

		//change format date to indonesian format
		$tgl_lahir_string = $data->tgl_lahir_user;
		$tgl_lahir = date("d-m-Y", strtotime($tgl_lahir_string));
		// replace array value
		$data->tgl_lahir_user = $tgl_lahir;

		echo json_encode($data);
	}

	public function list_checkout_history()
	{
		$id_user = $this->session->userdata('id_user');

		//=================== cek expired =======================
		$cek_expired = $this->db->query("SELECT id_checkout, timestamp from tbl_checkout where id_user = '".$id_user."'")->result();
		if ($cek_expired) {
			foreach ($cek_expired as $key => $cek) {
				$exp =  date('Y-m-d H:i:s', strtotime($cek->timestamp. '+ 3 days'));
				if (strtotime(date('Y-m-d H:i:s')) > strtotime($exp)) {
					$this->db->query("UPDATE tbl_checkout SET status ='nonaktif' WHERE id_checkout = '".$cek->id_checkout."' ");
				}
			}
		}
		//=================== end cek expired =======================
		
		$list = $this->mod_profil->get_data_checkout($id_user);
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listCheckout) {
			$link_detail = site_url('profil/checkout_detail/').$listCheckout->id_checkout;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listCheckout->tgl_checkout;
			$row[] = $listCheckout->id_checkout;
			$row[] = $listCheckout->method_checkout;
			$row[] = "Rp. ".number_format($listCheckout->harga_total_produk,0,",",".");
			$row[] = $listCheckout->jasa_ekspedisi;
			$row[] = $listCheckout->pilihan_paket;
			$row[] = $listCheckout->estimasi_datang;
			$row[] = "Rp. ".number_format($listCheckout->ongkos_kirim,0,",",".");
			$row[] = "Rp. ".number_format($listCheckout->ongkos_total,0,",",".");
			$row[] = $listCheckout->kode_ref;
			//add html for action button
			$row[] ='<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Checkout Detail" id="btn_detail" onclick=""><i class="fa fa-info-circle"></i> '.$listCheckout->jml.' Items</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="nonaktif" onclick="nonaktifCheckout('."'".$listCheckout->id_checkout."'".')"><i class="fa fa-times"></i> Nonaktif</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mod_profil->count_all(),
						"recordsFiltered" => $this->mod_profil->count_filtered($id_user),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function update_profil()
	{
		$this->load->library('Enkripsi');
		$this->load->library('upload');
		//declare variabel
		$id_user = $this->input->post('frm_id_user');
		$pass_string = $this->input->post('frm_pass_user');
		$password = $this->enkripsi->encrypt($pass_string);
		$tgl_lahir_string = $this->input->post('frm_tgllhr_user');
		$tgl_lahir = date("Y-m-d", strtotime($tgl_lahir_string));
		$timestamp = date('Y-m-d H:i:s');
		//konfigurasi upload img
		$nmfile = "img_".$id_user;
		$config['upload_path'] = './assets/img/foto_profil/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '0'; 
		$config['max_width']  = '0'; 
		$config['max_height']  = '0';
		$config['file_name'] = $nmfile;
		$this->upload->initialize($config);
		if(isset($_FILES['frm_foto'])) 
		{	
			//jika melakukan upload foto
			if ($this->upload->do_upload('frm_foto')) 
		    {
		    	$gbr = $this->upload->data();
		        //konfigurasi image lib
	            $config['image_library'] = 'gd2';
	            $config['source_image'] = './assets/img/foto_profil/'.$gbr['file_name'];
	            $config['create_thumb'] = FALSE;
	            $config['maintain_ratio'] = FALSE;
	            $config['new_image'] = './assets/img/foto_profil/'.$gbr['file_name'];
	            $config['overwrite'] = TRUE;
	            $config['width'] = 250; //resize
	            $config['height'] = 250; //resize
	            $this->load->library('image_lib',$config); //load image library
	            $this->image_lib->initialize($config);
	            $this->image_lib->resize();

	            //data input array
				$input = array(			
					'email' => trim($this->input->post('frm_email_user')),
					'password' => $password,
					'fname_user' => trim(strtoupper($this->input->post('frm_fname_user'))),
					'lname_user' => trim(strtoupper($this->input->post('frm_lname_user'))),
					'alamat_user' => trim(strtoupper($this->input->post('frm_alamat_user'))),
					'tgl_lahir_user' => $tgl_lahir,
					'no_telp_user' => trim(strtoupper($this->input->post('frm_telp_user'))),
					'id_provinsi' => $this->input->post('frm_prov_user'),
					'id_kota' => $this->input->post('frm_kota_user'),
					'id_kecamatan' => $this->input->post('frm_kec_user'),
					'id_kelurahan' => $this->input->post('frm_kel_user'),
					'kode_pos' => trim(strtoupper($this->input->post('frm_kode_pos'))),
					'id_level_user' => 2,
					'foto_user' => $gbr['file_name'],
					'timestamp' => $timestamp 
				);
		    } //end do upload
		    else 
		    {
				$input = array(			
					'email' => trim($this->input->post('frm_email_user')),
					'password' => $password,
					'fname_user' => trim(strtoupper($this->input->post('frm_fname_user'))),
					'lname_user' => trim(strtoupper($this->input->post('frm_lname_user'))),
					'alamat_user' => trim(strtoupper($this->input->post('frm_alamat_user'))),
					'tgl_lahir_user' => $tgl_lahir,
					'no_telp_user' => trim(strtoupper($this->input->post('frm_telp_user'))),
					'id_provinsi' => $this->input->post('frm_prov_user'),
					'id_kota' => $this->input->post('frm_kota_user'),
					'id_kecamatan' => $this->input->post('frm_kec_user'),
					'id_kelurahan' => $this->input->post('frm_kel_user'),
					'kode_pos' => trim(strtoupper($this->input->post('frm_kode_pos'))),
					'id_level_user' => 2,
					'timestamp' => $timestamp 
				);	
		    }
		    //update to db
		    $this->mod_profil->update_data_profil(array('id_user' => $id_user), $input);
		    echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Profil anda berhasil di Update !!'
			));
		} //end isset file foto
	}//end function

	public function update_profil_vendor()
	{
		$this->load->library('Enkripsi');
		$this->load->library('upload');
		$this->db->trans_begin();
		//declare variabel
		$id_vendor = $this->input->post('frm_id_vendor');
		$nama_vendor = $this->input->post('frm_name_vendor');
		$jenis_usaha = $this->input->post('frm_jenis_usaha');
		$no_ktp = $this->input->post('frm_ktp');
		$id_user = $this->input->post('frm_id_user');
		$pass_string = $this->input->post('frm_pass_user');
		$password = $this->enkripsi->encrypt($pass_string);
		$tgl_lahir_string = $this->input->post('frm_tgllhr_user');
		$tgl_lahir = date("Y-m-d", strtotime($tgl_lahir_string));
		$timestamp = date('Y-m-d H:i:s');
		$status_img_foto = FALSE;
		$status_img_ktp = FALSE;
		$status_img_usaha = FALSE;

		if(isset($_FILES['frm_foto'])) 
		{
			//konfigurasi upload img
			$nmfile = "img_".$id_user;
			$config['upload_path'] = './assets/img/foto_profil/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
			$config['overwrite'] = TRUE;
			$config['max_size'] = '0'; 
			$config['max_width']  = '0'; 
			$config['max_height']  = '0';
			$config['file_name'] = $nmfile;
			$this->upload->initialize($config);	
			//jika melakukan upload foto
			if ($this->upload->do_upload('frm_foto')) 
		    {
		    	$status_img_foto = TRUE;
		    	$gbr = $this->upload->data();
		        //konfigurasi image lib
	            $config['image_library'] = 'gd2';
	            $config['source_image'] = './assets/img/foto_profil/'.$gbr['file_name'];
	            $config['create_thumb'] = FALSE;
	            $config['maintain_ratio'] = FALSE;
	            $config['new_image'] = './assets/img/foto_profil/'.$gbr['file_name'];
	            $config['overwrite'] = TRUE;
	            $config['width'] = 250; //resize
	            $config['height'] = 250; //resize
	            $this->load->library('image_lib',$config); //load image library
	            $this->image_lib->initialize($config);
	            $this->image_lib->resize();	            
		    } //end do upload
		} //end isset file foto 

		if(isset($_FILES['frm_foto_ktp'])) 
		{
			//konfigurasi upload img
			$nmfile2 = "img-ktp_".$id_user;
			$config2['upload_path'] = './assets/img/foto_ktp/';
			$config2['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
			$config2['overwrite'] = TRUE;
			$config2['max_size'] = '0'; 
			$config2['max_width']  = '0'; 
			$config2['max_height']  = '0';
			$config2['file_name'] = $nmfile2;
			$this->upload->initialize($config2);	
			//jika melakukan upload foto
			if ($this->upload->do_upload('frm_foto_ktp')) 
		    {
		    	$status_img_ktp = TRUE;
		    	$gbr2 = $this->upload->data();
		        //konfigurasi image lib
	            $config2['image_library'] = 'gd2';
	            $config2['source_image'] = './assets/img/foto_ktp/'.$gbr2['file_name'];
	            $config2['create_thumb'] = FALSE;
	            $config2['maintain_ratio'] = FALSE;
	            $config2['new_image'] = './assets/img/foto_ktp/'.$gbr2['file_name'];
	            $config2['overwrite'] = TRUE;
	            $config2['width'] = 250; //resize
	            $config2['height'] = 250; //resize
	            $this->load->library('image_lib',$config2); //load image library
	            $this->image_lib->initialize($config2);
	            $this->image_lib->resize();	            
		    } //end do upload
		} //end isset file foto ktp

		if(isset($_FILES['frm_foto_usaha'])) 
		{
			//konfigurasi upload img
			$nmfile3 = "img-usaha_".$id_user;
			$config3['upload_path'] = './assets/img/foto_usaha/';
			$config3['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
			$config3['overwrite'] = TRUE;
			$config3['max_size'] = '0'; 
			$config3['max_width']  = '0'; 
			$config3['max_height']  = '0';
			$config3['file_name'] = $nmfile3;
			$this->upload->initialize($config3);	
			//jika melakukan upload foto
			if ($this->upload->do_upload('frm_foto_usaha')) 
		    {
		    	$status_img_usaha = TRUE;
		    	$gbr3 = $this->upload->data();
		        //konfigurasi image lib
	            $config3['image_library'] = 'gd2';
	            $config3['source_image'] = './assets/img/foto_usaha/'.$gbr3['file_name'];
	            $config3['create_thumb'] = FALSE;
	            $config3['maintain_ratio'] = FALSE;
	            $config3['new_image'] = './assets/img/foto_usaha/'.$gbr3['file_name'];
	            $config3['overwrite'] = TRUE;
	            $config3['width'] = 250; //resize
	            $config3['height'] = 250; //resize
	            $this->load->library('image_lib',$config3); //load image library
	            $this->image_lib->initialize($config3);
	            $this->image_lib->resize();	            
		    } //end do upload
		} //end isset file foto ktp

	    //data input array
		$input['email'] = trim($this->input->post('frm_email_user'));
		$input['password'] = $password;
		$input['fname_user'] = trim(strtoupper($this->input->post('frm_fname_user')));
		$input['lname_user'] = trim(strtoupper($this->input->post('frm_lname_user')));
		$input['alamat_user'] = trim(strtoupper($this->input->post('frm_alamat_user')));
		$input['tgl_lahir_user'] = $tgl_lahir;
		$input['no_telp_user'] = trim(strtoupper($this->input->post('frm_telp_user')));
		$input['id_provinsi'] = $this->input->post('frm_prov_user');
		$input['id_kota'] = $this->input->post('frm_kota_user');
		$input['id_kecamatan'] = $this->input->post('frm_kec_user');
		$input['id_kelurahan'] = $this->input->post('frm_kel_user');
		$input['kode_pos'] = trim(strtoupper($this->input->post('frm_kode_pos')));
		$input['id_level_user'] = 4;
		if ($status_img_foto) {
			$input['foto_user'] = $gbr['file_name'];
		}
		$input['timestamp'] = $timestamp;
	    //update tbl_user
	    $this->mod_profil->update_data_profil(array('id_user' => $id_user), $input);

	    $input2['nama_vendor'] = trim($nama_vendor);
		$input2['jenis_usaha_vendor'] = trim($jenis_usaha);
		$input2['ktp_pemilik'] = trim($no_ktp);
		$input2['updated_at'] = $timestamp;
	    if ($status_img_ktp) {
			$input2['img_ktp'] = $gbr2['file_name'];
		}
		if ($status_img_usaha) {
			$input2['img_vendor'] = $gbr3['file_name'];
		}
		//update tbl_vendor
		$this->mod_profil->update_data_profil_vendor(array('id_vendor' => $id_vendor), $input2);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => FALSE,
				'pesan' => 'Terjadi kesalahan, mohon hubungi pada kontak kami. Terima kasih'
			));
		}else {
			$this->db->trans_commit();			
			echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Profil anda berhasil di Update !!'
			));
		}
	}//end function

	public function checkout_detail($id)
	{
		$id_user = $this->session->userdata('id_user'); 
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$id_user = $this->session->userdata('id_user');
		$data_user = $this->mod_ckt->get_data_user($id_user);
		$checkout_notif = $this->mod_ckt->notif_count($id_user);

		$query_header = $this->mod_profil->get_detail_checkout_header($id);
		$query = $this->mod_profil->get_detail_checkout($id);

		$data = array(
			'content' => 'profil/view_checkout_detail',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
			'hasil_header' => $query_header,
			'hasil_data' => $query,
			'notif_count' => $checkout_notif,
		);

        $this->load->view('temp',$data);
	}

	public function nonaktif_checkout($id)
	{
		$input = array(
			'status' => "nonaktif" 
		);
		$this->mod_profil->update_nonaktif_checkout(array('id_checkout' => $id), $input);
		$data = array(
			'status' => TRUE,
			'pesan' => "Transaksi anda dengan kode ".$id." berhasil di nonaktifkan.",
		);

		echo json_encode($data);
	}

	public function konfirmasi_checkout($id)
	{
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$id_user = $this->session->userdata('id_user');
		$checkout_notif = $this->mod_ckt->notif_count($id_user);
		$data_user = $this->mod_ckt->get_data_user($id_user);

		$query = $this->mod_profil->get_detail_checkout($id);
		//var_dump($query);exit;
		$query_header = $this->mod_profil->get_detail_checkout_header($id);
		$data = array(
			'content' => 'profil/form_konfirmasi',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'profil/jsProfil',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
			'notif_count' => $checkout_notif,
			'hasil_data' => $query,
			'hasil_header' => $query_header,
		);

        $this->load->view('temp',$data);
	}

	public function konfirmasi_checkout_cod($id_checkout)
	{
		//update tbl checkout to nonaktif
		$input_checkout = array(
			'status' => "nonaktif" 
		);
		$this->mod_profil->update_nonaktif_checkout(array('id_checkout' => $id_checkout), $input_checkout);

		//insert tbl pembelian
		$id_user = $this->session->userdata('id_user');
		$timestamp = date('Y-m-d H:i:s');
		$id_beli = $this->mod_profil->getKodePembelian();
		$input_beli = array(
			'id_pembelian' => $id_beli,
			'id_checkout' => $this->input->post('cfrmIdCheckout'),
			'id_user' => $id_user,
			'tgl_pembelian' => date("Y-m-d"),
			'timestamp' => $timestamp
		);

		$this->mod_profil->insert_data_pembelian($input_beli);

		//proses update data stok
		$hitung = count($this->input->post('crfmIdStok'));
		//ambil niali stok sisa
		$stok_sisa = array(); //inisialisasi var array
		$hasil_kurang = array(); //inisialisasi var array
		for ($i=0; $i <$hitung; $i++) 
		{ 
			$stok_sisa[$i] = $this->mod_profil->get_sisa_stok($this->input->post("crfmIdStok")[$i]);
			$hasil_kurang[$i] = $stok_sisa[$i]->stok_sisa - $this->input->post('crfmQty')[$i];
		}
		// print_r($stok_sisa);
		// print_r($hasil_kurang);
		//siapkan data array untuk proses update
		$data_stok = array();
		for ($i=0; $i < $hitung; $i++) 
		{ 
			$data_stok[$i] = array(
				'id_stok' => $this->input->post('crfmIdStok')[$i],
				'stok_sisa' => $hasil_kurang[$i],
			);
			
		}
		//update batch
		$this->db->update_batch('tbl_stok',$data_stok,'id_stok');
        
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Terima Kasih Telah konfirmasi pembelian, Barang akan dikirim tiap hari senin-sabtu di jam kerja, Apabila barang belum datang lebih dari 2 hari dari tgl konfirmasi, anda dapat menghubungi Whatsapp kami'
		));
	}

	public function konfirmasi_checkout_tfr($id_checkout)
	{
		$this->load->library('upload');
		$this->db->trans_begin();
		//update tbl checkout to nonaktif
		$input_checkout = array(
			'status' => "nonaktif" 
		);
		//ubdate status to db
		$this->mod_profil->update_nonaktif_checkout(array('id_checkout' => $id_checkout), $input_checkout);

		//insert tbl pembelian
		$id_user = $this->session->userdata('id_user');
		$timestamp = date('Y-m-d H:i:s');
		$id_beli = $this->mod_profil->getKodePembelian();
		$omset_total = 0;
		for ($i=0; $i <count($this->input->post('crfmIdStok')); $i++) {
			//omset tanpa ongkir
			$omset_total += (int)$this->input->post('crfmHargatot')[$i];
		}
		
		$input_beli = array(
			'id_pembelian' => $id_beli,
			'id_checkout' => $this->input->post('cfrmIdCheckout'),
			'id_user' => $id_user,
			'tgl_pembelian' => date("Y-m-d"),
			'omset_pembelian' => $omset_total,
			'timestamp' => $timestamp
		);
		//konfigurasi upload img
		$config['upload_path'] = './assets/img/bukti_transfer/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		//$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		$config['encrypt_name'] = TRUE;// for encrypting the name
		$this->upload->initialize($config);

		for ($i=1; $i<=3 ; $i++) 
		{ 
			if(isset($_FILES['crfmBukti'.$i]))
			{
				//jika melakukan upload foto
		        if ($this->upload->do_upload('crfmBukti'.$i)) //will upload selected file to destiny folder
		        {
		        	$gbr = $this->upload->data();//get file upload data
		        	//konfigurasi image lib
	                $resize_gbr['image_library'] = 'gd2';
	                $resize_gbr['source_image'] = './assets/img/bukti_transfer/'.$gbr['file_name'];
	                $resize_gbr['create_thumb'] = FALSE;
	                $resize_gbr['maintain_ratio'] = FALSE;
	                $resize_gbr['width'] = 540; //resize
	                $resize_gbr['height'] = 540; //resize
	                $this->load->library('image_lib',$resize_gbr);
	                $this->image_lib->initialize($resize_gbr);
	                $this->image_lib->resize();
	                $input_beli["btransfer_".$i] = $gbr['file_name']; //add to array input
                    $this->image_lib->clear();//clear img lib after resize
		        }
			}
		}
		//print_r($input_beli);
		//add to db
		$this->mod_profil->insert_data_pembelian($input_beli);

		//insert to tbl_bukti_transfer_vendor
		$data_ckt_detail = $this->db->query("SELECT * FROM tbl_checkout_detail WHERE id_checkout = '".$this->input->post('cfrmIdCheckout')."'")->result_array();
		for ($i=0; $i <count($data_ckt_detail); $i++) { 
			//cek ada row tidaknya
			$cek = $this->db->query("SELECT * FROM tbl_bukti_transfer_vendor WHERE id_pembelian = '".$id_beli."' AND id_vendor = '".$data_ckt_detail[$i]['id_vendor']."'")->row();
			if (!$cek) {
				$bt_vendor = [
					'id_pembelian' => $id_beli,
					'id_vendor' => $data_ckt_detail[$i]['id_vendor'],
					'bukti_transfer_vendor' => null,
					'is_confirm' => 0,
					'created_at' => $timestamp,
					'confirmed_at' => null
				];
				$this->db->insert('tbl_bukti_transfer_vendor', $bt_vendor);
			}
		}
		//end insert to tbl_bukti_transfer_vendor
		
		//insert data tbl_trans_vendor
		for ($i=0; $i <count($this->input->post('crfmIdStok')); $i++) { 
			$input_t_vendor = array(
				'id' => $this->mod_profil->getKodeMax('tbl_trans_vendor', 'id'),
				'id_pembelian' => $id_beli,
				'id_vendor' => $this->input->post('crfmIdvendor')[$i],
				'tanggal' => date("Y-m-d"),
				'omset_total' => $this->input->post('crfmHargatot')[$i],
				'created_at' => $timestamp
			);

			$this->mod_global->save($input_t_vendor, 'tbl_trans_vendor');
		}
		//end insert data tbl_trans_vendor
		
		//insert data tbl_trans_rekber
		$input_t_rekber = array(
			'id' => $this->mod_profil->getKodeMax('tbl_trans_rekber', 'id'),
			'id_pembelian' => $id_beli,
			'id_rekber' => $this->input->post('cfrmIdRekber'),
			'nominal' => $this->input->post('cfrmHargaTotalHdn'),
			'nilai_ongkir' => $this->input->post('cfrmHargaOngkirHdn'),
			'harga_pembelian' => $this->input->post('cfrmHargaProdukHdn'),
			'tanggal' => date("Y-m-d"),
			'created_at' => $timestamp
		);

		$this->mod_global->save($input_t_rekber, 'tbl_trans_rekber');
		//end insert data tbl_trans_rekber

		//proses update data stok
		$hitung = count($this->input->post('crfmIdStok'));
		//ambil niali stok sisa
		$stok_sisa = array(); //inisialisasi var array
		$hasil_kurang = array(); //inisialisasi var array
		for ($i=0; $i <$hitung; $i++) 
		{ 
			$stok_sisa[$i] = $this->mod_profil->get_sisa_stok($this->input->post("crfmIdStok")[$i]);
			$hasil_kurang[$i] = $stok_sisa[$i]->stok_sisa - $this->input->post('crfmQty')[$i];
		}
		
		//siapkan data array untuk proses update
		$data_stok = array();
		for ($i=0; $i < $hitung; $i++) 
		{ 
			$data_stok[$i] = array(
				'id_stok' => $this->input->post('crfmIdStok')[$i],
				'stok_sisa' => $hasil_kurang[$i],
			);
			
		}
		//update batch
		$this->db->update_batch('tbl_stok',$data_stok,'id_stok');
        
        if ($this->db->trans_status() === FALSE) {
        	$this->db->trans_rollback();
        	echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Terjadi Kesalahan, Mohon hubungi kami pada nomor yg ada dikontak. Terima Kasih'
			));
		}else{
        	$this->db->trans_commit();
        	echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Terima Kasih Telah konfirmasi pembelian, Bukti Resi Pengiriman akan dikirim pada email anda, anda dapat menghubungi Whatsapp kami apabila ada keluhan'
			));
		}
		
	}
	
}//end of class profil.php
