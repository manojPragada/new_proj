<?php

class Site_model extends CI_Model {

    function is_username_exists($username) {
        $data = $this->db->get_where("users", ['username' => $username]);
        if ($data->num_rows() == 0) {
            return false;
        }
        $data = $data->row();
        return $data->id;
    }

    function get_home_page_menu() {
        $data = $this->db->get_where("categories", ["status" => 1])->result();
        foreach ($data as $item) {
            $item->sub_category = $this->get_home_page_sub_menu($item->id);
        }
        return $data;
    }

    function get_site_properties() {
        return $this->db->get_where("site_settings", ["id" => 1])->row();
    }

    function get_home_page_sub_menu($id) {
        return $this->db->get_where("sub_categories", ["category_id" => $id, "status" => 1])->result();
    }

    function get_active_professionals_count() {
        $role_id = $this->roles_model->get_professional_role_id();
        $this->db->where("role_id", $role_id);
        $this->db->where("email_verified", 1);
        $this->db->where("status", 1);
        return $this->db->get("users")->num_rows();
    }

    function get_active_customers_count() {
        $role_id = $this->roles_model->get_customer_role_id();
        $this->db->where("role_id", $role_id);
        $this->db->where("email_verified", 1);
        $this->db->where("status", 1);
        return $this->db->get("users")->num_rows();
    }

    function get_active_services_count() {
        $this->db->where("status", 1);
        return $this->db->get("sub_categories")->num_rows();
    }

    function get_social_media_links() {
        return $this->db->get_where("socail_media")->row();
    }

