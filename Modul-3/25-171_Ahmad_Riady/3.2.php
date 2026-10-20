<?php
$weight = array("Andy"=>"70","Barry"=>"65","Charlie"=>"75");
echo "weight = (";
foreach ($weight as $nama => $nilai) {
    echo '"' . $nama . '"=>"' . $nilai . '"';
    }
echo ")<br>";
echo "Data kedua: ".$weight["Barry"];