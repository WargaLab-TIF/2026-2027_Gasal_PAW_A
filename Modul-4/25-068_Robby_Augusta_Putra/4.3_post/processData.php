<!DOCTYPE html>
<html>
<head><title>Process Data</title></head>
<body>
<h3>Posted data: </h3>
<?php
foreach ($_POST as $key => $value)
    echo "($key) => ($value)<br/>";

//$surname = $_POST['surname'];
?>
</body>
</html>
