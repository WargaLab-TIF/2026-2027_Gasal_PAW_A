<?php
// 1. array_push()
$arr1 = array("A");
echo 'Array awal: ("A")<br>';
array_push($arr1, "B");
echo 'Hasil array_push: ' . implode(" ", $arr1) . '<br><br>';

// 2. array_merge()
$arr2_a = array("A", "B");
$arr2_b = array("C");
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$merged = array_merge($arr2_a, $arr2_b);
echo 'Hasil array_merge: ' . implode(" ", $merged) . '<br><br>';

// 3. array_values()
$arr3 = array("x" => 1, "y" => 2);
echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$vals = array_values($arr3);
echo 'Hasil array_values: ' . implode(" ", $vals) . '<br><br>';

// 4. array_search()
$arr4 = array("A", "B", "C");
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$pos = array_search("B", $arr4);
echo 'Hasil array_search: ' . $pos . '<br><br>';

// 5. array_filter()
$arr5 = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$filtered = array_filter($arr5);
echo 'Hasil array_filter: ';
foreach ($filtered as $val) {
    if (is_string($val) || is_numeric($val)) {
        echo $val . " ";
    }
}
echo '<br><br>';

// 6. Sorting untuk Array Terindeks (sort & rsort)
$arr6 = array(3, 1, 2);
echo 'Array awal: (3, 1, 2)<br>';
$sorted = $arr6; 
sort($sorted);
echo 'Hasil sort: ' . implode(" ", $sorted) . '<br>';
$rsorted = $arr6; 
rsort($rsorted);
echo 'Hasil rsort: ' . implode(" ", $rsorted) . '<br><br>';

// 7. Sorting untuk Array Asosiatif (asort, ksort, arsort, krsort)
$arr7 = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$asort_arr = $arr7; 
asort($asort_arr);
$res1 = array(); 
foreach($asort_arr as $k => $v) { $res1[] = "$k=> $v"; }
echo 'Hasil asort: ' . implode(", ", $res1) . '<br>';

$ksort_arr = $arr7; 
ksort($ksort_arr);
$res2 = array(); 
foreach($ksort_arr as $k => $v) { $res2[] = "$k=> $v"; }
echo 'Hasil ksort: ' . implode(", ", $res2) . '<br>';

$arsort_arr = $arr7; 
arsort($arsort_arr);
$res3 = array(); 
foreach($arsort_arr as $k => $v) { $res3[] = "$k=> $v"; }
echo 'Hasil arsort: ' . implode(", ", $res3) . '<br>';

$krsort_arr = $arr7; 
krsort($krsort_arr);
$res4 = array(); 
foreach($krsort_arr as $k => $v) { $res4[] = "$k=> $v"; }
echo 'Hasil krsort: ' . implode(", ", $res4) . '<br>';
?>