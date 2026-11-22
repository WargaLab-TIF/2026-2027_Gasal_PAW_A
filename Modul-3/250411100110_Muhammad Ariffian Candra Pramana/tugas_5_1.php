
<?php
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665"),
);

$baris = array();
foreach ($students as $row) {
    $dibungkus = array();
    foreach ($row as $nilai) {
        $dibungkus[] = '"' . $nilai . '"';
    }
    $baris[] = "(" . implode(", ", $dibungkus) . ")";
}
echo "Data awal: <br>";
echo "students = (<br>" . implode(",<br>", $baris) . "<br>)";

echo("<br>");
echo("<br>");

$students[] = array("Daniel", "220404", "0812345611");
$students[] = array("Elena", "220405", "0812345622");
$students[] = array("Fiona", "220406", "0812345633");
$students[] = array("Gabe", "220407", "0812345644");
$students[] = array("Hannah", "220408", "0812345655");

$baris = array();
foreach ($students as $row) {
    $dibungkus = array();
    foreach ($row as $nilai) {
        $dibungkus[] = '"' . $nilai . '"';
        }
    $baris[] = "(" . implode(", ", $dibungkus) . ")";
    }
echo "Data setelah ditambahkan 5 data lain: <br>";
echo "students = (<br>" . implode(",<br>", $baris) . "<br>)";

echo("<br>");
echo("<br>");

echo "<table border=1px solid>";
echo "<tr>";
echo "<th>Name</th>";
echo "<th>NIM</th>";
echo "<th>Mobile</th>";
echo "</tr>";

foreach($students as $row) {
    echo "<tr>";
    echo "<td>". $row[0] ."</td>";
    echo "<td>". $row[1] ."</td>";
    echo "<td>". $row[2] ."</td>";
    echo "</tr>";
}

echo "</table>";

?>