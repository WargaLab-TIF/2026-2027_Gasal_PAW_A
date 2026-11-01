<?php
$fruits = array(
    "Avocado", "Blueberry", "Cherry", "Durian",
    "Elderberry", "Fig", "Grape", "Honeydew"
);

unset($fruits[1]);

echo "Data Blueberry dihapus.<br>";
echo "fruits = (";

foreach ($fruits as $buah) {
    echo $buah . ", ";
}

echo ")<br>";

foreach ($fruits as $buah) {
    $terakhir = $buah;
}

echo "Nilai dengan indeks tertinggi: " . $terakhir;
?>