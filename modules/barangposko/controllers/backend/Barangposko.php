<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Barang Controller
*| --------------------------------------------------------------------------
*| Barang site
*|
*/
class Barangposko extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_barangposko');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Barangs
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		// $this->is_allowed('barang_list');

		// $filter = $this->input->get('q');
		// $field 	= $this->input->get('f');

		// $this->data['barangs'] = $this->model_barangposko->get($filter, $field, $this->limit_page, $offset);
		// $this->data['barang_counts'] = $this->model_barangposko->count_all($filter, $field);

		// $config = [
		// 	'base_url'     => 'administrator/barang/index/',
		// 	'total_rows'   => $this->data['barang_counts'],
		// 	'per_page'     => $this->limit_page,
		// 	'uri_segment'  => 4,
		// ];

		// $this->data['pagination'] = $this->pagination($config);

		$this->template->title('Stok Barang Posko');
		$this->render('backend/standart/administrator/barangposko/barangposko_list', $this->data);
	}
	

	public function getdata() {
		$posko_id = $this->input->get('id');
	
        $data_barang 	= db_get_all_data('barang');
        $data_masuk 	= $this->model_barangposko->posko_barang_masuk($posko_id)->result();
        $data_keluar 	= $this->model_barangposko->posko_barang_keluar($posko_id)->result();

		$array_masuk 	= [];
		$array_keluar 	= [];

		foreach ($data_masuk as $item) {
			$array_masuk[] = [
				'barang_id' 	=> $item->id_barang,
				'barang_nama' 	=> $item->nama_barang,
				'barang_satuan' => $item->nama_satuan,
				'jumlah' 		=> $item->jumlah_masuk,
			];
		}

		foreach ($data_keluar as $item) {
			$array_keluar[] = [
				'barang_id' 	=> $item->id_barang,
				'barang_nama' 	=> $item->nama_barang,
				'barang_satuan' => $item->nama_satuan,
				'jumlah' 		=> $item->jumlah_keluar,
			];
		}

		// $results_arrays = array_keys($array_masuk);

		// echo json_encode($array_masuk);
		// echo json_encode($array_keluar);
		// echo json_encode($results_arrays);

		$this->data = [
			'data' => $data_masuk,
		];

		$this->load->view('backend/standart/administrator/barangposko/barangposko_view', $this->data);
	}
}


/* End of file barang.php */
/* Location: ./application/controllers/administrator/Barang.php */