<?php
require 'validate.inc';

$errors = array();
validateName($errors ,$_POST, 'nama');
if($errors) {
    echo "Errors:<br/>";
    foreach($errors as $field => $error){
        echo "$field $error</br>";
    }
} else{
    echo "Data OK";
}

echo "<h3>Posted Data:</h3>";

foreach($_POST as $key => $value) {
    echo "($key => $value) <br>"; 
} 
?>