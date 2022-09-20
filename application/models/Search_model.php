<?php

class Search_model extends CI_Model {

    public $result = [];

    function run_search_query() {
        $professional_role = $this->roles_model->get_professional_role_id();
        extract($_REQUEST);
        $q = $this->input->get_post("q") ? $this->input->get_post("q") : NULL;
        $loc = $this->input->get_post("loc") ? $this->input->get_post("loc") : NULL;


        $this->db->select(
                "u.firstname, u.lastname, u.username as business_email, "
                . "u.profile_image, u.business_type, u.business_name, "
                . " u.token, u.address,"
                . "u.city, u.state, u.zipcode, u.country"
        );

        $this->db->from("users u");
        $this->db->join("subscription_info subscription", "subscription.user_id=u.id");

        $this->db->where("subscription.expiry_date >=  ", date("Y-m-d"));
        $this->db->where("subscription.status", 1);

        if ($q) {
            $this->db->where("(u.business_name LIKE '%$q%' "
                    . "OR u.described_service LIKE '%$q%' )");
        }

        if ($this->input->get_post("category")) {
            //$this->db->join("c categories");
            //$this->db->join("s services");
        }

        if ($loc) {
            $location_query = "(city LIKE '%$loc%' OR zipcode LIKE '%$loc%' OR state LIKE '%$loc%')";
            $this->db->like($location_query);
        }

        if ($this->input->get_post("sort_by")) {
            $sort = $this->input->get_post("sort_by");
            switch ($sort) {
                case "mv" : break;
                case "lp" : break;
                case "hp" : break;
                case "ra" : $this->db->order_by("created_at", "desc");
            }
        }

        if ($this->input->get_post('option_filter')) {
            // print_r($this->input->get_post('option_filter'));
        }

        //constraints
        $this->db->where("u.email_verified", 1);
        $this->db->where("role_id", $professional_role);
        $this->db->group_by("u.id");
        $this->result = $this->db->get();

        //echo $this->db->last_query();
    }

    function get_result_data() {
        $data_result = $this->result->result();
        foreach ($data_result as $item) {
            $item->rating = rand(2, 5);
            $item->is_online_payment_support = rand(0, 1);
            $item->is_cash_on_services_support = rand(0, 1);
            $item->price_rating = rand(1, 4);
            $item->country_name = $this->site_model->get_country_name_by_id($item->country);
        }
        return $data_result;
    }

    function get_total_results_count() {
        return $this->result->num_rows();
    }

    function set_limit_for_results($limit, $start) {
        $this->db->limit($limit, $start);
    }

}
