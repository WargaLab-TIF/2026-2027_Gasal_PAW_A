<?php
echo "<h3>1. Array_push</h3>";
$awal = array("A");
echo "Array awal: ";
echo '("' . implode(" ", $awal) . '")';
echo "<br>";
echo "Hasil array_push: ";
array_push($awal, "B");
echo implode(" ", $awal);

echo "<h3>2. Array_merge</h3>";
$awal1 = array("A", "B");
echo "Array awal: ";
$dibungkus = array();
foreach ($awal1 as $a1) {
    $dibungkus[] = '"' . $a1 . '"';
}
$akhir = array("C");
echo '(' . implode(", ", $dibungkus) . ')' . " digabung dengan " . '("' . implode(" ", $akhir) . '")';
$merge = array_merge($awal1,$akhir);
echo "<br>";
echo "Hasil array_merge: ";
echo implode(" ", $merge);

echo "<h3>3. Array_value</h3>";
$awal2 = array("x" => 1, "y" => 2);
echo "Array awal: ";
$bungkus = array();
foreach($awal2 as $key => $val) {
    $bungkus[] = '"' . $key . '"' . "=>" . $val;
}
echo "(" . implode(", ", $bungkus) . ")"; 
$value = array_values($awal2);
echo "<br>";
echo "Hasil array_values: ";
echo implode(" ", $awal2);

echo "<h3>4. Array_search</h3>";
$bungkus1 = array();
foreach($merge as $mer) {
    $bungkus1[] = '"' . $mer . '"';
}
echo 'Mencari "B" pada array: ';
echo '(' . implode(", ", $bungkus1) . ')';
$search = array_search("B", $merge);
echo "<br>";
echo "Hasil array_search: " . $search;

echo "<h3>5. Array_filter</h3>";
$awal3 = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")';
$filter = array_filter($awal3);
echo "<br>";
echo "Hasil array_filter: " . implode(" ", $filter);

echo "<h3>6. Sort dan Rsort</h3>";
$angka = array(3, 1, 2);
echo "Array awal: (" . implode(", ", $angka) . ")";
$urut = $angka;
sort($urut);
echo "<br>";
echo "Hasil sort: " . implode(" ", $urut);
$urutTurun = $angka;
rsort($urutTurun);
echo "<br>";
echo "Hasil rsort: " . implode(" ", $urutTurun);

echo "<h3>7. Sorting array asosiatif</h3>";
$umur = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)';

$a = $umur;
asort($a);
echo "<br>Hasil asort: ";
foreach ($a as $k => $v) {
    echo $k . "=> " . $v . ", ";
}

$b = $umur;
ksort($b);
echo "<br>Hasil ksort: ";
foreach ($b as $k => $v) {
    echo $k . "=> " . $v . ", ";
}

$c = $umur;
arsort($c);
echo "<br>Hasil arsort: ";
foreach ($c as $k => $v) {
    echo $k . "=> " . $v . ", ";
}

$d = $umur;
krsort($d);
echo "<br>Hasil krsort: ";
foreach ($d as $k => $v) {
    echo $k . "=> " . $v . ", ";
}

?>

