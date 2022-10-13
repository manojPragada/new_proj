<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * My_model is Developed by peacekeeper
 *
 * @author peacekeeper
 */
class My_model extends CI_Model {

    function get_data($table, $where = null, $select = null, $order_by_column = "id", $order_by = "desc", $check_status = false, $limit = null, $offset = 0) {
        if (!empty($select)) {
            $this->db->select($select);
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($order_by_column) {
            $this->db->order_by($order_by_column, $order_by);
        }
        if ($check_status) {
            $this->db->where("status", true);
        }
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get($table)->result();
    }

    function get_data_row($table, $where = null, $select = null, $check_status = false) {
        if (!empty($select)) {
            $this->db->select($select);
        }
        if ($where) {
            $this->db->where($where);
        }
        if ($check_status) {
            $this->db->where("status", true);
        }
        return $this->db->get($table)->row();
    }

    function insert_data($table, $data) {
        if ($this->db->insert($table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function insert_multi_data($table, $data) {
        foreach ($data as $ins) {
            if (!$this->db->insert($table, $ins)) {
                return false;
            }
        }
        return true;
    }

    function update_data($table, $where, $data) {
        if ($where) {
            $this->db->where($where);
        }
        $this->db->set($data);
        $this->db->update($table);
        return $this->db->affected_rows();
    }

    function update_multi_data($table, $data, $where_key) {
        return $this->db->update_batch($table, $data, $where_key);
    }

    function delete_data($table, $where, $file = "") {
        if (!empty($file)) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    function get_total($table, $column, $where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->select("SUM(" . $column . ") as " . $column);
        $res = $this->db->get($table)->row();
        return $res;
    }

    function kyc_change_status($customer_id, $status, $remarks = "") {
        $this->db->trans_start();
        if ($status == "Approved") {
            $data = array("kyc_status" => $status, "kyc_approved_by_user_id" => $this->session->userdata("admin_user_id"), "kyc_approved_date_time" => time());
        } else
        if ($status == "Rejected") {
            $data = array("kyc_status" => $status, "kyc_rejected_by_user_id" => $this->session->userdata("admin_user_id"), "kyc_rejected_date_time" => time(), 'remarks' => $remarks);
        }
        $this->db->set($data);
        $this->db->where("customer_id", $customer_id);
        $this->db->update("customers_kyc");
        $this->db->set(array("kyc_status" => $status));
        $this->db->where("id", $customer_id);
        $this->db->update("customers");
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function get_packages_by_vehicle_category($category_id) {
        $this->db->group_by("rental_package_names_id");
        $data = $this->get_data("fixed_rental_price_config", array("vehicle_categories_id" => $category_id), null, "id", "asc");
        if (!empty($data)) {
            $out = [];
            foreach ($data as $row) {
                $package_name = $this->get_data_row("rental_package_names", array("id" => $row->rental_package_names_id))->package_title;
                $data = $this->get_data("fixed_rental_price_config", array("vehicle_categories_id" => $category_id, "rental_package_names_id" => $row->rental_package_names_id), null, "id", "asc");
                $out[] = [
                    "title" => $package_name,
                    "data" => $data
                ];
            }
            return $out;
        }
        return false;
    }

}
