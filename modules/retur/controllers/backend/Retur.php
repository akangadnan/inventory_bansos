<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Retur Controller
*| --------------------------------------------------------------------------
*| Retur site
*|
*/
class Retur extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_retur');
		$this->load->model('barang/model_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Returs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('retur_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['returs'] = $this->model_retur->get($filter, $field, $this->limit_page, $offset);
		$this->data['retur_counts'] = $this->model_retur->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/retur/index/',
			'total_rows'   => $this->data['retur_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Retur Barang List');
		$this->render('backend/standart/administrator/retur/retur_list', $this->data);
	}
	
	/**
	* Add new returs
	*
	*/
	public function add()
	{
		$this->is_allowed('retur_add');

		$this->template->title('Retur Barang New');
		$this->render('backend/standart/administrator/retur/retur_add', $this->data);
	}

	/**
	* Add New Returs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('retur_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		

		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		

		$this->form_validation->set_rules('jumlah', 'Banyaknya', 'trim|required');
		

		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		

		

		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');
		
			$save_data = [
				'tanggal' => $this->input->post('tanggal'),
				'id_barang' => $id_barang,
				'jumlah' => $jumlah,
				'keterangan' => $this->input->post('keterangan'),
				'retur_created_at' => date('Y-m-d H:i:s'),
				'user_created' => get_user_data('id'),
			];
			
			$save_retur = $id = $this->model_retur->store($save_data);

			$barang = $this->db->where('id_barang', $id_barang)->from('barang')->get()->row();
			$stok 	= ($barang->stok + $jumlah);

			$update_barang = [
				'stok' => $stok,
			];
			
			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_retur) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_retur;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/retur/edit/' . $save_retur, 'Edit Retur'),
						anchor('administrator/retur', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/retur/edit/' . $save_retur, 'Edit Retur')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/retur');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/retur');
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
	* Update view Returs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('retur_update');

		$this->data['retur'] = $this->model_retur->find($id);

		$this->template->title('Retur Barang Update');
		$this->render('backend/standart/administrator/retur/retur_update', $this->data);
	}

	/**
	* Update Returs
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('retur_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Banyaknya', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');

			$save_data = [
				'tanggal' 		=> $this->input->post('tanggal'),
				'id_barang' 	=> $id_barang,
				'jumlah' 		=> $jumlah,
				'keterangan' 	=> $this->input->post('keterangan'),
			];

			$barang_retur 	= $this->db->where('id_retur', $id)->get('retur')->row();
			$barang 		= $this->db->where('id_barang', $id_barang)->get('barang')->row();
			$jumlah_retur 	= $barang_retur->jumlah;

			if ($jumlah_retur > $jumlah) {
				$sisa_jumlah 	= $jumlah_retur - $jumlah;
			}else{
				$sisa_jumlah 	= $jumlah - $jumlah_retur;
			}

			$stok_barang 	= $barang->stok + $sisa_jumlah;

			$update_barang = [
				'stok' => $stok_barang,
			];

			$save_retur = $this->model_retur->change($id, $save_data);

			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_retur) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/retur', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/retur');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/retur');
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
	* delete Returs
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('retur_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$barang_retur 	= $this->db->where('id_retur', $id)->get('retur')->row();
			$barang 		= $this->db->where('id_barang', $barang_retur->id_barang)->get('barang')->row();
			$jumlah_retur 	= $barang_retur->jumlah;
			$jumlah_barang 	= $barang->stok;

			if ($jumlah_retur > $jumlah_barang) {
				$stok_barang = $jumlah_retur-$jumlah_barang;
			}else{
				$stok_barang = $jumlah_barang-$jumlah_retur;
			}

			$update_barang = [
				'stok' => $stok_barang,
			];

			$save_barang = $this->model_barang->change($barang_retur->id_barang, $update_barang);

			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$barang_retur 	= $this->db->where('id_retur', $id)->get('retur')->row();
				$barang 		= $this->db->where('id_barang', $barang_retur->id_barang)->get('barang')->row();
				$jumlah_retur 	= $barang_retur->jumlah;
				$jumlah_barang 	= $barang->stok;

				if ($jumlah_retur > $jumlah_barang) {
					$stok_barang = $jumlah_retur-$jumlah_barang;
				}else{
					$stok_barang = $jumlah_barang-$jumlah_retur;
				}
	
				$update_barang = [
					'stok' => $stok_barang,
				];

				$save_barang = $this->model_barang->change($barang_retur->id_barang, $update_barang);

				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'retur'), 'success');
        } else {
            set_message(cclang('error_delete', 'retur'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Returs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('retur_view');

		$this->data['retur'] = $this->model_retur->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Retur Barang Detail');
		$this->render('backend/standart/administrator/retur/retur_view', $this->data);
	}
	
	/**
	* delete Returs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$retur = $this->model_retur->find($id);

		
		
		return $this->model_retur->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('retur_export');

		$this->model_retur->export_excel('Rekap Data Retur Barang');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('retur_export');

		$this->model_retur->pdf('retur', 'retur');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('retur_export');

		$table = $title = 'retur';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_retur->find($id);
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

	public function ajax_barang($id = null)
	{
		if (!$this->is_allowed('retur_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if ($id != null) {
			$results = $this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'left')->where('barang', $id)->get('barang')->result();
		}else{
			$results = $this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'left')->get('barang')->result();
		}

		$this->response($results);	
	}

	
}


/* End of file retur.php */
/* Location: ./application/controllers/administrator/Retur.php */