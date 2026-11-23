<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Register</title></head>
<body>
<h1>Register</h1>
<?php
$errors = array();
if (isset($_POST['surname']))
{
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    validateEmail($errors, $_POST, 'email');
    validatePassword($errors, $_POST, 'passwd');
    validateAddress($errors, $_POST, 'address');
    validateState($errors, $_POST, 'state');
    validateGender($errors, $_POST, 'sex');

    if ($errors)
    {
        echo '<h2>Invalid, correct the following errors:</h2>';
        foreach ($errors as $field => $error)
            echo "$field $error</br>";

        // tampilkan kembali form dengan isian data
        include 'form.inc';
    }
    else
    {
        echo 'Form submitted successfully with no errors';
    }
}
else
{
    include 'form.inc';
}
?>
</body>
</html>
