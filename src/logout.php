<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');


if(!LOG::isLoggedIn()){
	die('Not logged in!');
}


?>
<div style="text-align:center">
<h1>Logout from your account</h1>
<p>Are you sure you want to logout?</p>
<form action="login.php" method="POST">
	<input type="checkbox" name="alldevices" value="all"><p style="font-size:20px;">Logout from all devices?</p><br/><br/>
	<button class="btn btn-primary" type="submit" name="confirm">Confirm</button>
</form>
</div>