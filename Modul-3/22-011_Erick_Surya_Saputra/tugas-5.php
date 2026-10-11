<?php
echo "Ini jawaban soal 5.1";
echo "<br>";

//Mendeklarasikan array multidimensi dengan data awal
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

//Menampilkan format teks data awal
echo "Data awal:<br>";
echo "students = (<br>";
$format_awal = [];
foreach ($students as $row) {
    $format_awal[] = '("' . $row[0] . '", "' . $row[1] . '", "' . $row[2] . '")';
}
echo implode(",<br>", $format_awal) . "<br>)<br><br>";


//Menambahkan lima data baru ke dalam array multidimensi
$students[] = array("Daniel", "220404", "0812345611");
$students[] = array("Elena", "220405", "0812345622");
$students[] = array("Fiona", "220406", "0812345633");
$students[] = array("Gabe", "220407", "0812345644");
$students[] = array("Hannah", "220408", "0812345655");

//Menampilkan format teks data setelah ditambahkan
echo "Data setelah ditambah 5 data lain:<br>";
echo "students = (<br>";
$format_baru = [];
foreach ($students as $row) {
    $format_baru[] = '("' . $row[0] . '", "' . $row[1] . '", "' . $row[2] . '")';
}
echo implode(",<br>", $format_baru) . "<br>)<br><br>";


//Menampilkan seluruh data dalam bentuk tabel HTML
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>Name</th>
        <th>NIM</th>
        <th>Mobile</th>
      </tr>";

//Menggunakan perulangan untuk mencetak baris (<tr>) dan kolom (<td>) tabel
foreach ($students as $student) {
    echo "<tr>";
    echo "<td>" . $student[0] . "</td>";
    echo "<td>" . $student[1] . "</td>";
    echo "<td>" . $student[2] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>