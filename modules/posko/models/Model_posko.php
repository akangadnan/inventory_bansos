<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_posko extends MY_Model {

    private $primary_key    = 'posko_id';
    private $table_name     = 'posko';
    public $field_search   = ['kecamatan_id', 'kelurahan_id', 'posko_nama', 'posko_penanggung_jawab', 'posko_pic', 'kecamatan.kecamatan_nama', 'kelurahan.kelurahan_nama', 'users.user_nama_lengkap', 'users.user_nama_lengkap'];
    public $sort_option = ['posko_id', 'DESC'];
    
    public function __construct()
    {
        $config = array(
            'primary_key'   => $this->primary_key,
            'table_name'    => $this->table_name,
            'field_search'  => $this->field_search,
            'sort_option'   => $this->sort_option,
         );

        parent::__construct($config);
    }

    public function count_all($q = null, $field = null)
    {
        $iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);

        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = "posko.".$field;

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
            $where .= "(" . "posko.".$field . " LIKE '%" . $q . "%' )";
        }

        $this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $query = $this->db->get($this->table_name);

        return $query->num_rows();
    }

    public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
    {
        $iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
        $field = $this->scurity($field);

        if (empty($field)) {
            foreach ($this->field_search as $field) {
                $f_search = "posko.".$field;
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
            $where .= "(" . "posko.".$field . " LIKE '%" . $q . "%' )";
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
        $this->db->join('kecamatan', 'kecamatan.kecamatan_id = posko.kecamatan_id', 'LEFT');
        $this->db->join('kelurahan', 'kelurahan.kelurahan_id = posko.kelurahan_id', 'LEFT');
        $this->db->join('users', 'users.user_id = posko.posko_user_created', 'LEFT');
        $this->db->join('users penanggung_jawab', 'penanggung_jawab.user_id = posko.posko_penanggung_jawab', 'LEFT');
		$this->db->join('users pic', 'pic.user_id = posko.posko_pic', 'LEFT');
        
        $this->db->select('posko.*,
							posko.posko_nama AS nama_posko,
							kecamatan.kecamatan_nama AS kecamatan_nama,
							kelurahan.kelurahan_nama AS kelurahan_nama,
							penanggung_jawab.user_nama_lengkap AS nama_lengkap_penanggung_jawab,
							pic.user_nama_lengkap AS nama_lengkap_pic');
		
		$this->db->group_by('posko.posko_id');
							


        return $this;
    }

    public function filter_avaiable() {

        if (!$this->aauth->is_admin()) {
            $this->db->where($this->table_name.'.posko_user_created', get_user_data('id'));
        }

        return $this;
    }

}

/* End of file Model_posko.php */
/* Location: ./application/models/Model_posko.php */