    function check_user_status($user_id) {
        $data = $this->db->get_where("users", ['id' => $user_id, "role_id" => 1]);
        $data = $data->row();
        if ($data) {
            if ($data->status == 0) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    function user_profile_update() {
        extract($_POST);
        $user_id = $this->get_logged_user_id();
        $data = array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "mobile" => $mobile,
            "email" => $email,
            "address" => addslashes($address),
            "updated_at" => time()
        );
        $this->db->set($data);
        $this->db->where("id", $user_id);
        return $this->db->update("users");
    }

    function get_user_role($user_id) {
        return $this->db->get_where("users", ['id' => $user_id])->row()->role_id;
    }

    function get_logged_user_id() {
        return $this->session->userdata("user_id");
    }

    function reset_password($user_id) {
        $password = rand(1, 9) * 11;
        $password .= rand(1, 9) * 11;
        $password .= rand(1, 9) * 11;
        $salt = rand(552555, 258242152);
        $smsPwd = $password;
        $password = md5($password . $salt);
        $this->db->set("password", $password);
        $this->db->set("salt", $salt);
        $this->db->where("id", $user_id);
        if ($this->db->update("users")) {

            $ud = $this->get_user_details($user_id);

            $mobile_number = $ud->mobile;
            $message = "Dear " . $ud->firstname . " " . $ud->lastname . ", Your New Password is - " . $smsPwd . ", Please Don\'t share with anyone";
            send_message($message, $mobile_number);
            return true;
        } else {
            return false;
        }
    }

    function user_profile_password_update($user_id) {
        extract($_POST);
        $salt = rand(552555, 258242152);
        $smsPwd = $password;
        $password = md5($password . $salt);
        $this->db->set("password", $password);
        $this->db->set("salt", $salt);
        $this->db->where("id", $user_id);
        if ($this->db->update("users")) {

            $ud = $this->get_user_details($user_id);

            return true;
        } else {
            return false;
        }
    }

    function get_countries() {
        return $this->db->get_where("countries", ["status" => 1])->result();
    }

    function check_for_current_password_ok($user_id) {
        extract($_POST);
        $ud = $this->get_user_details($user_id);
        if ($ud->password == md5($current_password . $ud->salt)) {
            return true;
        } else {
            return false;
        }
    }

    function get_user_details($user_id) {
        $user_details = $this->db->get_where("users", ['id' => $user_id])->row();
        return $user_details;
    }

    function login_validation($user_id, $password) {
        $data = $this->get_user_details($user_id);
        if ($data->password == md5($password . $data->salt)) {
            $this->do_logout();
            $ip = $_SERVER["REMOTE_ADDR"];

            $this->session->set_userdata("user_id", $user_id);
            $this->session->set_userdata("logged", true);

            if ($ip != "::1") {
                $ch = curl_init();
                //curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$ip?fields=520191");
                curl_setopt($ch, CURLOPT_URL, "http://freegeoip.net/json/$ip");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $contents = curl_exec($ch);
                $response = json_decode($contents);
                curl_close($ch);
                
            }
            $insert_data = array("name"=>$data->username, "ip_address" => $ip,
                        "created_at" => time());
            $this->db->insert("login_logs", $insert_data);

            return true;
        } else {
            return false;
        }
    }

    function check_for_user_logged() {
        $user_id = $this->get_logged_user_id();
        if ($this->session->userdata("logged")) {
            if ($this->get_user_role($user_id) != 1) {
                $this->session->unset_userdata("user_id");
                $this->session->unset_userdata("logged");
                $this->session->set_flashdata("timeout");
                return false;
            }
            if ($this->check_user_status($user_id) == false) {
                return false;
            }
            return true;
        } else {
            $this->session->unset_userdata("user_id");
            $this->session->unset_userdata("logged");
            $this->session->set_flashdata("timeout");
            return false;
        }
    }

    function do_logout() {
        $this->session->unset_userdata("user_id");
        $this->session->unset_userdata("logged");
        $this->session->set_flashdata("timeout");
        delete_cookie("token");
        unset($_COOKIE["token"]);
        unset($_COOKIE["last_login"]);
        return true;
    }

    function get_currencies() {
        return $this->db->get("currency")->result();
    }

    function get_timezones() {
        return $this->db->get("time_zones")->result();
    }

    function get_refferal_sources() {
        return $this->db->get("referal_soruces", ["status" => 1])->result();
    }

    function get_cancellation_reasons() {
        return $this->db->get("cancellation_reasons", ["status" => 1])->result();
    }

    function get_country_name_by_id($id) {
        if ($id) {
            $this->db->limit(1);
            $data = $this->db->get_where("countries", ["id" => $id]);
            if ($data->num_rows()) {
                return $data->row()->name;
            }
        }
    }

    function add_professional_enquiry($data, $user_id) {

        extract($data);
        $today = strtotime('today');
        $tomorrow = strtotime('tomorrow');
        $count_value = 1;

        $max_id = $count_value + ($this->db->query("SELECT * FROM  `inquiry` WHERE created_at > $today && created_at < $tomorrow")->num_rows() + 1);

        $now_order_value = $max_id;

        $ref_id = date('dmY') . '-' . $now_order_value;

        $this->db->set("professional_id", $professional_id);
        $this->db->set("ref_id", $ref_id);
        $this->db->set("name", $name);
        $this->db->set("mobile", $mobile);
        $this->db->set("email", $email);
        $this->db->set("uploads", $uploads);
        $this->db->set("feedback", $feedback);
        $this->db->set("created_at", time());
        if ($this->db->insert("inquiry")) {
            $inquiry_id = $this->db->insert_id();
            $ip = $this->input->ip_address();
            $response = json_decode(file_get_contents("http://ip-api.com/json/$ip?fields=520191"));

            if (isset($response->city) && isset($response->regionName) && isset($response->country) && isset($response->isp)) {
                $address = "City : <b>$response->city</b> <br/>
                        Region : <b>$response->regionName</b><br/>	
                        Country : <b>$response->country</b><br/>	
                        Internet Provider : <b>$response->isp</b><br/>
                        IP Address: <b>$ip</b>
                        ";
                $insert_data = array("log_info" => $address);
                $this->db->where("id", $inquiry_id);
                $this->db->set($insert_data);
                $this->db->update("inquiry");
            }
            return true;
        } else {
            return false;
        }
    }

}
