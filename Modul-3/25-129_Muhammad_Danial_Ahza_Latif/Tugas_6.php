<?php
function menampilkan($array,) {
    for ($i = 0; $i < count($array); $i++) {
    echo $array[$i] . " ";
    }
}

function menampilkan_as($array,) {
    foreach ($array as $key => $value) {
        echo $key . "=> " . $value . ", ";
    }
}

$nilai = array("A");
echo "Array awal: ";
echo $nilai[0] . "<br>";

array_push($nilai, "B");

echo "Hasil array push: ";
menampilkan($nilai);
echo "<br>";
echo "<br>";

echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$nilai2 = array("C");
$j_nilai = array_merge($nilai, $nilai2);

echo "Hasil array_merge: ";
menampilkan($j_nilai);
echo "<br>";

echo "<br>";
$data = array("x" => 1, "y" => 2);

echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$jumlah = array_values($data);

echo "Hasil array_values: ";
menampilkan($jumlah);
echo "<br>";

echo "<br>";
echo 'Mencari "B" pada array ("A", "B", "C") <br>';

$cari = array_search("B", $j_nilai);

echo "Hasil array search: " . $cari . "<br>";

$campuran = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: ";
menampilkan($campuran);
echo "<br>";
echo "<br>";

$filter = array_values(array_filter($campuran));

echo "Hasil array_filter: ";
menampilkan($filter);
echo "<br>";



$angka = array(3, 1, 2);

echo "Array awal: ";
menampilkan($angka);
echo "<br>";

$naik = $angka;
sort($naik);

echo "Hasil sort: " ;
menampilkan($naik) ;
echo "<br>";

$turun = $angka;
rsort($turun);

echo "Hasil rsort: ";
menampilkan($turun) ;
echo"<br>";
echo "<br>";


$nilaiSiswa = array(
    "Peter" => 35,
    "Ben" => 37,
    "Joe" => 43
);

echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$asortnilai = $nilaiSiswa;
asort($asortnilai);
echo "Hasil asort: ";
menampilkan_as($asortnilai);
echo "<br>";

$ksortnilai = $nilaiSiswa;
ksort($ksortnilai);
echo "Hasil ksort: ";
menampilkan_as($ksortnilai);
echo "<br>";

$arsort = $nilaiSiswa;
arsort($arsort);
echo "Hasil arsort: ";
menampilkan_as($arsort);
echo "<br>";

$krsort = $nilaiSiswa;
krsort($krsort);
echo "Hasil krsort: ";
menampilkan_as($krsort);
echo "<br>";