<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Detail Identitas</title>
</head>
<body>
    <h2>Register</h2>
    <?php
$errors = array();
if(isset($_POST['nama'])) {
    require 'validate.inc';
    validateName($errors, $_POST, 'nama');
    if($errors) {
        echo '<h3>Invalid, correct the following errors:</h3>';
        foreach($errors as $field => $error)
            echo "$field $error</br>";
        include 'form.inc'; 
    }
    else {
        echo 'Form submitted successfully with no errors';
    }
} 
else {
    include 'form.inc';
}
?>
</body>
</html>