<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Lorries is Developed by peacekeeper
 *
 * @author peacekeeper
 */
class Lorries extends Api_controller {

    public $user_data, $user_id;

    public function __construct() {
        parent::__construct();
        $access_token = $this->input->post("access_token");
        $this->user_data = $this->check_owner($access_token);
        $this->user_id = $this->user_data->id;
        $this->base_url = base_url() . "uploads/lorries/";
    }

    public function index() {
        $trucks = $this->my_model->get_data("lorries", ["owner_id" => $this->user_id]);
        if (!empty($trucks)) {
            foreach ($trucks as $item) {
                $item->image_1 = $this->base_url . $item->reg_no . "/" . $item->image_1;
                $item->image_2 = $this->base_url . $item->reg_no . "/" . $item->image_2;
                $item->rc_book_front_image = $this->base_url . $item->reg_no . "/" . $item->rc_book_front_image;
                $item->rc_book_back_image = $this->base_url . $item->reg_no . "/" . $item->rc_book_back_image;
                $item->insurance_certificate_front = $this->base_url . $item->reg_no . "/" . $item->insurance_certificate_front;
                $item->insurance_certificate_back = $this->base_url . $item->reg_no . "/" . $item->insurance_certificate_back;
                unset($item->created_at);
                unset($item->updated_at);
                unset($item->status);
            }
            $arr = [
                "status" => "valid",
                "type" => "",
                "data" => $trucks
            ];
        } else {
            $arr = [
                "status" => "invalid",
                "type" => "",
                "message" => "No Trucks Data Found!"
            ];
        }
        echo json_encode($arr);
        die;
    }

