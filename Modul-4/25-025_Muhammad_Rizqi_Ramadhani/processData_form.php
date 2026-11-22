<!DOCTYPE html>
<html>
<head>
	<title>Form Input Data</title>
	<style>
		body{background-color: dodgerblue;}
		.field {display: flex;}
  		label {width: 160px; text-align: right;
  		margin-right: 20px}
	</style>
</head>
<body>
	<fieldset style="font-family: arial; background-color: white;">
		<h1>Register</h1>
		<fieldset style="background-color: lavender;">
			<legend style="font-weight: bold;">Person Details</legend>
	</fieldset>

	<?php
	$errors = array();

	if (isset($_POST['submit'])) {
	    if (file_exists('validate.inc')) {
	        require 'validate.inc';
	        
	        validateName($errors, $_POST, 'surname');
	        validateEmail($errors, $_POST, 'email');
	        validatePassword($errors, $_POST, 'password');
	        validateAddress($errors, $_POST, 'address');
	        validateState($errors, $_POST, 'state');
	        validateSex($errors, $_POST, 'sex');

	        if ($errors) {
	            echo '<h1 style="color: red;">Invalid, correct the following errors:</h1>';
	            include 'form.inc';
	        } else {
	            echo 'Form submitted successfully with no errors';
	        }
	    } else {
	        echo 'Error: file validate.inc tidak ditemukan.';
	    }
	} else {
	    include 'form.inc';
	}
	?>
</body>
</html>