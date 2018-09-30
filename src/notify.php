<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');

if(LOG::isLoggedIn()){

	$username = LOG::isLoggedIn();

}
else{
	echo 'Not Logged in!';
} 

echo "<br/><br/>
	<h1 style='margin-left:120px;'>N O T I F I C A T I O N</h1><hr/>
	<br/>
	<br/>
	<div style='margin-left:200px;'>";

if(DB::query('SELECT * FROM notifications WHERE receiver=:username',array(':username'=>$username))){

	$notifications = DB::query('SELECT * FROM notifications WHERE receiver=:username ORDER BY id DESC',
					array(':username'=>$username));

	foreach ($notifications as $n) {
		$postid = $n['post_id'];
		
		if($n['type'] == 1){

			echo "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." mentioned you in a post!";

		}
		else if($n['type'] == 2){

			echo "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." liked your post!";
		}
		else if($n['type'] == 3){

			echo "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." commented on your post!";
		}
		else if($n['type'] == 4){

			echo "<a href='postshow.php?postid=0&quesid=".$postid."'>".$n['sender']." has posted a question!";
		}
		else if($n['type'] == 5){

		   echo "<a href='postshow.php?postid=0&quesid=".$postid."'>".$n['sender']." answered to your question!";
		}
		echo "</a><hr/>";
	}

}

include('footer.php');
?>