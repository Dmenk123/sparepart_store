<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('mod_produk');
		$this->load->model('checkout/mod_checkout','mod_ckt');
		$this->load->model('homepage/mod_homepage','mod_hpg');
		$this->load->library('pagination');
	}

	public function sub_kategori()
	{ 
		$sub = $this->uri->segment(3);

		$per_page = $this->input->get('per_page');
		if (!isset($per_page)) {
			$per_page = 10; //default per page
		}

		$sort_by = $this->input->get('sort_by');
		if (!isset($sort_by)) {
			$sort_by = "created"; //default sort
		}

		//set array for pagination library
		$config = array();
		$total_row = $this->mod_produk->record_count($sub);
		$config["base_url"] = base_url() . "produk/sub_kategori/".$sub;
        $config["total_rows"] = $total_row;
        $config["per_page"] = $per_page;
        //beri tambahan path ketika next page
        $config['prefix'] = '/page/';
        //tampilkan url string pada next page
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['cur_tag_open'] = '&nbsp <a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $this->pagination->initialize($config);
        
        $page = $this->uri->segment(5);
        $str_links = $this->pagination->create_links();
		$id_show = $per_page;
		$id_sort = $sort_by;
		
		$get_data_page = $this->mod_produk->get_data_page($sub);
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		//hitung kategori yang terdapat pada submenu (groupby)
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		//print_r($submenu);
		$menu_select_search = $this->mod_hpg->get_menu_search();
		
		$data = array(
			'content' => 'produk/view_list_produk',
			'content_sidebar' => 'temp_content_sidebar',
			'menu_navbar' => $menu_navbar,
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'js' => 'produk/jsProduk',
			'menu_select_search' => $menu_select_search,
			'get_data_page' => $get_data_page,
			'results' => $this->mod_produk->get_list_produk($config["per_page"], $page, $sort_by, $sub),
			'links' => explode('&nbsp', $str_links),
			'total_baris' => $total_row,
			'id_show' => $id_show,
			'id_sort' => $id_sort
		);

		if ($this->session->userdata('id_user') == !null) {
			$id_user = $this->session->userdata('id_user');
			$checkout_notif = $this->mod_ckt->notif_count($id_user);
			$data['notif_count'] = $checkout_notif;
		}

		$this->load->view('temp', $data);
	}


	public function cari_produk()
	{
		$sub_kategori = $this->input->get('select');
		$key = $this->input->get('key');

		$per_page = $this->input->get('per_page');
		if (!isset($per_page)) {
			$per_page = 10; //default per page
		}
		$sort_by = $this->input->get('sort_by');
		if (!isset($sort_by)) {
			$sort_by = "created"; //default sort
		}

		$select_sub = $this->input->get('select');
		$search_key = $this->input->get('key');
		
		//set array for pagination library
		$config = array();
		$total_row = $this->mod_produk->record_count($sub_kategori);
		$config["base_url"] = base_url() . "produk/cari_produk/".$sub_kategori;
        $config["total_rows"] = $total_row;
        $config["per_page"] = $per_page;
        //beri tambahan path ketika next page
        $config['prefix'] = '/page/';
        //tampilkan url string pada next page
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $total_row;
        $config['cur_tag_open'] = '&nbsp <a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $this->pagination->initialize($config);

        $page = $this->uri->segment(5);
        $str_links = $this->pagination->create_links();
		$id_show = $per_page;
		$id_sort = $sort_by;
		
		$get_data_page = $this->mod_produk->get_data_page($sub_kategori);
		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		//hitung kategori yang terdapat pada submenu (groupby)
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		$menu_select_search = $this->mod_hpg->get_menu_search();

        $data = array(
			'content' => 'produk/view_list_produk',
			'content_sidebar' => 'temp_content_sidebar',
			'menu_navbar' => $menu_navbar,
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'js' => 'produk/jsProduk',
			'menu_select_search' => $menu_select_search,
			'get_data_page' => $get_data_page,
			'results' => $this->mod_produk->get_list_produk_search($config["per_page"], $page, $sort_by, $select_sub, $search_key),
			'links' => explode('&nbsp', $str_links),
			'total_baris' => $total_row,
			'id_show' => $id_show,
			'id_sort' => $id_sort
		);

		$this->load->view('temp', $data);
	}

	public function get_kategori()
	{
		$id_sub = $this->input->post('segment');
		$id_kategori = $this->mod_produk->get_id_kategori($id_sub);
		$count_kategori = $this->mod_hpg->count_kategori();

		$data = array(
			'kategori' => $id_kategori,
			'count_kategori' => $count_kategori 
		);

		echo json_encode($data);
	}

	public function get_size_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$size = $this->mod_produk->get_data_size_produk($id_produk);

		$data = array(
			'size' => $size, 
		);

		echo json_encode($data);
	}

	public function get_stok_produk()
	{
		$id_produk = $this->input->post('id_produk');
		$stok = $this->mod_produk->get_data_stok_produk($id_produk);
		foreach ($stok as $value) {
			$stok_length = $value->stok_sisa;
		}
		$data_stok = array();
		$angka = 1;
		for ($i=0; $i<$stok_length; $i++) { 
			$data_stok[$i] = $angka;
			$angka++;
		}

		$data = array(
			'stok' => $data_stok, 
		);

		echo json_encode($data);
	}

	public function produk_detail()
	{
		$id_produk = $this->uri->segment(4);
		$id_sub_kategori = $this->uri->segment(3);
		$cek = $this->db->query("SELECT id_produk from tbl_produk where id_produk = '".$id_produk."'")->row();
		if (!$cek) {
			redirect('home/error_404','refresh');
			return;
		}

		//jika kategori jasa maka ada case khusus
		$cek_kat = $this->db->query("SELECT id_kategori FROM tbl_sub_kategori_produk WHERE id_sub_kategori = '".$id_sub_kategori."'")->row();
		if ($cek_kat->id_kategori == '3') {
			redirect('produk/produk_detail_jasa/'.$id_sub_kategori.'/'.$id_produk,'refresh');
			return;	
		}

		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		//hitung kategori yang terdapat pada submenu (groupby)
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		//print_r($submenu);
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$img_detail_thumb = $this->mod_produk->get_img_detail_thumb($id_produk);
		$img_detail_big = $this->mod_produk->get_img_detail_big($id_produk);
		$detail_produk = $this->mod_produk->get_detail_produk($id_produk);
		//var_dump($detail_produk);exit;
		$produk_terlaris = $this->mod_produk->get_produk_terlaris();
		$pelapak = $this->db->query("
		SELECT
			vp.id_vendor,
			v.nama_vendor,
			v.jenis_usaha_vendor,
			v.img_vendor 
		FROM
			tbl_vendor_produk vp
			JOIN tbl_produk p ON vp.id_produk = p.id_produk
			JOIN tbl_vendor v ON vp.id_vendor = v.id_vendor 
		WHERE
			p.id_produk = '".$id_produk."'
		")->row_array();

		$data = array(
			'content' => 'produk/view_detail_produk',
			'content_sidebar' => 'temp_content_sidebar',
			'menu_navbar' => $menu_navbar,
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'js' => 'produk/jsProduk',
			'menu_select_search' => $menu_select_search,
			'img_detail_thumb' => $img_detail_thumb,
			'img_detail_big' => $img_detail_big,
			'detail_produk' => $detail_produk,
			'produk_terlaris' => $produk_terlaris,
			'pelapak' => $pelapak
		);

		if ($this->session->userdata('id_user') == !null) {
			$id_user = $this->session->userdata('id_user');
			$checkout_notif = $this->mod_ckt->notif_count($id_user);
			$data['notif_count'] = $checkout_notif;
		}

		$this->load->view('temp', $data); 
	}

	public function produk_detail_jasa()
	{
		$id_produk = $this->uri->segment(4);
		$id_sub_kategori = $this->uri->segment(3);
		
		$cek_kat = $this->db->query("SELECT * FROM tbl_sub_kategori_produk WHERE id_sub_kategori = '".$id_sub_kategori."'")->row();


		$cek = $this->db->query("SELECT id_produk from tbl_produk where id_produk = '".$id_produk."'")->row();
		if (!$cek) {
			redirect('home/error_404','refresh');
			return;
		}

		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		//hitung kategori yang terdapat pada submenu (groupby)
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}
		//print_r($submenu);
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$img_detail_thumb = $this->mod_produk->get_img_detail_thumb($id_produk);
		$img_detail_big = $this->mod_produk->get_img_detail_big($id_produk);
		$detail_produk = $this->mod_produk->get_detail_produk($id_produk);
		//var_dump($detail_produk);exit;
		$produk_terlaris = $this->mod_produk->get_produk_terlaris();
		$pelapak = $this->db->query("
		SELECT
			vp.id_vendor,
			v.nama_vendor,
			v.jenis_usaha_vendor,
			v.img_vendor 
		FROM
			tbl_vendor_produk vp
			JOIN tbl_produk p ON vp.id_produk = p.id_produk
			JOIN tbl_vendor v ON vp.id_vendor = v.id_vendor 
		WHERE
			p.id_produk = '".$id_produk."'
		")->row_array();

		$rekber = $this->db->query("SELECT * FROM tbl_m_rekber")->result();

		$data = array(
			'content' => 'produk/view_detail_produk_jasa',
			'content_sidebar' => 'temp_content_sidebar',
			'sub_menu_kategori' => $cek_kat,
			'menu_navbar' => $menu_navbar,
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'js' => 'produk/jsProduk',
			'menu_select_search' => $menu_select_search,
			'img_detail_thumb' => $img_detail_thumb,
			'img_detail_big' => $img_detail_big,
			'detail_produk' => $detail_produk,
			'produk_terlaris' => $produk_terlaris,
			'pelapak' => $pelapak,
			'rekber' => $rekber
		);

		if ($this->session->userdata('id_user') == !null) {
			$id_user = $this->session->userdata('id_user');
			$checkout_notif = $this->mod_ckt->notif_count($id_user);
			$data['notif_count'] = $checkout_notif;
		}

		$this->load->view('temp', $data); 
	}

	public function detail_vendor($id_vendor)
	{
		$cek = $this->db->query("SELECT id_vendor from tbl_vendor where id_vendor = '".$id_vendor."'")->row();
		if (!$cek) {
			redirect('home/error_404','refresh');
			return;
		}

		$menu_navbar = $this->mod_hpg->get_menu_navbar();
		//hitung kategori yang terdapat pada submenu (groupby)
		$count_kategori = $this->mod_hpg->count_kategori();
		$submenu = array();
		for ($i=1; $i <= $count_kategori; $i++) { 
			//set array key berdasarkan loop dari angka 1
			$submenu[$i] =  $this->mod_hpg->get_submenu_navbar($i);	
		}

		//print_r($submenu);
		$menu_select_search = $this->mod_hpg->get_menu_search();
		$produk_terlaris = $this->mod_produk->get_produk_terlaris_vendor($id_vendor);
		$img_detail_big = $this->mod_produk->get_img_detail_vendor_big($id_vendor);
		$detail_pelapak = $this->db->query("
			SELECT 
			tbl_vendor.id_vendor,
			tbl_vendor.nama_vendor,
			tbl_vendor.jenis_usaha_vendor,
			CONCAT( 'PROVINSI ', nama_provinsi, ', ', nama_kota, ', KECAMATAN ', nama_kecamatan, ', KELURAHAN ', nama_kelurahan, ', ', alamat_user, ', KODEPOS ', kode_pos, ' - INDONESIA' ) AS alamat 
			from tbl_vendor
			JOIN tbl_user ON tbl_vendor.id_user = tbl_user.id_user
			JOIN tbl_provinsi ON tbl_user.id_provinsi = tbl_provinsi.id_provinsi
			JOIN tbl_kota ON tbl_user.id_kota = tbl_kota.id_kota
			JOIN tbl_kecamatan ON tbl_user.id_kecamatan = tbl_kecamatan.id_kecamatan
			JOIN tbl_kelurahan ON tbl_user.id_kelurahan = tbl_kelurahan.id_kelurahan
			where id_vendor = '".$id_vendor."'"
		)->row_array();

		//var_dump($detail_produk);exit;
		$submenu_vendor = $this->db->query("
			SELECT
				tbl_sub_kategori_produk.id_sub_kategori,
				tbl_sub_kategori_produk.nama_sub_kategori
			FROM
				tbl_produk 
				LEFT JOIN tbl_sub_kategori_produk ON tbl_produk.id_sub_kategori= tbl_sub_kategori_produk.id_sub_kategori
				LEFT JOIN tbl_vendor_produk ON tbl_produk.id_produk = tbl_vendor_produk.id_produk
				WHERE
				tbl_vendor_produk.id_vendor = '".$id_vendor."' and tbl_produk.status = '1'
			GROUP BY
				tbl_sub_kategori_produk.id_sub_kategori
			ORDER BY
				tbl_sub_kategori_produk.id_sub_kategori 
		")->result_array();

		$produk = $this->db->query("
			SELECT
				tbl_produk.id_produk,
				tbl_produk.nama_produk,
				tbl_produk.id_sub_kategori as id_sk_produk,
				tbl_sub_kategori_produk.id_sub_kategori,
				tbl_sub_kategori_produk.nama_sub_kategori,
				tbl_produk.harga,
				tbl_satuan.nama_satuan,
				concat('produk/','produk_detail/',tbl_produk.id_sub_kategori,'/',tbl_produk.id_produk) as link_produk
			FROM
				tbl_produk 
				LEFT JOIN tbl_sub_kategori_produk ON tbl_produk.id_sub_kategori= tbl_sub_kategori_produk.id_sub_kategori
				LEFT JOIN tbl_vendor_produk ON tbl_produk.id_produk = tbl_vendor_produk.id_produk
				LEFT JOIN tbl_satuan ON tbl_produk.id_satuan= tbl_satuan.id_satuan
				WHERE
				tbl_vendor_produk.id_vendor = '".$id_vendor."' and tbl_produk.status = '1'
			ORDER BY
				id_sub_kategori, nama_produk asc 
		")->result_array();

		// echo "<PRE>";
		// print_r($pelapak);
		// echo "</PRE>";
		// exit;
		
		$data = array(
			'content' => 'produk/view_detail_vendor',
			'content_sidebar' => 'temp_content_sidebar',
			'menu_navbar' => $menu_navbar,
			'count_kategori' => $count_kategori,
			'submenu' => $submenu,
			'js' => 'produk/jsProduk',
			'menu_select_search' => $menu_select_search,
			'img_detail_big' => $img_detail_big,
			'detail_vendor' => $detail_pelapak,
			'produk_terlaris' => $produk_terlaris,
			'produk' => $produk,
			'submenu_vendor' => $submenu_vendor
		);

		if ($this->session->userdata('id_user') == !null) {
			$id_user = $this->session->userdata('id_user');
			$checkout_notif = $this->mod_ckt->notif_count($id_user);
			$data['notif_count'] = $checkout_notif;
		}

		$this->load->view('temp', $data); 
	}
}
