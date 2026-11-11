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
 * 4.3.2 - NOMOR 5
 * Struktur file PHP untuk skenario pemrosesan error:
 *   ada isian form? -> tidak : tampilkan form kosong
 *                   -> ya    : validasi -> ada error? ya : pesan error + form kosong
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
        include '4.3.2_no4_form.inc';
    }
    else {
        echo 'Form submitted successfully with no errors';
    }
 }
 else
    // tampilkan form (pertama kali dibuka)
    include '4.3.2_no4_form.inc';

/* ==========================================================
 * 4.3.2 - NOMOR 6 (PENGUJIAN, tidak ada kode tambahan)
 *   - surname benar (Smith)  : "Form submitted successfully with no errors"
 *   - surname salah/kosong   : pesan error + form tampil lagi (KOSONG)
 * Beda dengan 4.3.1 nomor 6: form dan proses ada di satu halaman,
 * pengguna langsung bisa mengisi ulang tanpa pindah halaman/Back.
 * ========================================================== */
?>
</body>
</html>
