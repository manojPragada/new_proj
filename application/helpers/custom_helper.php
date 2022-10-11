<?php

#/** Helper Functions Developed By peacekeeper **/

function validation_erros_for_app($message) {
    $message = str_replace('</p>', '\n', $message);
    $message = strip_tags($message);
    $message = explode('\n', $message);
    return $message[0];
}
