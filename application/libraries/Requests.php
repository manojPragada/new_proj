<?php

/*
 * This  Version 2.0
 * Innovated by Manikanta
 * Email : manikantak49@gmail.com
 * Mobile : 9553517603
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 */

class Requests {

    private $sa = array(); // db column
    private $sv = array(); //db column value that is to be inserted
    public $fillable;
    public $path_to_upload_file = './uploads/';
    public $allowed_types = 'gif|jpg|png|jpeg';
    public $file_upload_max_size = '10000KB';
    public $thumb_width = 200;
    public $thumb_height = 250;

    /*
     * Instance of Codeigniter
     */
    private $ci;

    /*
     * It takes the tablename of database where to store or update
     */
    public $tableName;

    /*
     * $whereCnd
     * For the update query where condition is required so provide the where conditions ex : array('id'=>$_POST['id'])
     */
    public $whereCnd = [];

    /*
     * Is password function required or not
     */
    public $is_password_update_required = 0;

    /*
     * Last inserted record id
     */
    public $insert_id;
    public $inserted_result_array;
    public $is_multiple_files_insert_in_seperate_table = 0;
    public $multiple_file_primary_column_name;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function prepare_fillable_posted_items() {
        //print_r($_POST);
        if (sizeof($this->fillable)) {
            $posted_values_array = $this->fillable;
            foreach ($posted_values_array as $one_column_name) {
                if (substr_count($one_column_name, "__") == 1) {
                    $actual_array[] = $one_column_name;
                } else if ($this->ci->db->field_exists($one_column_name, $this->tableName)) {
                    $actual_array[] = $one_column_name;
                }
            }
            $this->add_post_collection($actual_array);

            //echo "<pre>";
            //print_r($this->sa);
            //print_r($this->sv);die;
            return array('dbcolumns' => $this->sa, 'value' => $this->sv);
            //print_r($this->sa);
            //print_r($this->sv);
        }
        return false;
    }

