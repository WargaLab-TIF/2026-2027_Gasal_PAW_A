<?php
// FILE: 4.3.1_no4_validate.inc - jawaban 4.3.1 nomor 4
/* ==========================================================
 * 4.3.1 - NOMOR 4
 * Ubah fungsi validateName agar menyimpan detail pesan error
 * dalam bentuk array ($errors, pass by reference lewat tanda &).
 * ========================================================== */
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/"; // format surname (alfabet)

    // Tidak dikirim atau kosong -> 'required' (wajib diisi)
    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    // Ada isinya tetapi formatnya salah -> 'invalid'
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid';
}
?>
