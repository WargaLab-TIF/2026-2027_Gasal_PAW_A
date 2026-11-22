<?php
// 1. array_push()
$array = array("A");
echo "Array awal: ('A')<br>";

array_push($array, "B");

echo "Hasil array_push: ";
echo implode(" ", $array) . "<br><br>";


// 2. array_merge()
$array1 = array("A", "B");
$array2 = array("C");

echo "Array awal: ('A', 'B') digabung dengan ('C')<br>";

$gabung = array_merge($array1, $array2);

echo "Hasil array_merge: ";
echo implode(" ", $gabung) . "<br><br>";


// 3. array_values()
$array = array("x" => 1, "y" => 2);

echo 'Array awal: ("x" => 1, "y" => 2)<br>';

$nilai = array_values($array);

echo "Hasil array_values: ";
echo implode(" ", $nilai) . "<br><br>";


// 4. array_search()
$array = array("A", "B", "C");

echo 'Mencari "B" pada array: ("A", "B", "C")<br>';

$cari = array_search("B", $array);

echo "Hasil array_search: " . $cari . "<br><br>";


// 5. array_filter()
$array = array(0, 1, false, 2, "", 3, "array");

echo "Array awal: (0, 1, false, 2, '', 3, 'array')<br>";

$filter = array_filter($array);

echo "Hasil array_filter: ";
echo implode(" ", $filter) . "<br><br>";


// 6. Sorting array terindeks
$array = array(3, 1, 2);

echo "Array awal: (3, 1, 2)<br>";

sort($array);
echo "Hasil sort: ";
echo implode(" ", $array) . "<br>";

rsort($array);
echo "Hasil rsort: ";
echo implode(" ", $array) . "<br><br>";


// 7. Sorting array asosiatif
$array = array("Peter" => 35, "Ben" => 37, "Joe" => 43);

echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

asort($array);
echo "Hasil asort: ";
foreach ($array as $nama => $umur) {
    echo "$nama => $umur, ";
}
echo "<br>";

ksort($array);
echo "Hasil ksort: ";
foreach ($array as $nama => $umur) {
    echo "$nama => $umur, ";
}
echo "<br>";

arsort($array);
echo "Hasil arsort: ";
foreach ($array as $nama => $umur) {
    echo "$nama => $umur, ";
}
echo "<br>";

krsort($array);
echo "Hasil krsort: ";
foreach ($array as $nama => $umur) {
    echo "$nama => $umur, ";
}
echo "<br>";