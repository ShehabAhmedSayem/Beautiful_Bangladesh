<?php

session_start();
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');

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

if(isset($_GET['mid'])){
	
	$message = DB::query('SELECT * FROM messages WHERE id=:mid AND (receiver=:receiver OR sender=:sender)',
				array(':mid'=>$_GET['mid'], ':receiver'=>$username,':sender'=>$username))[0];
	
	echo "<br/><br/>
			<h1 style='margin-left:120px;'>Message Details</h1><hr/>
			<br/>
			<br/>
			<div style='margin-left:200px;'>";
	echo htmlspecialchars($message['body']);
	echo '<hr />';
	
	
	if($message['sender'] == $username){
		$id = $message['receiver'];
		
	}
	else {
		$id = $message['sender'];
	
	}
	
	DB::query('UPDATE messages SET seen=1 WHERE id=:mid',array(':mid'=>$_GET['mid']));

	?>
	
	<form action="send-messages.php?receiver=<?php echo $id;?>" method="POST">
		<textarea name="body" rows="4" cols="80"></textarea>
		<input type="hidden" name="nocsrf" value="<?php echo $_SESSION['token'];?>">
		<button type="submit" name="send">Reply</button>
	</form>
	
	<?php
}

else{
?>

<br/><br/>
<h1 style="margin-left:120px;">M E S S A G E S</h1><hr/>
<br/>
<br/>
<div style="margin-left:200px;">

<?php

	$messages = DB::query('SELECT * FROM messages WHERE receiver=:receiver OR sender=:sender ORDER BY id DESC',array(':receiver'=>$username,':sender'=>$username));

	foreach ($messages as $msg) {
		
		if(strlen($msg['body']) > 10){
			$m = substr($msg['body'], 0, 10);
			$m.="...";
		}
		else{
			$m = $msg['body'];
		}

		if($msg['seen']==0){
			 echo "<a href='my-messages.php?mid=".$msg['id']."'><strong>".$m."</strong></a> sent by ".$msg['sender']." to ".$msg['receiver']."<hr/>";
		}
		else{
			 echo "<a href='my-messages.php?mid=".$msg['id']."'>".$m."</a> sent by ".$msg['sender']." to ".$msg['receiver']."<hr/>";
		}

	}
}
?>
</div>



<?php include('footer.php'); ?>