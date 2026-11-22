<!DOCTYPE html>
<html>
<head>
	<title>Form</title>
	<style>
		body { background-color: dodgerblue; }
		.field { display: flex; }
		label { width: 160px; text-align: right; margin-right: 20px; }
	</style>
</head>
<body>
	<fieldset style="font-family: arial; background-color: white;">
		<h1>Register</h1>
		<fieldset style="background-color: lavender;">
			<legend style="font-weight: bold;">Person Details</legend>
			<?php
			$errors = array();
			if (isset($_POST['submit'])){
				require 'validate.inc';
				$cek = empty($_POST['surname']);
				if ($cek) {
					include 'form.inc';
				}else{
					validateName($errors, $_POST, 'surname');
					validateEmail($errors, $_POST, 'email');
					validatePassword($errors, $_POST, 'passwd');
					validateAddress($errors, $_POST, 'address');
					validateState($errors, $_POST, 'state');
					validateSex($errors, $_POST, 'sex');

					if ($errors){
						echo '<h1>Invalid, correct the following errors:</h1>';
						foreach ($errors as $field => $error){
							echo "$field $error</br>";
						}
						include 'form.inc';

					}else {
						echo 'Form submitted successfully with no errors';
					} 
				}				
			} else{
				include 'form.inc';				
			}
			?>
		</fieldset>
	</fieldset>
</body>
</html>