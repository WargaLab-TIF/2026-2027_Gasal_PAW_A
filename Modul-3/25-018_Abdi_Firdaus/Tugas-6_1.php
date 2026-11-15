<?php
// array_push
$arr_push = array("A");
echo "Array awal: (\"A\")<br>";
array_push($arr_push, "B");
echo "Hasil array_push: " . implode(" ", $arr_push) . "<br><br>";

// array_merge
$a1 = array("A", "B");
$a2 = array("C");
echo "Array awal: (\"$a1[0]\", \"$a1[1]\") digabung dengan (\"$a2[0]\")<br>";
$merged = array_merge($a1, $a2);
echo "Hasil array_merge: " . implode(" ", $merged) . "<br><br>";

// array_values
$assoc = array("x" => 1, "y" => 2);
echo "Array awal: (\"x\"=>1, \"y\"=>2)<br>";
$values = array_values($assoc);
echo "Hasil array_values: " . implode(" ", $values) . "<br><br>";

// array_search
$arr_search = array("A", "B", "C");
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")<br>";
$foundKey = array_search("B", $arr_search);
echo "Hasil array_search: " . $foundKey . "<br><br>";

// array_filter
$arr_filter = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")<br>";
$filtered = array_filter($arr_filter);
echo "Hasil array_filter: " . implode(" ", $filtered) . "<br><br>";

// Sorting Indexed Array
$num_sort = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";
$sort_asc = $num_sort; sort($sort_asc);
echo "Hasil sort: " . implode(" ", $sort_asc) . "<br>";
$sort_desc = $num_sort; rsort($sort_desc);
echo "Hasil rsort: " . implode(" ", $sort_desc) . "<br><br>";

// Sorting Associative Array
$assoc_sort = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)<br>";

function fmt($arr) {
    $res = [];
    foreach ($arr as $k => $v) { $res[] = "$k=>$v"; }
    return implode(", ", $res);
}

$t = $assoc_sort; asort($t); echo "Hasil asort: " . fmt($t) . "<br>";
$t = $assoc_sort; ksort($t); echo "Hasil ksort: " . fmt($t) . "<br>";
$t = $assoc_sort; arsort($t); echo "Hasil arsort: " . fmt($t) . "<br>";
$t = $assoc_sort; krsort($t); echo "Hasil krsort: " . fmt($t) . "<br>";
?>