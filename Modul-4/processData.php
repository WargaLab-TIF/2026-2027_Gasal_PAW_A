<?php
require 'validate.inc'; 

$errors = array(); // Menyiapkan array kosong untuk menampung error

// Memanggil fungsi validasi khusus untuk isian 'surname'
validateName($errors, $_POST, 'surname'); 

// Jika terdapat error di dalam array
if ($errors) 
{
    echo 'Errors:<br/>';
    foreach ($errors as $field => $error) {
        echo "$field $error</br>";
    }
} 
// Jika tidak ada error sama sekali
else {
    echo 'Data OK!';
}
?>