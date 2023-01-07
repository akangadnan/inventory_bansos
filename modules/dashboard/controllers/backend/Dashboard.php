<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Dashboard Controller
*| --------------------------------------------------------------------------
*| For see your board
*|
*/
class Dashboard extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('barang/model_barang');
	}

	public function index() {
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/', 'refresh');
		}

		$data = [
			'barang' 	=> db_get_all_data('barang'),
			'masuk' 	=> db_get_all_data('barangmasuk'),
			'keluar' 	=> db_get_all_data('barangkeluar'),
			'retur' 	=> db_get_all_data('retur'),
		];
		$this->render('backend/standart/administrator/dashboard', $data);
	}

	public function chart() {
		if (!$this->aauth->is_allowed('dashboard')) {
			redirect('/','refresh');
		}

		$data = [];
		$this->render('backend/standart/chart', $data);
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */