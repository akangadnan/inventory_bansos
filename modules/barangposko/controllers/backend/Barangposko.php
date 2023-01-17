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
		$this->is_allowed('barang_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['barangs'] = $this->model_barangposko->get($filter, $field, $this->limit_page, $offset);
		$this->data['barang_counts'] = $this->model_barangposko->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/barang/index/',
			'total_rows'   => $this->data['barang_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Barang List');
		$this->render('backend/standart/administrator/barangposko/barangposko_list', $this->data);
	}
	

	public function getdata() {
		$posko_id = $this->input->get('id');
	
        $data = $this->model_barangposko->get_posko($posko_id);
		var_dump($data);
		die();
        echo json_encode($data);
	}
}


/* End of file barang.php */
/* Location: ./application/controllers/administrator/Barang.php */