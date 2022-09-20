<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Main_menu extends CI_Controller {

    private $data;

    function __construct() {
        parent::__construct();
        if ($this->site_model->check_for_user_logged() == false) {
            redirect("admin/login");
        }
        $this->data['add_subject'] = "Main Menu";
        $this->data['subject'] = plural($this->data['add_subject']);
        $this->data['primary_table_name'] = "admin_dashboard_main_menu";
        $this->data['current_page_link'] = base_url() . "admin/".strtolower(__CLASS__);

        $this->data['primary_uri_segment_name'] = $this->data['primary_table_name'];

        $this->data['column_name_display_as'] = array('city_name' => 'City Name',
            'role_id' => 'Role',
            'time' => 'Date and Time');
        $this->data['hide_columns_in_list_view'] = array("updated_at", 'password', 'salt', 'password',
            'salt',
            'forgot_password_verfication_code',
            'activation_key',
            'token', 'email_verified');
        $this->data['hide_columns_in_add_view'] = array('updated_at',
            'password',
            'salt',
            'forgot_password_verfication_code',
            'activation_key',
            'token',
            'email_verified'
        );
        $this->data['hide_columns_in_edit_view'] = array("updated_at", 'password', 'salt');
        $this->data['hide_columns_in_add_view'] = array("forgot_password_verfication_code",
            "activation_key",
            "token", "email_verified", "status");
        $this->data['image_fields'] = array('image');
        $this->data['email_fields'] = array('username', 'email');

        $this->data['action_buttons'] = array(
            "delete_action" => true,
            "edit_action" => true,
            "add_action" => true,
            "view_action" => false
        );

        $this->data['button_size'] = "xs";


        $this->data['unset_all_actions'] = false;

        $this->data['password_fields'] = array("password");
        $this->data['readonly_fields'] = array();

        $this->data['relation_fields'] = array(
            'role_id' => array('table_name' => 'roles', 'display_column_name' => 'name', "condition" => array(""))
        );
        $this->data['multiple_selection_of_options'] = array("role_ids");

        $this->data['column_name_display_as'] = array('role_id' => 'User Role', 'time' => 'Date and Time');

        $this->data['unique_fileds'] = array();

        $this->data['signle_file_browse_fileds'] = array();

        $this->data["image_columns_properties"] = array(
            "image" => array("max_size" => "10000", //in kb format only
                "accepted_file_formats" => array("png", "jpeg", "jpg", "gif"))
        );

        $this->data['add_action_buttons'] = array();
        
        $this->data["generate_random_key_fields"] = array();

        if ($this->input->is_ajax_request()) {
            if (isset($_GET['existed_value'])) {
                $keys = array_keys($_POST);
                if ($this->db->get_where($this->data['primary_table_name'], [$keys[0] => $_REQUEST[$keys[0]], $keys[0] . "!=" => $_REQUEST['existed_value']])->num_rows()) {
                    echo "false";
                } else {
                    echo "true";
                }
            } else {
                $keys = array_keys($_POST);
                if ($this->db->get_where($this->data['primary_table_name'], [$keys[0] => $_REQUEST[$keys[0]]])->num_rows()) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
            exit;
        }

        $this->data["fileds_info"] = $this->curd_model->get_fields($this->data['primary_table_name']);

                   
        if (!empty($_POST)) {


            if (isset($_POST['submit'])) {

                if ($_POST['submit'] == "insert") {
                    unset($_POST['submit']);
                    //print_r($_POST);
                    
                    foreach ($this->data['generate_random_key_fields'] as $key_item){
                        echo $_POST[$key_item] = $this->curd_model->generate_random_string_code($this->data['primary_table_name'], $key_item);
                    }
                    
                    
                    $keys = array_keys($_POST);
                    
                    $obj = new Requests();
                    $obj->is_password_update_required = false;
                    $obj->tableName = $this->data['primary_table_name'];
                    $obj->fillable = $keys;

                    foreach ($this->data['image_fields'] as $item) {
                        if (isset($_FILES[$item . '__i']['name'])) {
                            if ($_FILES[$item . '__i']['name'] != '') {
                                array_push($obj->fillable, $item . '__i');
                            }
                        }
                    }
                    if ($obj->posted_values_dynamic_save()) {
                        redirect($this->data['current_page_link']."?msg=add");
                        //any extra code such as email, sms functionality code
                        //js_redirect($back_button_nav."?msg=add");
                    }
                } else if ($_POST['submit'] == "update") {

                    unset($_POST['submit']);


                    $obj = new Requests();

                    $id = $_POST['id'];
                    if (isset($_POST['password'])) {
                        if ($_POST['password'] != '') {
                            $password = $_POST['password'];
                            unset($_POST['password']);
                            $obj->is_password_update_required = false;
                        }
                    }

                    $keys = array_keys($_POST);


                    $obj->tableName = $this->data['primary_table_name'];
                    $obj->whereCnd = array("id" => $id);
                    $obj->fillable = $keys;
                    foreach ($this->data['image_fields'] as $item) {
                        if (isset($_FILES[$item . '__i']['name'])) {
                            if ($_FILES[$item . '__i']['name'] != '') {
                                array_push($obj->fillable, $item . '__i');
                            }
                        }
                    }
                    if (isset($password)) {
                        $_POST['password'] = $password;
                        array_push($obj->fillable, 'password');
                    }

                    if ($obj->posted_values_dynamic_update()) {
                        //any extra code such as email, sms functionality code
                        redirect($this->data['current_page_link']."?msg=update");
                    }
                }
            }
        }
    }

    function admin_view($design = null) {
        $this->load->view("admin/includes/header", $this->data);
        $this->load->view("admin/curd_files/" . $design);
        $this->load->view("admin/includes/footer", $this->data);
    }

    function index() {
        $this->data["list_items"] = $this->curd_model->get_all_data_from_table($this->data['primary_table_name']);
        $this->admin_view("list_view");
    }

    function add() {
        if($this->data["action_buttons"]['add_action']==false){
            die("Access Deined for add operation");
        }
        $this->admin_view("add_view");
    }

    function edit($item_primary_key) {
        if($this->data["action_buttons"]['edit_action']==false){
            die("Access Deined for edit operation");
        }
        $this->data['edit_item_row'] = $this->curd_model->get_row_from_pk($this->data['primary_table_name'], $item_primary_key);
        $this->admin_view("edit_view");
    }

    function delete($item_primary_key) {
        if($this->data["action_buttons"]['delete_action']==true){
            $this->db->where("id", $item_primary_key);
            $this->db->delete($this->data['primary_table_name']);
            redirect($this->data['current_page_link'] . "?msg=delete");
        }else{
            redirect($this->data['current_page_link']);
        }
    }
    function toggle_status($item_primary_key){
        $current_status = $this->curd_model->get_status($this->data['primary_table_name'], $item_primary_key);
        if($current_status!=FALSE){
            $this->curd_model->toggle_status($this->data['primary_table_name'], $item_primary_key, $current_status);
        }
        redirect($this->data['current_page_link']."?msg=update");
    }
}
