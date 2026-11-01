<?php
$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
$praktikum = ["JARKOM", "PAW"];

for ($i = 0; $i < count($matkul); $i++) {
    $nama_matkul = $matkul[$i];
    $indeks_plus_satu = $i + 1;
    if (in_array($nama_matkul, $praktikum)) {
        echo "Saya sedang mengambil matkul " . $nama_matkul . " termasuk praktikum nya<br>";
    } elseif ($i == 6 || $i == 7) { 
        echo "Saya belum mengambil matkul " . $nama_matkul . "<br>";
    } else {
        echo "Saya sudah mengambil matkul " . $nama_matkul . " semester lalu<br>";
    }
}

?>