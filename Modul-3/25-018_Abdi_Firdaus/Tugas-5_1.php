<?php
function cetakArray($arr) {
	$baris = [];
	foreach ($arr as $row) {
		$baris[] = '("' . $row[0] . '", "' . $row[1] . '", "' . $row[2] . '")';
	}
	echo "students = (<br>" . implode(",<br>", $baris) . "<br>)<br><br>";
}

$students = array (
  array("Alex", "220401", "0812345678"),
  array("Bianca", "220402", "0812345687"),
  array("Candice", "220403", "0812345665"),
);

echo "Data awal:<br>";
cetakArray($students);

array_push($students, 
	array("Daniel", "220404", "0812345611"),
	array("Elena", "220405", "0812345622"),
	array("Fiona", "220406", "0812345633"),
	array("Gabe", "220407", "0812345644"),
	array("Hannah", "220408", "0812345655"),
);

echo "Data setelah ditambah 5 data lain:<br>";
cetakArray($students);

echo "<table border='1' cellpadding='2' cellspacing='2'>";
echo "<tr><th>Name</th><th>NIM</th><th>Mobile</th></tr>";
foreach ($students as $row) {
	echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$row[2]}</td></tr>";
}
echo "</table>";
?>