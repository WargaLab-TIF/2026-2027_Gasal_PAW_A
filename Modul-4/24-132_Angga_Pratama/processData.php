
<?php
require 'validate.inc';

if (isset($_POST['surname'])) {
    $errors = array();

    if (validateName($errors, $_POST, 'surname')) {
        echo "Form submitted successfully with no errors.";
    } else {
        foreach ($errors as $key => $value) {
            echo "$key: $value<br/>";
        }

        require 'form.inc';
    }
} else {
    require 'form.inc';
}
?>
