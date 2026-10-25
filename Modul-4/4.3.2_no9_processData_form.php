<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body { font-family: Verdana, sans-serif; }
fieldset { background: #dde; width: 460px; }
.field { margin: 6px 0; }
.field label { display: inline-block; width: 120px; text-align: right; vertical-align: top; }
.error { color: #b00020; font-size: 12px; }
</style>
</head>
<body>
<h1>Register</h1>
<?php
/* ==========================================================
 * 4.3.2 - NOMOR 9
 * Validasi server-side untuk SEMUA field + pesan error sesuai field.
 * Aturan tiap field ada di 4.3.2_no9_validate.inc.
 * ========================================================== */
require '4.3.2_no9_validate.inc';
$errors = array();
if (isset($_POST['surname']))
{
    validateName($errors, $_POST, 'surname');        // huruf, ' dan -
    validateEmail($errors, $_POST, 'email');         // format email
    validatePassword($errors, $_POST, 'passwd');     // minimal 6 karakter
    validateAddress($errors, $_POST, 'address');     // tidak boleh kosong
    validateState($errors, $_POST, 'state');         // harus dipilih
    validateGender($errors, $_POST, 'sex');          // harus dipilih

    if ($errors)
    {
        echo '<h3 class="error">Invalid, correct the following errors:</h3>';
        foreach ($errors as $field => $error)
            echo htmlspecialchars(errorMessage($field, $error)) . '<br/>';
        include '4.3.2_no9_form.inc';   // form berisi data lama + pesan error per field
    }
    else
        echo 'Form submitted successfully with no errors';
}
else
    include '4.3.2_no9_form.inc';
?>
</body>
</html>
