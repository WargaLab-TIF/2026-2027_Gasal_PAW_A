<?php
echo "<b> 1.1 </b> <br>";
$fruits = array("Avocado","Blueberry","Cherry");

$fruits[] = "Apple";
$fruits[] = "Banana";
$fruits[] = "Mango";
$fruits[] = "Grape";
$fruits[] = "Durian";

echo "fruits = (";
for ($i = 0; $i < count($fruits); $i++) {
    echo $fruits[$i] . ", ";
}


echo ") <br>";
echo "Nilai dengan index tertinggi : " . $fruits[$i-1];
echo "<br>";
echo "<br>";
echo "<br>";
foreach ($fruits as $j) {
    echo $j . " ";
}
echo "<br>";
echo "<br>";
echo "<br>";

echo "<b> 1.2 </b> <br>";
$fruits = array("Avocado","Blueberry","Cherry");

$fruits[] = "Apple";
$fruits[] = "Banana";
$fruits[] = "Mango";
$fruits[] = "Grape";
$fruits[] = "Durian";

echo "Data $fruits[3] dihapus <br>";
unset($fruits[3]);

echo "fruits = (";
for ($i = 0; $i < count($fruits); $i++) {
    if ($i == 3) { 
        continue;
    }else {
    echo $fruits[$i] . ", ";
    }
}
echo ") <br>";
echo "Nilai dengan index tertinggi : " . $fruits[$i-1];