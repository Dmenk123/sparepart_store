<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_vendor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->model('produk/mod_produk');
		$this->load->model('homepage/mod_homepage','mod_hpg');
		$this->load->model('mod_register_vendor','m_reg');
		$this->load->model('checkout/mod_checkout','mod_ckt');
		if ($this->session->userdata('logged_in') == true) {
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
		
		//captcha	
		$imgCaptcha = $this->buat_captcha();
		

		$data = array(
			'content' => 'register_vendor/view_register_vendor',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'register_vendor/jsRegisterVendor',
			'menu_select_search' => $menu_select_search,
			'img' => $imgCaptcha,
		);

		if ($this->session->userdata('id_user') == !null) {
			$id_user = $this->session->userdata('id_user');
			$checkout_notif = $this->mod_ckt->notif_count($id_user);
			$data['notif_count'] = $checkout_notif;
		}

        $this->load->view('temp',$data);
	}

	public function buat_captcha()
	{
		$options = array(
			'img_path' => 'assets/img/captcha_img/',
			'img_url' => base_url().'assets/img/captcha_img/',
			'img_width' => '200',
			'img_height' => '50',
			'word_length'   => 5,
			'font_size' => 16,
			'expiration' => 7200,
			'font_path' => FCPATH.'assets/css/fonts/E004007T.ttf',
			'show_grid' => false,
			'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            ),
		);
		$cap = create_captcha($options);
		// Unset previous captcha and store new captcha word
		$this->session->unset_userdata('captchaCode');
		$this->session->set_userdata('captchaCode', $cap['word']);

		$image = $cap['image'];
		return $image;
	}

	public function refresh_captcha()
	{
        // Captcha configuration
        $options = array(
			'img_path' => 'assets/img/captcha_img/',
			'img_url' => base_url().'assets/img/captcha_img/',
			'img_width' => '200',
			'img_height' => '50',
			'word_length'   => 5,
			'font_size' => 16,
			'expiration' => 7200,
			'font_path' => FCPATH.'assets/css/fonts/E004007T.ttf',
			'show_grid' => false,
			'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            ),
		);
        $captcha = create_captcha($options);
        
        // Unset previous captcha and store new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        
        // Display captcha image
        echo $captcha['image'];
    }

	public function check_captcha()
	{
		if ($this->input->post('captcha') == $this->session->userdata('captchaCode')) 
		{
			return true;
		}
		else
		{
			echo json_encode(array('pesan_error' => 'Maaf terjadi kesalahan pada penulisan captcha'));
			return false;
		}
	}

	public function add_register_vendor()
	{
		$this->load->model('login/mod_login','m_log');
		$this->load->model('homepage/mod_homepage','mod_hpg');
		$this->load->library('Enkripsi');

		if ($this->input->post('reg_captcha') == $this->session->userdata('captchaCode')) 
		{	//declare variabel
			$rand = $this->m_reg->getKodeUser();
			$pass_string = '123456';
			$password = $this->enkripsi->encrypt($pass_string);
			$tgl_lahir_string = $this->input->post('reg_tgl_lahir');
			$tgl_lahir = date("Y-m-d", strtotime($tgl_lahir_string));
			$timestamp = date('Y-m-d H:i:s');
						
			if(isset($_FILES['reg_foto_ktp'])) 
			{
				//konfigurasi upload img
				$nmfilektp = "img-ktp_".$rand;
				$config['upload_path'] = './assets/img/foto_ktp/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
				$config['overwrite'] = TRUE;
				$config['max_size'] = '0'; 
				$config['max_width']  = '0'; 
				$config['max_height']  = '0';
				$config['file_name'] = $nmfilektp;
				$this->upload->initialize($config);
			
		        //jika melakukan upload foto
		        if ($this->upload->do_upload('reg_foto_ktp')) 
		        {
		        	$gbr_ktp = $this->upload->data();
		        	//konfigurasi image lib
	                $config['image_library'] = 'gd2';
	                $config['source_image'] = './assets/img/foto_ktp/'.$gbr_ktp['file_name'];
	                $config['create_thumb'] = FALSE;
	                $config['maintain_ratio'] = FALSE;
	                $config['new_image'] = './assets/img/foto_ktp/'.$gbr_ktp['file_name'];
	                $config['overwrite'] = TRUE;
	                $config['width'] = 400; //resize
	                $config['height'] = 400; //resize
	                $this->load->library('image_lib',$config); //load image library
	                $this->image_lib->initialize($config);
					$this->image_lib->resize();
					
					if(isset($_FILES['reg_foto_usaha'])) 
					{
						//konfigurasi upload img
						$nmfileusaha = "img-usaha_".$rand;
						$config['upload_path'] = './assets/img/foto_usaha/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
						$config['overwrite'] = TRUE;
						$config['max_size'] = '0'; 
						$config['max_width']  = '0'; 
						$config['max_height']  = '0';
						$config['file_name'] = $nmfileusaha;
						$this->upload->initialize($config);
			
						if ($this->upload->do_upload('reg_foto_usaha')) 
						{
							$gbr_usaha = $this->upload->data();
							//konfigurasi image lib
							$config['image_library'] = 'gd2';
							$config['source_image'] = './assets/img/foto_usaha/'.$gbr_usaha['file_name'];
							$config['create_thumb'] = FALSE;
							$config['maintain_ratio'] = FALSE;
							$config['new_image'] = './assets/img/foto_usaha/'.$gbr_usaha['file_name'];
							$config['overwrite'] = TRUE;
							$config['width'] = 400; //resize
							$config['height'] = 400; //resize
							$this->load->library('image_lib',$config); //load image library
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
						}
					}

					$this->db->trans_begin();
	                //insert tbl user
					$input_user = array(
						'id_user' => $rand,
						'email' => trim($this->input->post('reg_email')),
						'password' => $password,
						'fname_user' => trim(strtoupper($this->input->post('reg_nama'))),
						'lname_user' => trim(strtoupper($this->input->post('reg_nama_blkg'))),
						'alamat_user' => trim(strtoupper($this->input->post('reg_alamat'))),
						'tgl_lahir_user' => $tgl_lahir,
						'no_telp_user' => trim(strtoupper($this->input->post('reg_telp'))),
						'id_provinsi' => $this->input->post('reg_provinsi'),
						'id_kota' => $this->input->post('reg_kota'),
						'id_kecamatan' => $this->input->post('reg_kecamatan'),
						'id_kelurahan' => $this->input->post('reg_kelurahan'),
						'kode_pos' => trim(strtoupper($this->input->post('reg_kode_pos'))),
						'id_level_user' => 4,
						'foto_user' => 'user_default.png',
						'timestamp' => $timestamp,
						'status' => 0
					);

					//insert tbl vendor
					$input_vendor = array(
						'id_vendor' => $this->m_reg->getKodeVendor(),
						'nama_vendor' => trim(strtoupper($this->input->post('reg_nama_vendor'))),
						'jenis_usaha_vendor' => trim(strtoupper($this->input->post('reg_jenis_usaha'))),
						'ktp_pemilik' => trim(strtoupper($this->input->post('reg_no_ktp'))),
						'img_ktp' => $gbr_ktp['file_name'],
						'img_vendor' => $gbr_usaha['file_name'],
						'id_user' => $rand,
						'created_at' => $timestamp
					);

		        } //end do upload
		        //save to db
		        $this->m_reg->add_data_register($input_user);
				$this->m_reg->add_data_vendor($input_vendor);

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
						"pesan" => 'Terima kasih kami akan memverifikasi data anda, Kami akan mengirim email konfirmasi pada anda'
					));
					
				}
			} //end isset upload
		}//end if captcha true
		else
		{
			echo json_encode(array(
				"status" => FALSE,
				'pesan' => 'Maaf terjadi kesalahan pada penulisan captcha'
			));
		}
	}//end function

}
