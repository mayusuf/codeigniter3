<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends Web_controller {

    public $page_title = "Setup";

    function __construct() {
        parent::__construct();
        $this->is_logged_in($this->session->userdata('logged_in'));
        $this->title($this->page_title);
        $this->load->model("m_setup");
    }

    public function index() {
        $this->load->view('v_setup');
    }

    public function load_setup_data() {

        $this->setOutputMode(NORMAL);

        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

        // DB table to use
        $table = 'setup';

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";

        $aColumns = array('f_name', 'l_name', 'address', 'city', 'country', 'phone', 'age', 'action');

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        /* DB table to use */
        $sTable = "setup";

        /*
         * Paging
         */
        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        /*
         * Ordering
         */
        $aColumnsdup = array('f_name', 'l_name', 'address', 'city', 'country', 'phone', 'age', 'action');
        unset($aColumnsdup[7]);
        
        
        
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, true);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, true);

                if ($bSortable == 'true') {
                    $this->db->order_by($aColumnsdup[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";

        $aColumnsdup = array_values($aColumnsdup);
        $aColumns = array_values($aColumns);
        
        $mainSearch = $aColumns;
        unset($mainSearch[7]);
        
        if ($_GET['sSearch'] != "") {
            $sWhere .= "";
            for ($i = 0; $i < count($aColumnsdup); $i++) {
                $sWhere .= $aColumnsdup[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumnsdup); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere != "") {
                    $sWhere .= " AND ";
                }

                $sWhere .= $aColumnsdup[$i] . " LIKE '%" . ($_GET['sSearch_' . $i]) . "%' "; //mysql_real_escape_string($_GET['sSearch_' . $i])
            }
        }

        /*
         * SQL queries
         * Get data to display
         */
        $mainSearch[] = 'id';
        $this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $mainSearch)), false);
        $rResult = $this->m_setup->get_all_setup($sWhere);



        /* Data set length after filtering */
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;

        /* Total data set length */
        $iTotal = $this->count_row_of_table($sTable);

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($this->input->get_post('sEcho')),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

       
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            $edit = '';
            $delete = '';
            foreach ($aColumns as $col) {
                $id = $aRow['id'];
                if ($col == "action") {
                    $edit = "<a id='edit_" . $id . "' title='Edit' href='javascript:void(0)'><span class='glyphicon glyphicon-pencil'></span></a>";
                    $delete = "<a id='delete_" . $id . "' title='Delete' href='javascript: void(0)'><span class='glyphicon glyphicon-remove'></span></a> <span id='checkbox' style='background-color: #2A6496;margin-left:5px;'><input id='$id' class='chk' type='checkbox'></span>";
                    $row[] = "<span class='settings'>$edit $delete<span>";
                    continue;
                }

                if (strpos($col, '.') !== false) {
                    $explodedArray = explode('.', $col);
                    $col = $explodedArray[1];
                }

                $row[] = $aRow[$col];
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

}
