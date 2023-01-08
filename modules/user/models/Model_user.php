<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_user extends MY_Model {
	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_users';
	private $field_search 	= array('email', 'username', 'full_name');

	public function __construct() {
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = '', $field = '') {
		$iterasi 	= 1;
        $num 		= count($this->field_search);
        $where 		= NULL;
        $q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	            } else if ($iterasi == $num) {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

        $this->join_available();
		$this->db->where($where);
		$this->db->order_by($this->primary_key, 'DESC');
		
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = '', $field = '', $limit = 0, $offset = 0) {
		$iterasi 	= 1;
        $num 		= count($this->field_search);
        $where 		= NULL;
        $q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	            } else if ($iterasi == $num) {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

        $this->join_available();
		$this->db->where($where);
		$this->db->order_by($this->primary_key, 'DESC');
        $this->db->limit($limit, $offset);
        $this->sortable();
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function get_group_user($user_id = false) {
		if ($user_id === false) {
			$user_id = get_user_data('id');
		}

		$result_group_user = [];

		$query = $this->db->get_where('aauth_user_to_group', ['user_id' => $user_id]);
		foreach ($query->result() as $row) {
			$result_group_user[] = $row->group_id;
		}

		return $result_group_user;
	}


	public function get_user_oauth($email = null, $provider = null) {
		$this->db->where('email', $email);
		$this->db->where('oauth_provider', $provider);
		$query = $this->db->get($this->table_name);

		return $query->result();
	}
	
	public function join_available() {
		$this->db->select('aauth_users.*,users.*,posko.*,');
		$this->db->join('users', 'aauth_users.id = users.aauth_user_id', 'LEFT');
		$this->db->join('posko', 'users.posko_id = posko.posko_id', 'LEFT');
		
		return $this;
	}

	public function detail_group_user($id) {
		$groups 		= db_get_all_data('aauth_groups');
		$groups_user 	= $this->get_group_user($id);

		$user_groups = [];
		foreach ($groups as $group) {
			if (array_search($group->id, $groups_user) !== false) {
				$user_groups[] = $group->name;
			}
		}

		return implode(', ', $user_groups);
	}

	public function detail_user($id) {
        $query = $this->db->where('aauth_user_id', $id)->get('users');

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
	}

}


/* End of file Model_user.php */
/* Location: ./application/models/Model_user.php */