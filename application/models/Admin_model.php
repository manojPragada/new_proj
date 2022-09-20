<?php

class Admin_model extends CI_model {

    function update_site_settings(){
        extract($_REQUEST);
        $user_id = $this->user_model->get_user_id_by_token($token);
        $data = array(
            "keywords" => $keywords,
            "description" => $description,
            "google_analytics"=> $google_analytics,
            "contact_number"=>$contact_number,
            "contact_email"=>$contact_email,
            "contact_address"=>$contact_address,
            "user_id" => $user_id,
            "updated_at" => time()
        );
        $this->db->where("id", $id);
        $this->db->update("site_settings", $data);
    }
    
    function get_site_settings(){
        return $this->db->get("site_settings")->row();
    }
    
    function get_customers() {
        $role_id = $this->roles_model->get_customer_role_id();
        $this->db->select("firstname, lastname, username, mobile, created_at, updated_at, status");
        $this->db->where("role_id", $role_id);
        return $this->db->get("users")->result();
    }
    
    function get_professionals() {
        $role_id = $this->roles_model->get_professional_role_id();
        $this->db->where("role_id", $role_id);
        return $this->db->get("users")->result();
    }
    
    function add_customer(){
        $table = "users";
        extract($_POST);
        $salt = rand(111111, 99999999);
        $password = $password;
        $enc_password = md5($password . $salt);
        $activation_key = $this->generate_activation_key();
        $data = array(
            "firstname" => $firstname,
            "lastname"=>$lastname,
            "username" => $username,
            "password" => $enc_password,
            "salt" => $salt,
            "role_id" => $this->roles_model->get_customer_role_id(),
            "activation_key" => $activation_key,
            "created_at" => time(),
            "token" => $this->generate_security_token()
        );
        $info = $this->db->insert($table, $data); 
        if($info){
            $this->email->from(NO_REPLAY_MAIL, SITE_TITLE);
            $this->email->to($username);
            if(BCC_EMAIL){
                $this->email->bcc(BCC_EMAIL);
            }
            $this->email->subject("Welcome to ".SITE_TITLE."!, Your registration successfull ");
            $message=$this->load->view("mail_templates/user_registration_success",'',true);
            $this->email->message($message);
            $this->email->send();
            return true;
        } else {
            return FALSE;    
        }
    }
    
    function update_customer(){
        $table = "users";
        extract($_POST);
        $data = array(
            "firstname" => $firstname,
            "lastname"=>$lastname,
            "username" => $username,
            "role_id" => $this->roles_model->get_customer_role_id(),
            "updated_at" => time()
        );
        $this->db->where("id", $id);
        if($password!=""){
            $salt = rand(111111, 99999999);
            $password = $password;
            $enc_password = md5($password . $salt);
            $this->db->set("password", $enc_password);            
            $this->db->set("salt", $salt);
        }
        $info = $this->db->update($table, $data); 
        if($info){
            return true;
        } else {
            return FALSE;    
        }
    }
    
    function add_professional(){
        $table = "users";
        extract($_POST);
        $salt = rand(111111, 99999999);
        $password = $password;
        $enc_password = md5($password . $salt);
        $activation_key = $this->generate_activation_key();
        $data = array(
            "firstname"=>$firstname,
            "lastname"=>$lastname,
            "mobile" => $mobile,
            "business_type"=>$business_type,
            "username" => $username,
            "password" => $enc_password,
            "salt" => $salt,
            "role_id" => $this->roles_model->get_professional_role_id(),
            "activation_key" => $activation_key,
            "created_at" => time(),
            "token" => $this->generate_security_token()
        );
        $info = $this->db->insert($table, $data); 
        if($info){
            $this->email->from(NO_REPLAY_MAIL, SITE_TITLE);
            $this->email->to($username);
            if(BCC_EMAIL){
                $this->email->bcc(BCC_EMAIL);
            }
            $this->email->subject("Welcome to ".SITE_TITLE."!, Your registration successfull ");
            $message=$this->load->view("mail_templates/user_registration_success",'',true);
            $this->email->message($message);
            $this->email->send();
            return true;
        } else {
            return FALSE;    
        }
    }

    function get_roles() {
        return $this->db->get("roles")->result();
    }

    function get_privileges() {
        return $this->db->get("privileges")->result();
    }

