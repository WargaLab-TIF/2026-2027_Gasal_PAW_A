<?php

echo "<b> 4.1 </b> <br>";

$height = array("Andy"=> "176", "barry"=> "165", "Charlie" => "170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

foreach ($height as $k => $n) {
    echo "$k is $n cm tall. <br>";
}

echo "<br>";
echo "<br>";
echo "<br>";

echo "<b> 4.2 </b> <br>";
$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

$keys = array_keys($weight);

for ($i = 0; $i < count($weight); $i++) {
    echo $keys[$i] . " is " . $weight[$keys[$i]] . " kg <br>";
}