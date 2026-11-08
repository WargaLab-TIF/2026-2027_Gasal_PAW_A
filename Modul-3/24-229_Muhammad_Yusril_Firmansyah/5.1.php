<?php
// 5.1 Mendeklarasikan array multidimensi awal (data awal)
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

// Menampilkan representasi Data Awal[cite: 7]
echo "Data awal:<br>";
echo "students = (<br>";
foreach ($students as $row) {
    echo "(\"" . implode('", "', $row) . "\"),<br>";
}
echo ")<br><br>";

// Menambahkan lima data baru ke dalam array $students
array_push($students, 
    array("Daniel", "220404", "0812345611"),
    array("Elena", "220405", "0812345622"),
    array("Fiona", "220406", "0812345633"),
    array("Gabe", "220407", "0812345644"),
    array("Hannah", "220408", "0812345655")
);

// Menampilkan representasi Data setelah ditambah 5 data lain
echo "Data setelah ditambah 5 data lain:<br>";
echo "students = (<br>";
foreach ($students as $row) {
    echo "(\"" . implode('", "', $row) . "\"),<br>";
}
echo ")<br><br>";

// Menampilkan seluruh data dalam bentuk tabel HTML
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>Name</th>
        <th>NIM</th>
        <th>Mobile</th>
      </tr>";

foreach ($students as $student) {
    echo "<tr>";
    echo "<td>" . $student[0] . "</td>";
    echo "<td>" . $student[1] . "</td>";
    echo "<td>" . $student[2] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>