    private function add_post_collection($ea) {
        //echo "<table border='1px'>";
        //echo "<tr><th>Posted name</th><th>Posted value</th><th>Store format to database</th><th>Database table column</th><th>output how stored in database</th></tr>";
        for ($i = 0; $i < count($ea); $i++) {
            if (substr($ea[$i], -3) == "__j") {
                $se = substr($ea[$i], 0, -3);
                $this->sa[] = $se;
                if (!is_array($_POST[$se])) {
                    //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$se].'</td><td>Json</td><td>'.$se.'</td><td>'.json_encode($_POST[$se]).'</td></tr>';
                    $this->sv[] = count($_POST[$se]) > 0 ? json_encode($_POST[$se]) : '';
                } else {
                    $values = '';
                    for ($e = 0; $e < count($_POST[$se]); $e++) {
                        $values .= $_POST[$se][$e] . ',';
                    }
                    //echo "<tr><td>".$se ."</td><td>". substr($values, 0, -1) .'</td><td>Json</td><td>'.$se.'</td>/<td>'.json_encode($_POST[$se]).'</td></tr>';
                    $this->sv[] = count($_POST[$se]) > 0 ? json_encode($_POST[$se]) : '';
                }
            } else if (substr($ea[$i], -3) == "__it") { //for image and thumbnails
                $se = substr($ea[$i], 0, -3);
                $this->sa[] = $se;
                $config['upload_path'] = $this->path_to_upload_file;
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = $this->file_upload_max_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $this->ci->load->library('upload', $config);

                //print_r($_FILES);
                if (!is_array($_FILES[$se]['name'])) {
                    //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$se].'</td><td>Json</td><td>'.$se.'</td><td>'.json_encode($_POST[$se]).'</td></tr>';

                    $this->ci->upload->do_upload($se);
                    $imagedata = $this->ci->upload->data();
                    //print_r($imagedata);
                    //echo $imagedata['file_name'];
                    echo $this->ci->upload->display_errors('<p>', '</p>');

                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->path_to_upload_file . $imagedata['file_name'];
                    $config['create_thumb'] = TRUE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = $this->thumb_width;
                    $config['height'] = $this->thumb_height;

                    $this->ci->load->library('image_lib', $config);
                    $this->ci->image_lib->resize();

                    $thumbnail = $this->path_to_upload_file . $imagedata['raw_name'] . '_thumb' . $imagedata['file_ext']; // Here it is;
                    //$this->sv[] = 'web_assets/uploads/'.$imagedata['file_name'].'_1_2_@split'.$thumbnail;
                    $this->sv[] = $imagedata['file_name'] . '_1_2_@split' . $thumbnail;
                    //echo "single to images";die;
                }
            } else if (substr($ea[$i], -3) == "__i") {// for single images only
                $se = substr($ea[$i], 0, -3);
                $this->sa[] = $se;
                $config['upload_path'] = $this->path_to_upload_file;
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = $this->file_upload_max_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $this->ci->load->library('upload', $config);


                if (!is_array($_FILES[$se . "__i"]['name'])) {
                    //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$se].'</td><td>Json</td><td>'.$se.'</td><td>'.json_encode($_POST[$se]).'</td></tr>';
                    $this->ci->upload->do_upload($se . "__i");
                    $imagedata = $this->ci->upload->data();
                    //print_r($imagedata);
                    //echo $imagedata['file_name'];
                    echo $this->ci->upload->display_errors('<p>', '</p>');
                    $this->sv[] = $imagedata['file_name'];
                    //echo "single to images";die;
                }
            } else if (substr($ea[$i], -4) == "__mi") {// for multiple images only
                //print_r($_FILES);
                // echo "multi to images";
                // echo "<pre/>";
                $se = substr($ea[$i], 0, -4);
                $this->sa[] = $se;
                $config['upload_path'] = $this->path_to_upload_file;
                $config['allowed_types'] = $this->allowed_types;
                $config['max_size'] = $this->file_upload_max_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['remove_spaces'] = TRUE;
                //$this->ci->load->library('upload', $config);
                $this->ci->load->library('upload', $config);

                $files = $_FILES;

                $count = count($_FILES[$se]['name']);
                $filenames = [];

                for ($multi = 0; $multi < $count; $multi++) {
                    $_FILES[$se]['name'] = $files[$se]['name'][$multi];
                    $_FILES[$se]['type'] = $files[$se]['type'][$multi];
                    $_FILES[$se]['tmp_name'] = $files[$se]['tmp_name'][$multi];
                    $_FILES[$se]['error'] = $files[$se]['error'][$multi];
                    $_FILES[$se]['size'] = $files[$se]['size'][$multi];
                    $this->ci->upload->initialize($config);
                    if ($this->ci->upload->do_upload($se) == False) {
                        //error coming here
                        echo $this->ci->upload->display_errors('<p>', '</p>');
                        //$this->load->view('profile_view');
                    } else {
                        // Insert Code here
                        $tmp = $this->ci->upload->data();
                        //print_r($tmp);

                        array_push($filenames, $tmp['file_name']);
                    }
                }
                if ($this->is_multiple_files_insert_in_seperate_table == 0) {
                    $this->sv[] = implode(',', $filenames);
                } else if ($this->is_multiple_files_insert_in_seperate_table == 1) {
                    $this->sv[] = implode(',', $filenames);
                }
            } else if (substr($ea[$i], -3) == "__p") {
                $se = substr($ea[$i], 0, -3);
                $this->sa[] = $se;
                $config['upload_path'] = $this->path_to_upload_file;
                $config['allowed_types'] = 'pdf|doc|txt';
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $this->ci->load->library('upload', $config);
                if (!is_array($_FILES[$se]['name'])) {
                    //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$se].'</td><td>Json</td><td>'.$se.'</td><td>'.json_encode($_POST[$se]).'</td></tr>';

                    $this->ci->upload->do_upload($se);
                    $uploaddata = $this->ci->upload->data();
                    //print_r(var_dump($imagedata['file_name']));
                    $this->ci->upload->do_upload($se . "__p");
                    $imagedata = $this->ci->upload->data();
                    //print_r($imagedata);
                    //echo $imagedata['file_name'];
                    echo $this->ci->upload->display_errors('<p>', '</p>');
                    $this->sv[] = $imagedata['file_name'];
                    //$this->sv[] = 'web_assets/uploads/pdf/'.$uploaddata['file_name'];
                    // $this->sv[] = $this->path_to_upload_file . $uploaddata['file_name'];
                    //echo "single to pdf";die;
                } else {
                    echo "multi to images";
                    die;
                    $values = '';
                    for ($e = 0; $e < count($_FILES[$se]['name']); $e++) {
                        $values .= $_FILES[$se]['name'][$e] . ',';
                    }
                    //echo "<tr><td>".$se ."</td><td>". substr($values, 0, -1) .'</td><td>Json</td><td>'.$se.'</td>/<td>'.json_encode($_POST[$se]).'</td></tr>';
                    $this->sv[] = count($_FILES[$se]['name']) > 0 ? json_encode($_FILES[$se]['name']) : '';
                }
            } else {
                $this->sa[] = $ea[$i];
                if (!is_array($_POST[$ea[$i]])) {
                    //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$ea[$i]].'</td><td>Plain text</td><td>'.$ea[$i].'</td><td>'.$_POST[$ea[$i]].'</td></tr>';
                    $this->sv[] = $_POST[$ea[$i]];
                } else {
                    $pdata = $_POST[$ea[$i]];
                    $dt = '';
                    for ($e = 0; $e < count($pdata); $e++) {
                        //echo "<tr><td>".$ea[$i] ."</td><td>". $_POST[$ea[$i]][$e].'</td><td>Plain text</td><td>'.$ea[$i].'</td><td>'.$_POST[$ea[$i][$e]].'</td></tr>';
                        if ($dt == '') {
                            $dt = "" . $pdata[$e];
                        } else {
                            $dt = $dt . "," . $pdata[$e];
                        }
                    }
                    $this->sv[] = $dt;
                }
            }
        }
        //echo "</table>";
    }

