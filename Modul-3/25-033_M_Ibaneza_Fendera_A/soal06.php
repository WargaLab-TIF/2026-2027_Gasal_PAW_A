<?php

	$data1 = array("A");
	echo "Array awal : (" . '"' . $data1[0] . '")';
	echo "<br>";
	array_push($data1, "B");
	echo "Hasil array_push: ";
	for ($i=0; $i < count($data1); $i++) { 
		echo $data1[$i] . " ";
	}

	echo "<br><br>";

	echo "Array awal : (";
	for ($i=0; $i < count($data1); $i++) { 
		if ($i == count($data1) - 1) {
			echo '"' . $data1[$i] . '"';
		}else{
			echo '"' . $data1[$i] . '",';
		}
	}
	echo ") ";
	$data2 = array("C");
	echo "digabung dengan $data2[0]";
	$data3 = array_merge($data1, $data2);
	echo "<br>hasil array_merge: ";
	for ($i=0; $i < count($data3); $i++) { 
		echo $data3[$i] . " ";
	}

	echo "<br><br>";

	$huruf = array("x"=>1, "y"=>2);
	$nilai = array_values($huruf);
	echo "Array awal: (";
	foreach ($huruf as $key => $value) {
		if ($key == "y") {
			echo '"' . $key . '" => ' . $value . ")";
		}else{
			echo '"' . $key . '" => ' . $value . ",";			
		}
	}
	echo "<br>";
	echo "hasil array_values: ";
	for ($i=0; $i < count($nilai); $i++) { 
		echo "$nilai[$i] ";
	}

	echo "<br><br>";
	
	$abjad = array("A", "B", "C");
	echo "Mencari $abjad[1] pada array: (";	
	for ($i=0; $i < count($abjad); $i++) {
		if ($i == count($abjad) - 1) {
			echo '"' . "$abjad[$i]" . '")' ;
		 }else{
		 	echo '"' . "$abjad[$i]" . '", ' ;
		 }
	}
	$cari = array_search("B", $abjad);
	echo "<br>Hasil array_search: $cari";

	echo "<br><br>";
	
	$campuran = array(0, 1, false, 2, "", 3, "array");
	echo 'Array awal: (0, 1, false, 2, "", 3, "array")';
	$filter = array_filter($campuran);
	echo  "<br>Hasil array_filter: " . implode(" ", $filter);

	echo "<br><br>";

	$array = array(3 ,1 ,2);
	echo "Array awal: (";
	for ($i=0; $i < count($array); $i++) { 
		if ($i == count($array) - 1) {
			echo $array[$i];
		}else{
			echo $array[$i] . ", ";
		}
	}
	echo ")";
	sort($array);
	echo "<br>Hasil sort: ";
	for ($i=0; $i < count($array); $i++) { 
		echo "$array[$i] ";
	}
	rsort($array);
	echo "<br>Hasil rsort: ";
	for ($i=0; $i < count($array); $i++) { 
		echo "$array[$i] ";
	}

	echo "<br><br>";

	$data = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
	echo "Array awal: (";
	foreach ($data as $key => $value) {
		if ($key == "Joe") {
			echo '"' . $key . '" =>' . $value . ")";
		}else{
			echo '"' . $key . '" =>' . $value . ',';
		}
	}
	asort($data);
	echo "<br>Hasil asort: ";
	foreach ($data as $key => $value) {
		 echo $key . " => " . $value . ", ";
	}
	ksort($data);
	echo "<br>Hasil ksort: ";
	foreach ($data as $key => $value) {
		 echo $key . " => " . $value . ", ";
	}
	arsort($data);
	echo "<br>Hasil arsort: ";
	foreach ($data as $key => $value) {
		 echo $key . " => " . $value . ", ";
	}
	krsort($data);
	echo "<br>Hasil krsort: ";
	foreach ($data as $key => $value) {
		 echo $key . " => " . $value . ", ";
	}

?>