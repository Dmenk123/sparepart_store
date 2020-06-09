<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('cart');
		$this->load->model('mod_global');
		$this->load->model('homepage/mod_homepage','mod_hpg');
		$this->load->model('mod_checkout','m_ckt');
		if ($this->session->userdata('logged_in') == false ) {
			redirect('register');
		}
	}
	
	public function index()
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
		$data_user = $this->m_ckt->get_data_user($id_user);
		$q = "SELECT * from tbl_m_rekber";
		$data_rekber = $this->mod_global->getData($q);
		$data = array(
			'content' => 'checkout/view_checkout',
			'modal' => 'checkout/modal_checkout',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'checkout/jsCheckout',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
			'data_rekber' => $data_rekber,
		);

        $this->load->view('temp',$data);
	}

	public function summary_jasa()
	{
		$id_temp_jasa = $this->uri->segment(3);
		if (!$id_temp_jasa) {
			redirect('home','refresh');
			return;
		}

		$id_user = $this->session->userdata('id_user');
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();

		$data_user = $this->m_ckt->get_data_user($id_user);
		$data_cart = $this->db->query("
			SELECT 
				tbl_temp_jasa.*,
				tbl_vendor.nama_vendor,
				tbl_m_rekber.nama,
				tbl_m_rekber.no_rek
			FROM 
				tbl_temp_jasa 
			LEFT JOIN tbl_vendor on tbl_temp_jasa.id_vendor = tbl_vendor.id_vendor
			LEFT JOIN tbl_m_rekber on tbl_temp_jasa.id_rekber = tbl_m_rekber.id
			WHERE tbl_temp_jasa.id = '".$id_temp_jasa."'")->row_array();
		
		$data_input = array(
			'no_ref' => $this->generateRandomString(),
			'iduser_krm' => $id_user
		);


		$data = array(
			'beratTotal' => 0,
			'data_input' => $data_input,
			'content' => 'checkout/view_summary_jasa',
			'modal' => 'checkout/modal_checkout',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'checkout/jsCheckout',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
			'data_cart' => $data_cart
		);

        $this->load->view('temp',$data);
	}

	public function add_alamat_kirim()
	{
		$timestamp = date('Y-m-d H:i:s');
		$type = "kirim";
		$input = array(
				'id_user' => $this->input->post('checkout1Id'),
				'fname' => trim(strtoupper($this->input->post('checkout1Fname'))),
				'lname' => trim(strtoupper($this->input->post('checkout1Lname'))),
				'id_provinsi' => $this->input->post('checkout1Provinsi'),
				'id_kota' => $this->input->post('checkout1Kota'),
				'id_kecamatan' => $this->input->post('checkout1Kecamatan'),
				'id_kelurahan' => $this->input->post('checkout1Kelurahan'),
				'alamat' => trim(strtoupper($this->input->post('checkout1Alamat'))),
				'kdpos' => trim(strtoupper($this->input->post('checkout1Kdpos'))),
				'telp' => trim(strtoupper($this->input->post('checkout1Telp'))),
				'type' => $type,
				'timestamp' => $timestamp 
			);

		$insert = $this->m_ckt->insert_data_chekout1($input);

		echo json_encode(array(
			"status" => TRUE,
			"id" => $insert,
			"type" => $type,
			"pesan_kirim" => 'Ubah alamat pengiriman berhasil'
		));
	}

	public function add_alamat_tagih()
	{
		$timestamp = date('Y-m-d H:i:s');
		$type = "tagih";
		$input = array(
				'id_user' => $this->input->post('checkout1Id'),
				'fname' => trim(strtoupper($this->input->post('checkout1Fname'))),
				'lname' => trim(strtoupper($this->input->post('checkout1Lname'))),
				'id_provinsi' => $this->input->post('checkout1Provinsi'),
				'id_kota' => $this->input->post('checkout1Kota'),
				'id_kecamatan' => $this->input->post('checkout1Kecamatan'),
				'id_kelurahan' => $this->input->post('checkout1Kelurahan'),
				'alamat' => trim(strtoupper($this->input->post('checkout1Alamat'))),
				'kdpos' => trim(strtoupper($this->input->post('checkout1Kdpos'))),
				'telp' => trim(strtoupper($this->input->post('checkout1Telp'))),
				'type' => $type,
				'timestamp' => $timestamp 
			);

		$insert = $this->m_ckt->insert_data_chekout1($input);

		echo json_encode(array(
			"status" => TRUE,
			"id" => $insert,
			"type" => $type,
			"pesan_tagih" => 'Ubah alamat penagihan berhasil'
		));
	}

	public function ekspedisi_ongkir($origin="", $destination="", $weight="", $courier="")
	{
		$this->load->library('rajaongkir');
		$cities = $this->rajaongkir->cost($origin, $destination, $weight, $courier);
		return json_decode($cities, true);
	}
	
	public function get_alamat_user($id)
	{
		$data = $this->m_ckt->get_data_user($id);
		echo json_encode($data);
	}

	public function get_berat_total_cart()
	{
		//get berat total
		$beratTotal=0;
        foreach($this->cart->contents() as $item)
        {
           $beratTotal += $item['options']['Berat_produk'] * $item['qty'];
        }

        echo json_encode($beratTotal);
	}

	public function kota_ongkir($province_id="", $city_id="", $termProv="", $termKota="")
	{
		$where = "";
		$like = "";

		if ($termProv != "") {
			$like = "WHERE province LIKE '".'%'.$termProv.'%'."' GROUP BY province_id";
		}else{
			$like = "GROUP BY province_id";
		}

		if ($province_id != "") {
			if ($termKota != "") {
				$where = "WHERE province_id = '".$province_id."' AND ";
				$like = "city_name LIKE '".'%'.$termKota.'%'."'";
			}else{
				$where = "WHERE province_id = '".$province_id."'";
				$like = "";
			}
		}

		$cities = $this->db->query("SELECT * from tbl_m_kota_ongkir $where $like")->result_array();
		return $cities;
		// $this->load->library('rajaongkir');
		// $cities = $this->rajaongkir->city($province_id, $city_id);
		// return json_decode($cities, true);
	}

	public function generateRandomString($length = 8) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// public function prov_ongkir()
	// {
	// 	$this->load->library('rajaongkir');
	// 	$provinces = $this->rajaongkir->province();
	// 	return json_decode($provinces, true);
	// }
	
	public function suggest_provinsi()
	{
		$provinsi = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->m_ckt->lookup_data_provinsi($key);
		}else{
			$query = $this->m_ckt->lookup_data_provinsi();
		}
		
		foreach ($query as $row) {
			$provinsi[] = array(
						'id' => $row->id_provinsi,
						'text' => $row->nama_provinsi,
					);
		}
		echo json_encode($provinsi);
	}

	public function suggest_kotakabupaten()
	{
		// get data from ajax object (uri)
		$id_provinsi = $this->uri->segment(3);
		$kotkab = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->m_ckt->lookup_data_kotakabupaten($key, $id_provinsi);
		}else{
			$key = "";
			$query = $this->m_ckt->lookup_data_kotakabupaten($key, $id_provinsi);
		}
		
		foreach ($query as $row) {
			$kotkab[] = array(
						'id' => $row->id_kota,
						'text' => $row->nama_kota,
					);
		}
		echo json_encode($kotkab);
	}

	public function suggest_kecamatan()
	{
		// get data from ajax object (uri)
		$id_kota = $this->uri->segment(3);
		$kecamatan = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->m_ckt->lookup_data_kecamatan($key, $id_kota);
		}else{
			$key = "";
			$query = $this->m_ckt->lookup_data_kecamatan($key, $id_kota);
		}
		
		foreach ($query as $row) {
			$kecamatan[] = array(
						'id' => $row->id_kecamatan,
						'text' => $row->nama_kecamatan,
					);
		}
		echo json_encode($kecamatan);
	}

	public function suggest_kelurahan()
	{
		// get data from ajax object (uri)
		$id_kecamatan = $this->uri->segment(3);
		$kelurahan = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->m_ckt->lookup_data_kelurahan($key, $id_kecamatan);
		}else{
			$key = "";
			$query = $this->m_ckt->lookup_data_kelurahan($key, $id_kecamatan);
		}
		
		foreach ($query as $row) {
			$kelurahan[] = array(
						'id' => $row->id_kelurahan,
						'text' => $row->nama_kelurahan,
					);
		}
		echo json_encode($kelurahan);
	}

	// ========================= select2 checkout ====================

	public function suggest_provinsi_tujuan()
	{
		$provinsi = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->kota_ongkir("","",$key);
		}else{
			$query = $this->kota_ongkir();
		}

		/*print_r($query);*/
		/*if (isset($query['rajaongkir']['results'][0])) {
			for ($i=0; $i < count($query['rajaongkir']['results']); $i++) {
				$provinsi[] = array(
					'id' => $query['rajaongkir']['results'][$i]['province_id'],
					'text' => $query['rajaongkir']['results'][$i]['province'],
				);
			}
			echo json_encode($provinsi);
		}*/

		foreach ($query as $row) {
			$provinsi[] = array(
				'id' => $row['province_id'],
				'text' => $row['province'],
			);
		}
		echo json_encode($provinsi);

	}

	public function suggest_kota_tujuan()
	{
		$id = $this->input->get("idProv");
		$kota = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->kota_ongkir($id,"","",$key);
		}else{
			$query = $this->kota_ongkir($id);
		}

		/*print_r($query);*/
		/*if (isset($query['rajaongkir']['results'][0])) {
			for ($i=0; $i < count($query['rajaongkir']['results']); $i++) {
				$kota[] = array(
					'id' => $query['rajaongkir']['results'][$i]['city_id'],
					'text' => $query['rajaongkir']['results'][$i]['city_name'],
				);
			}
			echo json_encode($kota);
		}*/

		foreach ($query as $row) {
			$provinsi[] = array(
				'id' => $row['city_id'],
				'text' => $row['type'].' '.$row['city_name'],
			);
		}
		echo json_encode($provinsi);
	}

	public function suggest_paket_ongkir()
	{
		$origin = intval($this->input->get("origin"));
		$destination = intval($this->input->get("id"));
		$weight = intval($this->input->get("berat"));
		$courier = $this->input->get("kurir");
		$paket = [];
		if(!empty($this->input->get("term"))){
			$key = $_GET['term'];
			$query = $this->ekspedisi_ongkir($origin, $destination, $weight, $courier, $key);
		}else{
			$query = $this->ekspedisi_ongkir($origin, $destination, $weight, $courier);
		}

		/*print_r($query);*/
		if (isset($query['rajaongkir']['results'][0]['costs'][0])) {
			for ($i=0; $i < count($query['rajaongkir']['results'][0]['costs']); $i++) {
				$paket[] = array(
					'id' => $query['rajaongkir']['results'][0]['costs'][$i]['service'],
					'text' => array(
							'service' => $query['rajaongkir']['results'][0]['costs'][$i]['description'], 
							'etd' => $query['rajaongkir']['results'][0]['costs'][$i]['cost'][0]['etd'],
							'value' => $query['rajaongkir']['results'][0]['costs'][$i]['cost'][0]['value']
						), 
				);
			}
			echo json_encode($paket);
		}
	}

	//=======end select2 checkout==============================================

	//====================================== summary ===============================
	
	public function summary()
	{
		if ($this->input->post('FProvKurir') == '') {
			redirect('checkout','refresh');
			return;
		}

		$id_user = $this->session->userdata('id_user');
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$data_user = $this->m_ckt->get_data_user($id_user);

		$data_input = array(
			'no_ref' => $this->generateRandomString(),
			'iduser_krm' => $id_user,
			'fname_krm' => $this->input->post('FFnameKrm'),
			'lname_krm' => $this->input->post('FLnameKrm'),
			'alamat_krm' => $this->input->post('FAlamatKrm'),
			'kel_krm' => $this->input->post('FKelKrm'),
			'nm_kel_krm' => $this->input->post('FTxtKelKrm'),
			'kec_krm' => $this->input->post('FKecKrm'),
			'nm_kec_krm' => $this->input->post('FTxtKecKrm'),
			'kota_krm' => $this->input->post('FKotaKrm'),
			'nm_kota_krm' => $this->input->post('FTxtKotaKrm'),
			'prov_krm' => $this->input->post('FProvKrm'),
			'nm_prov_krm' => $this->input->post('FTxtProvKrm'),
			'kdpos_krm' => $this->input->post('FKdposKrm'),
			'telp_krm' => $this->input->post('FTelpKrm'),
			'method_krm' => $this->input->post('FMethodKrm'),
			'method_txt' => $this->input->post('FMethodKrmTxt'),
			'prov_kurir' => $this->input->post('FProvKurir'),
			'kota_kurir' => $this->input->post('FKotaKurir'),
			'berat_kurir' => $this->input->post('FBeratKurir'),
			'nama_kurir' => $this->input->post('FNamaKurir'),
			'paket_kurir' => $this->input->post('FPaketKurir'),
			'etd_kurir' => $this->input->post('FEtdKurir'),
			'harga_kurir' => $this->input->post('FHargaKurir'),
		);


		$data = array(
			'beratTotal' => 0,
			'data_input' => $data_input,
			'content' => 'checkout/view_summary',
			'modal' => 'checkout/modal_checkout',
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'menu_navbar' => $menu_navbar,
			'js' => 'checkout/jsCheckout',
			'menu_select_search' => $menu_select_search,
			'data_user' => $data_user,
		);

        $this->load->view('temp',$data);
	}

    public function proses_summary($is_jasa=false)
    {
    	$kode_ref = $this->input->post('frmRef');
    	$total_bayar = $this->input->post('frmBeaTotal');
    	$waktu_tanggal = date('Y-m-d H:i:s');
    	$tanggal = date('Y-m-d');

    	if (!$is_jasa) {
    		$this->db->trans_begin();
	    	$method = $this->input->post('frmMethod');
	    	if ($method == "cod") {
	    		$metode = "COD";
	    	}else{
	    		$metode = "TFR";
	    	}
	    	$kode = $this->m_ckt->getKodeCheckout($metode);

	    	//data header
	    	$data_header = array(
	    		'id_checkout' => $kode,
	    		'id_user' => $this->session->userdata('id_user'),
	    		'id_rekber' => $this->input->post('frmMethod'),
	    		'tgl_checkout' => $tanggal,
	    		'status' => "aktif",
	    		'harga_total_produk' => $this->input->post('frmBeaProduk'),
	    		'jasa_ekspedisi' => $this->input->post('frmKurir'),
	    		'pilihan_paket' => $this->input->post('frmPaket'),
	    		'estimasi_datang' => $this->input->post('frmEtd'),
	    		'ongkos_kirim' => $this->input->post('frmOngkir'),
	    		'alamat_kirim' => $this->input->post('frmAlamatKrm'),
	    		'fname_kirim' => strtoupper($this->input->post('frmFnameKrm')),
	    		'lname_kirim' => strtoupper($this->input->post('frmLnameKrm')),
	    		'ongkos_total' => $total_bayar,
	    		'method_checkout' => $metode,
	    		'kode_ref' => $kode_ref,
	    		'timestamp' => $waktu_tanggal,
	    	);

	    	//data detail header
	    	$hitung_item = count($this->input->post('frmIdproduk'));
	    	$total_berat_all = (int)$this->input->post('frmBeratKurir');
	    	$ongkos_kirim = (int)$this->input->post('frmOngkir');
			$data_item_detail = array();
			for ($i=0; $i < $hitung_item; $i++) 
			{
				$berat_total = (int)$this->input->post('frmBeratTotal')[$i];
				//cari presentase (berat/berat_total*100)
				$persentase = $berat_total / $total_berat_all * 100;
				$harga_ongkir = $persentase / 100 * $ongkos_kirim;
				$harga_total = (int)$this->input->post('frmHargaSatuan')[$i] * (int)$this->input->post('frmIdqty')[$i]; 
				$data_item_detail[$i] = array(
					'id_checkout' => $kode,
					'status' => "aktif",
					'id_produk' => $this->input->post('frmIdproduk')[$i],
					'id_vendor' => $this->input->post('frmIdvendor')[$i],
					'id_satuan' => $this->input->post('frmIdsatuan')[$i],
					'id_stok' => $this->input->post('frmIdstok')[$i],
					'harga_satuan' => $this->input->post('frmHargaSatuan')[$i],
					'berat_total' => $this->input->post('frmBeratTotal')[$i],
					'harga_ongkir' => (float)str_replace(",", "", number_format($harga_ongkir,2)),
					'harga_total' => $harga_total,
					'qty' => $this->input->post('frmIdqty')[$i],
				);
			}
			//insert to db
			$insert = $this->m_ckt->simpan_data($data_header, $data_item_detail);

			//remove cart after insert
			$hitung_item_cart = count($this->input->post('rowId'));
			$data_cart = array();
			for ($i=0; $i < $hitung_item_cart; $i++)
			{
				$data_cart[$i] = array(
		            'rowid' => $this->input->post('rowId')[$i], 
		            'qty' => 0, 
		        );
			}
			// print_r($data_cart);
			$this->cart->update($data_cart);
			if ($this->db->trans_status() === FALSE) {
	        	$this->db->trans_rollback();
	        	echo json_encode(array(
	        	"status" => FALSE,
				"pesan" => "Terjadi Kesalahan"
				));
			}else{
	        	$this->db->trans_commit();
	        	echo json_encode(array(
				"status" => TRUE,
				"pesan" => "Mohon Konfirmasi pembayaran sebelum 3 hari dari sekarang, untuk info lebih lanjut silahkan pilih menu nama anda pada menu navigasi. Terima kasih telah berbelanja."
				));
			}
    	}else{
    		$this->db->trans_begin();
    		$method = $this->input->post('frmMethod');
    		if ($method == "cod") {
	    		$metode = "COD";
	    	}else{
	    		$metode = "TFR";
	    	}
	    	$kode = $this->m_ckt->getKodeCheckout($metode);
	    	
	    	//data header
	    	$data_header = array(
	    		'id_checkout' => $kode,
	    		'id_user' => $this->session->userdata('id_user'),
	    		'id_rekber' => $this->input->post('frmMethod'),
	    		'tgl_checkout' => $tanggal,
	    		'status' => "aktif",
	    		'harga_total_produk' => $this->input->post('frmBeaProduk'),
	    		'jasa_ekspedisi' => null,
	    		'pilihan_paket' => null,
	    		'estimasi_datang' => null,
	    		'ongkos_kirim' => null,
	    		'alamat_kirim' => $this->input->post('frmAlamatKrm'),
	    		'fname_kirim' => strtoupper($this->input->post('frmFnameKrm')),
	    		'lname_kirim' => strtoupper($this->input->post('frmLnameKrm')),
	    		'ongkos_total' => $total_bayar,
	    		'method_checkout' => "TFR",
	    		'kode_ref' => $kode_ref,
	    		'timestamp' => $waktu_tanggal,
	    		'is_jasa' => 1
	    	);

	    	//data detail header
	    	$hitung_item = count($this->input->post('frmIdproduk'));
			$data_item_detail = array();
			for ($i=0; $i < $hitung_item; $i++) 
			{
				$berat_total = (int)$this->input->post('frmBeratTotal')[$i];
				$harga_total = (int)$this->input->post('frmHargaSatuan')[$i] * (int)$this->input->post('frmIdqty')[$i]; 
				$data_item_detail[$i] = array(
					'id_checkout' => $kode,
					'status' => "aktif",
					'id_produk' => $this->input->post('frmIdproduk')[$i],
					'id_vendor' => $this->input->post('frmIdvendor')[$i],
					'id_satuan' => $this->input->post('frmIdsatuan')[$i],
					'id_stok' => $this->input->post('frmIdstok')[$i],
					'harga_satuan' => $this->input->post('frmHargaSatuan')[$i],
					'berat_total' => $this->input->post('frmBeratTotal')[$i],
					'harga_ongkir' => 0,
					'harga_total' => $harga_total,
					'qty' => $this->input->post('frmIdqty')[$i],
					'id_temp_jasa' => $this->input->post('frmIdTempJasa')
				);
			}
			//insert to db
			$insert = $this->m_ckt->simpan_data($data_header, $data_item_detail);

			//update tbl temp jasa
			$id_temp_jasa = $this->input->post('frmIdTempJasa');
			$this->db->update('tbl_temp_jasa', ['is_checkout' => 1], ['id' => $id_temp_jasa]);

			if ($this->db->trans_status() === FALSE) {
	        	$this->db->trans_rollback();
	        	echo json_encode(array(
	        	"status" => FALSE,
				"pesan" => "Terjadi Kesalahan"
				));
			}else{
	        	$this->db->trans_commit();
	        	echo json_encode(array(
				"status" => TRUE,
				"pesan" => "Mohon Konfirmasi pembayaran sebelum 3 hari dari sekarang, untuk info lebih lanjut silahkan pilih menu nama anda pada menu navigasi. Terima kasih telah berbelanja."
				));
			}
    	}	
    }

    //====================================== end summary ===============================
 

	public function tampil_biaya_pengiriman($origin, $destination, $weight, $courier){
		echo "<pre>";
			print_r($this->ekspedisi_ongkir($origin, $destination, $weight, $courier));
		echo "</pre>";
	}

	public function tampil_kota($province_id="", $city_id=""){
		echo "<pre>";
			print_r($this->kota_ongkir($province_id, $city_id));
		echo "</pre>";


	}

	// public function insert_dump_rajaongkir($province_id="", $city_id="")
	// {
	// 	$data = $this->kota_ongkir($province_id, $city_id);
	// 	//var_dump($data['rajaongkir']['results']);

	// 	foreach ($data['rajaongkir']['results'] as $key => $value) {
	// 		$input = [
	// 			'city_id' 		=> $value['city_id'],
	// 			'province_id' 	=> $value['province_id'],
	// 			'province'		=> $value['province'],
	// 			'type'			=> $value['type'],
	// 			'city_name'		=> $value['city_name'],
	// 			'postal_code'	=> $value['postal_code']
	// 		];
	// 		$this->db->insert('tbl_m_kota_ongkir', $input);
	// 		echo $value->city_name." Telah di insert <br>";
	// 	}

	// 	echo "SUKSES";
	// }

	

}
