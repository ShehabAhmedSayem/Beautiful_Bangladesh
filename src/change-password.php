<?php
include('./classes/DB.php');
include('./classes/LOG.php');

$tokenIsValid = false;

if(LOG::isLoggedIn()){

	if(isset($_POST['changepass'])){
		
		$username=LOG::isLoggedIn();

		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		$newpassrepeat = $_POST['newpassrepeat'];

		if(password_verify($oldpass,DB::query('SELECT password FROM users WHERE username=:username',array(':username'=>$username))[0]['password'])){

			if($newpass == $newpassrepeat){
				
				if(strlen($newpass)>=5 && strlen($newpass)<=60){

					DB::query('UPDATE users SET password=:newpassword WHERE username=:username',
						array(':newpassword'=>password_hash($newpass,PASSWORD_BCRYPT), ':username'=>$username));
					echo 'Password changed successfully!';

				}else{
					echo 'Incorrect new password!';
				}
			
			}else{
				echo 'Passwords don\'t match!';
			}
		}
		else{
			echo 'Incorrect current password!'; 
		}
	
	}

} else {

	if(isset($_GET['token'])){
	
		$token = $_GET['token'];

		if(DB::query('SELECT username FROM password_tokens WHERE token=:token',array(':token'=>sha1($token)))){
			
			$username = DB::query('SELECT username FROM password_tokens WHERE token=:token',
				array(':token'=>sha1($token)))[0]['username'];

			$tokenIsValid = true;

			if(isset($_POST['changepass'])){

				$newpass = $_POST['newpass'];
				$newpassrepeat = $_POST['newpassrepeat'];

				if($newpass == $newpassrepeat){
					
					if(strlen($newpass)>=5 && strlen($newpass)<=60){

						DB::query('UPDATE users SET password=:newpassword WHERE username=:username',							array(':newpassword'=>password_hash($newpass,PASSWORD_BCRYPT), ':username'=>$username));
						echo 'Password change successfully!';

						DB::query('DELETE FROM password_tokens WHERE username=:username',
							array(':username'=>$username));

					} else echo 'Incorrect new password!';
					
				} else echo 'Passwords don\'t match!';
			}
		
		} else die('token invalid');

	} else die('Not logged in');
}

?>

<h1>Change your password</h1>

<form method="POST" action="<?php if(!$tokenIsValid) { echo 'change-password.php';} 
else { echo 'change-password.php?token='.$token.'';} ?>">
	<?php 
	
	if(!$tokenIsValid){ echo'<input type="password" name="oldpass" value="" placeholder="Current password..."><p/>';}
	
	?>

	<input type="password" name="newpass" value="" placeholder="New password..."><p/>
	<input type="password" name="newpassrepeat" value="" placeholder="Retype new password..."><p/>
	<button type="submit" name="changepass">Change Password</button>
</form>