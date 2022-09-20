<?php

function make_json($result) {
    $arr = $result != FALSE ? 
            array('err_code' => "valid", "message" => $result, 'status' => 'SUCCESS') : 
            array('err_code' => "invalid", "message" => "", 'status' => 'FAIL');
    echo json_encode($arr);
}
