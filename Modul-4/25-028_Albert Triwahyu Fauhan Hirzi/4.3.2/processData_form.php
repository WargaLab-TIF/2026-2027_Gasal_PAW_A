<?php
$errors = array();
$is_submitted = false;

if (isset($_POST['surname'])) {
    require 'validate.inc';
    validateName($errors, $_POST, 'surname');
    $is_submitted = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form</title>
    <style>
        body { 
            background-color: lavender; 
        }
        .field { 
            display: flex; 
        }
        label { 
            width: 160px; 
            text-align: right; 
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <fieldset style="background-color: white;">
        <h1>Register</h1>
        <fieldset style="background-color: lavender;">
            <legend>Person Details</legend>
            
            <?php
            if ($is_submitted) {
                if ($errors) {
                    echo '<div style="margin-left: 180px; color: red;">';
                    echo '<b>Invalid, correct the following errors:</b><br/>';
                    foreach ($errors as $field => $error) {
                        echo "- $field $error<br/>";
                    }
                    echo '</div><br/>';
                } else {
                    echo '<div style="margin-left: 180px; color: green; font-weight: bold; margin-bottom: 10px;">Form submitted successfully with no errors</div>';
                }
            }
            
            include 'form.inc';
            ?>
            
        </fieldset>
    </fieldset>
</body>
</html>