<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Default is Developed by peacekeeper
 *
 * @author peacekeeper
 */
class Index extends CI_Controller {

//put your code here
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view("defaults/index");
    }

    public function error_404() {
        $this->load->view("defaults/error_404");
    }

}
