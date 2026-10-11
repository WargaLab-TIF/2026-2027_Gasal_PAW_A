<?php
echo "Ini jawaban soal 6.1";
echo "<br>";

//array_push()
$arr_push = array("A");
echo "Array awal: (\"A\")\n";
array_push($arr_push, "B");
echo "Hasil array_push: " . implode(" ", $arr_push) . "\n\n";
echo "<br>";

//array_merge()
$arr_merge1 = array("A", "B");
$arr_merge2 = array("C");
echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")\n";
$hasil_merge = array_merge($arr_merge1, $arr_merge2);
echo "Hasil array_merge: " . implode(" ", $hasil_merge) . "\n\n";
echo "<br>";

//array_values()
$arr_val = array("x" => 1, "y" => 2);
echo "Array awal: (\"x\" => 1, \"y\" => 2)\n";
$hasil_values = array_values($arr_val);
echo "Hasil array_values: " . implode(" ", $hasil_values) . "\n\n";
echo "<br>";

//array_search()
$arr_search = array("A", "B", "C");
echo "Mencari \"B\" pada array: (\"A\", \"B\", \"C\")\n";
$index_search = array_search("B", $arr_search);
echo "Hasil array_search: " . $index_search . "\n\n";
echo "<br>";

//array_filter()
$arr_filter = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: (0, 1, false, 2, \"\", 3, \"array\")\n";
$hasil_filter = array_filter($arr_filter);
echo "Hasil array_filter: " . implode(" ", $hasil_filter) . "\n\n";
echo "<br>";

//Sorting Indexed Array (sort & rsort)
$arr_sort = array(3, 1, 2);
echo "Array awal: (3, 1, 2)\n";
$sort_asc = $arr_sort;
sort($sort_asc);
echo "Hasil sort: " . implode(" ", $sort_asc) . "\n";
$sort_desc = $arr_sort;
rsort($sort_desc);
echo "Hasil rsort: " . implode(" ", $sort_desc) . "\n\n";
echo "<br>";
echo "<br>";

//Sorting Associative Array (asort, ksort, arsort, krsort)
$arr_assoc = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
echo "Array awal: (\"Peter\"=>35, \"Ben\"=>37, \"Joe\"=>43)\n";
echo "<br>";

//Fungsi pembantu untuk memformat output array asosiatif sesuai contoh
function print_assoc($arr) {
    $result = "";
    foreach($arr as $k => $v) {
        $result .= $k . "=> " . $v . ", ";
    }
    return $result;
}

$arr_asort = $arr_assoc;
asort($arr_asort);
echo "Hasil asort: " . print_assoc($arr_asort) . "\n";
echo "<br>";

$arr_ksort = $arr_assoc;
ksort($arr_ksort);
echo "Hasil ksort: " . print_assoc($arr_ksort) . "\n";
echo "<br>";

$arr_arsort = $arr_assoc;
arsort($arr_arsort);
echo "Hasil arsort: " . print_assoc($arr_arsort) . "\n";
echo "<br>";

$arr_krsort = $arr_assoc;
krsort($arr_krsort);
echo "Hasil krsort: " . print_assoc($arr_krsort) . "\n";
echo "<br>";
?>