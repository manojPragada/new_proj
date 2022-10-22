<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Change_password
 *
 * @author saikumar surala
 */
class Change_password extends Api_controller {

    public $user_data, $user_id;

    public function __construct() {
        parent::__construct();
        $access_token = $this->input->post("access_token");
        $this->user_data = $this->check_driver($access_token);
        $this->user_id = $this->user_data->id;
    }

    public function index() {
        $user_data = $this->user_data;
        $this->form_validation->set_rules("old_password", "Old Password", "trim|required", array('required' => 'Please enter Old Password'));
        $this->form_validation->set_rules("new_password", "New Password", "trim|required", array('required' => 'Please enter New Password'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $old_password = $this->input->post("old_password");
            $new_password = $this->input->post("new_password");
            $enc_old_pass = md5($old_password . $user_data->salt);
            $this->check_passwords($user_data->password, $enc_old_pass, "Old");
            $salt = $this->generate_random_string(10, "drivers", "salt");
            $enc_new_password = md5($new_password . $salt);
            $inp_arr = [
                "salt" => $salt,
                "password" => $enc_new_password,
                "updated_at" => time()
            ];
            $update = $this->my_model->update_data("drivers", ["id" => $this->user_id], $inp_arr);
            if ($update) {
                $arr = array(
                    "status" => "valid",
                    "type" => "",
                    "message" => "Password Successfully Updated"
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

    private function check_passwords($prev, $given, $cue = "") {
        if ($prev == $given) {
            return true;
        } else {
            $arr = ['status' => "invalid",
                "type" => "", 'message' => "Invalid $cue Password!"];
            echo json_encode($arr);
            die;
        }
    }

}
