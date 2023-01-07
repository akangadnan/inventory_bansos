<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kelurahan Controller
*| --------------------------------------------------------------------------
*| Kelurahan site
*|
*/
class Kelurahan extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_kelurahan');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Kelurahans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('kelurahan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kelurahans'] = $this->model_kelurahan->get($filter, $field, $this->limit_page, $offset);
		$this->data['kelurahan_counts'] = $this->model_kelurahan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/kelurahan/index/',
			'total_rows'   => $this->data['kelurahan_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kelurahan List');
		$this->render('backend/standart/administrator/kelurahan/kelurahan_list', $this->data);
	}
	
	/**
	* Add new kelurahans
	*
	*/
	public function add()
	{
		$this->is_allowed('kelurahan_add');

		$this->template->title('Kelurahan New');
		$this->render('backend/standart/administrator/kelurahan/kelurahan_add', $this->data);
	}

	/**
	* Add New Kelurahans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('kelurahan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		

		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		

		$this->form_validation->set_rules('kelurahan_nama', 'Nama Kelurahan', 'trim|required|max_length[255]');
		

		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'kecamatan_id' => $this->input->post('kecamatan_id'),
				'kelurahan_nama' => $this->input->post('kelurahan_nama'),
				'kelurahan_created_at' => date('Y-m-d H:i:s'),
				'kelurahan_user_created' => get_user_data('id'),			];

			
			



			
			
			$save_kelurahan = $id = $this->model_kelurahan->store($save_data);
			

			if ($save_kelurahan) {
				
				
					
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_kelurahan;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/kelurahan/edit/' . $save_kelurahan, 'Edit Kelurahan'),
						anchor('administrator/kelurahan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/kelurahan/edit/' . $save_kelurahan, 'Edit Kelurahan')
					]), 'success');

					$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/kelurahan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/kelurahan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
		/**
	* Update view Kelurahans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('kelurahan_update');

		$this->data['kelurahan'] = $this->model_kelurahan->find($id);

		$this->template->title('Kelurahan Update');
		$this->render('backend/standart/administrator/kelurahan/kelurahan_update', $this->data);
	}

	/**
	* Update Kelurahans
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('kelurahan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
				$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		

		$this->form_validation->set_rules('kelurahan_nama', 'Nama Kelurahan', 'trim|required|max_length[255]');
		

		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kecamatan_id' => $this->input->post('kecamatan_id'),
				'kelurahan_nama' => $this->input->post('kelurahan_nama'),
				'kelurahan_updated_at' => date('Y-m-d H:i:s'),
				'kelurahan_user_updated' => get_user_data('id'),			];

			

			


			
			
			$save_kelurahan = $this->model_kelurahan->change($id, $save_data);

			if ($save_kelurahan) {

				

				
				
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/kelurahan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

					$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/kelurahan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/kelurahan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
	/**
	* delete Kelurahans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('kelurahan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
			set_message(cclang('has_been_deleted', 'kelurahan'), 'success');
		} else {
			set_message(cclang('error_delete', 'kelurahan'), 'error');
		}

		redirect_back();
	}

		/**
	* View view Kelurahans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kelurahan_view');

		$this->data['kelurahan'] = $this->model_kelurahan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Kelurahan Detail');
		$this->render('backend/standart/administrator/kelurahan/kelurahan_view', $this->data);
	}
	
	/**
	* delete Kelurahans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kelurahan = $this->model_kelurahan->find($id);

		
		
		return $this->model_kelurahan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kelurahan_export');

		$this->model_kelurahan->export(
			'kelurahan', 
			'kelurahan',
			$this->model_kelurahan->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kelurahan_export');

		$this->model_kelurahan->pdf('kelurahan', 'kelurahan');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('kelurahan_export');

		$table = $title = 'kelurahan';
		$this->load->library('HtmlPdf');
	  
		$config = array(
			'orientation' => 'p',
			'format' => 'a4',
			'marges' => array(5, 5, 5, 5)
		);

		$this->pdf = new HtmlPdf($config);
		$this->pdf->setDefaultFont('stsongstdlight'); 

		$result = $this->db->get($table);
	   
		$data = $this->model_kelurahan->find($id);
		$fields = $result->list_fields();

		$content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf_single', [
			'data' => $data,
			'fields' => $fields,
			'title' => $title
		], TRUE);

		$this->pdf->initialize($config);
		$this->pdf->pdf->SetDisplayMode('fullpage');
		$this->pdf->writeHTML($content);
		$this->pdf->Output($table.'.pdf', 'H');
	}

	
}


/* End of file kelurahan.php */
/* Location: ./application/controllers/administrator/Kelurahan.php */