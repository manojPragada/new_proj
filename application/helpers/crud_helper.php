<?php

function get_characters_only($string) {
    if (substr($string, 0, 4) == "enum") {
        return "enum";
    }
    if (substr($string, 0, 4) == "set") {
        return "set";
    }
    $string = explode(" ", $string);
    return preg_replace('/[^A-Za-z\-\']/', '', $string[0]);
}

function get_numbers_only($string) {
    return preg_replace('/[^0-9\-\']/', '', $string);
}

function get_enum_values($string) {
    $string = substr($string, 5, -1);
    $arr = explode(',', $string);
    $some = array();
    foreach ($arr as $item) {
        $item = substr($item, 1);
        $some[] = substr($item, 0, -1);
    }
    return $some;
}
function get_set_values($string) {
    $string = substr($string, 4, -1);
    $arr = explode(',', $string);
    $some = array();
    foreach ($arr as $item) {
        $item = substr($item, 1);
        $some[] = substr($item, 0, -1);
    }
    return $some;
}

function is_unsigned($string) {
    $string = explode(" ", $string);
    if (isset($string[1])) {
        return preg_replace('/[^A-Za-z\-\']/', '', $string[1]);
    } else {
        return false;
    }
}
if ( ! function_exists('humanize_column_name'))
{
	/**
	 * Checks if the given word has a plural version.
	 *
	 * @param	string	$word	Word to check
	 * @return	bool
	 */
	function humanize_column_name($word)
	{
		return ucfirst(str_replace('_',' ',$word));
	}
}

function get_database_date_format_from_us($date){
    $date = date_create_from_format('m-d-Y', $date);
    $selected_date = date_format($date, 'Y-m-d');
    //echo $selected_date; die;
    return $selected_date;
}