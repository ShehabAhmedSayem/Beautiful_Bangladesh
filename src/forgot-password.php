<?php
include('./classes/DB.php');

if(isset($_POST['resetpass'])){

	$email = $_POST['email'];

	$cstrong = true;
	$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

	$username = DB::query('SELECT username FROM users WHERE email=:email',array(':email'=>$email))[0]['username'];

	DB::query('INSERT INTO password_tokens VALUES(\'\', :token, :username)', 
		array(':token'=>sha1($token), ':username'=>$username));

	echo 'Email sent!<br/>';
	echo $token;
}

?>

<h1>Forgot password?</h1>
<form method="POST" action="forgot-password.php">
	<input type="text" name="email" value="" placeholder="Email..."><p/>
	<button type="submit" name="resetpass">Reset Password</button>
</form>