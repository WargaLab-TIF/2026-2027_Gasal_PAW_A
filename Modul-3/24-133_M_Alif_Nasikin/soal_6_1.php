<?php

// -1. array_push() 
$a = array("A");
echo 'Array awal: ("A")<br>';
array_push($a, "B");               // tambahkan "B"
echo "Hasil array_push: " . implode(" ", $a) . "<br><br>";

// 2. array_merge()
$a1 = array("A", "B");
$a2 = array("C");
$gabung = array_merge($a1, $a2);
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
echo "Hasil array_merge: " . implode(" ", $gabung) . "<br><br>";

// 3. array_values() 
$b = array("x" => 1, "y" => 2);
$nilai = array_values($b);
echo 'Array awal: ("x" => 1, "y" => 2)<br>';
echo "Hasil array_values: " . implode(" ", $nilai) . "<br><br>";

// 4. array_search() 
$c = array("A", "B", "C");
$posisi = array_search("B", $c);
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
echo "Hasil array_search: " . $posisi . "<br><br>";

// 5. array_filter() 
$d = array(0, 1, false, 2, "", 3, "array");
$bersih = array_filter($d);
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
echo "Hasil array_filter: " . implode(" ", $bersih) . "<br><br>";

//6. Sorting array terindeks: sort() dan rsort()
$e = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
sort($e);                          
echo "Hasil sort: " . implode(" ", $e) . "<br>";
rsort($e);                         
echo "Hasil rsort: " . implode(" ", $e) . "<br><br>";

$age = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

asort($age);
echo "Hasil asort: ";
foreach ($age as $key => $value) { echo $key . "=> " . $value . ", "; }
echo "<br>";

ksort($age);
echo "Hasil ksort: ";
foreach ($age as $key => $value) { echo $key . "=> " . $value . ", "; }
echo "<br>";

arsort($age);
echo "Hasil arsort: ";
foreach ($age as $key => $value) { echo $key . "=> " . $value . ", "; }
echo "<br>";

krsort($age);
echo "Hasil krsort: ";
foreach ($age as $key => $value) { echo $key . "=> " . $value . ", "; }
echo "<br>";
?>
