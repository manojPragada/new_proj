<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Register
 *
 * @author peacekeeper
 */
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

    function check_value_status($value, $title = "") {
        if (empty($value)) {
            $arr = [
                "status" => "invalid",
                "message" => $title . " is Required"
            ];
            echo json_encode($arr);
            die;
        }
        return true;
    }

    function generate_random_string($length = 8, $table, $column) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $gen = implode($pass);
        $check = $this->my_model->get_data_row($table, [$column => $gen]);
        if (!empty($check)) {
            return $this->get_random_string($length, $table, $column);
        }
        if ($column == "req_id") {
            $this->my_model->insert_data("req_ids", ["req_id" => $gen, "created_at" => time()]);
        }
        return $gen;
    }

    function generate_random_numbers($length = 8, $table, $column) {
        $alphabet = '1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $gen = implode($pass);
        $check = $this->my_model->get_data_row($table, [$column => $gen]);
        if (!empty($check)) {
            return $this->get_random_string($length, $table, $column);
        }
        if ($column == "req_id") {
            $this->my_model->insert_data("req_ids", ["req_id" => $gen, "created_at" => time()]);
        }
        return $gen;
    }

    function check_owner($access_token, $select = "*") {
        if (empty($access_token)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => "Invalid Access Token!"
            ];
            echo json_encode($arr);
            die;
        }
        $data = $this->my_model->get_data_row("transport_owners", ["access_token" => $access_token], $select);
        if (empty($data)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => (!empty($data) && $data->status == 0) ? "Account Inactive! Please Contact Admin." : "User does not Exists!"
            ];
            echo json_encode($arr);
            die;
        }
        return $data;
    }

    function check_driver($access_token, $select = "*") {
        if (empty($access_token)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => "Invalid Access Token!"
            ];
            echo json_encode($arr);
            die;
        }
        $data = $this->my_model->get_data_row("drivers", ["access_token" => $access_token], $select);
        if (empty($data)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => (!empty($data) && $data->status == 0) ? "Account Inactive! Please Contact Admin." : "User does not Exists!"
            ];
            echo json_encode($arr);
            die;
        }
        return $data;
    }

    function check_customer($access_token, $select = "*") {
        if (empty($access_token)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => "Invalid Access Token!"
            ];
            echo json_encode($arr);
            die;
        }
        $data = $this->my_model->get_data_row("customers", ["access_token" => $access_token], $select);
        if (empty($data)) {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => (!empty($data) && $data->status == 0) ? "Account Inactive! Please Contact Admin." : "User does not Exists!"
            ];
            echo json_encode($arr);
            die;
        }
        return $data;
    }

    function check_driver_with_id($id, $owner_id, $select = "*") {
        if (empty($id)) {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "Invalid Driver Id!"
            ];
            echo json_encode($arr);
            die;
        }
        $data = $this->my_model->get_data_row("drivers", ["id" => $id, "owner_id" => $owner_id], $select);
        if (empty($data)) {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "Driver does not Exists!"
            ];
            echo json_encode($arr);
            die;
        }
        return $data;
    }

    function check_lorry_with_id($id, $owner_id, $select = "*") {
        if (empty($id)) {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "Invalid Lorry Id!"
            ];
            echo json_encode($arr);
            die;
        }
        $data = $this->my_model->get_data_row("lorries", ["id" => $id, "owner_id" => $owner_id], $select);
        if (empty($data)) {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "Lorry does not Exists!"
            ];
            echo json_encode($arr);
            die;
        }
        return $data;
    }

    function check_owner_with_phone_number($phone_number) {
        return $this->my_model->get_data("transport_owners", ["phone_number" => $phone_number]);
    }

    function check_driver_with_phone_number($phone_number) {
        return $this->my_model->get_data("drivers", ["phone_number" => $phone_number]);
    }

    function check_customer_with_phone_number($phone_number, $select) {
        return $this->my_model->get_data_row("customers", ["phone_number" => $phone_number], $select);
    }

    private function create_folders($path) {
        $real_path = realpath(APPPATH . '../uploads/' . $path);
        if (!file_exists($real_path)) {
            $oldmask = umask(0);
            mkdir('uploads/' . $path, 0777, true);
            umask($oldmask);
        }
        return true;
    }

    function file_upload($var, $path_folder, $allowed_types = "*", $enc = true, $prev_file_name = "") {
        $this->create_folders($path_folder);
        if (!empty($_FILES[$var]['name'])) {
            $real_path = realpath(APPPATH . '../uploads/' . $path_folder);
            if (!file_exists($real_path)) {
                $oldmask = umask(0);
                mkdir('uploads/' . $path_folder, 0777, true);
                umask($oldmask);
                return $this->image_upload($var, $path_folder, $enc, $prev_file_name);
            } else {
                $config['upload_path'] = $real_path;
                $config['allowed_types'] = $allowed_types;
                $config['encrypt_name'] = $enc;
                if (!empty($file_name)) {
                    $config['overwrite'] = TRUE;
                    $config['file_name'] = $file_name;
                }
                $this->load->library('upload', $config);
                if ($this->upload->do_upload($var)) {
                    $img_data = $this->upload->data();
                    $path = $img_data['file_name'];
                    if (!empty($prev_file_name)) {
                        unlink('uploads/' . $path_folder . $prev_file_name);
                    }
                    return $path;
                } else {
                    print_r($this->upload->display_errors());
                    die;
                    return "";
                }
            }
        } else {
            return "";
        }
    }

    function response($arr) {
        echo json_encode($arr, JSON_PRETTY_PRINT);
        die;
    }

}
