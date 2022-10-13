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
class Register extends Api_controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $states = $this->my_model->get_data("states", null, "id,name", "name", "asc", true);
        $types_of_services = $this->my_model->get_data("types_of_services", null, "id,name", "name", "asc", true);
        $arr = [
            "status" => "valid",
            "type" => "",
            "data" => [
                "states" => $states,
                "types_of_services" => $types_of_services
            ]
        ];
        echo json_encode($arr);
        die;
    }

    public function do_register() {
        $this->form_validation->set_rules("first_name", "First Name", "trim|required", array('required' => 'Please enter First Name'));
        $this->form_validation->set_rules("last_name", "Last Name", "trim|required", array('required' => 'Please enter Last Name'));
        $this->form_validation->set_rules("dob", "Date of Birth", "trim|required", array('required' => 'Please enter Date of Birth'));
        $this->form_validation->set_rules("aadhar_number", "Aadhar Number", "trim|required", array('required' => 'Please enter Aadhar Number'));
        $this->form_validation->set_rules("license_number", "License Number", "trim|required", array('required' => 'Please enter License Number'));
        $this->form_validation->set_rules("pancard_number", "Pancard Number", "trim|required", array('required' => 'Please enter Pancard Number'));
        $this->form_validation->set_rules("experience", "Experience", "trim|required", array('required' => 'Please enter Experience'));
        $this->form_validation->set_rules("state_id", "State Id", "trim|required", array('required' => 'Please Select State'));
        $this->form_validation->set_rules("district_code", "District Code", "trim|required", array('required' => 'Please enter District Code'));
        $this->form_validation->set_rules("office_number", "Office Number", "trim|required", array('required' => 'Please enter Office Number'));
        $this->form_validation->set_rules("phone_number", "Phone Number", "trim|required", array('required' => 'Please enter Phone Number'));
        $this->form_validation->set_rules("types_of_services_id", "Types of Service", "trim|required", array('required' => 'Please select Type of Service'));
        $this->form_validation->set_rules("no_of_trucks", "No of Trucks", "trim|required", array('required' => 'Please enter No of Trucks'));
        $this->form_validation->set_rules("is_otp_verified", "Is OTP Verified", "trim|required", array('required' => 'Is OTP Verified ?'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]', array('required' => 'Please enter your password', 'min_length' => 'Password should be greater than 6 characters', 'max_length' => 'Password should be less than 32 characters'));
        $aadhar_front_image = $this->file_upload("aadhar_front_image", "owners/proofs/", "jpg|png|jpeg");
        $aadhar_back_image = $this->file_upload("aadhar_back_image", "owners/proofs/", "jpg|png|jpeg");
        $license_front_image = $this->file_upload("license_front_image", "owners/proofs/", "jpg|png|jpeg");
        $license_back_image = $this->file_upload("license_back_image", "owners/proofs/", "jpg|png|jpeg");
        $pancard_front_image = $this->file_upload("pancard_front_image", "owners/proofs/", "jpg|png|jpeg");
        $pancard_back_image = $this->file_upload("pancard_back_image", "owners/proofs/", "jpg|png|jpeg");
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
            $first_name = $this->input->post("first_name");
            $last_name = $this->input->post("last_name");
            $dob_raw = $this->input->post("dob");
            $dob = date("Y-m-d", strtotime($dob_raw));
            $aadhar_number = $this->input->post("aadhar_number");
            $license_number = $this->input->post("license_number");
            $pancard_number = $this->input->post("pancard_number");
            $experience = $this->input->post("experience");
            $state_id = $this->input->post("state_id");
            $district_code = $this->input->post("district_code");
            $office_number = $this->input->post("office_number");
            $phone_number = $this->input->post("phone_number");
            $password = $this->input->post("password");
            $types_of_services_id = $this->input->post("types_of_services_id");
            $no_of_trucks = $this->input->post("no_of_trucks");
            $is_otp_verified = $this->input->post("is_otp_verified"); // yes or no
            $salt = $this->generate_random_string(10, "transport_owners", "salt");
            $access_token = $this->generate_random_string(40, "transport_owners", "access_token");
            $user_id = OWNER_ID_PREFIX . $this->generate_random_numbers(12, "transport_owners", "user_id");
            $inp_arr = array(
                "access_token" => $access_token,
                "user_id" => $user_id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "dob" => $dob,
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
                "office_number" => $office_number,
                "phone_number" => $phone_number,
                "password" => md5($password . $salt),
                "salt" => $salt,
                "types_of_services_id" => $types_of_services_id,
                "no_of_trucks" => $no_of_trucks,
                "is_otp_verified" => $is_otp_verified,
                "created_at" => time(),
                "updated_at" => time()
            );
            $check = $this->check_owner_with_phone_number($phone_number);
            if (empty($check)) {
                $register = $this->my_model->insert_data("transport_owners", $inp_arr);
                if ($register) {
                    $owner_data = $this->check_owner($access_token, "id,access_token,user_id,first_name,last_name,phone_number");
                    $arr = array(
                        "status" => "valid",
                        "type" => "",
                        "message" => "Account Successfully Registered",
                        "data" => $owner_data
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
    }

}
