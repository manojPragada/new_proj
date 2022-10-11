<?php

header("access-control-allow-origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class MY_Controller extends CI_Controller {

    public $data;

    function __construct() {
        parent::__construct();
        $this->data = array();
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->library('email');

        $this->data["site_property"] = $this->site_model->get_site_properties();
        $this->data["social_media_link"] = $this->site_model->get_social_media_links();
        $this->data['services'] = $this->db->get_where("services", ["status" => 1])->result();
        foreach ($this->data['services'] as $item) {
            $item->image = base_url() . "uploads/" . $item->image;
        }
    }

    function admin_view($design = null) {
        $this->load->view("admin/includes/header", $this->data);
        //$this->load->view("admin/");
        $this->load->view("admin/includes/footer", $this->data);
    }

    function front_view($design = null) {
        $this->load->view("includes/header", $this->data);
        $this->load->view($design);
        $this->load->view("includes/footer", $this->data);
    }

    function front_iframe_view($design = null) {
        $this->load->view("includes/header", $this->data);
        $this->load->view("booking_frame/" . $design);
        $this->data["hide_footer"] = "yes";
        $this->load->view("includes/footer", $this->data);
    }

    function profession_view($design = null) {
        $this->load->view("includes/dash_header", $this->data);
        $this->load->view("professional/" . $design);
        $this->load->view("includes/dash_footer", $this->data);
    }

    function check_login_status($user_id) {
        if ($this->user_model->check_user_status($user_id) == false) {
            $this->session->set_flashdata("type", "inactive");
            redirect("login");
        }
        if ($this->user_model->check_email_verified($user_id) == false) {
            $this->session->set_flashdata("type", "email_inactive");
            redirect("login");
        }
        return true;
    }

    function get_user_id($token) {
        $user_id = $this->user_model->get_user_id_by_token($token);
        return $user_id;
    }

    function is_user_logged() {
        if (!get_cookie("token")) {
            redirect("login");
            return false;
        } else {
            $user_id = $this->user_model->get_user_id_by_token(get_cookie("token"));
            return $this->check_login_status($user_id);
        }
    }

    function routing_process() {
        $user_id = $this->user_model->get_user_id_by_token(get_cookie("token"));
        $role = $this->user_model->get_user_role_id($user_id);
        switch ($role) {
            case 3:
                redirect("professional/dashboard");
            case 2:
                redirect("customer/dashboard");
            case 1:
                redirect("admin/login");
        }
    }

    function is_token_required() {
        if (!$this->input->get_post('token')) {
            $arr = array('err_code' => "invalid", "error_type" => "token_required", "message" => "Unauthorized access..");
            echo json_encode($arr);
            die;
        }
    }

}

class Api_controller extends CI_Controller {

    public $data;

    function __construct() {
        parent::__construct();
    }

    function check_user() {
        
    }

}
