<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        fieldset { background-color: #e6e6fa; border: 1px solid #999; width: 420px; padding: 10px; }
        legend { font-weight: bold; }
        .field { clear: both; margin-bottom: 5px; overflow: hidden; }
        .field label:first-child { float: left; width: 120px; text-align: right; margin-right: 10px; }
        h2 { margin: 0 0 10px 0; }
        h1 { font-size: 16px; color: red; } 
    </style>
</head>
<body>
    <h2>Register</h2>
    <fieldset><legend>Person Details</legend>
    
    <?php
    $errors = array(); 

    if (isset($_POST['surname'])) {
        require 'validate.inc';
        validateName($errors, $_POST, 'surname');
        validateEmail($errors, $_POST, 'email');     
        validatePassword($errors, $_POST, 'passwd');
        
        if ($errors) {
            echo '<h1>Invalid, correct the following errors:</h1>';
            foreach ($errors as $field => $error) {
                echo "$field $error<br/>";
            }
            include 'form.inc';
        } 
        else {
            echo '<b>Form submitted successfully with no errors</b><br><br>';
            include 'form.inc';
        }
    } 
    else {
        include 'form.inc';
    }
    ?>
    
    </fieldset>
</body>
</html>