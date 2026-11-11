<?php
// FILE: 4.3.1_no5_processData.php - jawaban 4.3.1 nomor 5 (pengujian nomor 6 lewat 4.3.1_no6_processData_form.html)
/* ==========================================================
 * 4.3.1 - NOMOR 5
 * Ubah processData.php agar menampilkan detail pesan error.
 * ========================================================== */
require '4.3.1_no4_validate.inc';

$errors = array();                      // wadah pesan error (awalnya kosong)
validateName($errors, $_POST, 'surname');

if ($errors)                            // array tidak kosong => ada error
{
    echo 'Errors:<br/>';
    foreach ($errors as $field => $error)   // key = nama field, value = jenis error
        echo "$field $error</br>";
}
else
    echo 'Data OK!';

/* ==========================================================
 * 4.3.1 - NOMOR 6 (PENGUJIAN, tidak ada kode tambahan)
 * Buka 4.3.1_no6_processData_form.html lalu isi surname:
 *   - benar  : Smith     -> "Data OK!"
 *   - salah  : Smith123  -> "surname invalid"
 *   - kosong :           -> "surname required"
 * Beda dengan Langkah 3: pesan error kini menyebut nama field
 * dan jenis error (required / invalid).
 * ========================================================== */
?>
