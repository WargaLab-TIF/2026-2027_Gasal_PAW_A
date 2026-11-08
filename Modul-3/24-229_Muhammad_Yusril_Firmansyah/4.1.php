<?php
// Mendeklarasikan array asosiatif awal[cite: 5]
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// Menambahkan 5 data baru ke dalam array $height[cite: 5]
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

// Menampilkan format deklarasi array height[cite: 5]
echo "height = (";
$keys = array_keys($height);
$last_key = end($keys);
foreach ($height as $key => $value) {
    echo "\"$key\"=>\"$value\"";
    if ($key !== $last_key) {
        echo ", ";
    }
}
echo ")<br><br>";

// Menampilkan seluruh data menggunakan perulangan foreach[cite: 5]
foreach ($height as $name => $cm) {
    echo "$name is $cm cm tall.<br>";
}
?>