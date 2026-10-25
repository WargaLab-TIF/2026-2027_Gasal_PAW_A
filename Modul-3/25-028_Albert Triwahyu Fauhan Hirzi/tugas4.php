<?php
// Tugas 4.1
$height = array("Andy" => "176","Barry" => "165","Charlie" => "170");
$height += array("David" => "180","Ethan"=> "172","Frank" => "168","George" =>"175","Harry" => "182");

echo "<br>" . "height: (";
foreach($height as $key => $value){
    if ($key == "Andy"){
        echo '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
    elseif ($key == "Harry"){
        echo ",". '"' . $key . '"' . "=>" . '"' . $value . '"';
    } else {
        echo ',' . '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
}
echo ")" . "<br><br>";

foreach ($height as $key => $value){
    echo $key . " is " . $value . " cm tall.";
    echo "<br>";
}
echo "<br><br>";

// Tugas 4.2
$weight = array("Andy" => "70" , "Barry" => "65" , "Charlie" => "75");

echo "weight = (";
foreach($weight as $key => $value){
    if ($key == "Barry"){
    echo ','.'"' . $key . '"' . "=>" . '"' . $value . '"' . ',';
    }else{
        echo '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
}
echo ")";

$value = array_keys($weight);
$length = count($value);
echo "<br><br>";
for($i=0;$i < $length;$i++){
    $nama = $value[$i];
    $w = $weight[$nama];
    echo $nama . " is " . $w . ' kg. <br>';
}
?>