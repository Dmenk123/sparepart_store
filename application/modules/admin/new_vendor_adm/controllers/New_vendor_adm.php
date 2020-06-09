<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class New_vendor_adm extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('email');
		$this->load->model('dashboard_adm/Mod_dashboard_adm','m_dasbor');
		$this->load->model('Mod_new_vendor_adm','m_vendor');
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
			'css'	=> false,
			'modal' => 'modalNewVendorAdm',
			'css'	=> false,
			'js'	=> 'newVendorAdmJs',
			'view'	=> 'view_list_new_vendor'
		];

		//$this->load->view('temp_adm',$data);
		$this->template_view->load_view($content, $data);
	}

	public function list_new_vendor()
	{
		$id_level_user = $this->session->userdata('id_level_user');
		$list = $this->m_vendor->get_datatable_vendor($id_level_user);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $list) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $list->nama_user;
			$row[] = $list->email;
			$row[] = $list->nama_vendor;
			$row[] = $list->jenis_usaha_vendor;
			if ($list->status == '1') {
				$row[] = "aktif";	
			}else{
				$row[] = "nonaktif";
			}
			$row[] = date('d-m-Y H:i:s', strtotime($list->timestamp));
			//add html for action button
			$row[] =
				'<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="confirm_vendor('."'".$list->id_user."'".')"><i class="fa fa-check"></i> Confirm</a>
				<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick="detail_vendor('."'".$list->id_user."'".')"><i class="fa fa-search"></i> Detail</a>
			';		
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_vendor->count_all(),
						"recordsFiltered" => $this->m_vendor->count_filtered(),
						"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function get_detail_vendor($idUser)
	{
		$vendor = $this->m_vendor->get_data_vendor_detail($idUser);
		echo json_encode($vendor);
	}

	public function konfirmasi_vendor()
	{
		$this->load->library('Enkripsi');
		$id_user = $this->input->post('idUser');

		$this->db->trans_begin();
		$q = $this->m_vendor->get_email_vendor($id_user);
		$email = $q->email;
		$rand = $this->generateRandomString(10);
		$password = $this->enkripsi->encrypt($rand);
		
		//update status pelapak
		$upd_data = array(
			'password' => $password,
			'status' => 1
		);
		$this->m_vendor->update_status_user(array('id_user' => $id_user), $upd_data);

		//call email config
		$this->konfigurasi_email($email, $rand);
		//send email
		if (!$this->email->send()) {
	    	$this->db->trans_rollback();
	        echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Gagal dalam mengirim email, Mohon coba lagi.'
			));
			return;
	    }

	    if ($this->db->trans_status() === FALSE){
	        $this->db->trans_rollback();
	        echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Gagal konfirmasi pelapak',
			));
		}else{
	        $this->db->trans_commit();
	        echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Pesan berhasil dikirim ke email pelapak'
			));
		}
	}

	public function konfigurasi_email($email, $rand)
	{
		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 	465,
			'smtp_user' => 'mail.onpets@gmail.com',
			'smtp_pass' => 'onpets2019',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		//Email content
		$htmlContent = '<h2>OnPets E-Marketplace</h2>';
		$htmlContent .= '<p>Terima Kasih kepada : '.trim($email).' telah memberi kepercayaan kepada kami sebagai partner usaha anda.</p>';
		$htmlContent .= '<p>Berikut email dan password yang digunakan untuk login ke web kami.</p>';
		$htmlContent .= '<p>Username : '.trim($email).'</p>';
		$htmlContent .= '<p>Password : '.trim($rand).'</p>';
		$htmlContent .= '<p>Sekali lagi terimakasih dan salam hangat dari kami <strong>OnPets E-Marketplace</strong></p>';

		$this->email->to(trim($email));
		$this->email->from("mail.onpets@gmail.com", 'OnPets E-Marketplace'); 
		$this->email->subject(trim('Konfirmasi pendaftaran pelapak')); 
		$this->email->message($htmlContent);
	}

	function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}

	////////////////////////////////////////////////////////////////////////////////////////////////	
}
