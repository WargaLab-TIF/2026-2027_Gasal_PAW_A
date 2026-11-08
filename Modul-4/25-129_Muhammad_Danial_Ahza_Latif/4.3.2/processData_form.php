
<?php


$errors = array();
if (isset($_POST['surname']))
{
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    if ($errors)
    {
    echo '<h1>Invalid, correct the following errors:</h1>';
    foreach ($errors as $field => $error)
    echo "$field $error</br>";
    foreach ($_POST as $key => $value) {
    echo "($key) => ($value)<br/>";
}

    } else {
        echo 'Form submitted successfully with no errors';
    }
    // tampilkan kembali form
include 'form.inc';
}
?>