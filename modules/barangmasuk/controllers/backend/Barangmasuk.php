<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Barangmasuk Controller
*| --------------------------------------------------------------------------
*| Barangmasuk site
*|
*/
class Barangmasuk extends Admin	
{
	
	public function __construct()
	{
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
	public function index($offset = 0)
	{
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
	public function add()
	{
		$this->is_allowed('barangmasuk_add');

		$this->template->title('Barang Masuk New');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_add', $this->data);
	}

	/**
	* Add New Barangmasuks
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('barangmasuk_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		

		$this->form_validation->set_rules('asal_posko', 'Asal Poko', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal', 'trim|required');
		

		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		

		$this->form_validation->set_rules('jumlah', 'Jumlah Stok', 'trim|required|max_length[12]');
		

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		

		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');
		

		

		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');

			$save_data = [
				'asal_posko' => $this->input->post('asal_posko'),
				'asal' => $this->input->post('asal'),
				'id_barang' => $id_barang,
				'jumlah' => $jumlah,
				'keterangan' => $this->input->post('keterangan'),
				'tanggal' => $this->input->post('tanggal'),
				'waktu' => $this->input->post('waktu'),
				'created_at' => date('Y-m-d H:i:s'),
				'user_created' => get_user_data('id'),
			];
			
			
			$save_barangmasuk = $id = $this->model_barangmasuk->store($save_data);

			$barang = $this->db->where('id_barang', $id_barang)->from('barang')->get()->row();
			$stok 	= ($barang->stok + $jumlah);

			$update_barang = [
				'stok' => $stok,
			];
			
			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_barangmasuk) {
				
				$id = $save_barangmasuk;
				
				
					
				
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
	public function edit($id)
	{
		$this->is_allowed('barangmasuk_update');

		$this->data['barangmasuk'] = $this->model_barangmasuk->find($id);

		$this->template->title('Barang Masuk Update');
		$this->render('backend/standart/administrator/barangmasuk/barangmasuk_update', $this->data);
	}

	/**
	* Update Barangmasuks
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('barangmasuk_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$this->form_validation->set_rules('asal_posko', 'Asal Posko', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal', 'trim|required');
		

		$this->form_validation->set_rules('id_barang', 'Nama Barang', 'trim|required');
		

		$this->form_validation->set_rules('jumlah', 'Jumlah Stok', 'trim|required|max_length[12]');
		

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		

		$this->form_validation->set_rules('waktu', 'Waktu', 'trim|required');
		

		
		if ($this->form_validation->run()) {
			$id_barang 	= $this->input->post('id_barang');
			$jumlah 	= $this->input->post('jumlah');
		
			$save_data = [
				'asal' 			=> $this->input->post('asal'),
				'id_barang' 	=> $id_barang,
				'jumlah' 		=> $jumlah,
				'keterangan' 	=> $this->input->post('keterangan'),
				'tanggal' 		=> $this->input->post('tanggal'),
				'waktu' 		=> $this->input->post('waktu'),
			];

			$barang_masuk 	= $this->db->where('id_barangmasuk', $id)->get('barangmasuk')->row();
			$barang 		= $this->db->where('id_barang', $id_barang)->get('barang')->row();
			$jumlah_masuk 	= $barang_masuk->jumlah;

			if ($jumlah_masuk > $jumlah) {
				$sisa_jumlah 	= $jumlah_masuk - $jumlah;
				$stok_barang 	= $barang->stok - $sisa_jumlah;
			}else{
				$sisa_jumlah 	= $jumlah - $jumlah_masuk;
				$stok_barang 	= $barang->stok + $sisa_jumlah;
			}

			$update_barang = [
				'stok' => $stok_barang,
			];

			$save_barangmasuk = $this->model_barangmasuk->change($id, $save_data);

			$save_barang = $this->model_barang->change($id_barang, $update_barang);

			if ($save_barangmasuk) {
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
			$barang_masuk 	= $this->db->where('id_barangmasuk', $id)->get('barangmasuk')->row();

			$barang 		= $this->db->where('id_barang', $barang_masuk->id_barang)->get('barang')->row();

			if ($barang->stok > $barang_masuk->jumlah) {
				$stok_akhir 	= $barang->stok - $barang_masuk->jumlah;
			}else{
				$stok_akhir 	= $barang_masuk->jumlah - $barang->stok;
			}

			$update_barang = [
				'stok' => $stok_akhir,
			];

			$save_barang = $this->model_barang->change($barang_masuk->id_barang, $update_barang);

			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$barang_masuk 	= $this->db->where('id_barangmasuk', $id)->get('barangmasuk')->row();
	
				$barang 		= $this->db->where('id_barang', $barang_masuk->id_barang)->get('barang')->row();
	
				if ($barang->stok > $barang_masuk->jumlah) {
					$stok_akhir 	= $barang->stok - $barang_masuk->jumlah;
				}else{
					$stok_akhir 	= $barang_masuk->jumlah - $barang->stok;
				}
	
				$update_barang = [
					'stok' => $stok_akhir,
				];
	
				$save_barang = $this->model_barang->change($barang_masuk->id_barang, $update_barang);

				$remove = $this->_remove($id);
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
	public function view($id)
	{
		$this->is_allowed('barangmasuk_view');

		$this->data['barangmasuk'] = $this->model_barangmasuk->join_avaiable()->filter_avaiable()->find($id);

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
	public function export_pdf()
	{
		$this->is_allowed('barangmasuk_export');

		$this->model_barangmasuk->pdf('barangmasuk', 'barangmasuk');
	}


	public function single_pdf($id = null)
	{
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

	public function ajax_barang($id = null)
	{
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

	
}


/* End of file barangmasuk.php */
/* Location: ./application/controllers/administrator/Barangmasuk.php */