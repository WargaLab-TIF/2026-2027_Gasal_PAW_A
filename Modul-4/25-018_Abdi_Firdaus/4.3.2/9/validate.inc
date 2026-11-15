<?php
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/";

    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) === '') {
        $errors[$field_name] = 'required';
    }
    else if (!preg_match($pattern, $field_list[$field_name])) {
        $errors[$field_name] = 'invalid';
    }
}

function validateEmail(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) === '') {
        $errors[$field_name] = 'required';
    }
    else if (!filter_var(
        trim($field_list[$field_name]),
        FILTER_VALIDATE_EMAIL
    )) {
        $errors[$field_name] = 'invalid';
    }
}

function validateRequired(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) === '') {
        $errors[$field_name] = 'required';
    }
}

function validateState(&$errors, $field_list, $field_name)
{
    $validStates = ['1', '2', '3', '4', '5', '6', '7', '8'];

    if (!isset($field_list[$field_name]) ||
        $field_list[$field_name] === '-1' ||
        $field_list[$field_name] === '') {
        $errors[$field_name] = 'required';
    }
    else if (!in_array(
        $field_list[$field_name],
        $validStates,
        true
    )) {
        $errors[$field_name] = 'invalid';
    }
}

function validateGender(&$errors, $field_list, $field_name)
{
    $validGenders = ['male', 'female'];

    if (!isset($field_list[$field_name]) ||
        $field_list[$field_name] === '') {
        $errors[$field_name] = 'required';
    }
    else if (!in_array(
        $field_list[$field_name],
        $validGenders,
        true
    )) {
        $errors[$field_name] = 'invalid';
    }
}
?>