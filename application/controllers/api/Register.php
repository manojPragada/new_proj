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
        $this->form_validation->set_rules("email_or_phonenumber", "Email or Phone Number", "trim|required", array('required' => 'Please enter Email or Phone Number'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]', array('required' => 'Please enter your password', 'min_length' => 'Password should be greater than 6 characters', 'max_length' => 'Password should be less than 32 characters'));

        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid", 'message' => $message];
        } else {

        }
        echo json_encode($arr);
    }

}
