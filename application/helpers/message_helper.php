<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function send_message($message="", $mobile_number){
	
	$message = urlencode($message);
	$URL="http://app.smsmoon.com/submitsms.jsp";// connecting url 
	$post_fields = ['user' => 'HRHOME', 'key' => 'dc217ab5a5XX', 'mobile' => $mobile_number, 'message' => $message, 'senderid'=>"HRHOME", 'accusage'=>1];
	
	file_get_contents("http://app.smsmoon.com/submitsms.jsp?user=HRHOME&key=dc217ab5a5XX&mobile=".$mobile_number."&message=".$message."&senderid=HRHOME&accusage=1");
	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_exec($ch);*/
	return true;
}
?>
