<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact_form_model extends CI_Model {

    function send_contact_us_request_email() {
			$to_email = $this->db->get_where("site_settings")->row()->contact_email;
			$subject = "New Contact Us request";
			$message = "
			<html>
			<head>
			<title>Contact US</title>
			</head>
			<body>
			<p>New Contact Us request</p>
			<table border='1' width='100%' cellspacing='1' cellpadding='15'>
			 <tr>
			  <th style='text-align:left; width:25%'>Name</th>
			  <td>$name</td>
			 </tr>
			 <tr>
			  <th style='text-align:left'>Mobile</th>
			  <td>$phone</td>
			 </tr>
			 <tr>
			  <th style='text-align:left'>Email</th>
			  <td>$email</td>
			 </tr>
			 <tr>
			  <th style='text-align:left'>Message</th>
			  <td>$message</td>
			 </tr>
			</table>
			</body>
			</html>
			";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: <'.NO_REPLAY_MAIL.'> '.SITE_TITLE.'' . "\r\n";
			$headers .= 'BCc: '.BCC_EMAIL.'' . "\r\n";
			if(mail($to_email,$subject,$message,$headers)){
				//send_message("We have received your request", $data->mobile);
				return true;
			}else{
				return false;
			}
    }

}
