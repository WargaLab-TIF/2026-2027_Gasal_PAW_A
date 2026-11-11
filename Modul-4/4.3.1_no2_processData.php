<?php
// FILE: 4.3.1_no2_processData.php - jawaban 4.3.1 nomor 2 (pengujian nomor 3 lewat 4.3.1_no3_processData_form.html)
/* ==========================================================
 * 4.3.1 - NOMOR 2
 * Ubah processData.php: sertakan validate.inc dan validasi surname.
 * ========================================================== */
require '4.3.1_no1_validate.inc';

if (validateName($_POST, 'surname'))
    echo 'Data OK!';        // surname berformat alfabet
else
    echo 'Data invalid!';   // surname kosong / mengandung angka atau simbol lain

/* ==========================================================
 * 4.3.1 - NOMOR 3 (PENGUJIAN, tidak ada kode tambahan)
 * Buka 4.3.1_no3_processData_form.html lalu isi surname:
 *   - benar  : Smith, O'Brien   -> "Data OK!"
 *   - salah  : Smith123, kosong -> "Data invalid!"
 * ========================================================== */
?>
