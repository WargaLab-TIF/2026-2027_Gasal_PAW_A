<?php

$errors = array();

function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/";

    if (!isset($field_list[$field_name]) ||
        trim($field_list[$field_name]) === '') {
        $errors[$field_name] = 'required';
    } elseif (!preg_match($pattern, $field_list[$field_name])) {
        $errors[$field_name] = 'invalid';
    }
}

validateName($errors, $_POST, 'surname');

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Process Data</title></head>";
echo "<body>";

if (!empty($errors)) {
    echo "<h2>Errors:</h2>";

    foreach ($errors as $field => $error) {
        echo htmlspecialchars($field) . " "
            . htmlspecialchars($error) . "<br>";
    }

    echo '<br><a href="form.html">Kembali ke Form</a>';

} else {
    $data = array(
        "Surname" => $_POST['surname'] ?? '',
        "Email address" => $_POST['email'] ?? '',
        "Password" => $_POST['password'] ?? '',
        "Street Address" => $_POST['street_address'] ?? '',
        "State" => $_POST['state'] ?? '',
        "Country" => $_POST['country'] ?? '',
        "Gender" => $_POST['gender'] ?? '',
        "Vegetarian" => $_POST['vegetarian'] ?? 'No'
    );

    echo "<h2>Personal Data</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";

    foreach ($data as $key => $value) {
        echo "<tr>";
        echo "<td><b>" . htmlspecialchars($key) . "</b></td>";
        echo "<td>" . htmlspecialchars($value) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "</body></html>";
?>