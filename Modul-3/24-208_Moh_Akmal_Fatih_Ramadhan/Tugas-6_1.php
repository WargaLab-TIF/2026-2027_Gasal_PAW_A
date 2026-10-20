
<?php

// 1. array_push()
$arr = array("A");

echo 'Array awal: ("A")<br>';

array_push($arr, "B");

echo "Hasil array_push: " . implode(" ", $arr);
echo "<br><br>";


// 2. array_merge()
$arr1 = array("A", "B");
$arr2 = array("C");

echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';

$hasil = array_merge($arr1, $arr2);

echo "Hasil array_merge: " . implode(" ", $hasil);
echo "<br><br>";


// 3. array_values()
$arr = array("x" => 1, "y" => 2);

echo 'Array awal: ("x" => 1, "y" => 2)<br>';

$hasil = array_values($arr);

echo "Hasil array_values: " . implode(" ", $hasil);
echo "<br><br>";


// 4. array_search()
$arr = array("A", "B", "C");

echo 'Mencari "B" pada array: ("A", "B", "C")<br>';

$hasil = array_search("B", $arr);

echo "Hasil array_search: " . $hasil;
echo "<br><br>";


// 5. array_filter()
$arr = array(0, 1, false, 2, "", 3, "array");

echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';

$hasil = array_filter($arr);

echo "Hasil array_filter: " . implode(" ", $hasil);
echo "<br><br>";


// 6. sort()
$arr = array(3, 1, 2);

echo "Array awal: (3, 1, 2)<br>";

sort($arr);

echo "Hasil sort: " . implode(" ", $arr);
echo "<br>";


// 7. rsort()
$arr = array(3, 1, 2);

rsort($arr);

echo "Hasil rsort: " . implode(" ", $arr);
echo "<br><br>";


// Data array asosiatif
$nilai = array(
    "Peter" => 35,
    "Ben" => 37,
    "Joe" => 43
);

echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';


// 8. asort()
$data = $nilai;

asort($data);

echo "Hasil asort: ";
foreach ($data as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";


// 9. ksort()
$data = $nilai;

ksort($data);

echo "Hasil ksort: ";
foreach ($data as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";


// 10. arsort()
$data = $nilai;

arsort($data);

echo "Hasil arsort: ";
foreach ($data as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";


// 11. krsort()
$data = $nilai;

krsort($data);

echo "Hasil krsort: ";
foreach ($data as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}

?>
