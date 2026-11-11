<?php
// 1. Mendeklarasikan array multidimensi awal sesuai tabel
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

// Fungsi bantuan untuk mencetak format teks array students seperti pada contoh
function print_students_format($arr) {
    echo "students = (<br>";
    foreach ($arr as $row) {
        echo '(&quot;' . $row[0] . '&quot;, &quot;' . $row[1] . '&quot;, &quot;' . $row[2] . '&quot;),<br>';
    }
    echo ")<br><br>";
}

// Menampilkan data awal
echo "Data awal:<br>";
print_students_format($students);

// 2. Menambahkan lima data baru ke dalam array students
$new_students = array(
    array("Daniel", "220404", "0812345611"),
    array("Elena", "220405", "0812345622"),
    array("Fiona", "220406", "0812345633"),
    array("Gabe", "220407", "0812345644"),
    array("Hannah", "220408", "0812345655")
);

foreach ($new_students as $student) {
    array_push($students, $student);
}

// Menampilkan data setelah ditambah 5 data baru[cite: 6]
echo "Data setelah ditambah 5 data lain:<br>";
print_students_format($students);
?>

<!-- 3. Menampilkan seluruh data dalam bentuk tabel HTML[cite: 5, 6] -->
<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
    <tr>
        <th>Name</th>
        <th>NIM</th>
        <th>Mobile</th>
    </tr>
    <?php foreach ($students as $row): ?>
    <tr>
        <td><?php echo $row[0]; ?></td>
        <td><?php echo $row[1]; ?></td>
        <td><?php echo $row[2]; ?></td>
    </tr>
    <?php endforeach; ?>
</table>