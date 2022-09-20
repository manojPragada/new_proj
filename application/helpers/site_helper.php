<?php
/**
 @  Helper functions
 @ By Manikanta
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/Utility.php';

function load_instance(){
	$CI=get_instance();
	$CI->load->model('site/Site_model');
	return $CI->Site_model;
}

function get_option($opname){
    $utility =new Utility();
    return $utility->get_option($opname);
}
function get_admin_menu(){
    $utility =new Utility();
    return $utility->get_admin_menu();
}
function get_popular_cities(){
	$m=load_instance();
	return $m->get_popular_cities();
}
function get_cities(){
	$m=load_instance();
	return $m->get_cities();
}
function get_institute_types(){
	$m=load_instance();
	return $m->get_institute_types();
}
function get_last_login(){
	$m=load_instance();
	return $m->get_last_login();
}

function my_header_location($uri){
	echo "<script>location.href='".site_url($uri)."'</script>";
	redirect($uri);
}
?>
