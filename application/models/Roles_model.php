<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Roles_model extends CI_Model {

    function get_admin_role_id() {
        return 1;
    }

    function get_customer_role_id() {
        return 2;
    }

    function get_professional_role_id() {
        return 3;
    }
    
    function get_professional_staff_role_id(){
        return 4;
    }

    function get_role_name_by_id($id) {
        return $this->db->get_where("roles", ["id" => $id])->row()->name;
    }

    function is_professional_role($user_id) {
        if ($this->user_model->get_user_role_id($user_id) == $this->get_professional_role_id()) {
            return true;
        } else {
            return false;
        }
    }

    function is_customer_role($user_id) {
        if ($this->user_model->get_user_role_id($user_id) == $this->get_customer_role_id()) {
            return true;
        } else {
            return false;
        }
    }

}
