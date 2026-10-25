<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Register</title>
<style>
body{font-family:Verdana,sans-serif;font-size:13px;margin:20px}
fieldset{background:#ddf;width:430px}
.field{margin:6px 0;display:flex}
.field label{width:120px;text-align:right;padding-right:10px}
.err{color:#c00;font-size:12px;margin-left:6px}
.errbox{color:#c00}
</style>
</head>
<body>
<h1>Register</h1>
<?php
$errors = array();

// Ada isian form? (tombol Submit ditekan)
if (isset($_POST['surname']))
{
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    validateEmail($errors, $_POST, 'email');
    validatePassword($errors, $_POST, 'passwd');
    validateRequired($errors, $_POST, 'address');
    validateState($errors, $_POST, 'state');
    validateGender($errors, $_POST, 'sex');

    if ($errors)
    {
        echo '<h3 class="errbox">Invalid, correct the following errors:</h3>';
        foreach ($errors as $field => $error)
            echo '<span class="errbox">' . htmlspecialchars("$field $error") . '</span><br/>';

        // tampilkan kembali form (beserta isian sebelumnya)
        include 'form.inc';
    }
    else
    {
        echo '<h3>Form submitted successfully with no errors</h3>';
    }
}
else
{
    // tampilkan form kosong
    include 'form.inc';
}
?>
</body>
</html>
