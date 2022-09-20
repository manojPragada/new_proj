<?php

class Logout extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        if ($this->site_model->do_logout()) {
            $this->session->set_flashdata("type", "lout");
        }
        redirect("admin/login");
    }

}
