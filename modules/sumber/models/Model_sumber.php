<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sumber extends MY_Model {
	private $primary_key 	= 'id_sumber';
	private $table_name 	= 'sumber';
	public $field_search 	= ['nama_sumber'];
	public $sort_option 	= ['id_sumber', 'DESC'];
	
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
		$iterasi 	= 1;
		$num 		= count($this->field_search);
		$where 		= NULL;
		$q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "sumber.".$field;

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
			$where .= "(" . "sumber.".$field . " LIKE '%" . $q . "%' )";
		}

		$this->join_avaiable()->filter_avaiable();
		$this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = []) {
		$iterasi 	= 1;
		$num 		= count($this->field_search);
		$where 		= NULL;
		$q 			= $this->scurity($q);
		$field 		= $this->scurity($field);

		if (empty($field)) {
			foreach ($this->field_search as $field) {
				$f_search = "sumber.".$field;
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
			$where .= "(" . "sumber.".$field . " LIKE '%" . $q . "%' )";
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
		$this->db->select('sumber.*');

		return $this;
	}

	public function filter_avaiable() {
		if (!$this->aauth->is_admin()) {}

		return $this;
	}

	public function export_excel($subject = 'file', $field_search = []) {
        $result = $this->db->query("SELECT @no:=@no+1 AS 'no', `sumber`.`nama_sumber` FROM `sumber`, ( SELECT @NO := 0 ) AS no_urut");
		
        $this->load->library('excel');

        $this->excel->setActiveSheetIndex(0);

        $fields = $result->list_fields();

        $fields = array_unique($fields);

        $alphabet = 'ABCDEFGHIJKLMOPQRSTUVWXYZ';
        $alphabet_arr = str_split($alphabet);
        $column = [];

        foreach ($alphabet_arr as $alpha) {
            $column[] =  $alpha;
        }

        foreach ($alphabet_arr as $alpha) {
            foreach ($alphabet_arr as $alpha2) {
                $column[] =  $alpha . $alpha2;
            }
        }
        foreach ($alphabet_arr as $alpha) {
            foreach ($alphabet_arr as $alpha2) {
                foreach ($alphabet_arr as $alpha3) {
                    $column[] =  $alpha . $alpha2 . $alpha3;
                }
            }
        }

        foreach ($column as $col) {
            $this->excel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
        }

        $col_total = $column[count($fields) - 1];

        //styling
        $this->excel->getActiveSheet()->getStyle('A4:' . $col_total . '4')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '8EA9DB')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                )
            )
        );

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('000000');

        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->setCellValue('A1', 'DATA SUMBER');

		$this->excel->getActiveSheet()->setCellValue('A2', date('l, d F Y'));

        $this->excel->getActiveSheet()->getStyle('A4:' . $col_total . '4')->getFont()->setColor($phpColor)->setBold(true);

        $this->excel->getActiveSheet()->getRowDimension(4)->setRowHeight(20);

        $this->excel->getActiveSheet()->getStyle('A4:' . $col_total . '4')->getAlignment()->setWrapText(true);

        $col = 0;
        foreach ($fields as $field) {
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, ucwords(str_replace('_', ' ', $field)));
            $col++;
        }

        $row = 5;
        foreach ($result->result() as $data) {
            $col = 0;
            foreach ($fields as $field) {
                $this->excel->getActiveSheet()->setCellValueExplicit($column[$col] . $row, $data->$field, PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
            }

            $row++;
        }

        //set border
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->excel->getActiveSheet()->getStyle('A4:' . $col_total . '' . $row)->applyFromArray($styleArray);

        $this->excel->getActiveSheet()->setTitle(ucwords($subject));

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . ucwords($subject) . '-' . date('Y-m-d') . '.xls');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

}

/* End of file Model_sumber.php */
/* Location: ./application/models/Model_sumber.php */