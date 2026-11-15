<?php 
require_once "route.php";

$transaksi = $DBH->prepare("SELECT * from transaksi");
$transaksi->execute();
$data = $transaksi->fetchAll(); 

?>

<table border="1px solid">
    <tr>
        <td>Waktu Transakasi</td>
        <td>Aksi</td>
    </tr>
    <?php
        foreach($data as $items):
    ?>
    <tr>
        <td><?=  $items['waktu_transaksi'] ?></td>
        <td><a href="master-detail.php?id=<?= $items['id'] ?>">detail</a></td>
    </tr>
    <?php endforeach; ?>
</table>