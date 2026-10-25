<?php /* 4.3.2 NOMOR 2: nilai action diubah menjadi nama file ini sendiri (self-submission). NOMOR 3 = pengujian: form harus tampil tanpa error dan Submit memuat ulang halaman yang sama. */ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
body { font-family: Verdana, sans-serif; }
fieldset { background: #dde; width: 420px; }
.field label { display: inline-block; width: 120px; text-align: right; }
</style>
</head>
<body>
<!-- 4.3.2 NOMOR 2: action = file ini sendiri -->
<h1>Register</h1>
<fieldset>
<legend>Person Details</legend>
<form name="myForm" action="4.3.2_no2_processData_form.php" method="POST"> <!-- proses dilanjutkan ke processData.php, method POST -->
<div class="field">
<label for="surname">Surname</label>
<input type="text" name="surname" size="31"/> <!-- textbox Surname -->
</div>
<div class="field">
<label for="email">Email address</label>
<input type="text" name="email" size="31"/> <!-- textbox Email -->
</div>
<div class="field">
<label for="passwd">Password</label>
<input type="password" name="passwd" size="31"/> <!-- isian password (disamarkan) -->
</div>
<div class="field">
<label for="address">Street Address</label>
<textarea name="address" rows="3" cols="22"></textarea> <!-- textarea alamat -->
</div>
<div class="field">
<label for="state">State</label>
<select name="state"> <!-- dropdown menu State -->
<option value="-1" selected>[choose your state]</option> <!-- pilihan awal (belum memilih) -->
<option value="1">Australian Capital Territory</option>
<option value="2">Queensland</option>
<option value="3">Victoria</option>
<option value="4">New South Wales</option>
<option value="5">Tasmania</option>
<option value="6">South Australia</option>
<option value="7">Western Australia</option>
<option value="8">Northern Territory</option>
</select>
</div>
<div class="field">
<input type="hidden" name="country" value="Australia"/> <!-- data hidden: tidak tampil tapi ikut terkirim -->
</div>
<div class="field">
<label for="sex">Gender</label>
<input type="radio" name="sex" value="male"/>Male <!-- radio button: name sama agar hanya bisa pilih satu -->
<input type="radio" name="sex" value="female"/>Female
</div>
<div class="field">
<label for="vegetarian">Vegetarian?</label>
<input type="checkbox" name="vegetarian"/> <!-- checkbox: terkirim hanya jika dicentang -->
</div>
<div class="field">
<label>&nbsp;</label>
<input type="submit" value="Submit" name="submit"/><input type="reset" value="Reset" name="reset"/> <!-- tombol kirim & kosongkan -->
</div>
</form>
</fieldset>
</body>
</html>
