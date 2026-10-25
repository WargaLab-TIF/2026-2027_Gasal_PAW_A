<?php
/*
 * 4.3.2 NOMOR 9 : validate.inc versi lengkap (validasi semua field)
 * Kumpulan fungsi validasi server-side.
 * Semua fungsi menyimpan pesan error ke array $errors (dilewatkan by reference
 * dengan tanda &) sehingga perubahan di dalam fungsi terbawa keluar.
 * Isi $errors berupa kode: 'required' (kosong) atau 'invalid' (format salah).
 */

// Validasi surname: wajib diisi, hanya huruf, apostrof ('), dan tanda hubung (-)
function validateName(&$errors, $field_list, $field_name)
{
    $pattern = "/^[a-zA-Z'-]+$/"; // format nama (alfabet)

    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid';
}

// ---------- Validasi field lain (Langkah 9) ----------

// Email: wajib diisi dan harus berformat email yang sah
function validateEmail(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '')
        $errors[$field_name] = 'required';
    else if (!filter_var($field_list[$field_name], FILTER_VALIDATE_EMAIL))
        $errors[$field_name] = 'invalid';
}

// Password: wajib diisi, minimal 6 karakter
function validatePassword(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || $field_list[$field_name] === '')
        $errors[$field_name] = 'required';
    else if (strlen($field_list[$field_name]) < 6)
        $errors[$field_name] = 'invalid';
}

// Alamat: wajib diisi (tidak boleh kosong / hanya spasi)
function validateAddress(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || trim($field_list[$field_name]) === '')
        $errors[$field_name] = 'required';
}

// State: harus memilih salah satu (value "-1" = belum memilih), dan harus 1..8
function validateState(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]) || $field_list[$field_name] === '-1')
        $errors[$field_name] = 'required';
    else if (!in_array($field_list[$field_name], array('1','2','3','4','5','6','7','8'), true))
        $errors[$field_name] = 'invalid';
}

// Gender: radio button yang tidak dipilih tidak dikirim sama sekali -> cek isset()
function validateGender(&$errors, $field_list, $field_name)
{
    if (!isset($field_list[$field_name]))
        $errors[$field_name] = 'required';
    else if (!in_array($field_list[$field_name], array('male', 'female'), true))
        $errors[$field_name] = 'invalid';
}

// Mengubah kode error menjadi kalimat yang mudah dipahami pengguna
function errorMessage($field, $code)
{
    $labels = array(
        'surname' => 'Surname',  'email'   => 'Email address',
        'passwd'  => 'Password', 'address' => 'Street Address',
        'state'   => 'State',    'sex'     => 'Gender'
    );
    $label = isset($labels[$field]) ? $labels[$field] : $field;

    if ($code === 'required')
        return "$label wajib diisi.";
    if ($field === 'surname')
        return "$label hanya boleh berisi huruf, tanda ' dan -.";
    if ($field === 'passwd')
        return "$label minimal 6 karakter.";
    return "$label tidak valid.";
}
?>
