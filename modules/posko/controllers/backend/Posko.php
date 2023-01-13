<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Posko Controller
*| --------------------------------------------------------------------------
*| Posko site
*|
*/
class Posko extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_posko');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Poskos
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('posko_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['poskos'] = $this->model_posko->get($filter, $field, $this->limit_page, $offset);
		$this->data['posko_counts'] = $this->model_posko->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/posko/index/',
			'total_rows'   => $this->data['posko_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Posko List');
		$this->render('backend/standart/administrator/posko/posko_list', $this->data);
	}
	
	/**
	* Add new poskos
	*
	*/
	public function add() {
		$this->is_allowed('posko_add');

		$this->template->title('Posko New');
		$this->render('backend/standart/administrator/posko/posko_add', $this->data);
	}

	/**
	* Add New Poskos
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('posko_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('posko_nama', 'Nama Posko', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('posko_alamat', 'Alamat Posko', 'trim|required');

		if ($this->form_validation->run()) {
			$save_data = [
				'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
				'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
				'posko_nama' 				=> $this->input->post('posko_nama'),
				'posko_alamat' 				=> $this->input->post('posko_alamat'),
				'posko_penanggung_jawab' 	=> $this->input->post('posko_penanggung_jawab'),
				'posko_pic' 				=> $this->input->post('posko_pic'),
				'posko_user_created' 		=> get_user_data('id'),
				'posko_created_at' 			=> date('Y-m-d H:i:s'),
			];

			$save_posko = $id = $this->model_posko->store($save_data);

			$jenis_layanan 	= $this->input->post('jenis_layanan[]');
			$pic_layanan 	= $this->input->post('pic_layanan[]');

			$data_layanan = [];
			if (count($jenis_layanan) > 0) {
				for ($i=0; $i < count($jenis_layanan); $i++) {
					$data_layanan[] = [
						'posko_id' 						=> $id,
						'jenis_layanan_id' 				=> $jenis_layanan[$i],
						'layanan_posko_pic' 			=> $pic_layanan[$i],
						'layanan_posko_created_at' 		=> date('Y-m-d H:i:s'),
						'layanan_posko_user_created' 	=> get_user_data('id'),
					];
				}
			}

			$this->db->insert_batch('layanan_posko', $data_layanan);

			if ($save_posko) {
				$id = $save_posko;

				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_posko;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/posko/edit/' . $save_posko, 'Edit Posko'),
						anchor('administrator/posko', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/posko/edit/' . $save_posko, 'Edit Posko')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/posko');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/posko');
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
	* Update view Poskos
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('posko_update');

		$this->data['posko'] = $this->model_posko->find($id);

		$this->template->title('Posko Update');
		$this->render('backend/standart/administrator/posko/posko_update', $this->data);
	}

	/**
	* Update Poskos
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('posko_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('posko_nama', 'Nama Posko', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('posko_alamat', 'Alamat Posko', 'trim|required');
		
		if ($this->form_validation->run()) {
			$save_data = [
				'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
				'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
				'posko_nama' 				=> $this->input->post('posko_nama'),
				'posko_alamat' 				=> $this->input->post('posko_alamat'),
				'posko_penanggung_jawab' 	=> $this->input->post('posko_penanggung_jawab'),
				'posko_pic' 				=> $this->input->post('posko_pic'),
			];

			$save_posko = $this->model_posko->change($id, $save_data);

			$this->db->delete('layanan_posko', ['posko_id' => $id]);

			$jenis_layanan 	= $this->input->post('jenis_layanan[]');
			$pic_layanan 	= $this->input->post('pic_layanan[]');

			if (count($jenis_layanan) > 0) {
				$data_layanan = [];

				for ($i=0; $i < count($jenis_layanan); $i++) {
					$data_layanan[] = [
						'posko_id' 						=> $id,
						'jenis_layanan_id' 				=> $jenis_layanan[$i],
						'layanan_posko_pic' 			=> $pic_layanan[$i],
						'layanan_posko_created_at' 		=> date('Y-m-d H:i:s'),
						'layanan_posko_user_created' 	=> get_user_data('id'),
					];
				}
			}

			$save_layanan = $this->db->insert_batch('layanan_posko', $data_layanan);

			if ($save_posko || $save_layanan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/posko', ' Go back to list')
					]);
				} else {
					set_message(cclang('success_update_data_redirect', []), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/posko');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/posko');
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
	* delete Poskos
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('posko_delete');

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
            set_message(cclang('has_been_deleted', 'posko'), 'success');
        } else {
            set_message(cclang('error_delete', 'posko'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Poskos
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('posko_view');

		$this->data['posko'] 	= $this->model_posko->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Posko Detail');
		$this->render('backend/standart/administrator/posko/posko_view', $this->data);
	}
	
	/**
	* delete Poskos
	*
	* @var $id String
	*/
	private function _remove($id) {
		$posko = $this->model_posko->find($id);
		
		return $this->model_posko->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('posko_export');

		$this->model_posko->export_excel('Rekap Data Posko');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('posko_export');

		$this->model_posko->pdf('posko', 'posko');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('posko_export');

		$table = $title = 'posko';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_posko->find($id);
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

	public function ajax_kelurahan_id($id = null) {
		if (!$this->is_allowed('posko_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$results = db_get_all_data('kelurahan', ['kecamatan_id' => $id]);
		$this->response($results);	
	}

	
}


/* End of file posko.php */
/* Location: ./application/controllers/administrator/Posko.php */