<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Authentication
 *
 * @author peacekeeper
 */
class Authentication extends Api_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $phone_number = $this->input->post("phone_number");
        $user_data = $this->check_customer_with_phone_number($phone_number, "id,access_token,user_id,name,phone_number");
        if (!empty($user_data)) {
            $arr = [
                "status" => "valid",
                "type" => "",
                "message" => "Login Success!.",
                "data" => $user_data
            ];
        } else {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => "User does not Exists!"
            ];
        }
        echo json_encode($arr);
    }

    public function auto_login() {
        $access_token = $this->input->post("access_token");
        $user_data = $this->check_customer($access_token, "id,access_token,user_id,name,phone_number");
        if (!empty($user_data)) {
            $arr = [
                "status" => "valid",
                "type" => "",
                "message" => "Login Success!.",
                "data" => $user_data
            ];
        } else {
            $arr = [
                "status" => "invalid",
                "type" => "login_error",
                "message" => "User does not Exists!"
            ];
        }
        echo json_encode($arr);
    }

}
