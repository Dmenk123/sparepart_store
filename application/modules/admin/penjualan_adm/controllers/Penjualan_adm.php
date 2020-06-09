<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email'); // untuk kirim email
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_penjualan_adm','m_jual');
		$this->load->model('Order_produk_adm/mod_order_produk_adm','m_order');
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
			'modal' => 'modalPenjualanAdm',
			'css'	=> 'cssPenjualanAdm',
			'js'	=> 'jsPenjualanAdm',
			'view'	=> 'view_list_penjualan_adm'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_penjualan()
	{
		$id_vendor = $this->get_id_vendor();
		$flag_id_vendor = "";
		if ($this->session->userdata('id_level_user') == '1') {
			$list = $this->m_jual->get_datatable_penjualan();
		}else{
			$list = $this->m_jual->get_datatable_penjualan($id_vendor);
			$flag_id_vendor = $id_vendor;
		}
		
		//echo $this->db->last_query();exit;
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listPenjualan) {
			$link_detail = site_url('penjualan_adm/penjualan_detail/').$listPenjualan->id_pembelian;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listPenjualan->id_pembelian;
			$row[] = $listPenjualan->fname_user." ".$listPenjualan->lname_user;
			$row[] = $listPenjualan->fname_kirim." ".$listPenjualan->lname_kirim;
			$row[] = $listPenjualan->alamat_kirim;
			$row[] = $listPenjualan->kode_ref;
			
			if ($this->session->userdata('id_level_user')=='1') {
				if ($listPenjualan->status_confirm_vendor == '0') {
					$row[] = "<span style='color: red;'>Belum</span>";
				}elseif ($listPenjualan->status_confirm_vendor == '1'){
					$row[] = "Sudah";
				}else{
					$row[] = "<span style='color: red;'><strong>Dibatalkan</strong></span>";
				}
			}else{
				if ($listPenjualan->status_confirm_vendor == '0') {
					$row[] = "<span style='color: red;'>Belum</span>";
				}elseif ($listPenjualan->status_confirm_vendor == '1'){
					$row[] = "Sudah";
				}else{
					$row[] = "<span style='color: red;'><strong>Dibatalkan</strong></span>";
				}
			}
			
			///////// jika administrator
			if ($this->session->userdata('id_level_user')=='1') {
				if ($listPenjualan->status_confirm_customer == '0') {
					$row[] = "<span style='color: red;'>Belum</span>";
				}elseif ($listPenjualan->status_confirm_customer == '1'){
					$row[] = "Sudah";
				}else{
					$row[] = "<span style='color: red;'><strong>Dibatalkan</strong></span>";
				}

				if ($listPenjualan->status_confirm_adm == '0') {
					$row[] = "<span style='color: red;'>Belum</span>";
				}elseif ($listPenjualan->status_confirm_adm == '1'){
					$row[] = "Sudah";
				}else{
					$row[] = "<span style='color: red;'><strong>Dibatalkan</strong></span>";
				}
			}
			///////// end jika administrator

			//add html for action button
			if ($listPenjualan->jml > 0) {
				if ($listPenjualan->status_confirm_vendor != '1') {
					if ($this->session->userdata('id_level_user') == '1') {
						if ($listPenjualan->status_confirm_vendor == '2') {
							$row[] = 
							'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
							 <a class="btn btn-sm btn-danger btn_edit_status" href="javascript:void(0)" title="Batal" id="'.$listPenjualan->id_pembelian.'"><i class="fa fa-times"></i> Batal</a>';
						}else{
							$row[] = 
							'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
							 <a class="btn btn-sm btn-success btn_edit_status" href="javascript:void(0)" title="Aktif" id="'.$listPenjualan->id_pembelian.'"><i class="fa fa-check"></i> Aktif</a>';
						}
					}else{
						$row[] = 
						'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>';
					}
				}else{
					if ($this->session->userdata('id_level_user') == '1') {
						if ($listPenjualan->status_confirm_vendor == '1') {
							$row[] = 
							'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>';
						}else{
							$row[] = 
							'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
							 <a class="btn btn-sm btn-danger btn_edit_status" href="javascript:void(0)" title="Batal" id="'.$listPenjualan->id_pembelian.'"><i class="fa fa-times"></i> Batal</a>';
						}
					}else{
						$row[] = 
						'<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Penjualan Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>';
					}	
					
				}
			}
			else
			{
				$row[] = null;
			}

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_jual->count_all_penjualan($flag_id_vendor),
						"recordsFiltered" => $this->m_jual->count_filtered_penjualan($flag_id_vendor),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function penjualan_detail()
	{
		$id_vendor = "";
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);

		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		if ($this->session->userdata('id_level_user') != '1') {
			$id_vendor = $this->get_id_vendor();
		}

		$id_pembelian = $this->uri->segment(3);
		//cek tipe pembelian apakah reguler / jasa
		$cek_tipe_pembelian = $this->m_jual->cek_tipe_pembelian($id_pembelian);
		if ($cek_tipe_pembelian->is_jasa == '1') {
			$flag_tipe = 'jasa';	
		}else{
			$flag_tipe = 'reguler';
		}

		$query_header = $this->m_jual->get_data_penjualan_header($id_pembelian, $id_vendor);
		
		//echo $this->db->last_query();exit;
		$id_checkout = $this->m_jual->get_data_id_checkout($id_pembelian);
		$query_data = $this->m_jual->get_data_penjualan_detail($id_checkout, $id_vendor);
		// echo $this->db->last_query();exit;
		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			'hasil_header' => $query_header,
			'hasil_data' => $query_data,
			'flag_tipe' => $flag_tipe
		);

		$content = [
			'modal' => 'modalPenjualanAdm',
			'css'	=> 'cssPenjualanAdm',
			'js'	=> 'jsPenjualanAdm',
			'view'	=> 'view_detail_penjualan_adm'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function cetak_nota_penjualan()
	{
		$id_vendor = "";
		$nama_vendor = "OnPets E-Marketplace";
		$this->load->library('Pdf_gen');
	
		if ($this->session->userdata('id_level_user') != '1') {
			$id_vendor = $this->get_id_vendor();
			$dt_vendor = $this->m_jual->get_by_id_advanced(
				"nama_vendor", 
				'tbl_vendor', 
				"id_vendor = '".$id_vendor."'",
				$join=false, 
				$order=false, 
				$single=true
			);
			$nama_vendor = $dt_vendor['nama_vendor'];
		}

		$id_pembelian = $this->uri->segment(3);
		$tipe = $this->uri->segment(4);

		$query_header = $this->m_jual->get_data_penjualan_header($id_pembelian, $id_vendor);
		//get id_checkout
		$id_checkout = $this->m_jual->get_data_id_checkout($id_pembelian);
		$query_data = $this->m_jual->get_data_penjualan_detail($id_checkout, $id_vendor);

		$data = array(
			'title' => 'Nota Penjualan',
			'hasil_header' => $query_header,
			'hasil_data' => $query_data, 
			'nama_vendor' => $nama_vendor,
			'flag_tipe' => $tipe
		);


	    $html = $this->load->view('view_nota_penjualan', $data, true);
	    
	    $filename = 'nota_penjualan_'.$id_pembelian.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function get_konfirmasi_penjualan($id)
	{
		$data = array(
			'data_header' => $this->m_jual->get_data_penjualan_header($id),
		);
		echo json_encode($data);
	}

	public function konfigurasi_upload_bukti($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './assets/img/bukti_konfirmasi/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		$config['file_name'] = $nmfile;
		//load library with custom object name alias
		$this->load->library('upload', $config, 'gbr_bukti');
		$this->gbr_bukti->initialize($config);
	}

	public function konfigurasi_email($userEmail, $tokenString)
	{
		$url = base_url('profil/index/');
		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 	465,
			'smtp_user' => 'mail.onpets@gmail.com',
			'smtp_pass' => 'onpets2019',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'smtp_timeout' => '7',
			'validation'=> TRUE
		);
		
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		//Email content
		$htmlContent = '<h2>OnPets E-Marketplace</h2>';
		$htmlContent .= '<p>Terima Kasih kepada : '.trim($userEmail).' yang telah memilih berbelanja pada kami</p>';
		$htmlContent .= '<p>Berikut Merupakan Bukti Bahwa barang telah kami kirimkan, gambar yang kami kirimkan berupa gambar hasil scan dari Resi pengiriman Ekspedisi</p>';
		$htmlContent .= '<p>Mohon konfirmasi apabila barang telah sampai ditangan anda dengan cara klik link dibawah ini. Terimakasih.</p>';
		$htmlContent .= '<p>Klik disini untuk Konfirmasi Kedatangan : '.$url.$tokenString.'</p>';

		$this->email->to(trim($userEmail));
		$this->email->from("mail.onpets@gmail.com", 'OnPets E-Marketplace'); 
		$this->email->subject(trim('Konfirmasi Pembelian')); 
		$this->email->message($htmlContent);
	}

	public function konfigurasi_email_jasa($userEmail, $tokenString, $txtAlamatVendor)
	{
		$url = base_url('profil/index/');
		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 	465,
			'smtp_user' => 'mail.onpets@gmail.com',
			'smtp_pass' => 'onpets2019',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'smtp_timeout' => '7',
			'validation'=> TRUE
		);
		
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		//Email content
		$htmlContent = '<h2>OnPets E-Marketplace</h2>';
		$htmlContent .= '<p>Terima Kasih kepada : '.trim($userEmail).' yang telah memilih berbelanja pada kami</p>';
		$htmlContent .= '<p>Berikut Merupakan tanda bahwa pesanan anda telah disetujui</p>';
		$htmlContent .= '<p>Silahkan anda mengunjungi store/toko kami pada alamat : '.$txtAlamatVendor.'</p>';
		$htmlContent .= '<p>Mohon konfirmasi apabila anda setuju untuk mengunjungi store dengan cara klik link dibawah ini. Terimakasih.</p>';
		$htmlContent .= '<p>Klik disini untuk Konfirmasi : '.$url.$tokenString.'</p>';

		$this->email->to(trim($userEmail));
		$this->email->from("mail.onpets@gmail.com", 'OnPets E-Marketplace'); 
		$this->email->subject(trim('Konfirmasi Pembelian')); 
		$this->email->message($htmlContent);
	}

	public function konfigurasi_image_bukti($filename)
	{
		//konfigurasi image lib
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = './assets/img/bukti_konfirmasi/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './assets/img/bukti_konfirmasi/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 600; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	public function generate_token($id_customer)
	{
		//create valid dan expire token (2hours)
  		$date_create_token = date('Y-m-d H:i:s');
  		// $date_expired_token = date('Y-m-d H:i:s', strtotime('+2 hour', strtotime($date_create_token)));
 		// create token string (concat string user_token and date_create)
  		$tokenstring = md5(sha1($id_customer.$date_create_token));
  		return $tokenstring;
	}

	public function konfirmasi_penjualan($idBeli)
	{
		$this->db->trans_begin();
		$txtAlamatVendor = "";
		$id_customer = $this->input->post('fieldIdCustomer');
		
		//get_id_checkout
		$d_checkout = $this->m_jual->get_by_id_advanced(
			'id_checkout',
			'tbl_pembelian',
			['id_pembelian' => $this->input->post('fieldIdBeli')],
			false,
			false,
			true 
		);
		$id_checkout = $d_checkout['id_checkout'];
		//end get id_checkout
		
		$id_vendor = $this->get_id_vendor();

		//cek tipe pembelian apakah reguler / jasa
		$cek_tipe_pembelian = $this->m_jual->cek_tipe_pembelian($this->input->post('fieldIdBeli'));
		// var_dump($cek_tipe_pembelian->is_jasa);exit;
		if ($cek_tipe_pembelian->is_jasa == '1') {
			$flag_tipe = 'jasa';
			//get alamat vendor
			$query_vendor = $this->db->query("
				SELECT 
					v.nama_vendor,
					CONCAT(u.alamat_user,', Kelurahan ',kel.nama_kelurahan, ', Kecamatan ',kec.nama_kecamatan, ', ', kota.nama_kota, ', ', prov.nama_provinsi) AS alamat_lengkap
				FROM
					tbl_vendor v
					JOIN tbl_user u ON v.id_user = u.id_user
					JOIN tbl_provinsi prov ON u.id_provinsi = prov.id_provinsi
					JOIN tbl_kota kota ON u.id_kota = kota.id_kota
					JOIN tbl_kecamatan kec ON u.id_kecamatan = kec.id_kecamatan
					JOIN tbl_kelurahan kel ON u.id_kelurahan = kel.id_kelurahan
				WHERE v.id_vendor = '".$id_vendor."'
			")->row();

			$txtAlamatVendor = $query_vendor->alamat_lengkap;
			// var_dump($txtAlamatVendor);exit;
		}else{
			$flag_tipe = 'reguler';
		}
		//end cek tipe pembelian
		//	
		//cek ada token pada checkout
		$cek_token = $this->m_jual->cek_token($id_checkout);
		if ($cek_token['status'] == FALSE) {
			$str_token = $this->generate_token($id_customer);
		}else{
			$str_token = $cek_token['token_confirm'];
		}

		//replace space with dash
		$nmfile = str_replace(" ", "-", strtolower(trim("bukti transfer ".$this->input->post('fieldIdBeli'))));
		//load konfig upload
		$this->konfigurasi_upload_bukti($nmfile);
		//jika melakukan upload foto
		if ($this->gbr_bukti->do_upload('buktiConfirm')) 
		{
			//update token to tbl_checkout
			$this->db->update('tbl_checkout', ['token_confirm' => $str_token], ['id_checkout' => $id_checkout]);

			$gbrBukti = $this->gbr_bukti->data();
			//inisiasi variabel u/ digunakan pada fungsi config img bukti
			$nama_file_bukti = $gbrBukti['file_name'];
			//cek tipe dan tentukan email config
			if ($flag_tipe == 'jasa') {
				$this->konfigurasi_email_jasa($this->input->post('fieldEmailCustomer'), $str_token, $txtAlamatVendor);
			}else{
				$this->konfigurasi_email($this->input->post('fieldEmailCustomer'), $str_token);
			}
			//add attachment pada konfigurasi email
			$this->email->attach($gbrBukti['full_path']);
		    //send email dan cek jika email sukses terkirim
		    if (!$this->email->send()) {
		    	$this->db->trans_rollback();
		        echo json_encode(array(
					"status" => FALSE,
					"pesan" => 'Gagal dalam mengirim email, Mohon coba lagi.'
				));
				return;
		    }
		    //load config img bukti
		    $this->konfigurasi_image_bukti($nama_file_bukti);
			//clear img lib after resize
			$this->image_lib->clear();

			//update img transfer vendor 
			$bt_vendor = [
				'bukti_transfer_vendor' => $gbrBukti['file_name'],
				'is_confirm' => 1,
				'confirmed_at' => date('Y-m-d H:i:s')
			];
			$this->db->update('tbl_bukti_transfer_vendor', $bt_vendor, [
				'id_pembelian' => $this->input->post('fieldIdBeli'),
				'id_vendor' => $id_vendor
			]);
			//end update to tbl_bukti_transfer_vendor

			//update status
			//cek jika semua sudah diconfirm 
			$arr_sudah_konfirm = [];
			$cek = $this->db->query("SELECT * FROM tbl_bukti_transfer_vendor WHERE id_pembelian = '".$this->input->post('fieldIdBeli')."'")->result();
			foreach ($cek as $key => $val) {
				if ($val->is_confirm == 1) {
					$arr_sudah_konfirm[] = TRUE;
				}else{
					$arr_sudah_konfirm[] = FALSE;
				}
			}

			//cek apakah ada false pada array
			if (in_array(FALSE, $arr_sudah_konfirm)){
				$sudah_konfirm = false;
			}else{
				$sudah_konfirm = true;
			}

			//jika sudah konfirm semua maka update konfirmasi vendor pada tabel pembelian
			if ($sudah_konfirm == TRUE) {
				$data_input = array(
					'status_confirm_vendor' => "1"
				);
				$this->m_jual->update_data_konfirmasi(array('id_pembelian' => $this->input->post('fieldIdBeli')), $data_input);
			}
			//end update status
			
			//insert tbl log
			$data_log = array(
				'keterangan' => 'Insert data konfirmasi ke tabel pembelian, id_user = '.$this->input->post('fieldIdUser'),
				'datetime' => date('Y-m-d H:i:s'),
				'id_user' => $this->input->post('fieldIdUser'),
				'data_baru' => $nmfile
			);
			$this->m_jual->tambah_datalog_konfirmasi($data_log);
		} //end 

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
				"pesan" => 'Data Telah berhasil dikonfirmasi'
			));
		}
	}

	public function konfirmasi_penjualan_adm()
	{
		$id_pembelian = $this->input->post('id');
		//var_dump($id_checkout);exit;
		$this->db->trans_begin();
		$this->db->update("tbl_trans_rekber", ['status_penarikan' => 1], ['id_pembelian' => $id_pembelian]);
		$this->db->update("tbl_pembelian", ['status_confirm_adm' => 1], ['id_pembelian' => $id_pembelian]);

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
				"pesan" => 'Transaksi berhasil dikonfirmasi, Untuk Pengelolaan omset dapat dilihat pada menu Kelola Omset.'
			));
		}
	}

	public function edit_status_penjualan($id)
	{
		$id_vendor = $this->get_id_vendor();
		$input_status = $this->input->post('status');
		// jika aktif maka di set ke Batal / "0"
		if ($input_status == " Aktif") {
			$status = '2';
			$psn_txt = "Penjualan dengan kode ".$id." dinonaktifkan.";
			$data_lama = '0';
			$data_baru = '2';
		} elseif ($input_status == " Batal") {
			$status = '0';
			$psn_txt = "Penjualan dengan kode ".$id." diaktifkan kembali.";
			$data_lama = '2';
			$data_baru = '0';
		}
		
		$input = array(
			'status_confirm_adm' => $status, 'status_confirm_customer' => $status, 'status_confirm_vendor' => $status,
		);

		$this->m_jual->update_status_penjualan(array('id_pembelian' => $id), $input);

		//jika di aktifkan kurangi stok, jika dibatalkan tambah stok
		$d_ckt = $this->m_jual->get_by_id_advanced(
			'id_checkout',
			'tbl_pembelian',
			['id_pembelian' => $id],
			false,
			false,
			true
		);

		//update status checkout ketika di nonaktif/batalkan
		if ($status == '0') {
			$this->m_jual->update_status_checkout(['id_checkout' => $d_ckt['id_checkout']], ['status' => 'nonaktif']);
		}else{
			$this->m_jual->update_status_checkout(['id_checkout' => $d_ckt['id_checkout']], ['status' => 'batal']);
		}
		

		//get data checkout detail
		$where = ['id_checkout' => $d_ckt['id_checkout']];
		$d_ckt = $this->m_jual->get_by_id_advanced(
			'id_checkout, id_produk, id_vendor, id_stok, qty',
			'tbl_checkout_detail',
			$where,
			false,
			false,
			false
		);

		foreach ($d_ckt as $key => $val) {
			//get data stok
			$d_stok = $this->m_jual->get_by_id_advanced('stok_sisa', 'tbl_stok', ['id_stok'=>$val['id_stok']], false, false, true);
			if ($input_status == " Aktif") {
				$stok_akhir = (int)$d_stok['stok_sisa'] + (int)$val['qty'];
			}else{
				$stok_akhir = (int)$d_stok['stok_sisa'] - (int)$val['qty'];
			}

			$this->db->update('tbl_stok', ['stok_sisa' => $stok_akhir], ['id_stok' => $val['id_stok']]);
		}

		//insert tbl log
		$data_log = array(
			'keterangan' => 'Update status tabel pembelian, id = '.$id,
			'datetime' => date('Y-m-d H:i:s'),
			'id_user' => $this->session->userdata('id_user'),
			'data_lama' => 'status_confirm_adm = '.$data_lama,
			'data_baru' => 'status_confirm_adm = '.$data_baru
		);
		$this->m_jual->tambah_datalog_konfirmasi($data_log);

		$data = array(
			'status' => TRUE,
			'pesan' => $psn_txt,
		);
		echo json_encode($data);
	}

	public function get_id_vendor($value='')
	{
		$id_user = $this->session->userdata('id_user'); 
		$where_user = ['id_user' => $id_user];
		$d_vendor = $this->m_jual->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];
		return $id_vendor;
	}

}