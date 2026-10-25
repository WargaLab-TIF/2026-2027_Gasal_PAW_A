<?php
//Array Push
$push = array("A");
echo "Array awal: ". '("' . $push[0] . '")' ."<br>";
array_push($push, "B");
echo "Hasil array_push: " . implode(" ", $push) . "<br><br>";

//Array Merge
$merge1 = array("A", "B");
$merge2 = array("C");
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
$merged = array_merge($merge1, $merge2);
echo "Hasil array_merge: " . implode(" ", $merged) . "<br><br>";

//Array Values
$val = array("x" => 1, "y" => 2);
echo "Array awal: (\"x\" => 1, \"y\" => 2)<br>";
$values = array_values($val);
echo "Hasil array_values: " . implode(" ", $values) . "<br><br>";

//Array Search
$search = array("A", "B", "C");
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
$search_index = array_search("B", $search);
echo "Hasil array_search: " . $search_index . "<br><br>";

//Array filter
$filter = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
$filtered = array_filter($filter);
echo "Hasil array_filter: " . implode(" ", $filtered) . "<br><br>";

$num = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
//Sort
$sort = $num;
sort($sort);
echo "Hasil sort: " . implode(" ", $sort) . "<br>";
//R-Sort
$rsort = $num;
rsort($rsort);
echo "Hasil rsort: " . implode(" ", $rsort) . "<br><br>";

$assoc_sort = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";

//A-Sort
$asort_arr = $assoc_sort;
asort($asort_arr);
echo "Hasil asort: ";
foreach($asort_arr as $k => $v) echo "$k=> $v, ";
echo "<br>";

//K-Sort
$ksort_arr = $assoc_sort;
ksort($ksort_arr);
echo "Hasil ksort: ";
foreach($ksort_arr as $k => $v) echo "$k=> $v, ";
echo "<br>";

//Ar-Sort
$arsort_arr = $assoc_sort;
arsort($arsort_arr);
echo "Hasil arsort: ";
foreach($arsort_arr as $k => $v) echo "$k=> $v, ";
echo "<br>";

//Kr-Sort
$krsort_arr = $assoc_sort;
krsort($krsort_arr);
echo "Hasil krsort: ";
foreach($krsort_arr as $k => $v) echo "$k=> $v, ";
echo "<br>";
?>