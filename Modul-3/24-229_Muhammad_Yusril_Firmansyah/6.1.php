<?php

$arr_push = array("A");
array_push($arr_push, "B");
echo "Array awal: (\"A\")<br>";
echo "Hasil array_push: " . implode(" ", $arr_push) . "<br><br>";

$arr_merge1 = array("A", "B");
$arr_merge2 = array_merge($arr_merge1, array("C"));
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";
echo "Hasil array_merge: " . implode(" ", $arr_merge2) . "<br><br>";

// 3. array_values()
$arr_assoc = array("x" => 1, "y" => 2);
$arr_vals = array_values($arr_assoc);
echo "Array awal: (\"x\" => 1, \"y\" => 2)<br>";
echo "Hasil array_values: " . implode(" ", $arr_vals) . "<br><br>";

// 4. array_search()
$arr_search = array("A", "B", "C");
$pos = array_search("B", $arr_search);
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
echo "Hasil array_search: " . $pos . "<br><br>";

// 5. array_filter()
$arr_filter = array(0, 1, false, 2, "", 3, "array");
// Menggunakan callback default untuk menyaring nilai yang dianggap true secara boolean
$filtered = array_filter($arr_filter);
echo "Array awal: (0, 1, false, \"\", 3, \"array\")<br>";
echo "Hasil array_filter: " . implode(" ", $filtered) . "<br><br>";

// 6. Sorting untuk Array Terindeks (sort & rsort)
$arr_num = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$sorted = $arr_num;
sort($sorted);
echo "Hasil sort: " . implode(" ", $sorted) . "<br>";
$rsorted = $arr_num;
rsort($rsorted);
echo "Hasil rsort: " . implode(" ", $rsorted) . "<br><br>";

// 7. Sorting untuk Array Asosiatif (asort, ksort, arsort, krsort)
$arr_age = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";

// asort (sorting berdasarkan nilai secara ascending)
$asort_arr = $arr_age;
asort($asort_arr);
echo "Hasil asort: ";
$temp = [];
foreach($asort_arr as $k => $v) { $temp[] = "$k => $v"; }
echo implode(", ", $temp) . ",<br>";

// ksort (sorting berdasarkan key secara ascending)
$ksort_arr = $arr_age;
ksort($ksort_arr);
echo "Hasil ksort: ";
$temp = [];
foreach($ksort_arr as $k => $v) { $temp[] = "$k => $v"; }
echo implode(", ", $temp) . ",<br>";

// arsort (sorting berdasarkan nilai secara descending)
$arsort_arr = $arr_age;
arsort($arsort_arr);
echo "Hasil arsort: ";
$temp = [];
foreach($arsort_arr as $k => $v) { $temp[] = "$k => $v"; }
echo implode(", ", $temp) . ",<br>";

// krsort (sorting berdasarkan key secara descending)
$krsort_arr = $arr_age;
krsort($krsort_arr);
echo "Hasil krsort: ";
$temp = [];
foreach($krsort_arr as $k => $v) { $temp[] = "$k => $v"; }
echo implode(", ", $temp) . ",";
?>
```[cite: 8]