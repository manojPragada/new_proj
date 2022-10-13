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
class Authentication extends Api_controller {

    public function __construct() {
        parent::__construct();
    }

    public function do_login() {
        $this->form_validation->set_rules("phone_number", "Phone Number", "trim|required", array('required' => 'Please enter Phone Number'));
        $this->form_validation->set_rules("password", "Password", "trim|required", array('required' => 'Please enter Password'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $phone_number = $this->input->post("phone_number");
            $password = $this->input->post("password");
            $data = $this->my_model->get_data_row("transport_owners", ["phone_number" => $phone_number]);
            if (!empty($data)) {
                $existing_password = $data->password;
                $salt = $data->salt;
                $current_password = md5($password . $salt);
                if ($data->status == 0) {
                    $arr = [
                        "status" => "invalid",
                        "type" => "login_error",
                        "message" => "Acount Deactivated! Please Contact Admin."
                    ];
                } else if ($current_password == $existing_password) {
                    $user_data = $this->check_owner($data->access_token, "id,access_token,user_id,first_name,last_name,phone_number");
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
                        "message" => "Invalid Password! Please Try Again."
                    ];
                }
            } else {
                $arr = [
                    "status" => "invalid",
                    "type" => "login_error",
                    "message" => "User does not Exists!"
                ];
            }
        }
        echo json_encode($arr);
    }

}
