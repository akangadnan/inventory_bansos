<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Permohonan Controller
*| --------------------------------------------------------------------------
*| Permohonan site
*|
*/
class Permohonan extends Admin {
	public function __construct() {
		parent::__construct();

		$this->load->model('model_permohonan');
		$this->load->model('barangkeluar/model_barangkeluar');
		$this->load->model('barang/model_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Permohonans
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('permohonan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['permohonans'] = $this->model_permohonan->get($filter, $field, $this->limit_page, $offset);
		$this->data['permohonan_counts'] = $this->model_permohonan->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/permohonan/index/',
			'total_rows'   => $this->data['permohonan_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Permohonan List');
		$this->render('backend/standart/administrator/permohonan/permohonan_list', $this->data);
	}
	
	/**
	* Add new permohonans
	*
	*/
	public function add() {
		$this->is_allowed('permohonan_add');

		$this->template->title('Permohonan New');
		$this->render('backend/standart/administrator/permohonan/permohonan_add', $this->data);
	}

	/**
	* Add New Permohonans
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('permohonan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');

		$this->form_validation->set_rules('permohonan_tanggal', 'Tanggal Permohonan', 'trim|required');
		$this->form_validation->set_rules('permohonan_waktu', 'Waktu', 'trim|required');
		$this->form_validation->set_rules('posko_id', 'Posko', 'trim|required');
		$this->form_validation->set_rules('permohonan_pemohon', 'Pemohon', 'trim|required|max_length[75]');
		$this->form_validation->set_rules('permohonan_mengetahui', 'Mengetahui Posko', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Posko Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Posko Kelurahan', 'trim|required');

		
		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				$save_data = [
					'permohonan_tanggal' 		=> $this->input->post('permohonan_tanggal'),
					'permohonan_waktu' 			=> $this->input->post('permohonan_waktu'),
					'posko_id' 					=> $this->input->post('posko_id'),
					'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
					'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
					'permohonan_pemohon' 		=> $this->input->post('permohonan_pemohon'),
					'permohonan_keterangan' 	=> $this->input->post('permohonan_keterangan'),
					'permohonan_status' 		=> '1',
					'permohonan_respon_posko' 	=> $this->input->post('permohonan_respon_posko'),
					'permohonan_mengetahui' 	=> $this->input->post('permohonan_mengetahui'),
					'permohonan_created_at' 	=> date('Y-m-d H:i:s'),
					'permohonan_user_created' 	=> get_user_data('id'),
				];
	
				$save_permohonan = $id = $this->model_permohonan->store($save_data);
	
				if (count($barang) > 0) {
					$data_permohonan_bantuan_barang = [];
	
					for ($i=0; $i < count($barang); $i++) {
						$data_permohonan_bantuan_barang[] = [
							'permohonan_id' 					=> $id,
							'barang_id' 						=> $barang[$i],
							'permohonan_detail_jumlah' 			=> $jumlah[$i],
							'permohonan_detail_keterangan' 		=> $keterangan[$i],
							'permohonan_detail_created_at' 		=> date('Y-m-d H:i:s'),
							'permohonan_detail_user_created' 	=> get_user_data('id'),
						];
					}
	
					$this->db->insert_batch('permohonan_detail', $data_permohonan_bantuan_barang);
				}
	
				if ($save_permohonan) {
					$id = $save_permohonan;
	
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $save_permohonan;
						$this->data['message'] = cclang('success_save_data_stay', [
							anchor('administrator/permohonan/edit/' . $save_permohonan, 'Edit Permohonan'),
							anchor('administrator/permohonan', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							anchor('administrator/permohonan/edit/' . $save_permohonan, 'Edit Permohonan')
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/permohonan');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/permohonan');
					}
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
	* Update view Permohonans
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('permohonan_update');

		$this->data['permohonan'] = $this->model_permohonan->find($id);

		$this->template->title('Permohonan Update');
		$this->render('backend/standart/administrator/permohonan/permohonan_update', $this->data);
	}

	/**
	* Update Permohonans
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('permohonan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');

		$this->form_validation->set_rules('permohonan_tanggal', 'Tanggal Permohonan', 'trim|required');
		$this->form_validation->set_rules('permohonan_waktu', 'Waktu', 'trim|required');
		$this->form_validation->set_rules('posko_id', 'Posko', 'trim|required');
		$this->form_validation->set_rules('permohonan_pemohon', 'Pemohon', 'trim|required|max_length[75]');
		$this->form_validation->set_rules('permohonan_mengetahui', 'Mengetahui Posko', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Posko Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Posko Kelurahan', 'trim|required');
		
		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				$save_data = [
					'permohonan_tanggal' 		=> $this->input->post('permohonan_tanggal'),
					'permohonan_waktu' 			=> $this->input->post('permohonan_waktu'),
					'posko_id' 					=> $this->input->post('posko_id'),
					'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
					'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
					'permohonan_pemohon' 		=> $this->input->post('permohonan_pemohon'),
					'permohonan_keterangan' 	=> $this->input->post('permohonan_keterangan'),
					'permohonan_respon_posko' 	=> $this->input->post('permohonan_respon_posko'),
					'permohonan_mengetahui' 	=> $this->input->post('permohonan_mengetahui'),
				];
				
				$save_permohonan = $this->model_permohonan->change($id, $save_data);
	
				$this->db->delete('permohonan_detail', ['permohonan_id' => $id]);
	
				$save_details = 0;
				if (count($barang) > 0) {
					$data_permohonan_bantuan_barang = [];
	
					for ($i=0; $i < count($barang); $i++) {
						$data_permohonan_bantuan_barang[] = [
							'permohonan_id' 					=> $id,
							'barang_id' 						=> $barang[$i],
							'permohonan_detail_jumlah' 			=> $jumlah[$i],
							'permohonan_detail_keterangan' 		=> $keterangan[$i],
							'permohonan_detail_created_at' 		=> date('Y-m-d H:i:s'),
							'permohonan_detail_user_created' 	=> get_user_data('id'),
						];
					}
	
					$save_details = $this->db->insert_batch('permohonan_detail', $data_permohonan_bantuan_barang);
				}
	
				if ($save_permohonan || $save_details) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $id;
						$this->data['message'] = cclang('success_update_data_stay', [
							anchor('administrator/permohonan', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_update_data_redirect', [
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/permohonan');
					}
				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/permohonan');
					}
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}

	public function verified($id) {
		//input ke barang keluar
		//get id barang
		// $getbarang = $this->model_permohonan->get($id);

		
		//input ke barang_keluar
		//id barang belum komplit
		//jumlah belum keseting

		// $save_databarangkeluar = [
		// 	'id_barang' 	=> 14,
		// 	'tujuan_posko' 	=> $getbarang[0]->posko_id,
		// 	'tujuan' 		=> $getbarang[0]->permohonan_pemohon,
		// 	'jumlah' 		=> 2,
		// 	'keterangan' 	=> $getbarang[0]->permohonan_keterangan,
		// 	'tanggal'		=> date('Y-m-d'),
		// 	'waktu'			=> date('H:i')
		// ];

		// $this->db->insert('barangkeluar', $save_databarangkeluar);
		
		// var_dump($getbarang);
		// var_dump($save_databarangkeluar);
		// die();
		// $barang = $this->db->where('id_barang', 14)->from('barang')->get()->row();
		// $stok 	= ($barang->stok - 2);

		// $update_barang = [
		// 	'stok' => $stok,
		// ];
		
		// $save_barang = $this->model_barang->change($barang->id_barang, $update_barang);
			
			$save_data = [
				'permohonan_status' 		=> '2',
				'permohonan_verified' 		=> get_user_data('id'),
				'permohonan_verified_at' 	=> date('Y-m-d H:i:s'),
			];
	
			$this->model_permohonan->change($id, $save_data);

		redirect_back();
	}

	public function process_order($id) {
		if (!$this->is_allowed('permohonan_proses', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$update_data = [
			'permohonan_status' => '3',
		];

		$this->model_permohonan->change($id, $update_data);

		$this->data['permohonan'] = $this->model_permohonan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Proses Permintaan Bantuan');
		$this->render('backend/standart/administrator/permohonan/permohonan_proses', $this->data);
	}

	public function save_proses($id) {
		if (!$this->is_allowed('permohonan_proses', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$permohonan = $this->model_permohonan->join_avaiable()->filter_avaiable()->find($id);
		$details 	= db_get_all_data('permohonan_detail', ['permohonan_id' => $id]);

		$catatan 	= $this->input->post('catatan');
		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');

		if (!empty($jumlah[0])) {
			$update_data = [
				'permohonan_status' 		=> '4',
			];
	
			$this->model_permohonan->change($id, $update_data);

			$save_barangkeluar = [
				'permohonan_id' => $id,
				'kecamatan_id' 	=> $permohonan->kecamatan_id,
				'kelurahan_id' 	=> $permohonan->kelurahan_id,
				'tujuan_posko' 	=> $permohonan->posko_id,
				'pemohon' 		=> $permohonan->permohonan_pemohon,
				'keterangan' 	=> $catatan,
				'tanggal' 		=> date('Y-m-d'),
				'waktu' 		=> date('H:i:s'),
				'created_at' 	=> date('Y-m-d H:i:s'),
				'user_created' 	=> get_user_data('id'),
			];

			$barangkeluar_id = $this->db->insert('barangkeluar', $save_barangkeluar);

			$save_details = [];

			for ($i=0; $i < count($details); $i++) {
				$save_details[] = [
					'barangkeluar_id' 					=> $barangkeluar_id,
					'barang_id' 						=> $details[$i]->barang_id,
					'barangkeluar_detail_jumlah' 		=> $jumlah[$i],
					'barangkeluar_detail_status' 		=> '2',
					'barangkeluar_detail_keterangan' 	=> $keterangan[$i],
					'barangkeluar_detail_user_created' 	=> get_user_data('id'),
					'barangkeluar_detail_created_at' 	=> date('Y-m-d H:i:s'),
				];
			}

			$this->db->insert_batch('barangkeluar_detail', $save_details);
			
		}

		// $this->data = [
		// 	'save_detail' 		=> $save_details,
			// 'permohonan' 	=> $permohonan,
			// 'detail' 		=> $details,
			// 'catatan' 		=> $catatan,
			// 'barang' 		=> $barang,
			// 'jumlah' 		=> $jumlah,
			// 'keterangan' 	=> $keterangan,
		// ];

		// echo json_encode($this->data);
		// exit;

		set_message('Data berhasil dikirim!', 'success');

		$this->data['success'] = true;
		$this->data['redirect'] = base_url('administrator/barangkeluar');

		$this->response($this->data);
	}
	
	/**
	* delete Permohonans
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('permohonan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);

			$this->db->delete('permohonan_detail', ['permohonan_id' => $id]);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);

				$this->db->delete('permohonan_detail', ['permohonan_id' => $id]);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'permohonan'), 'success');
        } else {
            set_message(cclang('error_delete', 'permohonan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Permohonans
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('permohonan_view');

		$this->data['permohonan'] = $this->model_permohonan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Permohonan Detail');
		$this->render('backend/standart/administrator/permohonan/permohonan_view', $this->data);
	}
	
	/**
	* delete Permohonans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$permohonan = $this->model_permohonan->find($id);

		
		
		return $this->model_permohonan->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('permohonan_export');

		$this->model_permohonan->export(
			'permohonan', 
			'permohonan',
			$this->model_permohonan->field_search
		);
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('permohonan_export');

		$this->model_permohonan->pdf('permohonan', 'permohonan');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('permohonan_export');

		$table = $title = 'permohonan';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_permohonan->find($id);
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

	public function ajax_barang($id = null) {
		if (!$this->is_allowed('permohonan_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if ($id != null) {
			$results = $this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'left')->where('id_barang', $id)->get('barang')->result();
		}else{
			$results = $this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'left')->get('barang')->result();
		}

		$this->response($results);	
	}

	public function ajax_kelurahan_id($id = null) {
		if (!$this->is_allowed('permohonan_list', false)) {
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


/* End of file permohonan.php */
/* Location: ./application/controllers/administrator/Permohonan.php */