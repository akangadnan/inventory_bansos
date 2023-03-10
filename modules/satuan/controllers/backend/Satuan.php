<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Satuan Controller
*| --------------------------------------------------------------------------
*| Satuan site
*|
*/
class Satuan extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_satuan');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Satuans
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('satuan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['satuans'] = $this->model_satuan->get($filter, $field, $this->limit_page, $offset);
		$this->data['satuan_counts'] = $this->model_satuan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/satuan/index/',
			'total_rows'   => $this->data['satuan_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Satuan List');
		$this->render('backend/standart/administrator/satuan/satuan_list', $this->data);
	}
	
	/**
	* Add new satuans
	*
	*/
	public function add() {
		$this->is_allowed('satuan_add');

		$this->template->title('Satuan New');
		$this->render('backend/standart/administrator/satuan/satuan_add', $this->data);
	}

	/**
	* Add New Satuans
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('satuan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_satuan', 'Nama Satuan', 'trim|required|max_length[255]');

		if ($this->form_validation->run()) {
			$save_data = [
				'nama_satuan' 			=> $this->input->post('nama_satuan'),
				'satuan_created_at' 	=> date('Y-m-d H:i:s'),
				'satuan_user_created' 	=> get_user_data('id'),
			];
			
			$save_satuan = $id = $this->model_satuan->store($save_data);

			if ($save_satuan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_satuan;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/satuan/edit/' . $save_satuan, 'Edit Satuan'),
						anchor('administrator/satuan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/satuan/edit/' . $save_satuan, 'Edit Satuan')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/satuan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/satuan');
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
	* Update view Satuans
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('satuan_update');

		$this->data['satuan'] = $this->model_satuan->find($id);

		$this->template->title('Satuan Update');
		$this->render('backend/standart/administrator/satuan/satuan_update', $this->data);
	}

	/**
	* Update Satuans
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('satuan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_satuan', 'Nama Satuan', 'trim|required|max_length[255]');
		
		if ($this->form_validation->run()) {
			$save_data = [
				'nama_satuan' => $this->input->post('nama_satuan'),
			];
			
			$save_satuan = $this->model_satuan->change($id, $save_data);

			if ($save_satuan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/satuan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/satuan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/satuan');
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
	* delete Satuans
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('satuan_delete');

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
            set_message(cclang('has_been_deleted', 'satuan'), 'success');
        } else {
            set_message(cclang('error_delete', 'satuan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Satuans
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('satuan_view');

		$this->data['satuan'] = $this->model_satuan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Satuan Detail');
		$this->render('backend/standart/administrator/satuan/satuan_view', $this->data);
	}
	
	/**
	* delete Satuans
	*
	* @var $id String
	*/
	private function _remove($id) {
		$satuan = $this->model_satuan->find($id);
		
		return $this->model_satuan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('satuan_export');

		$this->model_satuan->export_excel('Data Satuan Barang');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('satuan_export');

		$this->model_satuan->pdf('satuan', 'satuan');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('satuan_export');

		$table = $title = 'satuan';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_satuan->find($id);
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


/* End of file satuan.php */
/* Location: ./application/controllers/administrator/Satuan.php */