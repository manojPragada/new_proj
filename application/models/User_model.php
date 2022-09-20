<?php

class User_model extends CI_model {

    function send_verfication_email($email) {
        $activation_key = $this->generate_activation_key();
        $this->db->set("activation_key", $activation_key);
        $this->db->where("username", $email);
        $this->db->update("users");

        $ud = $this->db->get_where("users", ["username" => $email])->row();
        $data['name'] = $ud->firstname . " " . $ud->lastname;
        $data['activation_key'] = $activation_key;
        $this->email->from(NO_REPLAY_MAIL, SITE_TITLE);
        $this->email->to($email);
        if (BCC_EMAIL) {
            $this->email->bcc(BCC_EMAIL);
        }
        $this->email->subject("Please verify your email address");
        $message = $this->load->view("mail_templates/send_user_verification_email", $data, true);
        $this->email->message($message);
        $this->email->send();
        return true;
    }

    function send_reset_password_email($user_id) {
        $forgot_password_verfication_code = $this->generate_forgot_password_link();
        $this->db->set("forgot_password_verfication_code", $forgot_password_verfication_code);
        $this->db->set("password_link_expiry_time", strtotime("+30 minutes"));
        $this->db->where("id", $user_id);
        $this->db->update("users");

        $ud = $this->db->get_where("users", ["id" => $user_id])->row();
        $data['name'] = $ud->firstname . " " . $ud->lastname;
        $data['forgot_password_verfication_code'] = $forgot_password_verfication_code;
        $this->email->from(NO_REPLAY_MAIL, SITE_TITLE);
        $this->email->to($ud->username);
        if (BCC_EMAIL) {
            $this->email->bcc(BCC_EMAIL);
        }
        $this->email->subject("Reset Password");
        $message = $this->load->view("mail_templates/send_user_password_reset_link", $data, true);
        $this->email->message($message);
        $this->email->send();
        return true;
    }

    function check_forgotten_password_verification_link_time($forgot_password_verfication_code) {
        if ($forgot_password_verfication_code == "") {
            return false;
        }
        $data = $this->db->get_where("users", ["forgot_password_verfication_code" => $forgot_password_verfication_code]);
        if ($data->num_rows() == 0) {
            return false;
        } else {
            $actual_expiry_time = $data->row()->password_link_expiry_time;
            $current_time = time();
            if ($current_time <= $actual_expiry_time) {
                return true;
            }
            return false;
        }
    }

    function reset_password_by_forgotten_password_verification_link($key, $password, $user_id) {
        $salt = rand(111111, 99999999);
        $enc_password = md5($password . $salt);

        $this->db->set("password", $enc_password);
        $this->db->set("salt", $salt);
        $this->db->set("password_link_expiry_time", null);
        $this->db->set("forgot_password_verfication_code", null);
        $this->db->where("forgot_password_verfication_code", $key);
        $this->db->where("id", $user_id);
        $response = $this->db->update("users");
        if ($response) {
            return true;
        } else {
            return false;
        }
    }

    function get_user_id_with_forgot_password_verfication_code($forgot_password_verfication_code) {
        $data = $this->db->get_where("users", ["forgot_password_verfication_code" => $forgot_password_verfication_code]);
        if ($data->num_rows() == 0) {
            return false;
        } else if ($data->num_rows() == 1) {
            return $data->row()->id;
        } else {
            return false;
        }
    }

    function mark_as_email_verified($activation_key) {
        $row = $this->db->get_where("users", ["activation_key" => $activation_key])->row();
        if (!$row) {
            return false;
        } else {
            if ($row->email_verified == 0) {
                $ud = $this->db->get_where("users", ["activation_key" => $activation_key])->row();
                $this->db->set("email_verified", 1);
                $this->db->set("activation_key", null);
                $this->db->where("activation_key", $activation_key);
                if ($this->db->update("users")) {
                    return true;
                } else {
                    return FALSE;
                }
            }
        }
    }

    function is_username_exists($username) {
        $data = $this->db->get_where("users", ['username' => $username]);
        if ($data->num_rows() == 0) {
            return false;
        }
        $data = $data->row();
        return $data->id;
    }