    function posted_multiple_files_values_dynamic_save($createdAt = TRUE, $updatedAt = FALSE) {
        //echo "<pre>";

        $s = $this->prepare_fillable_posted_items();


        for ($i = 0, $l = sizeof($this->fillable); $i < $l; $i++) {
            if (substr($this->fillable[$i], -4) == "__mi") {
                $key = $i;
            }
        }

        $multiple_file_col_name = explode(',', $s['dbcolumns'][$key]);
        $multiple_file_col_name = $multiple_file_col_name[0];

        $multi = explode(',', $s['value'][$key]);



        for ($i = 0, $l = sizeof($multi); $i < $l; $i++) {
            if ($this->ci->db->field_exists('created_at', $this->tableName)) {
                $this->ci->db->set('created_at', time());
            }
            for ($j = 0, $jl = sizeof($s['dbcolumns']); $j < $jl; $j++) {
                if ($s['dbcolumns'][$j] != $multiple_file_col_name) {
                    $this->ci->db->set($s['dbcolumns'][$j], $s['value'][$j]);
                } else {
                    $this->ci->db->set($multiple_file_col_name, $multi[$i]);
                }
            }
            $this->ci->db->insert($this->tableName);
            //echo $this->ci->db->last_query()."<br/>";
        }
        //print_r($s); die;
        return true;
    }

    function posted_values_dynamic_save() {
        $s = $this->prepare_fillable_posted_items();

        if ($this->ci->db->field_exists('created_at', $this->tableName)) {
            $this->ci->db->set('created_at', time());
        }
        if ($this->is_password_update_required) {
            $s = $this->password_encryption($s);
        }

//  echo "<pre>";
        for ($i = 0, $len = count($s['dbcolumns']); $i < $len; $i++) {
            $this->ci->db->set($s['dbcolumns'][$i], $s['value'][$i]);
        }

        //$this->ci->db->set("created_at", time());

        if ($this->ci->db->insert($this->tableName)) {
            $this->insert_id = $this->ci->db->insert_id();
            $this->ci->db->select('*');
            $this->ci->db->where('id', $this->insert_id);
            $data = $this->ci->db->get($this->tableName);
            $this->inserted_result_array = $data->result();
            return true;
        } else {
            return false;
        }
    }

