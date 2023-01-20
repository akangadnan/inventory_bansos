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
	public function index($offset = 0) {
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
	public function add() {
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
		
		if (!array_keys([1, 5], get_user_group_id(get_user_data('id')))) {
			$posko_id 	= $this->session->userdata('posko_id');
		} else {
			$this->form_validation->set_rules('asal_posko', 'Asal Posko', 'trim|required');

			$posko_id 	= $this->input->post('asal_posko');
		}

		$this->form_validation->set_rules('tujuan_posko', 'Tujuan Posko', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('pemohon', 'Pemohon', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');

		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');

		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				if (count($barang) > 0) {
					$save_data = [
						'barangkeluar_asal_posko' 	=> $posko_id,
						'tujuan_posko' 				=> $this->input->post('tujuan_posko'),
						'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
						'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
						'keterangan' 				=> $this->input->post('keterangan'),
						'pemohon' 					=> $this->input->post('pemohon'),
						'tanggal' 					=> $this->input->post('tanggal'),
						'waktu' 					=> $this->input->post('waktu'),
						'created_at' 				=> date('Y-m-d H:i:s'),
						'user_created' 				=> get_user_data('id'),
					];
		
					$save_barangkeluar = $id = $this->model_barangkeluar->store($save_data);

					for ($i=0; $i < count($barang); $i++) {
						$data_barang_keluar = [
							'barangkeluar_id' 					=> $id,
							'barang_id' 						=> $barang[$i],
							'barangkeluar_detail_jumlah' 		=> $jumlah[$i],
							'barangkeluar_detail_status' 		=> '1',
							'barangkeluar_detail_keterangan' 	=> $keterangan[$i],
							'barangkeluar_detail_user_created' 	=> get_user_data('id'),
							'barangkeluar_detail_created_at' 	=> date('Y-m-d H:i:s'),
						];

						$this->db->insert('barangkeluar_detail', $data_barang_keluar);

						$stok_barang_posko 	= $this->db->get_where('stok_posko', ['posko_id' => $posko_id, 'barang_id' => $barang[$i]])->row();

						if (count($stok_barang_posko) > 0) {
							$stok_posko_total 	= $stok_barang_posko->stok_posko_total;

							$hasil = $stok_posko_total - $jumlah[$i];

							$this->db->update('stok_posko', ['stok_posko_total' => $hasil], ['posko_id' => $posko_id, 'barang_id' => $barang[$i]]);
						}
					}

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
				}else{
					$this->data['success'] = false;
					$this->data['message'] = 'Tidak ada data barang yang di input!';
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
	public function edit($id) {
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
		
		if (!array_keys([1, 5], get_user_group_id(get_user_data('id')))) {
			$posko_id 	= $this->session->userdata('posko_id');
		} else {
			$this->form_validation->set_rules('asal_posko', 'Asal Posko', 'trim|required');

			$posko_id 	= $this->input->post('asal_posko');
		}

		$this->form_validation->set_rules('tujuan_posko', 'Tujuan Posko', 'trim|required');
		$this->form_validation->set_rules('pemohon', 'Pemohon', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');

		$barang 	= $this->input->post('id_barang[]');
		$jumlah 	= $this->input->post('jumlah[]');
		$keterangan = $this->input->post('keterangan_barang[]');
		
		if ($this->form_validation->run()) {
			if (empty($barang[0]) || empty($jumlah[0])) {
				$this->data['success'] = false;
				$this->data['message'] = 'Tidak ada data barang yang di input!';
			}else{
				$save_data = [
					'barangkeluar_asal_posko' 	=> $posko_id,
					'tujuan_posko' 				=> $this->input->post('tujuan_posko'),
					'pemohon' 					=> $this->input->post('pemohon'),
					'kecamatan_id' 				=> $this->input->post('kecamatan_id'),
					'kelurahan_id' 				=> $this->input->post('kelurahan_id'),
					'keterangan' 				=> $this->input->post('keterangan'),
					'tanggal' 					=> $this->input->post('tanggal'),
					'waktu' 					=> $this->input->post('waktu'),
				];

				$data_barangkeluar 	= $this->model_barangkeluar->query_barangkeluar($id)->result();

				foreach ($data_barangkeluar as $item) {
					$posko_keluar 	= $item->barangkeluar_asal_posko;
					$barang_keluar 	= $item->barang_id;
					$jumlah_keluar 	= $item->barangkeluar_detail_jumlah;

					$data_stok_posko = $this->db->get_where('stok_posko', ['posko_id' => $posko_keluar, 'barang_id' => $barang_keluar])->row();
					$stok_posko 	= $data_stok_posko->stok_posko_total;

					$hasil 			= $stok_posko - $jumlah_keluar;

					$this->db->update('stok_posko', ['stok_posko_total' => $hasil], ['posko_id' => $posko_keluar, 'barang_id' => $barang_keluar]);
					$this->db->delete('barangkeluar_detail', ['barangkeluar_id' => $id, 'barang_id' => $barang_keluar]);
				}
	
				$save_barangkeluar = $this->model_barangkeluar->change($id, $save_data);

				if (count($barang) > 0) {
					for ($i=0; $i < count($barang); $i++) {
						$data_barang_keluar = [
							'barangkeluar_id' 					=> $id,
							'barang_id' 						=> $barang[$i],
							'barangkeluar_detail_jumlah' 		=> $jumlah[$i],
							'barangkeluar_detail_status' 		=> '1',
							'barangkeluar_detail_keterangan' 	=> $keterangan[$i],
							'barangkeluar_detail_user_created' 	=> get_user_data('id'),
							'barangkeluar_detail_created_at' 	=> date('Y-m-d H:i:s'),
						];

						$this->db->insert('barangkeluar_detail', $data_barang_keluar);

						$stok_barang_posko 	= $this->db->get_where('stok_posko', ['posko_id' => $posko_id, 'barang_id' => $barang[$i]])->row();

						if (count($stok_barang_posko) > 0) {
							$stok_posko_total 	= $stok_barang_posko->stok_posko_total;

							$hasil = $stok_posko_total + $jumlah[$i];

							$this->db->update('stok_posko', ['stok_posko_total' => $hasil], ['posko_id' => $posko_id, 'barang_id' => $barang[$i]]);
						}else{
							$simpan_stok_posko = [
								'posko_id' 			=> $posko_id,
								'barang_id' 		=> $barang[$i],
								'stok_posko_total' 	=> $jumlah[$i],
							];

							$this->db->insert('stok_posko', $simpan_stok_posko);
						}
					}
				}
	
				// if ($save_barangkeluar) {
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
				// } else {
				// 	if ($this->input->post('save_type') == 'stay') {
				// 		$this->data['success'] = false;
				// 		$this->data['message'] = cclang('data_not_change');
				// 	} else {
				// 		$this->data['success'] = false;
				// 		$this->data['message'] = cclang('data_not_change');
				// 		$this->data['redirect'] = base_url('administrator/barangkeluar');
				// 	}
				// }
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
	public function delete($id = null) {
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

			$this->model_barang->change($barang_keluar->id_barang, $update_barang);

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

				$this->model_barang->change($barang_keluar->id_barang, $update_barang);

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
	public function view($id) {
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
	private function _remove($id) {
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
	public function export_pdf() {
		$this->is_allowed('barangkeluar_export');

		$this->model_barangkeluar->pdf('barangkeluar', 'barangkeluar');
	}


	public function single_pdf($id = null) {
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

	public function ajax_barang($id = null) {
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

	public function ajax_kelurahan_id($id = null) {
		if (!$this->is_allowed('barangkeluar_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$results = db_get_all_data('kelurahan', ['kecamatan_id' => $id]);
		$this->response($results);
	}

	public function ajax_stok_posko($posko_id = null) {
		if (!$this->is_allowed('barangkeluar_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		foreach (db_get_all_data('stok_posko', ['posko_id' => $posko_id]) as $item) {
			$results[] = [
				'barang_id' 	=> $item->barang_id,
				'barang_nama' 	=> join_multi_select($item->barang_id, 'barang', 'id_barang', 'nama_barang'),
				'barang_satuan' => join_multi_select(join_multi_select($item->barang_id, 'barang', 'id_barang', 'satuan'), 'satuan', 'id_satuan', 'nama_satuan'),
				'barang_stok' 	=> $item->stok_posko_total,
			];
		}

		// $results = db_get_all_data('stok_posko', ['posko_id' => $posko_id]);
		$this->response($results);
	}

	public function coba() {
		$posko_id 	= $this->input->get('id');
		$barang_id 	= $this->input->get('barang');

		$this->model_barangkeluar->query_barangkeluar($posko_id);

		echo $this->db->last_query();
		exit;
	}
}


/* End of file barangkeluar.php */
/* Location: ./application/controllers/administrator/Barangkeluar.php */