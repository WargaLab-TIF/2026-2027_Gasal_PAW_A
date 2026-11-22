<?php
function validateName(&$errors, $field_list, $field_name)
{
	$pattern = "/^[a-zA-Z'-]+$/"; // format surname (alfabet) 
	if (!isset($field_list[$field_name]) || empty($field_list[$field_name])){
		$errors[$field_name] = 'required';
	} else if (!preg_match($pattern, $field_list[$field_name])){
		$errors[$field_name] = 'invalid';		
	}

}

function validateEmail(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name]) || empty(trim($field_list[$field_name]))) {
        $errors[$field_name] = 'wajib diisi.';
    }
}

function validatePassword(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name])) {
        $errors[$field_name] = 'wajib diisi.';
    } else if (strlen($field_list[$field_name]) < 6) {
        $errors[$field_name] = 'minimal terdiri dari 6 karakter.';
    }
}

function validateAddress(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name]) || empty(trim($field_list[$field_name]))) {
        $errors[$field_name] = 'wajib diisi.';
    }
}

function validateState(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name]) || $field_list[$field_name] == '-1') {
        $errors[$field_name] = 'wajib dipilih.';
    }
}

function validateSex(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name])) {
        $errors[$field_name] = 'wajib dipilih.';
    }
}
?>
