<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Profile
 *
 * @author saikumar surala
 */
class Profile extends Api_controller {

    public $user_data, $user_id;

    public function __construct() {
        parent::__construct();
        $access_token = $this->input->post("access_token");
        $this->user_data = $this->check_owner($access_token);
        $this->user_id = $this->user_data->id;
    }

    public function index() {
        $user_data = $this->user_data;
        $user_data->image = !empty($user_data->image) ? base_url("uploads/") . OWNERS_UPLOADS_PROFILE . $user_data->user_id . "/" . $user_data->image : "";
        $user_data->aadhar_front_image = !empty($user_data->aadhar_front_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->aadhar_front_image : "";
        $user_data->aadhar_back_image = !empty($user_data->aadhar_back_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->aadhar_back_image : "";
        $user_data->license_front_image = !empty($user_data->license_front_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->license_front_image : "";
        $user_data->license_back_image = !empty($user_data->license_back_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->license_back_image : "";
        $user_data->pancard_front_image = !empty($user_data->pancard_front_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->pancard_front_image : "";
        $user_data->pancard_back_image = !empty($user_data->pancard_back_image) ? base_url("uploads/") . OWNERS_UPLOADS_PROOFS . $user_data->user_id . "/" . $user_data->pancard_back_image : "";
        $arr = [
            "status" => "valid",
            "type" => "",
            "data" => $user_data
        ];
        echo json_encode($arr);
        die;
    }

    public function update() {
        $user_data = $this->user_data;
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
        $user_id = $user_data->user_id;
        $image = $this->file_upload("image", OWNERS_UPLOADS_PROFILE . "$user_id/", "jpg|png|jpeg");
        $aadhar_front_image = $this->file_upload("aadhar_front_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->aadhar_front_image);
        $aadhar_back_image = $this->file_upload("aadhar_back_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->aadhar_back_image);
        $license_front_image = $this->file_upload("license_front_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->license_front_image);
        $license_back_image = $this->file_upload("license_back_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->license_back_image);
        $pancard_front_image = $this->file_upload("pancard_front_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->pancard_front_image);
        $pancard_back_image = $this->file_upload("pancard_back_image", OWNERS_UPLOADS_PROOFS . "$user_id/", "jpg|png|jpeg", true, $user_data->pancard_back_image);
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
            $types_of_services_id = $this->input->post("types_of_services_id");
            $no_of_trucks = $this->input->post("no_of_trucks");
            $inp_arr = array(
                "first_name" => $first_name,
                "last_name" => $last_name,
                "dob" => $dob,
                "aadhar_number" => $aadhar_number,
                "license_number" => $license_number,
                "pancard_number" => $pancard_number,
                "experience" => $experience,
                "state_id" => $state_id,
                "district_code" => $district_code,
                "office_number" => $office_number,
                "phone_number" => $phone_number,
                "types_of_services_id" => $types_of_services_id,
                "no_of_trucks" => $no_of_trucks,
                "updated_at" => time()
            );
            if (!empty($image)) {
                $inp_arr["image"] = $image;
            }
            if (!empty($aadhar_front_image)) {
                $inp_arr["aadhar_front_image"] = $aadhar_front_image;
            }
            if (!empty($aadhar_back_image)) {
                $inp_arr["aadhar_back_image"] = $aadhar_back_image;
            }
            if (!empty($license_front_image)) {
                $inp_arr["license_front_image"] = $license_front_image;
            }
            if (!empty($license_back_image)) {
                $inp_arr["license_back_image"] = $license_back_image;
            }
            if (!empty($pancard_front_image)) {
                $inp_arr["pancard_front_image"] = $pancard_front_image;
            }
            if (!empty($pancard_back_image)) {
                $inp_arr["pancard_back_image"] = $pancard_back_image;
            }
            $update = $this->my_model->update_data("transport_owners", ["id" => $this->user_id], $inp_arr);
            if ($update) {
                $owner_data = $this->check_owner($user_data->access_token, "id,access_token,user_id,first_name,last_name,phone_number");
                $arr = array(
                    "status" => "valid",
                    "type" => "",
                    "message" => "Success! Profile Updated.",
                    "data" => $owner_data
                );
            } else {
                $arr = array(
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Something went Wrong! Please try again."
                );
            }
        }
        echo json_encode($arr);
        die;
    }

}
