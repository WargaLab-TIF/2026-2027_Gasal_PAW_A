<?php
// FILE: 4.3.1_no1_validate.inc - jawaban 4.3.1 nomor 1
/* ==========================================================
 * 4.3.1 - NOMOR 1
 * Buat file validate.inc berisi fungsi validateName untuk
 * memvalidasi field surname dengan format alfabet.
 * ========================================================== */
function validateName($field_list, $field_name)
{
    // Jika field tidak ada di data kiriman -> tidak valid
    if (!isset($field_list[$field_name]))
        return false;

    $pattern = "/^[a-zA-Z'-]+$/"; // format nama (alfabet): huruf, apostrof ('), tanda hubung (-)

    // preg_match() mencocokkan isian dengan pola; jika tidak cocok -> tidak valid
    if (!preg_match($pattern, $field_list[$field_name]))
        return false;

    return true; // lolos semua pengecekan
}
?>
