
<?php

include "../Tugas-3/koneksi.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $query = mysqli_prepare(
        $koneksi,
        "DELETE FROM supplier WHERE id = ?"
    );

    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)) {

        echo "<script>
                alert('Supplier berhasil dihapus!');
                window.location='../Tugas-3/index.php';
              </script>";

    } else {

        echo "<script>
                alert('Supplier gagal dihapus!');
                window.location='../Tugas-3/index.php';
              </script>";

    }

} else {

    header("Location: ../Tugas-3/index.php");
    exit;

}

?>
