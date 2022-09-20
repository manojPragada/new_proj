<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Manage_admin extends CI_Controller {

    private $data;

    function __construct() {
        parent::__construct();
        if ($this->site_model->check_for_user_logged() == false) {
            redirect("admin/login");
        }
        $this->data['add_subject'] = "Admin Settings";
        $this->data['subject'] = plural($this->data['add_subject']);
        $this->data['primary_table_name'] = "users";
        $this->data['current_page_link'] = base_url() . "admin/" . __CLASS__;

        $this->data['primary_uri_segment_name'] = $this->data['primary_table_name'];
        $this->data["fileds_info"] = $this->curd_model->get_fields($this->data['primary_table_name']);
        //print_r($this->data["fileds_info"]);

        $this->data['relation_fields'] = array(
            'role_id' => array('table_name' => 'roles', 'display_column_name' => 'name', "condition" => array("")),
                //'countries_id' => array('table_name' => 'countries', 'display_column_name' => 'name', "condition" => array(""))
        );

        $this->data['column_name_display_as'] = array('city_name' => 'City Name', 'role_id' => 'Role', 'time' => 'Date and Time');

        foreach ($this->data["fileds_info"] as $item) {
            if (strpos($item->Field, "_id")) {
                $db_table_name = substr($item->Field, 0, -3);

                $this->data['column_name_display_as'][$item->Field] = ucfirst(singular($db_table_name));

                if (!$this->db->table_exists($db_table_name)) {
                    continue;
                }
                $db_columns = $this->db->query("DESCRIBE $db_table_name")->result();
                $display_column_name = [];
                foreach ($db_columns as $db_filed) {
                    if ($db_filed->Field == "name") {
                        $display_column_name[] = $db_filed->Field;
                    }
                }

                $this->data['relation_fields'][$item->Field] = array('table_name' => $db_table_name, 'display_column_name' => implode(",", $display_column_name), "condition" => array(""));
            }
        }

        $this->data['ajax_base_relation_result'] = array(
            'cities_id' => array("parent_field_name" => 'countries_id', "filter_from_table" => "cities", "parent_table_name" => "countries", 'display_column_name' => 'name'),
            'locations_id' => array("parent_field_name" => 'cities_id', "filter_from_table" => "locations", "parent_table_name" => "cities", 'display_column_name' => 'name')
        );

        $this->data['hide_columns_in_list_view'] = array("updated_at", "created_At", "password", "salt", "role_id", "device_id", "push_notification_player_id", "referral_code",
            "subscription_mode", "land_mark", "display_image", "address", "min_delivery_time", "max_delivery_time", "countries_id", "status");

        $this->data['hide_columns_in_edit_view'] = array("updated_at", "role_id", "device_id", "push_notification_player_id", "subscription_mode", "referral_code", "status");
        $this->data['hide_columns_in_add_view'] = array("status", "updated_at", "role_id", "device_id", "push_notification_player_id", "subscription_mode", "referral_code", "status");
        $this->data['image_fields'] = array('image', "profile_photo");
        $this->data['email_fields'] = array('username');

        $this->data['action_buttons'] = array(
            "delete_action" => false,
            "edit_action" => true,
            "add_action" => false,
            "view_action" => false
        );

        $this->data['button_size'] = "xs";

        $this->data['unset_all_actions'] = false;

        $this->data['password_fields'] = array("password");
        $this->data['readonly_fields'] = array();

        $this->data['multiple_selection_of_options'] = array("role_ids");

        $this->data['unique_fileds'] = array('email');

        $this->data['signle_file_browse_fileds'] = array();

        $this->data["image_columns_properties"] = array(
            "image" => array("max_size" => "10000", //in kb format only
                "accepted_file_formats" => array("png", "jpeg", "jpg", "gif"))
        );

        $this->data['add_action_buttons'] = array();

        $this->data["generate_random_key_fields"] = array();

        $this->data["exclude_ck_editor_fields"] = array("address");

        if ($this->input->is_ajax_request()) {
            if ($this->input->get_post("type") == "child_data") {

                $this->db->where($this->input->get_post("field_name"), $this->input->get_post($this->input->get_post("field_name")));
                $this->db->where("status", 1);

                $json_result = $this->db->get($this->input->get_post("db_table_name"))->result();
                foreach ($json_result as $item) {
                    if ($this->input->get_post("selected_id") == $item->id) {
                        $item->is_selected = true;
                    }
                }
                echo json_encode($json_result);
            } else if (isset($_GET['existed_value'])) {
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

        if (!empty($_POST)) {


            if (isset($_POST['submit'])) {

                if ($_POST['submit'] == "insert") {
                    unset($_POST['submit']);
                    //print_r($_POST);

                    foreach ($this->data['generate_random_key_fields'] as $key_item) {
                        echo $_POST[$key_item] = $this->curd_model->generate_random_string_code($this->data['primary_table_name'], $key_item);
                    }


                    $keys = array_keys($_POST);

                    $obj = new Requests();
                    $obj->is_password_update_required = true;
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
                        redirect($this->data['current_page_link'] . "?msg=add");
                        //any extra code such as email, sms functionality code
                        //js_redirect($back_button_nav."?msg=add");
                    }
                } else if ($_POST['submit'] == "update") {

                    unset($_POST['submit']);

                    $obj = new Requests();

                    $id = $_POST['id'];
                    if (isset($_POST['password'])) {
                        if ($_POST['password'] != '' && $_POST['password'] != null) {
                            $password = $_POST['password'];
                            unset($_POST['password']);
                            $obj->is_password_update_required = true;
                        } else {
                            unset($_POST['password']);
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
                        redirect($this->data['current_page_link'] . "?msg=update");
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
        $this->db->order_by("id", "desc");
        $data = $this->curd_model->get_all_data_from_table($this->data['primary_table_name']);

        foreach ($data as $item) {
            /* $display_text = $item->name . " ";
              $item->add_action_buttons[] = array(
              "title" => "Manage Gallery",
              "link" => base_url() . "admin/restaurant_gallery?restaurants_id=" . $item->id . "&hide_back_action=true&display_text=" . $display_text,
              "target" => "_blank",
              "btn_class"=>"primary"
              );

              $item->add_action_buttons[] = array(
              "title" => "Manage Work Hours",
              "link" => base_url() . "admin/restaurant_work_hours?restaurants_id=" . $item->id . "&hide_back_action=true&display_text=" . $display_text,
              "target" => "_blank",
              "btn_class"=>"primary"
              ); */
        }

        $this->data["list_items"] = $data;
        $this->admin_view("list_view");
    }

    function add() {
        if ($this->data["action_buttons"]['add_action'] == false) {
            die("Access Deined for add operation");
        }
        $this->admin_view("add_view");
    }

    function edit($item_primary_key) {
        if ($this->data["action_buttons"]['edit_action'] == false) {
            die("Access Deined for edit operation");
        }
        $this->data['edit_item_row'] = $this->curd_model->get_row_from_pk($this->data['primary_table_name'], $item_primary_key);
        $this->admin_view("edit_view");
    }

    function delete($item_primary_key) {
        if ($this->data["action_buttons"]['delete_action'] == true) {
            $this->db->where("id", $item_primary_key);
            $this->db->delete($this->data['primary_table_name']);
            redirect($this->data['current_page_link'] . "?msg=delete");
        } else {
            redirect($this->data['current_page_link']);
        }
    }

    function toggle_status($item_primary_key) {
        $current_status = $this->curd_model->get_status($this->data['primary_table_name'], $item_primary_key);
        if ($current_status != FALSE) {
            $this->curd_model->toggle_status($this->data['primary_table_name'], $item_primary_key, $current_status);
        }
        redirect($this->data['current_page_link'] . "?msg=update");
    }

}
