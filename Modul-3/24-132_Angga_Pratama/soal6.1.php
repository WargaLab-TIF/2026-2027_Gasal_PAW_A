
<?php
// 1. array_push()
$array = array("A");
echo 'Array awal: ("A")<br>';

array_push($array, "B");
echo "Hasil array_push: ";
foreach ($array as $data) {
    echo $data . " ";
}
echo "<br><br>";

// 2. array_merge()
$array1 = array("A", "B");
$array2 = array("C");

echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$hasil = array_merge($array1, $array2);

echo "Hasil array_merge: ";
foreach ($hasil as $data) {
    echo $data . " ";
}
echo "<br><br>";

// 3. array_values()
$array = array("x" => 1, "y" => 2);

echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$hasil = array_values($array);

echo "Hasil array_values: ";
foreach ($hasil as $data) {
    echo $data . " ";
}
echo "<br><br>";

// 4. array_search()
$array = array("A", "B", "C");

echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$hasil = array_search("B", $array);

echo "Hasil array_search: " . $hasil . "<br><br>";

// 5. array_filter()
$array = array(0, 1, false, 2, "", 3, "array");

echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$hasil = array_filter($array);

echo "Hasil array_filter: ";
foreach ($hasil as $data) {
    echo $data . " ";
}
echo "<br><br>";

// 6. sort() dan rsort()
$array = array(3, 1, 2);

echo "Array awal: (3, 1, 2)<br>";

sort($array);
echo "Hasil sort: ";
foreach ($array as $data) {
    echo $data . " ";
}
echo "<br>";

rsort($array);
echo "Hasil rsort: ";
foreach ($array as $data) {
    echo $data . " ";
}
echo "<br><br>";

// 7. Sorting array asosiatif
$nilai = array("Peter" => 35, "Ben" => 37, "Joe" => 43);

echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

asort($nilai);
echo "Hasil asort: ";
foreach ($nilai as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";

ksort($nilai);
echo "Hasil ksort: ";
foreach ($nilai as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";

arsort($nilai);
echo "Hasil arsort: ";
foreach ($nilai as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";

krsort($nilai);
echo "Hasil krsort: ";
foreach ($nilai as $nama => $angka) {
    echo $nama . "=> " . $angka . ", ";
}
echo "<br>";
?>