    function add_update_access_rights() {
        $table = "access_rights";
        extract($_POST);

        foreach ($_POST['privilege_name'] as $name) {
            if ($name == "CATEGORY_WISE_PERMISSIONS") {
                $data = array(
                    "role_id" => $role,
                    "privileges_code" => $name,
                    "categories" => implode(",", $_POST['category']),
                    "phases" => implode(",", $_POST['phases']),
                    "status" => 1
                );
            } else {
                $data = array(
                    "role_id" => $role,
                    "privileges_code" => $name,
                    "status" => in_array($name, $_POST['privilege_code']) ? 1 : 0
                );
            }
            if ($this->db->get_where($table, ["privileges_code" => $name, "role_id" => $role])->num_rows()) {
                $this->db->set($data);
                $this->db->where(["privileges_code" => $name, "role_id" => $role]);
                $this->db->set("updated_at", time());
                $this->db->update($table);
            } else {
                $this->db->set("created_at", time());
                $this->db->insert($table, $data);
            }
        }
        return true;
    }

    function get_access_rights($id) {
        return $this->db->get_where("access_rights", ["role_id" => $id])->result();
    }

    function get_all_active_access_rights($role_id) {
        return $this->db->get_where("access_rights", ["role_id" => $id, "status" => 1])->result();
    }

    function get_all_active_access_rights_count($role_id) {
        return $this->db->get_where("access_rights", ["role_id" => $role_id, "status" => 1])->num_rows();
    }

    function has_access_right($role_id, $privilege_code) {
        return $this->db->get_where("access_rights", ["privileges_code" => $privilege_code, "role_id" => $role_id, "status" => 1])->num_rows();
    }

    function has_access_right_for_category($role_id, $category_id) {
        $this->db->where("FIND_IN_SET($category_id, categories)");
        $this->db->where("role_id", $role_id);
        return $this->db->get_where("access_rights")->num_rows();
    }

    function has_access_right_for_phase($role_id, $phase) {
        $this->db->where("FIND_IN_SET($phase, phases)");
        $this->db->where("role_id", $role_id);
        return $this->db->get_where("access_rights")->num_rows();
    }

    function add_update_role() {
        $table = "roles";
        extract($_POST);
        $data = array(
            "name" => $name
        );
        if (isset($_REQUEST['id'])) {
            $this->db->set($data);
            $this->db->where("id", $id);
            return $this->db->update($table);
        } else {
            return $this->db->insert($table, $data);
        }
    }

    function delete_role($id) {
        $this->db->where("id", $id);
        return $this->db->delete("roles");
    }

    function add_update_branch() {
        $table = "branches";
        extract($_POST);
        $data = array(
            "name" => $name,
            "unique_code" => $unique_code,
            "pincode" => $pincode,
            "phone_no" => $phone_no
        );
        if (isset($_REQUEST['id'])) {
            $this->db->set("updated_at", time());
            $this->db->set($data);
            $this->db->where("id", $id);
            return $this->db->update($table);
        } else {
            $this->db->set("created_at", time());
            return $this->db->insert($table, $data);
        }
    }

    function get_branch_details($id) {
        return $this->db->get_where("branches", ["id" => $id])->row();
    }

    function delete_branch($id) {
        $this->db->where("id", $id);
        return $this->db->delete("branches");
    }

    function get_categories() {
        return $this->db->get("categories")->result();
    }

    function add_update_category() {
        $table = "categories";
        extract($_POST);
        $data = array(
            "name" => $name,
            "unique_code" => $unique_code,
            "description" => $description,
            "application_fee" => $application_fee
        );
        if (isset($_REQUEST['id'])) {
            $this->db->set("updated_at", time());
            $this->db->set($data);
            $this->db->where("id", $id);
            return $this->db->update($table);
        } else {
            $this->db->set("created_at", time());
            return $this->db->insert($table, $data);
        }
    }

    function delete_category($id) {
        $this->db->where("id", $id);
        return $this->db->delete("categories");
    }

    function get_category_details($id) {
        return $this->db->get_where("categories", ["id" => $id])->row();
    }

    function get_employees() {
        $items = $this->db->get("users")->result();
        foreach ($items as $item) {
            $item->role = $this->get_role_name($item->role);
            $item->branch_id = explode(",", $item->branch_id);
            $item->branches = [];
            foreach ($item->branch_id as $it) {
                $item->branches[] = $this->get_branch_name($it);
            }
        }
        return $items;
    }