    function check_user_status($user_id) {
        $role_id = $this->get_user_role_id($user_id);
        $data = $this->db->get_where("users", ['id' => $user_id, "role_id" => $role_id]);
        $user = $data->row();
        if ($user->status == 0) {
            return false;
        }
        return true;
    }

    function check_email_verified($user_id) {
        $role_id = $this->get_user_role_id($user_id);
        $data = $this->db->get_where("users", ['id' => $user_id, "role_id" => $role_id])->row();
        if ($data->email_verified == 0) {
            return false;
        }
        return true;
    }

    function is_professional_described_about_what_is_best($user_id) {
        $data = $this->get_user_details($user_id);
        if ($data->is_described_about_what_is_best == 0) {
            return false;
        } else {
            return true;
        }
    }

    function is_business_details($user_id) {
        $data = $this->get_user_details($user_id);
        if ($data->is_updated_business == 0) {
            return false;
        } else {
            return true;
        }
    }

    function is_payment_details_given($user_id) {
        $data = $this->get_user_details($user_id);
        if ($data->is_payment_details_given == 0) {
            return false;
        } else {
            $this->load->model("professional_model");
            $response = $this->professional_model->get_subscription_info($user_id);
            if ($response["is_expired"]) {
                return false;
            }
            return true;
        }
    }

    function login_validation($user_id, $password) {
        $data = $this->get_user_details($user_id);
        if ($data->password == md5($password . $data->salt)) {
            $this->do_logout();
            try {
                $ip = $_SERVER["REMOTE_ADDR"];
                if ($ip != "::1") {
                    $ch = curl_init();
                    //curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$ip?fields=520191");
                    curl_setopt($ch, CURLOPT_URL, "http://freegeoip.net/json/$ip");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $contents = curl_exec($ch);
                    $response = json_decode($contents);
                    curl_close($ch);
                    if (isset($response->city) && isset($response->region_name) && isset($response->country_name)) {
                        $address = "City : <b>$response->city</b> <br/>
                            Region : <b>$response->region_name</b><br/>	
                            Country : <b>$response->country_name</b><br/>	
                                TimeZone : <b>$response->time_zone</b><br/>	
                            ";

                        $insert_data = array("address" => $address,
                            "user_id" => $data->id, "created_at" => time());
                        $this->db->insert("login_logs", $insert_data);
                    }
                }
            } catch (Exception $exc) {
                log_message(1, "Location not found");
            }
            $_COOKIE['token'] = $data->token;
            $_COOKIE['last_login'] = $this->get_last_login($data->id);
            $_COOKIE['user_type'] = $data->role_id;
            return true;
        } else {
            return false;
        }
    }

    function do_logout() {
        $this->session->unset_userdata("user_id");
        $this->session->unset_userdata("logged");
        $this->session->set_flashdata("timeout");
        return true;
    }

    function get_last_login($user_id) {
        $this->db->order_by("id", "desc");
        $this->db->limit(1, 1);
        $data = $this->db->get_where("login_logs", ["user_id" => $user_id]);
        if ($data->num_rows()) {
            return $data->row()->created_at;
        } else {
            return false;
        }
    }

    function get_user_role_id($user_id) {
        return $this->db->get_where("users", ["id" => $user_id])->row()->role_id;
    }

    function get_user_role_name($role_id) {
        return $this->roles_model->get_role_name_by_id($role_id);
    }

    function get_user_token_by_id($user_id) {
        return $this->db->get_where("users", ["id" => $user_id])->limit(1)->row()->token;
    }

