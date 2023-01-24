<?php
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_barangposko extends MY_Model {
	private $primary_key 	= 'id_barang';
	private $table_name 	= 'barang';
	public $field_search 	= ['nama_barang', 'stok', 'keterangan', 'satuan.nama_satuan'];
	public $sort_option 	= ['id_barang', 'DESC'];

	public function __construct() {
		$config = array(
			'primary_key'   => $this->primary_key,
			'table_name'    => $this->table_name,
			'field_search'  => $this->field_search,
			'sort_option'   => $this->sort_option,
		 );

		parent::__construct($config);
	}

	function posko_barang_masuk($posko_id){
        $this->db->select('barang.id_barang AS id_barang,
							barang.nama_barang AS nama_barang,
							SUM(barangmasuk_detail.barangmasuk_detail_jumlah) as jumlah_masuk,
							satuan.nama_satuan AS nama_satuan');
		$this->db->join('barangmasuk', 'barangmasuk.id_barangmasuk = barangmasuk_detail.barangmasuk_id', 'LEFT');
		$this->db->join('barang', 'barang.id_barang = barangmasuk_detail.barang_id', 'LEFT');
		$this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'LEFT');
		$this->db->where('barangmasuk.asal_posko', $posko_id);
		$this->db->group_by('barang.id_barang');

		$query = $this->db->get('barangmasuk_detail');

		return $query;
    }

	public function posko_barang_keluar($posko_id) {
		$this->db->select('barang.id_barang AS id_barang,
							barang.nama_barang AS nama_barang,
							SUM( barangkeluar_detail.barangkeluar_detail_jumlah ) AS jumlah_keluar,
							satuan.nama_satuan AS nama_satuan ');
		$this->db->join('barangkeluar', 'barangkeluar.id_barangkeluar = barangkeluar_detail.barangkeluar_id', 'LEFT');
		$this->db->join('barang', 'barang.id_barang = barangkeluar_detail.barang_id', 'LEFT');
		$this->db->join('satuan', 'satuan.id_satuan = barang.satuan', 'LEFT');
		$this->db->where('barangkeluar.tujuan_posko', $posko_id);
		$this->db->group_by('barang.id_barang');

		$query = $this->db->get('barangkeluar_detail');
		
		return $query;
	}

}

/* End of file Model_barang.php */
/* Location: ./application/models/Model_barang.php */