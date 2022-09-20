<?php

function my_pagination($showUrl, $perPage = 6, $totalRows, $isJsonPagination = false) {
    if (strpos($_SERVER['REQUEST_URI'], '?')) {
        $sufix = '&' . http_build_query($_GET, '', "&");
    } else
        $sufix = '?' . http_build_query($_GET, '', "&");
    $sufix = explode('&', $sufix);
    $c = count($sufix);
    $v = array();
    for ($i = 0; $i < $c; $i++) {
        $ss = substr($sufix[$i], 0, 4);
        if ($ss != 'page' && $sufix[$i] != '') {
            $v[] = $sufix[$i];
        }
    }


    $cd = implode('&', $v);

    count($_GET) == 0 ? $showUrl .= $cd : $showUrl .= "?" . $cd;

    $ci = &get_instance();
    $ci->load->library("pagination");
    $cfg = array(
        'base_url' => base_url() . $showUrl,
        'total_rows' => $totalRows,
        'per_page' => $perPage,
        'use_page_numbers' => TRUE,
        'enable_query_strings' => TRUE,
        'page_query_string' => TRUE,
        'query_string_segment' => 'page',
            //'suffix' => '?'.http_build_query($_GET, '', "&")
    );

    $cfg['full_tag_open'] = '<ul class="pagination">';
    $cfg['full_tag_close'] = '</ul>';
    $cfg['prev_link'] = '&lt;';
    $cfg['prev_tag_open'] = '<li>';
    $cfg['prev_tag_close'] = '</li>';
    $cfg['next_link'] = '&gt;';
    $cfg['next_tag_open'] = '<li>';
    $cfg['next_tag_close'] = '</li>';
    if ($isJsonPagination) {
        $cfg['cur_tag_open'] = '<li class="active"><a>';
    } else {
        $cfg['cur_tag_open'] = '<li class="active"><a href="' . base_url() . $showUrl . '#">';
    }
    $cfg['cur_tag_close'] = '</a></li>';
    $cfg['num_tag_open'] = '<li>';
    $cfg['num_tag_close'] = '</li>';

    $cfg['first_tag_open'] = '<li>';
    $cfg['first_tag_close'] = '</li>';
    $cfg['last_tag_open'] = '<li>';
    $cfg['last_tag_close'] = '</li>';

    $cfg['first_link'] = '&lt;&lt;';
    $cfg['last_link'] = '&gt;&gt;';

    $ci->pagination->initialize($cfg);
    $page = 0;
    if (isset($_GET['page'])) {
        $page = $_GET['page'] > 0 ? $_GET['page'] : 0;
    }
    //$data['report']=$this->Salemodel->get_user_transaction($cfg["per_page"],$page);
    $data['page'] = $page;
    $data["pagination"] = $ci->pagination->create_links();
    $data["pagination_helper"] = $ci->pagination;
    return $data;
}

function timeAgo($time_ago, $time_next) {
//$cur_time         = time();
    $cur_time = $time_next;
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
// Seconds
    if ($seconds < 60) {
        echo "$seconds seconds";
    }
//Minutes
    else if ($minutes < 60) {
        if ($minutes == 1) {
            echo "1 minute";
        } else {
            echo "$minutes minutes";
        }
    }
//Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            echo "1 hour";
        } else {
            echo "$hours hours";
        }
    }
//Days
    else if ($days <= 7) {
        if ($days == 1) {
            echo "1 day";
        } else {
            echo "$days days";
        }
    }
//Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            echo "a week ago";
        } else {
            echo "$weeks weeks ago";
        }
    }
//Months
    else if ($months <= 12) {
        if ($months == 1) {
            echo "1 month";
        } else {
            echo "$months months";
        }
    }
//Years
    else {
        if ($years == 1) {
            echo "1 year";
        } else {
            echo "$years years";
        }
    }
}

?>
