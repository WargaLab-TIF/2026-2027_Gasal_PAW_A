<?php

echo "<b> 3.1 </b> <br>";

$height = array("Andy"=> "176", "barry"=> "165", "Charlie" => "170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

# echo "Andy is " . $height["Andy"] . " cm tall. <br>";

print_r($height); 
echo "<br>";

echo "Nilai dengan index terakhir :  " . end($height) . " cm <br>";

echo "<br>";
echo "<br>";
echo "<br>";

echo "<b> 3.2 </b> <br>";

$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

print_r($weight);

echo "<br> Data kedua : ";
echo next($weight);