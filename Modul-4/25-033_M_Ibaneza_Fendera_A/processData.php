<!DOCTYPE html>
<html>
<head>
    <title>proses data</title>
    <style>
        h3 {
            font-family: arial;
            font-weight: bold;
        }
    </style>
</head>
<body style="background-color: royalblue;">
    <fieldset style="background-color: white;">
    <h3>Posted data:</h3>
    <?php
    foreach ($_POST as $key => $value) {
        echo "($key) => ($value)<br>";
    }
    require 'validate.inc'; 
    $errors = array();
    validateName($errors, $_POST, 'surname'); 
    if($errors){
        echo 'Errors:<br/>';
        foreach ($errors as $field => $error)
        echo "$field $error</br>";
    } else
        echo 'Data OK!';
    ?>
    </fieldset>
</body>
</html>