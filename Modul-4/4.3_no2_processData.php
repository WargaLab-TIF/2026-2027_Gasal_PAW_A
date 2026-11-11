<!-- 4.3 NOMOR 2: file ini menerima data dari 4.3_no1_processData_form.html -->
<!-- $_POST adalah array asosiatif: key = atribut name input, value = isian pengguna -->
<!-- foreach menelusuri setiap pasangan key => value -->
<?php
foreach ($_GET as $key => $value) // ulangi untuk setiap data yang dikirim
    echo "($key) => ($value)<br/>"; // tampilkan dalam format (key) => (value)

//$surname = $_POST['surname']; // contoh mengambil satu nilai saja
?>

