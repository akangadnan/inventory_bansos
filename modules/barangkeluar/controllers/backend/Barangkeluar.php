<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Barangkeluar Controller
*| --------------------------------------------------------------------------
*| Barangkeluar site
*|
*/
class Barangkeluar extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_barangkeluar');
		$this->load->model('barang/model_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Barangkeluars
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('barangkeluar_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['barangkeluars'] = $this->model_barangkeluar->get($filter, $field, $this->limit_page, $offset);
		$this->data['barangkeluar_counts'] = $this->model_barangkeluar->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/barangkeluar/index/',
			'total_rows'   => $this->data['barangkeluar_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Barangkeluar List');
		$this->render('backend/standart/administrator/barangkeluar/barangkeluar_list', $this->data);
	}
	
	/**
	* Add new barangkeluars
	*
	*/
	public function add()
	{
		$this->is_allowed('barangkeluar_add');

		$this->template->title('Barangkeluar New');
		$this->render('backend/standart/administrator/barangkeluar/barangkeluar_add', $this->data);
	}

	/**
	* Add New Barangkeluars
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('barangkeluar_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		// $this->form_validation->set_rules('tujuan_posko', 'Tujuan Posko', 'trim|required');
		$this->form_validation->set_rules('tujuan', 'Penerima', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Banyaknya', 'trim|required|max_length[12]');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');

		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');
		
			$save_data = [
				'id_barang' 	=> $id_barang,
				// 'tujuan_posko' 	=> $this->input->post('tujuan_posko'),
				'tujuan' 		=> $this->input->post('tujuan'),
				'jumlah' 		=> $jumlah,
				'keterangan' 	=> $this->input->post('keterangan'),
				'tanggal' 		=> $this->input->post('tanggal'),
				'waktu' 		=> $this->input->post('waktu'),
				'created_at' 	=> date('Y-m-d H:i:s'),
				'user_created' 	=> get_user_data('id'),
			];
			
			$save_barangkeluar = $id = $this->model_barangkeluar->store($save_data);

			$barang = $this->db->where('id_barang', $id_barang)->from('barang')->get()->row();
			$stok 	= ($barang->stok - $jumlah);

			$update_barang = [
				'stok' => $stok,
			];
			
			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_barangkeluar) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_barangkeluar;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('administrator/barangkeluar/edit/' . $save_barangkeluar, 'Edit Barangkeluar'),
						anchor('administrator/barangkeluar', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/barangkeluar/edit/' . $save_barangkeluar, 'Edit Barangkeluar')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/barangkeluar');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/barangkeluar');
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
	* Update view Barangkeluars
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('barangkeluar_update');

		$this->data['barangkeluar'] = $this->model_barangkeluar->find($id);

		$this->template->title('Barangkeluar Update');
		$this->render('backend/standart/administrator/barangkeluar/barangkeluar_update', $this->data);
	}

	/**
	* Update Barangkeluars
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('barangkeluar_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		// $this->form_validation->set_rules('tujuan_posko', 'Tujuan Posko', 'trim|required');
		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		$this->form_validation->set_rules('tujuan', 'Penerima', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Banyaknya', 'trim|required|max_length[12]');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');
		
		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');

			$save_data = [
				'id_barang'		=> $id_barang,
				// 'tujuan_posko' 	=> $this->input->post('tujuan_posko'),
				'tujuan' 		=> $this->input->post('tujuan'),
				'jumlah' 		=> $jumlah,
				'keterangan' 	=> $this->input->post('keterangan'),
				'tanggal' 		=> $this->input->post('tanggal'),
				'waktu' 		=> $this->input->post('waktu'),
			];

			$barang_keluar 	= $this->db->where('id_barangkeluar', $id)->get('barangkeluar')->row();
			$barang 		= $this->db->where('id_barang', $id_barang)->get('barang')->row();
			$jumlah_keluar 	= $barang_keluar->jumlah;

			if ($jumlah_keluar > $jumlah) {
				$sisa_jumlah 	= $jumlah_keluar - $jumlah;
				$stok_barang 	= $barang->stok + $sisa_jumlah;
			}else{
				$sisa_jumlah 	= $jumlah - $jumlah_keluar;
				$stok_barang 	= $barang->stok - $sisa_jumlah;
			}

			$update_barang = [
				'stok' => $stok_barang,
			];
			
			$save_barangkeluar = $this->model_barangkeluar->change($id, $save_data);

			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_barangkeluar) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('administrator/barangkeluar', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('administrator/barangkeluar');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('administrator/barangkeluar');
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
	* delete Barangkeluars
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('barangkeluar_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$barang_keluar 	= $this->db->where('id_barangkeluar', $id)->get('barangkeluar')->row();

			$barang 		= $this->db->where('id_barang', $barang_keluar->id_barang)->get('barang')->row();

			$stok_akhir 	= $barang_keluar->jumlah + $barang->stok;

			$update_barang = [
				'stok' => $stok_akhir,
			];

			$save_barang = $this->model_barang->change($barang_keluar->id_barang, $update_barang);

			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$barang_keluar 	= $this->db->where('id_barangkeluar', $id)->get('barangkeluar')->row();
	
				$barang 		= $this->db->where('id_barang', $barang_keluar->id_barang)->get('barang')->row();
	
				if ($barang->stok > $barang_keluar->jumlah) {
					$stok_akhir 	= $barang->stok - $barang_keluar->jumlah;
				}else{
					$stok_akhir 	= $barang_keluar->jumlah - $barang->stok;
				}
	
				$update_barang = [
					'stok' => $stok_akhir,
				];

				$save_barang = $this->model_barang->change($barang_keluar->id_barang, $update_barang);

				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'barangkeluar'), 'success');
        } else {
            set_message(cclang('error_delete', 'barangkeluar'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Barangkeluars
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('barangkeluar_view');

		$this->data['barangkeluar'] = $this->model_barangkeluar->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Barangkeluar Detail');
		$this->render('backend/standart/administrator/barangkeluar/barangkeluar_view', $this->data);
	}
	
	/**
	* delete Barangkeluars
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$barangkeluar = $this->model_barangkeluar->find($id);

		
		
		return $this->model_barangkeluar->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('barangkeluar_export');

		$this->model_barangkeluar->export_excel('Rekap Data Barang Keluar');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('barangkeluar_export');

		$this->model_barangkeluar->pdf('barangkeluar', 'barangkeluar');
	}


	public function single_pdf($id = null)
	{
		$this->is_allowed('barangkeluar_export');

		$table = $title = 'barangkeluar';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_barangkeluar->find($id);
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
		if (!$this->is_allowed('barangkeluar_list', false)) {
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


/* End of file barangkeluar.php */
/* Location: ./application/controllers/administrator/Barangkeluar.php */