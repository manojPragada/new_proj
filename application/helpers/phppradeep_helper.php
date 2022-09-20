<?php

//to get the current page url
function curPageURL() {
    $pageURL = 'http';
    // if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function sms_gateway_enabled() {
    $CI = & get_instance();
    return $CI->parent_model->get_result('name,status', 'utilities', ['status' => 'YES']);
}

function email_enabled() {
    $CI = & get_instance();
    return $CI->parent_model->get_result('name,status', 'utilities', ['status' => 'YES']);
}

function get_ads($ad_size_id, $no_of_ads_to_display = 1, $image_class_name = 'img-responsive') {
    $CI = & get_instance();
    switch ($ad_size_id) {
        case 1 : //Means get 250 x 250 square ads
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 2 : //Means get 200 x 200 small square ads
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 3 : //Means get 468 x 60 banner
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 4 : //Means get 728 x 90 Leaderboard
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 5 : //Means get 300 x 250 inline rectangle
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 6 : //Means get 336 x 280 large rectangle
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 7 : //Means get 120 x 600 skyscraper
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        case 8 : //Means get 160 x 600 wide skyscraper
            return $CI->parent_model->get_result('', 'ads', ['display_size' => $ad_size_id]);
            break;
        default : break;
    }
    return false;
}

function forward_to_view() {
    return;
}

function how_many_days($start_date, $end_date) {
    $today = date('d-m-Y', strtotime($start_date));

    //$exp = date('d-m-Y',strtotime($end_date)); //query result form database
    $exp = date('d-m-Y', strtotime($end_date)); //query result form database

    $expDate = date_create($exp);
    $todayDate = date_create($today);
    $diff = date_diff($todayDate, $expDate);
    if ($diff->format("%R%a") > 0) {
        //echo "active";
    } else {
        //echo "inactive";
    }
    //echo "Remaining Days " . $diff->format("%R%a days");
    return $diff->format("%R%a");
}

function how_many_days_with_out_sign($start_date, $end_date) {
    $today = date('d-m-Y', strtotime($start_date));

    //$exp = date('d-m-Y',strtotime($end_date)); //query result form database
    $exp = date('d-m-Y', strtotime($end_date)); //query result form database

    $expDate = date_create($exp);
    $todayDate = date_create($today);
    $diff = date_diff($todayDate, $expDate);
    if ($diff->format("%R%a") > 0) {
        //echo "active";
    } else {
        //echo "inactive";
    }
    //echo "Remaining Days " . $diff->format("%R%a days");
    return $diff->format("%a");
}

function how_many_days_left($end_date) {
    $today = date('d-m-Y', time());

    //$exp = date('d-m-Y',strtotime($end_date)); //query result form database
    $exp = date('d-m-Y', $end_date); //query result form database

    $expDate = date_create($exp);
    $todayDate = date_create($today);
    $diff = date_diff($todayDate, $expDate);
    if ($diff->format("%R%a") > 0) {
        //echo "active";
    } else {
        //echo "inactive";
    }
    //echo "Remaining Days ".$diff->format("%R%a days");
    if ($diff->format("%R%a") == 1) {
        return singular($diff->format("%R%a days"));
    }
    return $diff->format("%a days");
}

function how_many_days_left_count($end_date) {
    $today = date('d-m-Y', time());

    //$exp = date('d-m-Y',strtotime($end_date)); //query result form database
    $exp = date('d-m-Y', $end_date); //query result form database 

    $expDate = date_create($exp);
    $todayDate = date_create($today);
    $diff = date_diff($todayDate, $expDate);
    if ($diff->format("%R%a") > 0) {
        //echo "active";
    } else {
        //echo "inactive";
    }
    //echo "Remaining Days ".$diff->format("%R%a days");
    return $diff->format("%R%a");
}

function get_mystrtotime($mydate) {
    $dmy = $mydate;
    list($day, $month, $year) = explode("/", $dmy);

    $ymd = "$year-$month-$day";

    return strtotime($ymd);
}

function get_tiny_url($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function time_slots($from, $to, $interval) {
    $arr = array();
    $start = strtotime($from);
    $in = $interval * 60;
    //echo date('h:i A',strtotime("+$interval minutes", strtotime($to))); die;
    //$end = strtotime($to) - $in;
    $end = strtotime("+$interval minutes", strtotime($to)) - $in;
    $time = strtotime("+0 minutes", $start);
    //echo date('h:i A', $time); echo "<br />";
    $arr[] = date('h:i A', $time);

    for ($i = $start; $i < $end; $i = $i + $in) {
        if ($i >= $start && $i <= $end - $in) {
            $time = strtotime("+" . $interval . " minutes", $i);
            $arr[] = date('h:i A', $time);
            //echo date('h:i A', $time); echo "<br />";
        }
    }
    return $arr;
}

function left_pad($number) {
    return (($number < 10 && $number >= 0) ? '0' : '') + $number;
}

function convert_minutes_to_string($minutes) {
    $sign = '';
    if ($minutes < 0) {
        $sign = '-';
    }
    $hours = left_pad(floor(abs($minutes) / 60));
    $minutes = left_pad(abs($minutes) % 60);
    return $sign . $hours . 'hrs ' . $minutes . 'min';
}

function get_time_difference($start_time, $end_time) {
    $firstTime = strtotime($start_time);
    $lastTime = strtotime($end_time);
    $timeDiff = $lastTime - $firstTime;
    return ($timeDiff / 60) / 60;
}
