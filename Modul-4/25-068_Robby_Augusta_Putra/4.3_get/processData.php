<!DOCTYPE html>
<html>
<head><title>Process Data</title></head>
<body>
<h3>Received data (GET): </h3>
<?php
foreach ($_GET as $key => $value)
    echo "($key) => ($value)<br/>";
?>
</body>
</html>
