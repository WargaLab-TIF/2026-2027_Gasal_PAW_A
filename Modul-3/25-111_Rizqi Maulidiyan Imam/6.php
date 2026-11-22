<?php
// array_push
	$a = array("A");
	array_push($a, "B");
	echo "Hasil array_push: " . implode(" ", $a) . "<br>";

// array_merge
	$b1 = array("A", "B");
	$b2 = array("C");
	$merged = array_merge($b1, $b2);
	echo "Hasil array_merge: " . implode(" ", $merged) . "<br>";

// array_values
	$c = array("x" => 1, "y" => 2);
	echo "Hasil array_values: " . implode(" ", array_values($c)) . "<br>";

// array_search
	$d = array("A", "B", "C");
	$pos = array_search("B", $d);
	echo "Hasil array_search: " . $pos . "<br>";

// array_filter
	$e = array(0, 1, false, 2, "", 3, "array");
	$filtered = array_filter($e);
	echo "Hasil array_filter: " . implode(" ", $filtered) . "<br>";

// sort & rsort
	$f = array(3, 1, 2);
	sort($f);
	echo "Hasil sort: " . implode(" ", $f) . "<br>";
	rsort($f);
	echo "Hasil rsort: " . implode(" ", $f) . "<br>";

// asort, ksort, arsort, krsort (array asosiatif)
	$g = array("Peter"=>35, "Ben"=>37, "Joe"=>43);

	asort($g);
	echo "Hasil asort: "; foreach($g as $k=>$v){ echo "$k=> $v, "; } echo "<br>";

	ksort($g);
	echo "Hasil ksort: "; foreach($g as $k=>$v){ echo "$k=> $v, "; } echo "<br>";

	arsort($g);
	echo "Hasil arsort: "; foreach($g as $k=>$v){ echo "$k=> $v, "; } echo "<br>";

	krsort($g);
	echo "Hasil krsort: "; foreach($g as $k=>$v){ echo "$k=> $v, "; } echo "<br>";
?>