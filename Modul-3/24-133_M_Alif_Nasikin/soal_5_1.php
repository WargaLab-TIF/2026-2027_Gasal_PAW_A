<?php
function tampilkan($students) {
    echo "students = (<br>";
    $baris = array();
    foreach ($students as $s) {
        $baris[] = '("' . $s[0] . '", "' . $s[1] . '", "' . $s[2] . '")';
    }
    echo implode(",<br>", $baris);
    echo "<br>)<br>";
}

$students = array(
    array("Alex",    "220401", "0812345678"),
    array("Bianca",  "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

echo "Data awal:<br>";
tampilkan($students);

// Menambah 5 data lain
$students[] = array("Daniel", "220404", "0812345611");
$students[] = array("Elena",  "220405", "0812345622");
$students[] = array("Fiona",  "220406", "0812345633");
$students[] = array("Gabe",   "220407", "0812345644");
$students[] = array("Hannah", "220408", "0812345655");

echo "<br>Data setelah ditambah 5 data lain:<br>";
tampilkan($students);

echo "<br>";
echo "<table border='1' cellpadding='3'>";
echo "<tr><th>Name</th><th>NIM</th><th>Mobile</th></tr>";

foreach ($students as $s) {
    echo "<tr>";
    foreach ($s as $kolom) {
        echo "<td>" . $kolom . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
