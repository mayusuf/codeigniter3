<?php

class m_login extends CI_Model {
    
    public $table = 'user_auth';
    public $email = 'u_email';
    public $pass = 'u_pass';
    
    function __construct() {
        parent::__construct();
        $this->load->library("Db_lib");
    }

    function user_auth($user_email, $pass) {

        $this->db->select('*')
                ->from($this->table)
                ->where($this->email, $user_email)
                ->where($this->pass, $pass);
        
        $result = $this->db->get()->result();

        return $result;
    }

}
