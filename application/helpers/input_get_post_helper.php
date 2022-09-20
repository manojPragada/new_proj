<?php function g($element_name){$ci =& get_instance();return $ci->input->get_post($element_name,TRUE);}
function set_flash_msg($msg, $class='mygreen'){
    $ci =& get_instance();
    $ci->session->set_flashdata('msg',$msg);
    $ci->session->set_flashdata('class',$class);
}
function get_flash_class(){
    $ci =& get_instance();
    return $ci->session->flashdata('class');
}
function get_flash_msg(){
    $ci =& get_instance();
    return $ci->session->flashdata('msg');
}

function make_seo_name($title) {
    return preg_replace('/[^a-z0-9_-]/i', '', strtolower(str_replace(' ', '-', trim($title))));
}