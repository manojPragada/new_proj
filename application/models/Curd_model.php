<?php
class Curd_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function get_fields($table_name){
        $fields = $this->get_full_fields_info($table_name);
        $ci_fields_data = $this->db->field_data($table_name);
        foreach($fields as $key=>$item){
            $item->data_type = $ci_fields_data[$key]->type;            
            $item->maximum_text_acceptable_length = $ci_fields_data[$key]->max_length;            
            $item->is_primary_key = $ci_fields_data[$key]->primary_key;            
            $item->is_unique_key = $item->Key=="UNI"?1:0;            
            $item->default_value = $item->Default;            
            $item->is_enum = $ci_fields_data[$key]->type=="enum"?1:0;            
            $item->is_set = $ci_fields_data[$key]->type=="set"?1:0;            
            $item->column_name = $item->Field;
        }
        return $fields;
    }
    
    function get_full_fields_info($table_name){
        $data = $this->db->query("SHOW FULL COLUMNS FROM $table_name")->result();
        return $data;
    }
    
    function get_column_names($table_name){
        print_r($this->db->list_fields($table_name));
    }
    function get_status($table_name, $primary_key){
        $data = $this->db->get_where($table_name, ["id"=>$primary_key])->row();
        if($data->num_rows()){
            return $data->row()->status;
        }else{
            return FALSE;
        }
    }
    function toggle_status($table_name, $primary_key, $current_status){
        $status = $current_status==0?1:0;
        $this->db->set("status", $status);
        $this->db->where("id", $primary_key);
        return $this->db->update($table_name);
    }
    function get_row_from_pk($table_name, $primary_key){
        return $this->db->get_where($table_name, ["id"=>$primary_key])->row();
    }
    function get_all_data_from_table($table_name){
        $data = $this->db->get($table_name);
        return $data->result();
    }
    function get_all_data_from_table_with_limit($table_name, $limit = false, $start = false) {
        if ($limit) {
            $this->db->limit($limit, $start);
        }
        $data = $this->db->order_by('id', 'desc')->get($table_name);
//        echo $this->db->last_query();
//        die;

        return $data->result();
    }
    function get_all_data_from_table_with_where_limit($table_name, $limit = false, $start = false, $where = false) {
        if ($limit) {
            $this->db->limit($limit, $start);
        }
        $data = $this->db->order_by('id', 'desc')->where($where)->get($table_name);
//        echo $this->db->last_query();
//        die;

        return $data->result();
    }

    function generate_random_string_code($table_name, $column_name) {
        $string = $this->generateRandomString(6);
        if ($this->db->get_where($table_name, [$column_name => $string])->num_rows() == 0) {
            return $string;
        } else {
            return $this->generate_random_string_code($table_name, $column_name);
        }
    }
    
    function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function get_dashboard_menu(){
        $this->db->order_by("display_order", "asc");
        $this->db->where("status", 1);
        $data = $this->db->get("admin_dashboard_main_menu")->result();
        foreach ($data as $item){
            $item->sub_menu = $this->get_sub_dashboard_menu($item->id);
        }
        return $data;
    }
    function get_sub_dashboard_menu($main_menu_id){
        $this->db->order_by("display_order", "asc");
        $this->db->where("status", 1);
        $this->db->where("main_menu_id", $main_menu_id);
        $data = $this->db->get("admin_dashboard_sub_menu");
        if($data->num_rows()){
            return $data->result();
        }else{
            return false;
        }
    }
}