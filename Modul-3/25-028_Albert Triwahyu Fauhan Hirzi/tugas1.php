<?php
// Tugas 1.1
$fruits=array("Avocado","Blueberry","Cherry");

array_push($fruits,"Durian","Elderberry","Fig","Grape","Honeydew");

echo "fruits: (" .'"'. implode('"'. "," . '"', $fruits) .'"' . ")" . "<br>"; 
echo "Nilai dengan indeks tertinggi: " . end($fruits);

// Tugas 1.2
echo "<br><br>";

echo "Data ". $fruits[1] . " dihapus" . "<br>";
unset($fruits[1]);

echo "fruits: (" .'"'. implode('"'. "," . '"', $fruits) .'"' . ")" . "<br>"; 
echo "Nilai dengan indeks tertinggi: " . end($fruits);

?>