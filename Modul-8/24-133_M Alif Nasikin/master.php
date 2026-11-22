<?php
// Konfigurasi Data Master yang memakai pola CRUD yang sama.
// Format: "nama_kolom" => [label, tipe input, wajib diisi?]
$MASTER = [
    "barang" => [
        "judul" => "Barang",
        "kolom" => [
            "kode_barang" => ["Kode Barang", "text", true],
            "nama_barang" => ["Nama Barang", "text", true],
            "harga"       => ["Harga", "number", true],
            "stok"        => ["Stok", "number", true],
        ],
    ],
    "supplier" => [
        "judul" => "Supplier",
        "kolom" => [
            "nama_supplier" => ["Nama Supplier", "text", true],
            "alamat"        => ["Alamat", "text", false],
            "telepon"       => ["Telepon", "text", false],
        ],
    ],
    "pelanggan" => [
        "judul" => "Pelanggan",
        "kolom" => [
            "nama_pelanggan" => ["Nama Pelanggan", "text", true],
            "alamat"         => ["Alamat", "text", false],
            "telepon"        => ["Telepon", "text", false],
        ],
    ],
];