    function posted_values_dynamic_update(){
    
        if($this->ci->db->field_exists('updated_at', $this->tableName)){
            $this->ci->db->set('updated_at',time());
        }
        $s = $this->prepare_fillable_posted_items();
        
        if($this->is_password_update_required){
            $s=$this->password_encryption_while_update($s);
        }

        for($i=0, $len=count($s['dbcolumns']); $i<$len; $i++){
            $this->ci->db->set($s['dbcolumns'][$i],$s['value'][$i]);
        }
        //$this->ci->db->set("updated_at", time());
        $this->ci->db->where($this->whereCnd);
        if($this->ci->db->update($this->tableName)){
            //echo $this->ci->db->last_query();
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function time_stamps($created_at = FALSE, $updated_at = FALSE) {

        if ($created_at == TRUE)
            $this->ci->db->set('created_at', date('Y-m-d H:i:s'));

        if ($updated_at == TRUE)
            $this->ci->db->set('updated_at', date('Y-m-d H:i:s'));
    }

    private function password_encryption($s) {

        $salt = $this->generate_password(10) . rand(0, 500);
        $password = md5($this->ci->input->post('password') . $salt);

        $this->ci->db->set('password', $password);
        $this->ci->db->set('salt', $salt);

        $s = $this->_password_key_remove_algo($s);
        return $s;
    }

    private function password_encryption_while_update($s) {

        $salt = $this->generate_password(10) . rand(0, 500);
        if ($this->ci->input->post('new_password')) {
            $password = md5($this->ci->input->post('new_password') . $salt);
        } else if ($this->ci->input->post('password')) {
            $password = md5($this->ci->input->post('password') . $salt);
        }


        $this->ci->db->set('password', $password);
        $this->ci->db->set('salt', $salt);

        $s = $this->_password_key_remove_algo($s);
        return $s;
    }

    protected function generate_password($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function _password_key_remove_algo($s) {
        if (in_array('password', $s['dbcolumns'])) {
            $first_array = $s['dbcolumns'];
            $second_array = $s['value'];

            $flipped_array = array_flip($first_array);
            //print_r($flipped_array);

            unset($second_array[$flipped_array['password']]);
            unset($flipped_array['password']);

            $or_array = array_flip($flipped_array);

            $or_array2 = $second_array;

            $first_array['dbcolumns'] = array_values($or_array);
            $first_array['value'] = array_values($or_array2);

            $s['dbcolumns'] = $first_array['dbcolumns'];
            $s['value'] = $first_array['value'];
            //print_r($s);
        }
        if (in_array('new_password', $s['dbcolumns'])) {
            $first_array = $s['dbcolumns'];
            $second_array = $s['value'];

            $flipped_array = array_flip($first_array);
            //print_r($flipped_array);

            unset($second_array[$flipped_array['new_password']]);
            unset($flipped_array['new_password']);

            $or_array = array_flip($flipped_array);

            $or_array2 = $second_array;

            $first_array['dbcolumns'] = array_values($or_array);
            $first_array['value'] = array_values($or_array2);

            $s['dbcolumns'] = $first_array['dbcolumns'];
            $s['value'] = $first_array['value'];
            //print_r($s);
        }
        return $s;
    }

    //This function validation the passwords before change
    public function password_validation_before_change() {
        extract($_POST);
        if ($new_password != $new_passwordcnf) {
            set_flash_msg('Password Mismatch not changed', 'myred');
        } else {

            $this->ci->db->select('username, password, salt')->where('active', 'YES')->where($this->whereCnd);
            $userdata = $this->ci->db->get($this->tableName);

            $password = $_POST['old_password'];

            if ($userdata->num_rows()) {
                $userdata = $userdata->result();
                $salt = $userdata[0]->salt;
                $pwd = $userdata[0]->password;
                $user_password = md5($password . $salt);
                if ($user_password === $pwd) {
                    return true;
                }
            } else {
                set_flash_msg('Invalid current password', 'myred');
                return false;
            }
        }
        return false;
    }

    function print_post_variable_keys() {
        //print_r($_POST);
        echo "'" . implode("','", array_keys($_POST)) . "'";
        echo "<br/>";
    }

    function print_file_variable_keys() {
        //This function will print the single and multiple files keys ..
        //echo "<pre>";
        //print_r($_FILES);
        $tmparray = [];
        $keysarray = array_keys($_FILES);
        // print_r($keysarray);
        if (sizeof($keysarray)) {
            for ($i = 0, $l = count($_FILES); $i < $l; $i++) {
                if (!is_array($_FILES[$keysarray[$i]]['name'])) {
                    //echo $_FILES[$keysarray[$i]]['name'];
                    array_push($tmparray, $keysarray[$i] . '__i');
                    //echo $keysarray[$i].'__i';
                } else if (is_array($_FILES[$keysarray[$i]]['name'])) {
                    $item = $_FILES[$keysarray[$i]]['name'];
                    for ($j = 0, $lj = count($item); $j < $lj; $j++) {
                        //echo $item[$j];
                        if ($j == 0)
                            array_push($tmparray, $keysarray[$i] . '__mi');
                        //echo $keysarray[$i].'__mi';
                    }
                }
            }
            // print_r($tmparray);
        }
        echo "'" . implode("','", array_values($tmparray)) . "'";
    }

}

?>