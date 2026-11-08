<?php
// array_push()
$a = array("A");
echo 'Array awal: ("A")<br>';
array_push($a, "B");
echo "Hasil array_push: ";
foreach ($a as $x) {
    echo $x . " ";
}
echo "<br><br>";

// array_merge()
$b1 = array("A", "B");
$b2 = array("C");
echo 'Array awal: ("A", "B") digabung dengan ("C")<br>';
$hasil = array_merge($b1, $b2);
echo "Hasil array_merge: ";
foreach ($hasil as $x) {
    echo $x . " ";
}
echo "<br><br>";

// array_values()
$c = array("x" => 1, "y" => 2);
echo 'Array awal: ("x" => 1, "y" => 2)<br>';
$hasil = array_values($c);
echo "Hasil array_values: ";
foreach ($hasil as $x) {
    echo $x . " ";
}
echo "<br><br>";

// array_search()
$d = array("A", "B", "C");
echo 'Mencari "B" pada array: ("A", "B", "C")<br>';
$hasil = array_search("B", $d);
echo "Hasil array_search: " . $hasil;
echo "<br><br>";

// array_filter()
$e = array(0, 1, false, 2, "", 3, "array");
echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';
$hasil = array_filter($e);
echo "Hasil array_filter: ";
foreach ($hasil as $x) {
    echo $x . " ";
}
echo "<br><br>";

// sort() dan rsort() (array berindeks)
$f = array(3, 1, 2);
echo "Array awal: (3, 1, 2)<br>";

$f1 = $f;
sort($f1);
echo "Hasil sort: ";
foreach ($f1 as $x) {
    echo $x . " ";
}
echo "<br>";

$f2 = $f;
rsort($f2);
echo "Hasil rsort: ";
foreach ($f2 as $x) {
    echo $x . " ";
}
echo "<br><br>";

// asort(), ksort(), arsort(), krsort() (array asosiatif)
$umur = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$g1 = $umur;
asort($g1);
echo "Hasil asort: ";
foreach ($g1 as $k => $x) {
    echo $k . "=> " . $x . ", ";
}
echo "<br>";

$g2 = $umur;
ksort($g2);
echo "Hasil ksort: ";
foreach ($g2 as $k => $x) {
    echo $k . "=> " . $x . ", ";
}
echo "<br>";

$g3 = $umur;
arsort($g3);
echo "Hasil arsort: ";
foreach ($g3 as $k => $v) {
    echo $k . "=> " . $v . ", ";
}
echo "<br>";

$g4 = $umur;
krsort($g4);
echo "Hasil krsort: ";
foreach ($g4 as $k => $x) {
    echo $k . "=> " . $x . ", ";
}
echo "<br>";
