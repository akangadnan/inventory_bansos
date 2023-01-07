<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
*| --------------------------------------------------------------------------
*| Sumber Controller
*| --------------------------------------------------------------------------
*| Sumber site
*|
*/
class Sumber extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_sumber');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Sumbers
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('sumber_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['sumbers'] = $this->model_sumber->get($filter, $field, $this->limit_page, $offset);
		$this->data['sumber_counts'] = $this->model_sumber->count_all($filter, $field);

		$config = [
			'base_url' 		=> 'administrator/sumber/index/',
			'total_rows' 	=> $this->data['sumber_counts'],
			'per_page' 		=> $this->limit_page,
			'uri_segment' 	=> 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Sumber List');
		$this->render('backend/standart/administrator/sumber/sumber_list', $this->data);
	}
	
	/**
	* Add new sumbers
	*
	*/
	public function add() {
		$this->is_allowed('sumber_add');

		$this->template->title('Sumber New');
		$this->render('backend/standart/administrator/sumber/sumber_add', $this->data);
	}

	/**
	* Add New Sumbers
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('sumber_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_sumber', 'Nama', 'trim|required|max_length[255]');

		if ($this->form_validation->run()) {
			$save_data = [
				'nama_sumber' 	=> $this->input->post('nama_sumber'),
				'created_at' 	=> date('Y-m-d H:i:s'),
				'user_created' 	=> get_user_data('id'),
			];

			$save_sumber = $id = $this->model_sumber->store($save_data);

			if ($save_sumber) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_sumber;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/sumber/edit/' . $save_sumber, 'Edit Sumber'),
						anchor('administrator/sumber', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/sumber/edit/' . $save_sumber, 'Edit Sumber')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/sumber');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/sumber');
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
	* Update view Sumbers
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('sumber_update');

		$this->data['sumber'] = $this->model_sumber->find($id);

		$this->template->title('Sumber Update');
		$this->render('backend/standart/administrator/sumber/sumber_update', $this->data);
	}

	/**
	* Update Sumbers
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('sumber_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_sumber', 'Nama', 'trim|required|max_length[255]');
		
		if ($this->form_validation->run()) {
			$save_data = [
				'nama_sumber' => $this->input->post('nama_sumber'),
			];
			
			$save_sumber = $this->model_sumber->change($id, $save_data);

			if ($save_sumber) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/sumber', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/sumber');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/sumber');
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
	* delete Sumbers
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('sumber_delete');

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
            set_message(cclang('has_been_deleted', 'sumber'), 'success');
        } else {
            set_message(cclang('error_delete', 'sumber'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Sumbers
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('sumber_view');

		$this->data['sumber'] = $this->model_sumber->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Sumber Detail');
		$this->render('backend/standart/administrator/sumber/sumber_view', $this->data);
	}
	
	/**
	* delete Sumbers
	*
	* @var $id String
	*/
	private function _remove($id) {
		$sumber = $this->model_sumber->find($id);

		return $this->model_sumber->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('sumber_export');

		$this->model_sumber->export_excel('Data Sumber');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('sumber_export');

		$this->model_sumber->pdf('sumber', 'sumber');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('sumber_export');

		$table = $title = 'sumber';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_sumber->find($id);
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


/* End of file sumber.php */
/* Location: ./application/controllers/administrator/Sumber.php */