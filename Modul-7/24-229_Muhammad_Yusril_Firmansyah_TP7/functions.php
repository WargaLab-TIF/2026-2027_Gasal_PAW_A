<?php
function validDate(string $value): bool {
    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value;
}

function rupiah($angka): string {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}
