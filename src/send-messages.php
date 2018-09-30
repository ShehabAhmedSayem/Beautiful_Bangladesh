<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');

session_start();

$cstrong = true;
$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

if(!isset($_SESSION['token'])){
	$_SESSION['token'] = $token;
}





if(LOG::isLoggedIn()){
	
	$username = LOG::isLoggedIn();

}
else{
	die('Not Logged in!');
}

if(isset($_POST['send'])){

	if(!isset($_POST['nocsrf'])){
		die('Invalid Token');
	}

	if($_POST['nocsrf'] != $_SESSION['token']){
		die('Invalid Token');
	}
	
	if(DB::query('SELECT username FROM users WHERE username=:receiver',array(':receiver'=>$_GET['receiver']))){
		DB::query('INSERT INTO messages VALUES (\'\', :body, :sender, :receiver, 0)',array(':body'=>$_POST['body'], ':sender'=>$username, ':receiver'=>htmlspecialchars($_GET['receiver'])));
		echo "Message sent!";
	}else {
		die('User does not exist!');
	}
	session_destroy();
}

?>

<div style="margin-left:200px;">
<h1>Send Message</h1>
<form action="send-messages.php?receiver=<?php echo htmlspecialchars($_GET['receiver']);?>" method="POST">
	
	<textarea name="body" rows="8" cols="80" required></textarea>
	<input type="hidden" name="nocsrf" value="<?php echo $_SESSION['token'];?>">
	<button type="submit" name="send">Send</button>

</form>
</div>>