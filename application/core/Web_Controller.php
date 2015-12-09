<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Web_controller extends MY_Controller {

    private $login;
    public $dashboard;
    public $logout_url;

    function __construct() {
        parent::__construct();
        $this->login = 'welcome/login';
        $this->dashboard = 'dashboard';
        $this->logout_url = 'welcome/login/logout';
    }

    protected function is_logged_in($check = FALSE) {

        if ($check == FALSE) {
            redirect($this->login);
        }
    }

    protected function title($title) {
        $this->data['title'] = trim($title);
    }

    protected function count_row_of_table($table, $column = 'is_active', $column_val = 1) {
        $this->db->where($column, $column_val);
        return $this->db->count_all_results($table);
    }

//    public function convert_number_to_words($number) {
//        $hyphen = '-';
//        $conjunction = ' and ';
//        $separator = ' ';
//        $negative = 'negative ';
//        $decimal = ' point ';
//        $dictionary = array(
//            0 => 'zero',
//            1 => 'one',
//            2 => 'two',
//            3 => 'three',
//            4 => 'four',
//            5 => 'five',
//            6 => 'six',
//            7 => 'seven',
//            8 => 'eight',
//            9 => 'nine',
//            10 => 'ten',
//            11 => 'eleven',
//            12 => 'twelve',
//            13 => 'thirteen',
//            14 => 'fourteen',
//            15 => 'fifteen',
//            16 => 'sixteen',
//            17 => 'seventeen',
//            18 => 'eighteen',
//            19 => 'nineteen',
//            20 => 'twenty',
//            30 => 'thirty',
//            40 => 'fourty',
//            50 => 'fifty',
//            60 => 'sixty',
//            70 => 'seventy',
//            80 => 'eighty',
//            90 => 'ninety',
//            100 => 'hundred',
//            1000 => 'thousand',
//            1000000 => 'million',
//            1000000000 => 'billion',
//            1000000000000 => 'trillion',
//            1000000000000000 => 'quadrillion',
//            1000000000000000000 => 'quintillion'
//        );
//
//        if (!is_numeric($number)) {
//            return false;
//        }
//
//        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
//            // overflow
//            trigger_error(
//                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
//            );
//            return false;
//        }
//
//        if ($number < 0) {
//            return $negative . $this->convert_number_to_words(abs($number));
//        }
//
//        $string = $fraction = null;
//
//        if (strpos($number, '.') !== false) {
//            list($number, $fraction) = explode('.', $number);
//        }
//
//        switch (true) {
//            case $number < 21:
//                $string = $dictionary[$number];
//                break;
//            case $number < 100:
//                $tens = ((int) ($number / 10)) * 10;
//                $units = $number % 10;
//                $string = $dictionary[$tens];
//                if ($units) {
//                    $string .= $hyphen . $dictionary[$units];
//                }
//                break;
//            case $number < 1000:
//                $hundreds = $number / 100;
//                $remainder = $number % 100;
//                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
//                if ($remainder) {
//                    $string .= $conjunction . $this->convert_number_to_words($remainder);
//                }
//                break;
//            default:
//                $baseUnit = pow(1000, floor(log($number, 1000)));
//                $numBaseUnits = (int) ($number / $baseUnit);
//                $remainder = $number % $baseUnit;
//                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
//                if ($remainder) {
//                    $string .= $remainder < 100 ? $conjunction : $separator;
//                    $string .= $this->convert_number_to_words($remainder);
//                }
//                break;
//        }
//
//        if (null !== $fraction && is_numeric($fraction)) {
//            $string .= $decimal;
//            $words = array();
//            foreach (str_split((string) $fraction) as $number) {
//                $words[] = $dictionary[$number];
//            }
//            $string .= implode(' ', $words);
//        }
//
//        return $string;
//    }

    /*
      Why function : This function is for language change.
      @  $short_code = this parameter is for detect language
     */

//    public function change_language($short_code) {
//        if ($short_code == 'bn') {
//            $language = 'bangla';
//        } else {
//            $language = 'english';
//        }
//        $this->change_language_action($short_code, $language);
//        $current_url = $this->session->userdata('current_url');
//        redirect($current_url);
//    }

    /*
      Why function : This function is for language change action .
      @  $short_code = this parameter is for detect language
      @  $language = this parameter is the language name
     */

//    public function change_language_action($short_code, $language) {
//        $this->session->set_userdata('short_code', $short_code);
//        $this->session->set_userdata('language', $language);
//        $this->lang->load($short_code, $language);
//    }
//
//    public function permissionData() {
//        $this->load->model("dashboard/m_dashboard");
//        $permissionData = $this->m_dashboard->permisionData($this->session->userdata('roleId'));
//        $permissionArray = array();
//        if (!empty($permissionData)) {
//            foreach ($permissionData as $permissionVal) {
//                $pageName = $permissionVal['pageName'];
//                unset($permissionVal['pageName']);
//                $permissionArray[$pageName] = $permissionVal;
//            }
//        }
//
//        return $permissionArray;
//    }
//    public function reCheckForCokie($pageVal)
//    {
//
//        $this->load->model("dashboard/m_dashboard");
//        $cookieVal = base64_decode($this->input->cookie('contms'));
//
//        if (isset($cookieVal) && !empty($cookieVal)) {
//            $result = $this->m_dashboard->checkUser($cookieVal);
//            $result = $result->result_array();
//            if (!empty($result[0]['u_id']) && isset($result[0]['u_id'])) {
//                $result = $this->m_dashboard->user_auth($result[0]['user_name'], $result[0]['user_pass']);
//                if (!empty($result[0]->u_id) && isset($result[0]->u_id)) {
//                    $user_info = array(
//                        'user_id' => $result[0]->u_id,
//                        'username' => $result[0]->user_name,
//                        'auth_module_id' => $result[0]->auth_module_id,
//                        'auth_module_name' => $result[0]->auth_module_name,
//                        'v_code' => $result[0]->v_code,
//                        'logged_in' => TRUE
//                    );
//                    $this->session->set_userdata($user_info);
//                    $this->is_logged_in($this->session->userdata('logged_in'));
//                    redirect($pageVal);
//                } else {
//                    redirect('login');
//                }
//            } else {
//                redirect('login');
//            }
//        } else {
//            redirect('login');
//        }
//    }
}
