
<?php
function validateName(&$errors, $field_list, $field_name) {
    if (!isset($field_list[$field_name])) {
        $errors[$field_name] = 'required';
        return false;
    }

    if (!preg_match("/^[a-zA-Z'-]+$/", $field_list[$field_name])) {
        $errors[$field_name] = 'invalid';
        return false;
    }

    return true;
}
?>
