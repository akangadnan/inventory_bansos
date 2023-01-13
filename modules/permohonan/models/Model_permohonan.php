<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_permohonan extends MY_Model {
	private $primary_key 	= 'permohonan_id';
	private $table_name 	= 'permohonan';
	public $field_search 	= ['permohonan_tanggal', 'posko_id', 'permohonan_pemohon', 'permohonan_keterangan', 'permohonan_status', 'permohonan_respon_posko', 'permohonan_mengetahui', 'posko.posko_nama', 'users.user_nama_lengkap'];
	public $sort_option 	= ['permohonan_id', 'DESC'];
	
	public function __construct() {
		$config = array(
			'primary_key' 	=> $this->primary_key,
			'table_name' 	=> $this->table_name,
			'field_search' 	=> $this->field_search,
			'sort_option' 	=> $this->sort_option,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null) {
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "permohonan.".$field;

				if (strpos($field, '.')) {
					$f_search = $field;
				}

				if ($iterasi == 1) {
					$where .=  $f_search . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " .  $f_search . " LIKE '%" . $q . "%' ";
				}

				$iterasi++;
			}

			$where = '('.$where.')';
		} else {
			$where .= "(" . "permohonan.".$field . " LIKE '%" . $q . "%' )";
		}

		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = []) {
		$iterasi = 1;
		$num = count($this->field_search);
		$where = NULL;
		$q = $this->scurity($q);
		$field = $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "permohonan.".$field;
				if (strpos($field, '.')) {
					$f_search = $field;
				}

				if ($iterasi == 1) {
					$where .= $f_search . " LIKE '%" . $q . "%' ";
				} else {
					$where .= "OR " .$f_search . " LIKE '%" . $q . "%' ";
				}
				$iterasi++;
			}

			$where = '('.$where.')';
		} else {
			$where .= "(" . "permohonan.".$field . " LIKE '%" . $q . "%' )";
		}

		if (is_array($select_field) AND count($select_field)) {
			$this->db->select($select_field);
		}
		
		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$this->db->limit($limit, $offset);
		
		$this->sortable();
		
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function join_avaiable() {
		$this->db->join('posko', 'posko.posko_id = permohonan.posko_id', 'LEFT');
		$this->db->join('users', 'users.user_id = permohonan.permohonan_mengetahui', 'LEFT');
		
		$this->db->select('posko.posko_nama,users.user_nama_lengkap,permohonan.*,posko.posko_nama as posko_posko_nama,posko.posko_nama as posko_nama,users.user_nama_lengkap as users_user_nama_lengkap,users.user_nama_lengkap as user_nama_lengkap');


		return $this;
	}

	public function filter_avaiable() {

		if (!$this->aauth->is_admin()) {
			$this->db->where($this->table_name.'.permohonan_user_created', get_user_data('id'));
		}

		return $this;
	}

}

/* End of file Model_permohonan.php */
/* Location: ./application/models/Model_permohonan.php */