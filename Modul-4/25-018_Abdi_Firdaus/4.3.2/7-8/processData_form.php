<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 550px;
            padding: 15px;
            border: 1px solid #999;
            background-color: #e8e7fb;
        }

        fieldset {
            border: 1px solid #999;
        }

        .field {
            display: grid;
            grid-template-columns: 140px 1fr;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"],
        textarea,
        select {
            box-sizing: border-box;
            max-width: 100%;
        }
    </style>
</head>
<body>

<?php
$errors = array();

if (isset($_POST['surname'])) {
    require 'validate.inc';

    validateName($errors, $_POST, 'surname');

    if ($errors) {
        echo '<h1>Invalid, correct the following errors:</h1>';

        foreach ($errors as $field => $error) {
            echo "$field $error<br>";
        }

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