    function check_email_existance($email) {
        if ($this->db->get_where("users", ["username" => $email])->num_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function customer_update_profile($data, $user_id) {
        $this->db->set("firstname", $data['firstname']);
        $this->db->set("lastname", $data['lastname']);
        $this->db->set("mobile", $data['mobile']);
        $this->db->where("id", $user_id);
        $this->db->set("updated_at", time());
        $this->db->update("users");
        return true;
    }

    function validate_token($token) {
        $this->db->limit(1);
        $this->db->where("token", $token);
        $data = $this->db->get("users");
        if ($data->num_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function delete_user(int $id) {
        $this->db->where("id", $id);
        return $this->db->delete("users");
    }

    function get_user_details($id) {
        $data = $this->db->get_where("users", ["id" => $id])->row();
        if ($data) {
            $data->country_name = $this->site_model->get_country_name_by_id($data->country);
        }
        return $data;
    }

    function get_user_details_by_username($username) {
        return $this->db->get_where("users", ["username" => $username])->row();
    }

    function generate_activation_key() {
        $activation_key = generateRandomString(20);
        if ($this->db->get_where("users", ["activation_key" => $activation_key])->num_rows() == 0) {
            return $activation_key;
        } else {
            return $this->generate_activation_key();
        }
    }

    function generate_security_token() {
        $token = generateRandomString(32);
        if ($this->db->get_where("users", ["token" => $token])->num_rows() == 0) {
            return $token;
        } else {
            return $this->generate_security_token();
        }
    }

    function check_is_email_by_username_verified($username) {
        return $this->db->get_where("users", ["email_verified" => 1, "username" => $username])->row();
    }

    function generate_forgot_password_link() {
        $forgot_password_verfication_code = generateRandomString(32);
        if ($this->db->get_where("users", ["forgot_password_verfication_code" => $forgot_password_verfication_code])->num_rows() == 0) {
            return $forgot_password_verfication_code;
        } else {
            return $this->generate_forgot_password_link();
        }
    }

    function reset_password_send_email_link(string $username) {
        if ($this->check_email_existance($username) == FALSE) {
            return FALSE;
        } else {
            $code = $this->generate_forgot_password_link();
            $this->db->set("forgot_password_verfication_code", $code);
            $this->db->where("username", $username);
            if ($this->db->update("users")) {
                $this->email->from(NO_REPLAY_MAIL, SITE_TITLE);
                $this->email->to($username);
                if (BCC_EMAIL) {
                    $this->email->bcc(BCC_EMAIL);
                }
                $data = $this->get_user_details_by_username($id);
                $this->email->subject("Reset your " . SITE_TITLE . " password");
                $message = $this->load->view("mail_templates/user_forgot_password_reset_page", $data, true);
                $this->email->message($message);
                $this->email->send();
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function get_user_id_by_token($token) {
        return $this->db->get_where("users", ["access_token" => $token])->row()->id;
    }

    function update_user_business_locations() {
        extract($_POST);
        $user_id = $this->get_user_id_by_token($token);
        $data = array(
            "address_line_1" => $address_line_1,
            "address_line_2" => $address_line_2,
            "city" => $city,
            "state" => $state,
            "zipcode" => $zipcode,
            "country" => $country,
            "updated_at" => time(),
        );
        $this->db->where("id", $id);
        $this->db->where("user_id", $user_id);
        if ($this->db->update("user_business_locations", $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_business_details() {
        extract($_POST);
        $user_id = $this->get_user_id_by_token($token);
        $data = array(
            "user_id" => $user_id,
            "business_tile" => $business_tile,
            "description" => addslashes($description),
            "address" => $address,
            "website" => $state,
            "contact_number" => $contact_number,
            "business_type" => $business_type,
            "country" => $country,
            "time_zone" => $timezone,
            "currency" => $currency,
            "time_format" => $time_format,
            "created_at" => time(),
        );
        if ($this->db->insert("user_business_details", $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_business_details() {
        extract($_POST);
        $user_id = $this->get_user_id_by_token($token);
        $data = array(
            "user_id" => $user_id,
            "business_tile" => $business_tile,
            "description" => addslashes($description),
            "address" => $address,
            "website" => $state,
            "contact_number" => $contact_number,
            "business_type" => $business_type,
            "country" => $country,
            "time_zone" => $timezone,
            "currency" => $currency,
            "time_format" => $time_format,
            "updated_at" => time(),
        );
        $this->db->where("id", $id);
        $this->db->where("user_id", $user_id);
        if ($this->db->update("user_business_details", $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function add_user_what_describes() {
        extract($_POST);
        $user_id = $this->get_user_id_by_token($token);
        $data = array(
            "meta_key" => "Service Option",
            "meta_value" => $service,
            "user_id" => $user_id
        );
        if ($this->db->insert("user_meta", $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_profile_picture($file_name, $user_id) {
        $this->db->set("profile_image", $file_name);
        $this->db->where("id", $user_id);
        $update = $this->db->update("users");
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

}
