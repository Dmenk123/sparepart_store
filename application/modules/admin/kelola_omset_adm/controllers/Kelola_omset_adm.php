<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelola_omset_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_kelola_omset_adm','m_omset');
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
			'modal' => null,
			'css'	=> 'cssKelolaOmsetAdm',
			'js'	=> 'jsKelolaOmsetAdm',
			'view'	=> 'view_list_kelola_omset_adm'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_omset()
	{
		$list = $this->m_omset->get_datatable();
		//echo $this->db->last_query();exit;
		// echo "<PRE>";
		// print_r($list);
		// echo "</PRE>";
		// exit;
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listOmset) {
			$link_detail = site_url('kelola_omset_adm/omset_detail/').$listOmset->id;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listOmset->id_pembelian;
			$row[] = $listOmset->rekening;
			$row[] = $listOmset->penyedia;
			$row[] = "<span class='pull-left'>Rp. </span>
					 <span class='pull-right'>".number_format($listOmset->biaya_adm,2,",",".")."</span>";
			$row[] = "<span class='pull-left'>Rp. </span>
					  <span class='pull-right'>".number_format($listOmset->nominal,2,",",".")."</span>";
			
			if ($listOmset->status_penarikan == '1') {
				$row[] = "<span style='color: red;'>Belum</span>";
			}elseif ($listOmset->status_penarikan == '2'){
				$row[] = "Sudah";
			}else{
				$row[] = "<span style='color: red;'>Belum Dikonfirmasi</span>";
			}
			
			//add html for action button
			if ($listOmset->status_penarikan != '0') {
				$row[] = '<a class="btn btn-sm btn-default" href="'.$link_detail.'" title="Omset Detail" id="btn_detail"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>';
			}else{
				$row[] = null;
			}

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_omset->count_all(),
						"recordsFiltered" => $this->m_omset->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function omset_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->m_dasbor->get_data_user($id_user);

		$jumlah_notif = $this->m_dasbor->email_notif_count($id_user);  //menghitung jumlah email masuk
		$notif = $this->m_dasbor->get_email_notif($id_user); //menampilkan isi email
		if ($this->session->userdata('id_level_user') != '1') {
			$id_vendor = $this->get_id_vendor();
		}

		$id_omset = $this->uri->segment(3);
		$query_header = $this->m_omset->get_data_omset_header($id_omset);
		$query_data = $this->m_omset->get_data_omset_detail($id_omset);

		$data = array(
			'data_user' => $data_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			'hasil_header' => $query_header,
			'hasil_data' => $query_data,
		);

		$content = [
			'modal' => null,
			'css'	=> 'cssKelolaOmsetAdm',
			'js'	=> 'jsKelolaOmsetAdm',
			'view'	=> 'view_detail_kelola_omset_adm'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function cetak_nota()
	{
		$this->load->library('Pdf_gen');

		$id_omset = $this->uri->segment(3);
		$query_header = $this->m_omset->get_data_omset_header($id_omset);
		$query_data = $this->m_omset->get_data_omset_detail($id_omset);

		$data = array(
			'title' => 'Nota Pengelolaan Omset',
			'hasil_header' => $query_header,
			'hasil_data' => $query_data, 
		);

	    $html = $this->load->view('view_nota_kelola_omset', $data, true);
	    //$this->template_view->load_view($content, $data);
	    $filename = 'nota_omset_'.$id_omset.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function konfirmasi_omset_adm()
	{
		$this->db->trans_begin();
		$id_pembelian = $this->input->post('id_pembelian');
		$id_checkout = $this->input->post('id_checkout');
		$nominal = $this->input->post('nominal');
		$bea_layanan = $this->input->post('bea_layanan');
		$bea_vendor = $this->input->post('bea_vendor');
		$laba_adm_bruto = $this->input->post('laba_adm_bruto');
		$laba_adm_nett = $this->input->post('laba_adm_nett');

		//insert tbl_laba_adm
		$input = [
			'id' => $this->m_omset->get_max_id('id', 'tbl_laba_adm'),
			'id_pembelian' => $id_pembelian,
			'id_checkout' => $id_checkout,
			'nominal' => $nominal,
			'bea_layanan' => $bea_layanan,
			'bea_vendor' => $bea_vendor,
			'omset_bruto' => $laba_adm_bruto,
			'omset_nett' => $laba_adm_nett,
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('tbl_laba_adm', $input);
		
		//inser tbl_laba_vendor
		for ($i=0; $i < count($this->input->post('ongkir')); $i++) { 
			$input2['id'] = $this->m_omset->get_max_id('id', 'tbl_laba_vendor');
			$input2['id_vendor'] = $this->input->post('id_vendor')[$i];
			$input2['id_pembelian'] = $id_pembelian;
			$input2['id_checkout'] = $id_checkout;
			$input2['ongkir'] = $this->input->post('ongkir')[$i];
			$input2['omset_bruto'] = $this->input->post('omset_bruto')[$i];
			$input2['potongan'] = $this->input->post('potongan')[$i];
			$input2['omset_nett'] = $this->input->post('omset_nett')[$i];
			$input2['created_at'] = date('Y-m-d H:i:s');
			$this->db->insert('tbl_laba_vendor', $input2);
		}

		//update status trans rekber -> 2
		$this->db->update("tbl_trans_rekber", ['status_penarikan' => 2], ['id' => $this->input->post('id_t_rekber')]);

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
				"pesan" => 'Omset telah berhasil ditarik dan dikelola.'
			));
		}
	}

	public function edit_status_penjualan($id)
	{
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
			'status_confirm_adm' => $status 
		);

		$this->m_omset->update_status_penjualan(array('id_pembelian' => $id), $input);

		//insert tbl log
		$data_log = array(
			'keterangan' => 'Update status tabel pembelian, id = '.$id,
			'datetime' => date('Y-m-d H:i:s'),
			'id_user' => $this->session->userdata('id_user'),
			'data_lama' => 'status_confirm_adm = '.$data_lama,
			'data_baru' => 'status_confirm_adm = '.$data_baru
		);
		$this->m_omset->tambah_datalog_konfirmasi($data_log);

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
		$d_vendor = $this->m_omset->get_by_id_advanced(false, 'tbl_vendor', $where_user, false, false, true);
		$id_vendor = $d_vendor['id_vendor'];
		return $id_vendor;
	}

}