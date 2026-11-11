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
 * 4.3.2 - NOMOR 7 (struktur sama dengan nomor 5, tetapi form yang di-include sudah berisi data lama)
 * Struktur file PHP untuk skenario pemrosesan error:
 *   ada isian form? -> tidak : tampilkan form kosong
 *                   -> ya    : validasi -> ada error? ya : pesan error + form BERISI DATA LAMA
 *                                                     tidak: pesan sukses
 * ========================================================== */
 $errors = array();
 if (isset($_POST['surname']))              // form sudah dikirim?
 {
    require '4.3.1_no4_validate.inc';        // fungsi validateName versi array
    validateName($errors, $_POST, 'surname');
    if ($errors)                            // ada error
    {
        echo '<h1>Invalid, correct the following errors:</h1>';
        foreach ($errors as $field => $error)
            echo "$field $error</br>";

        // tampilkan kembali form
        include '4.3.2_no7_form.inc';
    }
    else {
        echo 'Form submitted successfully with no errors';
    }
 }
 else
    // tampilkan form (pertama kali dibuka)
    include '4.3.2_no7_form.inc';

/* ==========================================================
 * 4.3.2 - NOMOR 8 (PENGUJIAN, tidak ada kode tambahan)
 *   - surname benar (Smith)  : "Form submitted successfully with no errors"
 *   - surname salah/kosong   : pesan error + form tampil lagi BERISI DATA LAMA
 * Beda dengan nomor 6: pengguna tidak perlu mengetik ulang semua isian,
 * cukup memperbaiki field yang salah (password tetap harus diisi ulang).
 * ========================================================== */
?>
</body>
</html>
