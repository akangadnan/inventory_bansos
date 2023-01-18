<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Barangmasuk Controller
*| --------------------------------------------------------------------------
*| Barangmasuk site
*|
*/
class Barangmasuk extends Admin	{
	public function __construct() {
		parent::__construct();

		$this->load->model('model_barangmasuk');
		$this->load->model('barang/model_barang');
		$this->load->model('group/model_group');
		$this->lang->load('web_lang', $this->current_lang);
	}

	/**
	* show all Barangmasuks
	*
	* @var $offset String
	*/
	public function index($offset = 0) {
		$this->is_allowed('barangmasuk_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['barangmasuks'] = $this->model_barangmasuk->get($filter, $field, $this->limit_page, $offset);
		$this->data['barangmasuk_counts'] = $this->model_barangmasuk->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/barangmasuk/index/',
			'total_rows'   => $this->data['barangmasuk_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Barang Masuk List');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_list', $this->data);
	}
	
	/**
	* Add new barangmasuks
	*
	*/
	public function add() {
		$this->is_allowed('barangmasuk_add');

		$this->template->title('Barang Masuk New');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_add', $this->data);
	}

	/**
	* Add New Barangmasuks
	*
	* @return JSON
	*/
	public function add_save() {
		if (!$this->is_allowed('barangmasuk_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$this->form_validation->set_rules('asal_posko', 'Asal Posko', 'trim|required');
		$this->form_validation->set_rules('nama_donatur', 'Nama Donatur', 'trim|required');
		$this->form_validation->set_rules('alamat_donatur', 'Alamat Donatur', 'trim|required');
		$this->form_validation->set_rules('phone_donatur', 'No Telepon Donatur', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');

		if ($this->form_validation->run()) {
			$posko_id 	= $this->input->post('asal_posko');
			$barang 	= $this->input->post('id_barang[]');
			$jumlah 	= $this->input->post('jumlah[]');
			$keterangan = $this->input->post('keterangan_barang[]');

			if (count($barang) > 0) {
				$save_data = [
					'asal_posko' 		=> $posko_id,
					'nama_donatur' 		=> $this->input->post('nama_donatur'),
					'alamat_donatur' 	=> $this->input->post('alamat_donatur'),
					'phone_donatur' 	=> $this->input->post('phone_donatur'),
					'keterangan' 		=> $this->input->post('keterangan'),
					'tanggal' 			=> $this->input->post('tanggal'),
					'waktu' 			=> $this->input->post('waktu'),
					'kecamatan_id' 		=> $this->input->post('kecamatan_id'),
					'kelurahan_id' 		=> $this->input->post('kelurahan_id'),
					'created_at' 		=> date('Y-m-d H:i:s'),
					'user_created' 		=> get_user_data('id'),
				];
	
				$save_barangmasuk = $id = $this->model_barangmasuk->store($save_data);
	
				if ($save_barangmasuk) {
					$data_barang_masuk = [];

					for ($i=0; $i < count($barang); $i++) {
						$data_barang_posko = $this->model_barangmasuk->query_barangmasuk_posko($posko_id, $barang[$i])->result();

						if (count($data_barang_posko) >= 1) {
							$keterangan_masuk = $keterangan[$i];
						}else{
							if (empty($keterangan[$i])) {
								$keterangan_masuk = 'Saldo Awal';
							}else{
								$keterangan_masuk = $keterangan[$i].' (Saldo Awal)';
							}
						}

						$data_barang_masuk = [
							'barangmasuk_id' 					=> $id,
							'barang_id' 						=> $barang[$i],
							'barangmasuk_detail_jumlah' 		=> $jumlah[$i],
							'barangmasuk_detail_keterangan' 	=> $keterangan_masuk,
							'barangmasuk_detail_user_created' 	=> get_user_data('id'),
							'barangmasuk_detail_created_at' 	=> date('Y-m-d H:i:s'),
						];

						$this->db->insert('barangmasuk_detail', $data_barang_masuk);

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

					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $save_barangmasuk;
						$this->data['message'] = cclang('success_save_data_stay', [
							anchor('administrator/barangmasuk/edit/' . $save_barangmasuk, 'Edit Barangmasuk'),
							anchor('administrator/barangmasuk', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_save_data_redirect', [
							anchor('administrator/barangmasuk/edit/' . $save_barangmasuk, 'Edit Barangmasuk')
						]), 'success');
	
						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/barangmasuk');
					}

				} else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/barangmasuk');
					}
				}
			}else{
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_change');
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
		/**
	* Update view Barangmasuks
	*
	* @var $id String
	*/
	public function edit($id) {
		$this->is_allowed('barangmasuk_update');

		$this->data['barangmasuk'] = $this->model_barangmasuk->find($id);

		// echo json_encode($this->data);
		// exit;

		$this->template->title('Barang Masuk Update');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_update', $this->data);
	}

	/**
	* Update Barangmasuks
	*
	* @var $id String
	*/
	public function edit_save($id) {
		if (!$this->is_allowed('barangmasuk_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('asal_posko', 'Asal Posko', 'trim|required');
		$this->form_validation->set_rules('nama_donatur', 'Nama Donatur', 'trim|required');
		$this->form_validation->set_rules('alamat_donatur', 'Alamat Donatur', 'trim|required');
		$this->form_validation->set_rules('phone_donatur', 'No Telepon Donatur', 'trim|required');
		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');
		$this->form_validation->set_rules('kecamatan_id', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('kelurahan_id', 'Kelurahan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$posko_id 	= $this->input->post('asal_posko');
			$barang 	= $this->input->post('id_barang[]');
			$jumlah 	= $this->input->post('jumlah[]');
			$keterangan = $this->input->post('keterangan_barang[]');

			if (count($barang) > 0) {
				$save_data = [
					'asal_posko' 		=> $posko_id,
					'nama_donatur' 		=> $this->input->post('nama_donatur'),
					'alamat_donatur' 	=> $this->input->post('alamat_donatur'),
					'phone_donatur' 	=> $this->input->post('phone_donatur'),
					'keterangan' 		=> $this->input->post('keterangan'),
					'tanggal' 			=> $this->input->post('tanggal'),
					'waktu' 			=> $this->input->post('waktu'),
					'kecamatan_id' 		=> $this->input->post('kecamatan_id'),
					'kelurahan_id' 		=> $this->input->post('kelurahan_id'),
				];

				$data_barangmasuk 	= $this->model_barangmasuk->query_barangmasuk($id)->result();

				foreach ($data_barangmasuk as $item) {
					$posko_masuk 	= $item->asal_posko;
					$barang_masuk 	= $item->barang_id;
					$jumlah_masuk 	= $item->barangmasuk_detail_jumlah;

					$data_stok_posko = $this->db->get_where('stok_posko', ['posko_id' => $posko_masuk, 'barang_id' => $barang_masuk])->row();
					$stok_posko 	= $data_stok_posko->stok_posko_total;

					$hasil 			= $stok_posko - $jumlah_masuk;

					$this->db->update('stok_posko', ['stok_posko_total' => $hasil], ['posko_id' => $posko_masuk, 'barang_id' => $barang_masuk]);
					$this->db->delete('barangmasuk_detail', ['barangmasuk_id' => $id, 'barang_id' => $barang_masuk]);
				}

				$save_barangmasuk = $this->model_barangmasuk->change($id, $save_data);

				$data_barang_masuk = [];

				for ($i=0; $i < count($barang); $i++) {
					$data_barang_masuk = [
						'barangmasuk_id' 					=> $id,
						'barang_id' 						=> $barang[$i],
						'barangmasuk_detail_jumlah' 		=> $jumlah[$i],
						'barangmasuk_detail_keterangan' 	=> $keterangan[$i],
						'barangmasuk_detail_user_created' 	=> get_user_data('id'),
						'barangmasuk_detail_created_at' 	=> date('Y-m-d H:i:s'),
					];

					$this->db->insert('barangmasuk_detail', $data_barang_masuk);

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

				// if ($save_barangmasuk) {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = true;
						$this->data['id'] 	   = $id;
						$this->data['message'] = cclang('success_update_data_stay', [
							anchor('administrator/barangmasuk', ' Go back to list')
						]);
					} else {
						set_message(
							cclang('success_update_data_redirect', [
						]), 'success');

						$this->data['success'] = true;
						$this->data['redirect'] = base_url('administrator/barangmasuk');
					}
				/* } else {
					if ($this->input->post('save_type') == 'stay') {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
					} else {
						$this->data['success'] = false;
						$this->data['message'] = cclang('data_not_change');
						$this->data['redirect'] = base_url('administrator/barangmasuk');
					}
				} */
			}else{
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_change');
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = 'Opss validation failed';
			$this->data['errors'] = $this->form_validation->error_array();
		}

		$this->response($this->data);
	}
	
	/**
	* delete Barangmasuks
	*
	* @var $id String
	*/
	public function delete($id = null) {
		$this->is_allowed('barangmasuk_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);

			$this->db->delete('barangmasuk_detail', ['barangmasuk_id' => $id]);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);

				$this->db->delete('barangmasuk_detail', ['barangmasuk_id' => $id]);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'barangmasuk'), 'success');
        } else {
            set_message(cclang('error_delete', 'barangmasuk'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Barangmasuks
	*
	* @var $id String
	*/
	public function view($id) {
		$this->is_allowed('barangmasuk_view');

		$this->data['barangmasuk'] 			= $this->model_barangmasuk->join_avaiable()->filter_avaiable()->find($id);
		// $this->data['detailbarangmasuk'] 	= $this->model_barangmasuk->query_detail_barang_masuk($id)->result();

		$this->template->title('Barang Masuk Detail');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_view', $this->data);
	}
	
	/**
	* delete Barangmasuks
	*
	* @var $id String
	*/
	private function _remove($id) {
		$barangmasuk = $this->model_barangmasuk->find($id);
		
		return $this->model_barangmasuk->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export() {
		$this->is_allowed('barangmasuk_export');

		$this->model_barangmasuk->export_excel('Rekap Data Barang Masuk');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf() {
		$this->is_allowed('barangmasuk_export');

		$this->model_barangmasuk->pdf('barangmasuk', 'barangmasuk');
	}


	public function single_pdf($id = null) {
		$this->is_allowed('barangmasuk_export');

		$table = $title = 'barangmasuk';
		$this->load->library('HtmlPdf');
      
        $config = array(
            'orientation' => 'p',
            'format' => 'a4',
            'marges' => array(5, 5, 5, 5)
        );

        $this->pdf = new HtmlPdf($config);
        $this->pdf->setDefaultFont('stsongstdlight'); 

        $result = $this->db->get($table);
       
        $data = $this->model_barangmasuk->find($id);
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
		if (!$this->is_allowed('barangmasuk_list', false)) {
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
		if (!$this->is_allowed('barangmasuk_list', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);

			exit;
		}

		$results = db_get_all_data('kelurahan', ['kecamatan_id' => $id]);
		$this->response($results);	
	}

	public function coba() {
		// $posko_id 	= $this->input->get('posko');
		$barang 	= $this->input->get('id');

		// $this->model_barangmasuk->query_barangmasuk($posko_id, $barang)->result();
		// $this->db->join('barangmasuk', 'barangmasuk.id_barangmasuk = barangmasuk_detail.barangmasuk_id', 'LEFT');
		// $this->db->where('barangmasuk.id_barangmasuk', $barang);
		// $query = $this->db->get('barangmasuk_detail');


		// $data_stok_posko 	= [];
		$data_barangmasuk 	= $this->model_barangmasuk->query_barangmasuk($barang)->result();
		
		// echo $this->db->last_query();
		// exit;

		foreach ($data_barangmasuk as $item) {
			$posko_masuk 	= $item->asal_posko;
			$barang_masuk 	= $item->barang_id;
			$jumlah_masuk 	= $item->barangmasuk_detail_jumlah;

			$data_stok_posko = $this->db->get_where('stok_posko', ['posko_id' => $posko_masuk, 'barang_id' => $barang_masuk])->row();
			$stok_posko 	= $data_stok_posko->stok_posko_total;

			$hasil 			= $stok_posko-$jumlah_masuk;

			$this->data[] = [
				'jumlah_masuk' 	=> $jumlah_masuk,
				'stok_posko' 	=> $stok_posko,
				'hasil' 		=> $hasil,
			];
		}
		
		echo json_encode($this->data);
	}

	
}


/* End of file barangmasuk.php */
/* Location: ./application/controllers/administrator/Barangmasuk.php */