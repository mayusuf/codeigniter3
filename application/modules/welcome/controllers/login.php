<?php

/**
 * Description of login
 *
 * @author yusuf
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class login extends Web_Controller {

    public $page_title = "Login";

    function __construct() {
        parent::__construct();
        $this->title($this->page_title);
        $this->load->model('m_login');
    }

    function index() {
        $this->load->view('v_login');
    }

    public function loginProcess() {

        $postArray = $this->input->post();
        
        if (!empty($postArray)) {
            $result = $this->m_login->user_auth($postArray['user_email'], md5($postArray['user_pass']));
            if (!empty($result[0]->u_id)) {
                $user_info = array(
                    'user_id' => $result[0]->u_id,
                    'user_email' => $result[0]->u_email,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($user_info);
                $this->is_logged_in($this->session->userdata('logged_in'));
                redirect('setup');
            } else {
                redirect();
            }
        }
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect();
    }
}
