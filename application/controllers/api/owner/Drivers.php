<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Drivers is Developed by peacekeeper
 *
 * @author peacekeeper
 */
class Drivers extends Api_controller {

    public $user_data, $user_id;

    public function __construct() {
        parent::__construct();
        $access_token = $this->input->post("access_token");
        $this->user_data = $this->check_owner($access_token);
        $this->user_id = $this->user_data->id;
        $this->base_url = base_url() . "uploads/lorries/";
    }

    public function index() {
        $drivers = $this->my_model->get_data("drivers", ["owner_id" => $this->user_id], "id,user_id,name,phone_number");
        if (!empty($drivers)) {
            $arr = [
                "status" => "valid",
                "type" => "",
                "data" => $drivers
            ];
        } else {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "No Drivers Data Found!"
            ];
        }
        echo json_encode($arr);
        die;
    }

    public function add() {
        $this->form_validation->set_rules("name", "Driver Name", "trim|required", array('required' => 'Please enter Name'));
        $this->form_validation->set_rules("aadhar_number", "Driver Name", "trim|required", array('required' => 'Please enter Aadhar Number'));
        $this->form_validation->set_rules("license_number", "License Number", "trim|required", array('required' => 'Please enter License Number'));
        $this->form_validation->set_rules("pancard_number", "PAN Card Number", "trim|required", array('required' => 'Please enter PAN Card Number'));
        $this->form_validation->set_rules("experience", "Experience", "trim|required", array('required' => 'Please enter Experience'));
        $this->form_validation->set_rules("state_id", "State Id", "trim|required", array('required' => 'Please enter State Id'));
        $this->form_validation->set_rules("district_code", "District Code", "trim|required", array('required' => 'Please enter District Code'));
        $this->form_validation->set_rules("phone_number", "Phone Number", "trim|required", array('required' => 'Please enter Phone Number'));
        $this->form_validation->set_rules("password", "Password", "trim|required", array('required' => 'Please enter Password'));
        $user_id = DRIVER_ID_PREFIX . $this->generate_random_numbers(12, "drivers", "user_id");
        $aadhar_front_image = $this->file_upload("aadhar_front_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $aadhar_back_image = $this->file_upload("aadhar_back_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $license_front_image = $this->file_upload("license_front_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $license_back_image = $this->file_upload("license_back_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $pancard_front_image = $this->file_upload("pancard_front_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $pancard_back_image = $this->file_upload("pancard_back_image", "drivers/proofs/$user_id/", "jpg|png|jpeg");
        $this->check_value_status($aadhar_front_image, "Aadhar front Image");
        $this->check_value_status($aadhar_back_image, "Aadhar back Image");
        $this->check_value_status($license_front_image, "License front Image");
        $this->check_value_status($license_back_image, "License back Image");
        $this->check_value_status($pancard_front_image, "Pancard front Image");
        $this->check_value_status($pancard_back_image, "Pancard back Image");
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $name = $this->input->post("name");
            $aadhar_number = $this->input->post("aadhar_number");
            $license_number = $this->input->post("license_number");
            $pancard_number = $this->input->post("pancard_number");
            $experience = $this->input->post("experience");
            $state_id = $this->input->post("state_id");
            $district_code = $this->input->post("district_code");
            $phone_number = $this->input->post("phone_number");
            $password = $this->input->post("password");
            $salt = $this->generate_random_string(10, "drivers", "salt");
            $access_token = $this->generate_random_string(40, "drivers", "access_token");
            $inp_arr = [
                "owner_id" => $this->user_id,
                "access_token" => $access_token,
                "user_id" => $user_id,
                "name" => $name,
                "aadhar_number" => $aadhar_number,
                "aadhar_front_image" => $aadhar_front_image,
                "aadhar_back_image" => $aadhar_back_image,
                "license_number" => $license_number,
                "license_front_image" => $license_front_image,
                "license_back_image" => $license_back_image,
                "pancard_number" => $pancard_number,
                "pancard_front_image" => $pancard_front_image,
                "pancard_back_image" => $pancard_back_image,
                "experience" => $experience,
                "state_id" => $state_id,
                "district_code" => $district_code,
                "phone_number" => $phone_number,
                "password" => md5($password . $salt),
                "salt" => $salt,
                "created_at" => time(),
                "updated_at" => time()
            ];
            $check = $this->check_driver_with_phone_number($phone_number);
            if (empty($check)) {
                $register = $this->my_model->insert_data("drivers", $inp_arr);
                if ($register) {
                    $arr = array(
                        "status" => "valid",
                        "type" => "",
                        "message" => "Driver Account Successfully Registered"
                    );
                } else {
                    $arr = array(
                        "status" => "invalid",
                        "type" => "",
                        "message" => "Something went Wrong! Please try again."
                    );
                }
            } else {
                $arr = array(
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Account with this Phone Number Already Exists!"
                );
            }
        }
        echo json_encode($arr);
        die;
    }

    public function assign_to_lorry() {
        $this->form_validation->set_rules("driver_id", "Driver Id", "trim|required", array('required' => 'Invalid Driver Id'));
        $this->form_validation->set_rules("lorry_id", "Lorry Id", "trim|required", array('required' => 'Invalid Lorry Id'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $driver_id = $this->input->post("driver_id");
            $lorry_id = $this->input->post("lorry_id");
            $this->check_driver_with_id($driver_id, $this->user_id);
            $this->check_lorry_with_id($lorry_id, $this->user_id);
            $this->my_model->update_data("drivers_and_lorries_relation", ["lorry_id" => $lorry_id], ["lorry_id" => null]);
            $inp_array = [
                "driver_id" => $driver_id,
                "lorry_id" => $lorry_id,
                "created_at" => time(),
                "updated_at" => time()
            ];
            $check_entry = $this->my_model->get_data_row("drivers_and_lorries_relation", ["driver_id" => $driver_id]);
            if (!empty($check_entry)) {
                unset($inp_array["created_at"]);
                $qry = $this->my_model->update_data("drivers_and_lorries_relation", ["id" => $check_entry->id], $inp_array);
            } else {
                $qry = $this->my_model->insert_data("drivers_and_lorries_relation", $inp_array);
            }

            if ($qry) {
                $arr = [
                    "status" => "valid",
                    "type" => "",
                    "message" => "Lorry Successfully Assigned"
                ];
            } else {
                $arr = [
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Something went wrong! Please Try Again."
                ];
            }
        }
        echo json_encode($arr);
        die;
    }

}