    function add_update_employee() {
        $table = "users";
        extract($_POST);
        $data = array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "branch_id" => $branch_id,
            "username" => $username,
            "role" => $role,
            "email" => $email,
            "mobile" => $mobile,
            "address" => addslashes($address)
        );
        if (isset($_REQUEST['id'])) {
            $this->db->set("updated_at", time());
            $this->db->set($data);
            $this->db->where("id", $id);
            return $this->db->update($table);
        } else {
            $this->db->set("salt", $salt);
            $this->db->set("password", $password);
            $this->db->set("created_at", time());

            return $this->db->insert($table, $data);
        }
    }

    function delete_employee($id) {
        $this->db->where("id", $id);
        return $this->db->delete("users");
    }

    function get_employee_details($id) {
        return $this->db->get_where("users", ["id" => $id])->row();
    }

    function get_role_name($id) {
        return $this->db->get_where("roles", ["id" => $id])->row()->name;
    }

    function get_branch_name($id) {
        return $this->db->get_where("branches", ["id" => $id])->row()->name;
    }

    function get_username($id) {
        return $this->db->get_where("users", ["id" => $id])->row()->username;
    }

    function get_user_role($id) {
        return $this->db->get_where("users", ["id" => $id])->row()->role;
    }

    function get_logs() {
        $this->db->order_by("id", "desc");
        $items = $this->db->get('login_logs')->result();
        foreach ($items as $item) {
            $item->username = $this->get_username($item->user_id);
            $item->role = $this->get_role_name($this->get_user_role($item->user_id));
        }
        return $items;
    }

    function get_all_new_applications() {
        $today = strtotime('today');
        $tomorrow = strtotime('tomorrow');

        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("forms", ["created_at >=" => $today, "created_at <= " => $tomorrow])->result();
        foreach ($data as $item) {
            $b = $this->db->get_where("form_branch", ['form_id' => $item->id])->row();
            $uid = $b->user_id;
            $bid = $b->branch_id;
            $item->username = $this->get_username($uid);
            $item->branch_name = $this->get_branch_name($bid);
            $item->history = $this->get_latest_action_history($item->id);
        }
        return $data;
    }

    function get_under_review_applications() {
        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("forms", ["application_status" => "UNDER REVIEW"])->result();
        foreach ($data as $item) {
            $b = $this->db->get_where("form_branch", ['form_id' => $item->id])->row();
            $uid = $b->user_id;
            $bid = $b->branch_id;
            $item->username = $this->get_username($uid);
            $item->branch_name = $this->get_branch_name($bid);
            $item->history = $this->get_latest_action_history($item->id);
        }
        return $data;
    }

    function get_pending_payment_applications() {
        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("forms", ["application_status" => "REVIEWED"])->result();
        foreach ($data as $item) {
            $b = $this->db->get_where("form_branch", ['form_id' => $item->id])->row();
            $uid = $b->user_id;
            $bid = $b->branch_id;
            $item->username = $this->get_username($uid);
            $item->branch_name = $this->get_branch_name($bid);
            $item->history = $this->get_latest_action_history($item->id);
        }
        return $data;
    }

    function get_fixing_meter_applications() {
        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("forms", ["application_status" => "READY_TO_FIX_METER"])->result();
        foreach ($data as $item) {
            $b = $this->db->get_where("form_branch", ['form_id' => $item->id])->row();
            $uid = $b->user_id;
            $bid = $b->branch_id;
            $item->username = $this->get_username($uid);
            $item->branch_name = $this->get_branch_name($bid);
            $item->history = $this->get_latest_action_history($item->id);
        }
        return $data;
    }

    function get_completed_applications() {
        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("forms", ["application_status" => "COMPLETED"])->result();
        foreach ($data as $item) {
            $b = $this->db->get_where("form_branch", ['form_id' => $item->id])->row();
            $uid = $b->user_id;
            $bid = $b->branch_id;
            $item->username = $this->get_username($uid);
            $item->branch_name = $this->get_branch_name($bid);
        }
        return $data;
    }

    function get_application_action_history($application_id) {
        $this->db->order_by("id", "desc");
        $data = $this->db->get_where("form_actions", ["form_id" => $application_id])->result();
        foreach ($data as $item) {
            $item->branch_name = $this->get_branch_name($item->branch_id);
            $item->username = $this->get_username($item->user_id);
            $item->user_role = $this->get_user_role($this->user_id);
            $item->role_name = $this->get_role_name($item->user_role);
        }
        return $data;
    }

    function get_latest_action_history($application_id) {
        $this->db->order_by("id", "desc");
        $item = $this->db->get_where("form_actions", ["form_id" => $application_id])->row();

        $item->branch_name = $this->get_branch_name($item->branch_id);
        $item->username = $this->get_username($item->user_id);
        $item->user_role = $this->get_user_role($this->user_id);
        $item->role_name = $this->get_role_name($item->user_role);

        return $item;
    }

}
