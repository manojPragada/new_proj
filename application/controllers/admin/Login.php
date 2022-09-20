<?php

class Login extends CI_Controller {

    private $data;

    function __construct() {
        parent::__construct();
        $this->data = [];
        if ($this->site_model->check_for_user_logged() == true) {
            redirect("admin/dashboard");
        }
    }

    function index() {
        if (!empty($_POST)) {

            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|max_length[32]', array('required' => 'You must provide a valid %s.',
                'min_length' => 'Invalid Credentials',
                'max_length' => 'Invalid Credentials')
            );
            if ($this->form_validation->run() == FALSE) {
                $this->load->view("admin/login");
            } else {
                $user_id = $this->site_model->is_username_exists($this->input->post("username"));
                
                if ($user_id == false || $user_id=="") {
                    $this->session->set_flashdata("type", "not_exists");
                    redirect("admin/login");
                }
                if ($this->site_model->check_user_status($user_id)==false) {
                    $this->session->set_flashdata("type", "inactive");
                    redirect("admin/login");
                }
                if ($this->site_model->login_validation($user_id, $this->input->post("password"))) {
                    redirect("admin/login");
                } else {
                    $this->session->set_flashdata("type", "log");
                    redirect("admin/login");
                }
            }
            
        } else {
            $this->load->view("admin/login");
        }
    }

}
