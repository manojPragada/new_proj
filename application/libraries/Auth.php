<?php

/*
 * This Auth Version 2.0
 * Innovated by Manikanta
 * Email : manikantak49@gmail.com
 * Mobile : 9553517603
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 */

class Auth {

    static $ci;
    public $tableName;
    public $role;
    private $activity_logs_table_name = 'activiy_logs';
    private $comment;
    public $otp_verification_need;

    function __construct() {
        self::$ci = & get_instance();

        /*
          $config['protocol'] = 'smtp';
          $config['charset'] = 'utf-8';
          $config['smtp_host'] = 'smtp.sendgrid.net';
          $config['smtp_user'] = 'satya1';
          $config['smtp_pass'] = 'Flowers1$';
          $config['smtp_port'] = 587;
          $config['crlf'] = "\r\n";
          $config['newline'] = "\r\n";
          $config['wordwrap'] = TRUE;
          $config['mailtype'] = 'html'; //Use 'text' if you don't need html tags and images
         */

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        self::$ci->load->library('email');
        self::$ci->email->initialize($config);
    }

    public function login_validataion() {
		$res = self::$ci->db->get_where($this->tableName, ['username' => self::$ci->input->post('username')])->row();

        if ($res) {
            if ($res->status != 1 ) {
                set_flash_msg('Your Account not activated or approved');
                return false;
            }
			
            //echo $res->password."<br/>";die;
            $password = md5(self::$ci->input->post('password') . $res->salt);
            if ($res->password != $password) {
                //return "INVALID_PASSWORD";
                //echo isset($res->password_attempt_count)?"MAni":"Not mani";
                //User login stop upto one hour if user entered 10 times wrong password

                return FALSE;
            }
            else if ($res->password == $password) {
                $res = self::$ci->db->get_where($this->tableName, ['id' => $res->id])->row();
                //echo self::$ci->db->last_query(); die;
                $this->set_role();
				
                $this->member_login_session_creation($res);
                return true;
            }
        }
        else {
            //echo "INVALID_USERNAME_OR_PASSWORD";
            return FALSE;
        }
    }

    public function admin_validation() {
        $this->tableName = 'admins';
        return $this->login_validataion();
    }

    public function member_login_session_creation($res) {
        if ($this->tableName == 'admins') {
            $username = $res->email;
        }
        else {
            $username = isset($res->username) ? $res->username : $res->email;
        }

        if (isset($res->firstname) && isset($res->lastname)) {
            $name = $res->firstname . ' ' . $res->lastname;
        }
        else if (isset($res->hospital_name)) {
            $name = $res->hospital_name;
        }
        else {
            $name = $res->email;
        }

        $user_data = array(
            'member_id' => $res->id,
            'username' => $username,
            'email' => $res->email,
            'name' => $name,
            'logged_in' => true,
        );
        self::$ci->session->set_userdata($user_data);
        $this->comment = "Logged in";
        $this->create_activity_log();
    }

    public function set_role() {
        //1. super_admin
        //2. sub_admin
        //3. pharama_admin
        //4. delivery_boy
        //5. customer
        self::$ci->session->set_userdata('role', $this->role);
    }

    public function get_role() {
        return self::$ci->session->userdata('role');
    }

    public function create_activity_log() {
        $arr = array('user_id' => self::$ci->session->userdata('member_id'),
            'role_id' => $this->get_role(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => self::$ci->input->ip_address(),
            'last_activity' => time(),
            'table_name' => $this->tableName,
            'comment' => $this->comment);
        self::$ci->db->set($arr)->insert($this->activity_logs_table_name);
    }

    public function is_admin_logged() {
        if (self::$ci->session->userdata('logged_in') && self::$ci->session->userdata('role') == 1) {
            return true;
        }
        return false;
    }

    public function logout() {
        $this->comment = "Logged out";
        $this->activity_logs_table_name;
        $this->auth_unset_session();
    }

    public function auth_unset_session() {
        self::$ci->session->set_userdata('role', '');
        $user_data = array(
            'member_id' => '',
            'username' => '',
            'logged_in' => '',
            'email' => ''
        );
        self::$ci->session->set_userdata($user_data);
        self::$ci->session->sess_destroy();
    }

    public function get_member_details($id) {
        return self::$ci->db->get_where($this->tableName, ['id' => $id])->result();
    }

    public function super_admin_login_id() {
        if (self::$ci->session->userdata('role') == 1) {
            return self::$ci->session->userdata('member_id');
        }
    }

   
    public function user_validation() {	
        $this->tableName = 'users';
        return $this->login_validataion();
    }

    public function is_user_logged() {
        if (self::$ci->session->userdata('logged_in') && self::$ci->session->userdata('role') == 4) {
            return true;
        }
        return false;
    }

    public function user_login_id() {
        if (self::$ci->session->userdata('role') == 4) {
            return self::$ci->session->userdata('member_id');
        }
    }

    public function user_last_login() {
        if (self::$ci->session->userdata('role') == 4) {
            self::$ci->db->order_by('last_activity', 'desc')->select('last_activity')->limit(1);
            $last_login = self::$ci->db->get_where('activiy_logs', ['comment' => 'Logged in', 'table_name' => 'users', 'user_id' => $this->user_login_id(), 'role_id' => 4])->row();
            return date('m-d-Y h:m:i A', $last_login->last_activity);
        }
    }

    
    //for hospital end
}