    public function get_single() {
        $this->form_validation->set_rules("id", "Truck Id", "trim|required", array('required' => 'Please Select a Truck'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $id = $this->input->post("id");
            $truck = $this->my_model->get_data_row("lorries", ["id" => $id, "owner_id" => $this->user_id]);
            if (!empty($truck)) {
                $truck->image_1 = $this->base_url . $truck->reg_no . "/" . $truck->image_1;
                $truck->image_2 = $this->base_url . $truck->reg_no . "/" . $truck->image_2;
                $truck->rc_book_front_image = $this->base_url . $truck->reg_no . "/" . $truck->rc_book_front_image;
                $truck->rc_book_back_image = $this->base_url . $truck->reg_no . "/" . $truck->rc_book_back_image;
                $truck->insurance_certificate_front = $this->base_url . $truck->reg_no . "/" . $truck->insurance_certificate_front;
                $truck->insurance_certificate_back = $this->base_url . $truck->reg_no . "/" . $truck->insurance_certificate_back;
                unset($truck->created_at);
                unset($truck->updated_at);
                unset($truck->status);
                $arr = [
                    "status" => "valid",
                    "type" => "",
                    "data" => $truck
                ];
            } else {
                $arr = [
                    "status" => "invalid",
                    "type" => "",
                    "message" => "No Trucks Data Found!"
                ];
            }
        }
        echo json_encode($arr);
        die;
    }

    public function add() {
        $this->form_validation->set_rules("reg_no", "Registration Number", "trim|required", array('required' => 'Please enter Registration Number'));
        $this->form_validation->set_rules("rc_book_number", "RC Book Number", "trim|required", array('required' => 'Please enter RC Book Number'));
        $this->form_validation->set_rules("vehicle_type_id", "Vehicle Type", "trim|required", array('required' => 'Please Select Vehicle Type'));
        $this->form_validation->set_rules("truck_body_type_id", "Truck Body Type", "trim|required", array('required' => 'Please Select Truck Body Type'));
        $this->form_validation->set_rules("no_of_tyres", "No of Tyres", "trim|required", array('required' => 'Please Select No of Tyres'));
        $this->form_validation->set_rules("capacity", "Capacity", "trim|required", array('required' => 'Please enter Capacity'));
        if ($this->form_validation->run() == FALSE) {
            $message = validation_erros_for_app(validation_errors());
            $arr = ['status' => "invalid",
                "type" => "", 'message' => $message];
        } else {
            $id = $this->input->post("id");
            $reg_no = $this->input->post("reg_no");
            $rc_book_number = $this->input->post("rc_book_number");
            $vehicle_type_id = $this->input->post("vehicle_type_id");
            $truck_body_type_id = $this->input->post("truck_body_type_id");
            $no_of_tyres = $this->input->post("no_of_tyres");
            $capacity = $this->input->post("capacity");
            if (empty($id)) {
                $truck = $this->check_truck(null, $reg_no);
            } else {
                $truck = $this->check_truck($id, null);
            }
            if (empty($truck) || (!empty($id) && !empty($truck))) {
                $lorry_image_1 = $this->file_upload("lorry_image_1", "lorries/$reg_no", "jpg|png|jpeg");
                $lorry_image_2 = $this->file_upload("lorry_image_2", "lorries/$reg_no", "jpg|png|jpeg");
                $rc_book_front_image = $this->file_upload("rc_book_front_image", "lorries/$reg_no", "jpg|png|jpeg");
                $rc_book_back_image = $this->file_upload("rc_book_back_image", "lorries/$reg_no", "jpg|png|jpeg");
                $insurance_certificate_front = $this->file_upload("insurance_certificate_front", "lorries/$reg_no", "jpg|png|jpeg");
                $insurance_certificate_back = $this->file_upload("insurance_certificate_back", "lorries/$reg_no", "jpg|png|jpeg");
                $this->check_value_status($lorry_image_1, "Lorry Image 1");
                $this->check_value_status($lorry_image_2, "Lorry Image 2");
                $this->check_value_status($rc_book_front_image, "RC Book front Image");
                $this->check_value_status($rc_book_back_image, "RC Book back Image");
                $this->check_value_status($insurance_certificate_front, "Insurance Certificate Front Image");
                $this->check_value_status($insurance_certificate_back, "Insurance Certificate Back Image");
                $inp_arr = array(
                    "owner_id" => $this->user_id,
                    "reg_no" => strtoupper($reg_no),
                    "image_1" => $lorry_image_1,
                    "image_2" => $lorry_image_2,
                    "rc_book_number" => $rc_book_number,
                    "rc_book_front_image" => $rc_book_front_image,
                    "rc_book_back_image" => $rc_book_back_image,
                    "insurance_certificate_front" => $insurance_certificate_front,
                    "insurance_certificate_back" => $insurance_certificate_back,
                    "vehicle_type_id" => $vehicle_type_id,
                    "truck_body_type_id" => $truck_body_type_id,
                    "no_of_tyres" => $no_of_tyres,
                    "capacity" => $capacity,
                    "created_at" => time(),
                    "updated_at" => time()
                );
                if (!empty($id)) {
                    unset($inp_arr["owner_id"]);
                    unset($inp_arr["reg_no"]);
                    $query = $this->my_model->update_data("lorries", ["id" => $id], $inp_arr);
                    $message = "Vehicle Updated Successfully.";
                } else {
                    $query = $this->my_model->insert_data("lorries", $inp_arr);
                    $message = "Vehicle Added Successfully.";
                }
                if ($query) {
                    $arr = [
                        "status" => "valid",
                        "type" => "",
                        "message" => $message
                    ];
                } else {
                    $arr = [
                        "status" => "invalid",
                        "type" => "",
                        "message" => "Something went Wrong! Please Try Again."
                    ];
                }
            } else {
                $arr = [
                    "status" => "invalid",
                    "type" => "",
                    "message" => "Truck with this Registration Number Already Exists!"
                ];
            }
        }
        echo json_encode($arr);
        die;
    }

    private function check_truck($id = null, $vehicle_number = null) {
        if (!empty($id)) {
            $where["id"] = $id;
        }
        if (!empty($vehicle_number)) {
            $where["reg_no"] = $vehicle_number;
        }
        $truck = $this->my_model->get_data_row("lorries", $where);
        return $truck;
    }

    public function reg_data() {
        $types_of_vehicles = $this->my_model->get_data("vehicle_types", null, "id,name", "name", "desc", true);
        $truck_body_types = $this->my_model->get_data("truck_body_types", null, "id,name", "name", "desc", true);
        $tyres = $this->my_model->get_data("tyres", null, "id,name", "name", "desc", true);
        $arr = [
            "status" => "valid",
            "type" => "",
            "data" => [
                "types_of_vehicles" => $types_of_vehicles,
                "truck_body_types" => $truck_body_types,
                "tyres" => $tyres
            ]
        ];
        echo json_encode($arr);
        die;
    }

}
