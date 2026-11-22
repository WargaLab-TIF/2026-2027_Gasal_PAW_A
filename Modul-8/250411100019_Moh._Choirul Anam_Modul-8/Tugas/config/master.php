<?php
// Definisi Data Master. Satu entri = satu menu CRUD (dipakai views/master & services/master).
//   table / pk      : nama tabel & primary key
//   pk_manual       : true jika ID diisi manual (pelanggan)
//   list_sql        : query untuk tabel daftar
//   columns         : kolom yang ditampilkan di daftar (kolom => judul)
//   rupiah          : kolom yang diformat Rp.
//   fields          : isian form (type: text, number, textarea, select, password)
return [

  'barang' => [
    'title'   => 'Data Barang',
    'table'   => 'barang',
    'pk'      => 'id',
    'list_sql' => "SELECT b.id, b.kode_barang, b.nama_barang, b.harga, b.stok, s.nama AS supplier
                   FROM barang b JOIN supplier s ON s.id = b.supplier_id
                   ORDER BY b.id DESC",
    'columns' => ['kode_barang' => 'Kode', 'nama_barang' => 'Nama Barang', 'harga' => 'Harga', 'stok' => 'Stok', 'supplier' => 'Supplier'],
    'rupiah'  => ['harga'],
    'fields'  => [
      'kode_barang' => ['label' => 'Kode Barang', 'type' => 'text', 'max' => 10],
      'nama_barang' => ['label' => 'Nama Barang', 'type' => 'text', 'max' => 100],
      'harga'       => ['label' => 'Harga', 'type' => 'number'],
      'stok'        => ['label' => 'Stok', 'type' => 'number'],
      'supplier_id' => ['label' => 'Supplier', 'type' => 'select', 'options_sql' => "SELECT id, nama FROM supplier ORDER BY nama"],
    ],
  ],

  'supplier' => [
    'title'   => 'Data Supplier',
    'table'   => 'supplier',
    'pk'      => 'id',
    'list_sql' => "SELECT id, nama, telp, alamat FROM supplier ORDER BY id DESC",
    'columns' => ['nama' => 'Nama', 'telp' => 'Telp', 'alamat' => 'Alamat'],
    'fields'  => [
      'nama'   => ['label' => 'Nama Supplier', 'type' => 'text', 'max' => 100],
      'telp'   => ['label' => 'Telp', 'type' => 'text', 'max' => 12, 'optional' => true],
      'alamat' => ['label' => 'Alamat', 'type' => 'textarea', 'optional' => true],
    ],
  ],

  'pelanggan' => [
    'title'     => 'Data Pelanggan',
    'table'     => 'pelanggan',
    'pk'        => 'id',
    'pk_manual' => true,
    'pk_label'  => 'ID Pelanggan',
    'list_sql'  => "SELECT id, nama, jenis_kelamin, telp, alamat FROM pelanggan ORDER BY id",
    'columns'   => ['id' => 'ID', 'nama' => 'Nama', 'jenis_kelamin' => 'JK', 'telp' => 'Telp', 'alamat' => 'Alamat'],
    'fields'    => [
      'nama'          => ['label' => 'Nama', 'type' => 'text', 'max' => 100],
      'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
      'telp'          => ['label' => 'Telp', 'type' => 'text', 'max' => 12, 'optional' => true],
      'alamat'        => ['label' => 'Alamat', 'type' => 'textarea', 'optional' => true],
    ],
  ],

  'users' => [
    'title'   => 'Data User',
    'table'   => 'user',
    'pk'      => 'id_user',
    'list_sql' => "SELECT id_user, username, nama, hp, IF(level = 1, 'Owner', 'Kasir') AS level_nama
                   FROM `user` ORDER BY level ASC, id_user DESC",
    'columns' => ['username' => 'Username', 'nama' => 'Nama', 'hp' => 'HP', 'level_nama' => 'Level'],
    'fields'  => [
      'username' => ['label' => 'Username', 'type' => 'text', 'max' => 30],
      'password' => ['label' => 'Password', 'type' => 'password'],
      'nama'     => ['label' => 'Nama', 'type' => 'text', 'max' => 50],
      'alamat'   => ['label' => 'Alamat', 'type' => 'text', 'max' => 150, 'optional' => true],
      'hp'       => ['label' => 'HP', 'type' => 'text', 'max' => 20, 'optional' => true],
      'level'    => ['label' => 'Level', 'type' => 'select', 'options' => [1 => '1 - Owner', 2 => '2 - Kasir']],
    ],
  ],

];
