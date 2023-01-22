<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Register is Developed by peacekeeper
 *
 * @author peacekeeper
 */
class Register extends Api_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->form_validation->set_rules("phone_number", "Phone Number", "trim|required", array('required' => 'Please enter Phone Number'));
        $this->form_validation->set_rules("name", "Name", "trim|required", array('required' => 'Please enter Name'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]', array('required' => 'Please enter your password', 'min_length' => 'Password should be greater than 6 characters', 'max_length' => 'Password should be less than 32 characters'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $phone_number = $this->input->post("phone_number");
            $name = $this->input->post("name");
            $password = $this->input->post("password");
            $this->check_user_existance($phone_number);
            $access_token = $this->generate_random_string(40, "customers", "access_token");
            $user_id = $this->generate_random_string(20, "customers", "user_id");
            $salt = $this->generate_random_string(10, "transport_owners", "salt");

            $inp_arr = [
                "name" => $name,
                "phone_number" => $phone_number,
                "access_token" => $access_token,
                "user_id" => $user_id,
                "password" => md5($password . $salt),
                "salt" => $salt,
                "is_otp_verified" => "no",
                "created_at" => time()
            ];

            $add_customer = $this->my_model->insert_data("customers", $inp_arr);
            if ($add_customer) {
                $customer_data = $this->check_customer($access_token, "id,access_token,user_id,name,phone_number");
                $arr = array(
                    "status" => "valid",
                    "type" => "",
                    "message" => "Registration success!",
                    "data" => $customer_data
                );
            } else {
                $arr = array(
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Something went wrong, Please try again."
                );
            }
        }
        $this->response($arr);
    }

    public function update_otp_status() {
        $this->form_validation->set_rules("access_token", "Access Token", "trim|required", array('required' => 'Please provide Access Token'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $access_token = $this->input->post("access_token");
            $customer_data = $this->check_customer($access_token, "id,access_token,user_id,name,phone_number");
            $up_data = ["is_otp_verified" => "yes"];
            $update = $this->my_model->update_data("customers", ["id" => $customer_data->id], $up_data);
            if ($update) {
                $customer_data = $this->check_customer($access_token, "id,access_token,user_id,name,phone_number");
                $arr = array(
                    "status" => "valid",
                    "type" => "",
                    "message" => "OTP Status updated!",
                    "data" => $customer_data
                );
            } else {
                $arr = [
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Something went wrong, Please try again."
                ];
            }
            $this->response($arr);
        }
    }

    private function check_user_existance($phone_number) {
        $check = $this->my_model->get_data_row("customers", ["phone_number" => $phone_number]);
        if (!empty($check)) {
            $arr = [
                "status" => "invalid",
                "type" => "user_exists",
                "message" => "Mobile Number already Exists!"
            ];
            $this->response($arr);
        }
        return true;
    }

}
