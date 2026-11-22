<?php
// require 'validate.inc.php'; if
// (validateName($_POST, 'surname')) {
//     echo "Data OK! <br>";
// } else {
//     echo "Data invalid <br>";
// }
// foreach ($_POST as $key => $value) {
//     echo "($key) => ($value)<br>";
// }

// Surname = $_POST['surname'];

require 'validate.inc'; $errors
= array();
validateName($errors, $_POST, 'surname'); if
($errors)
{
echo 'Errors:<br/>';
foreach ($errors as $field => $error)
echo "$field $error</br>";
} else
echo 'Data OK!';