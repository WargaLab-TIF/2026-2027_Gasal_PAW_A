<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, sans-serif; margin: 0; color: #222; background: #f5f6f8; }
  h1 { font-size: 24px; margin: 0 0 16px; }
  h1 small { font-size: 14px; color: #666; font-weight: normal; }
  .wrap { max-width: 1000px; margin: 24px auto; padding: 0 16px; }
  .wrap.narrow { max-width: 420px; margin-top: 12vh; }
  .page-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }

  /* Navbar */
  .topnav { background: #1f4e8c; color: #fff; }
  .topnav-in { max-width: 1000px; margin: 0 auto; padding: 0 16px; display: flex; align-items: center; flex-wrap: wrap; }
  .brand { color: #fff; font-weight: bold; text-decoration: none; padding: 14px 16px 14px 0; }
  .menu { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; }
  .menu.right { margin-left: auto; }
  .menu a { display: block; color: #e8eefc; text-decoration: none; padding: 14px 12px; font-size: 14px; }
  .menu a:hover { background: rgba(255, 255, 255, .12); color: #fff; }
  .dd { position: relative; }
  .dd-menu { display: none; position: absolute; left: 0; top: 100%; z-index: 20; min-width: 180px;
             list-style: none; margin: 0; padding: 4px 0; background: #fff; border: 1px solid #ccc;
             box-shadow: 0 4px 10px rgba(0, 0, 0, .15); }
  .dd-menu.end { left: auto; right: 0; }
  .dd:hover > .dd-menu, .dd:focus-within > .dd-menu { display: block; }
  .dd-menu a { color: #222; }
  .dd-menu a:hover { background: #eef3fb; color: #222; }

  /* Tombol, form, alert */
  .btn { display: inline-block; padding: 6px 12px; background: #0d6efd; color: #fff; border: 0; border-radius: 3px;
         text-decoration: none; cursor: pointer; font-size: 14px; }
  .btn-oranye { background: #e8590c; }
  .btn-biru { background: #0d6efd; }
  .btn-merah { background: #c92a2a; }
  .btn-abu { background: #6c757d; }
  .btn-kecil { padding: 3px 8px; font-size: 13px; }
  form.inline { display: inline; }
  .form { background: #fff; border: 1px solid #ddd; padding: 16px; max-width: 520px; }
  .form label { display: block; margin: 12px 0 4px; font-weight: bold; font-size: 14px; }
  .form input, .form select, .form textarea { width: 100%; padding: 7px 10px; border: 1px solid #bbb; border-radius: 3px; font: inherit; }
  .form small { color: #666; }
  .form .aksi-form { margin-top: 16px; }
  .alert { padding: 10px 14px; margin: 10px 0; border-radius: 3px; }
  .alert.error { background: #fde8e8; color: #b00020; border: 1px solid #f5c2c2; }
  .alert.success { background: #e6f6ea; color: #1b6b32; border: 1px solid #b7e1c1; }
  .error { color: #b00020; margin: 8px 0; }
  .card-box { background: #fff; border: 1px solid #ddd; padding: 16px; margin-bottom: 12px; }

  /* Tabel & laporan */
  table { border-collapse: collapse; width: 100%; background: #fff; margin-top: 12px; }
  th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; font-size: 14px; }
  th { background: #d9edf7; }
  td.aksi, th.aksi { width: 140px; white-space: nowrap; }
  .chart-box { max-width: 800px; height: 320px; margin-top: 16px; }
  .card { display: inline-block; margin-top: 16px; border: 1px solid #ccc; background: #fff; }
  .card .judul { background: #d9edf7; padding: 6px 16px; display: flex; gap: 60px; font-weight: bold; }
  .card .isi { padding: 8px 16px; display: flex; gap: 60px; justify-content: space-between; }

  @media print {
    .no-print { display: none !important; }
    body { margin: 0; background: #fff; }
    .wrap { margin: 0; max-width: none; }
  }
</style>
