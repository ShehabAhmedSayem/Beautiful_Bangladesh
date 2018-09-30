<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');
include('./classes/Post.php');
include('./classes/Image.php');
include('./classes/Notify.php');
include('./classes/Comment.php');


$username = "";
$follower_name = "";
$isFollowing = false;
$coverphoto = NULL;

if(isset($_GET['username'])){

	if(DB::query('SELECT username from users WHERE username=:username',array(':username'=>$_GET['username']))){

		$coverphoto = DB::query('SELECT coverphoto from users WHERE username=:username',
			array(':username'=>$_GET['username']))[0]['coverphoto'];

		$username = DB::query('SELECT username from users WHERE username=:username',
			array(':username'=>$_GET['username']))[0]['username'];
		$follower_name = LOG::isLoggedIn();
		$user_verified = DB::query('SELECT verified FROM users WHERE username=:username',
										array(':username'=>$_GET['username']))[0]['verified'];

		if(isset($_POST['follow'])){

			DB::query('INSERT INTO followers VALUES(\'\', :username, :followername)',
				array(':username'=>$username, ':followername'=>$follower_name));		

			$isFollowing = true;
		
		}

		if(isset($_POST['unfollow'])){

			DB::query('DELETE FROM followers WHERE username=:username AND follower_name=:followername',
				array(':username'=>$username, ':followername'=>$follower_name));

			echo 'Unfollow Success!';

			$isFollowing = false;
		}

		if(DB::query('SELECT id FROM followers WHERE username=:username AND follower_name=:followername',
			array(':username'=>$username, ':followername'=>$follower_name))){
			$isFollowing = true;
		}
		
	} else{
		die('User not found!');
	}

}

?>

<div style="margin-left:50px;background-image:url('<?php echo $coverphoto; ?>');width:1200px;height:300px;background-repeat:no-repeat;">
<br/><br/>
<div class="container-fluid">
<div class="row">
<div class="col-xs-2" style="padding-left:50px;padding-top:100px;">
<?php
	$profilepic = DB::query('SELECT profileimg FROM users WHERE username=:username',
					array(':username'=>$username))[0]['profileimg'];

	 echo "<img src = '".$profilepic."' style='width:150px;height:150px;'>";
			
?>
</div>


<div class="col-xs-3" style="padding-top:100px;">
<h3 style="color:lightgrey;"><b><?php if(DB::query('SELECT full_name FROM users WHERE username=:username',array(':username'=>$username))[0]['full_name'])
		{
			echo strtoupper(DB::query('SELECT full_name FROM users WHERE username=:username',array(':username'=>$username))[0]['full_name']);
		}

		else echo strtoupper($username);	

	?><?php if($user_verified==1) echo " (v)"; ?></b></h3>

<form method="POST" action="profile.php?username=<?php echo $username; ?>">
	<?php 
	if($username != $follower_name){

		if(!$isFollowing) echo '<button class="btn btn-primary" type="submit" name="follow">Follow</button>';
		else echo '<button class="btn btn-primary" type="submit" name="unfollow">Unfollow</button>';
		echo '    <button class="btn btn-primary" name="message"><a href="send-messages.php?receiver='.$_GET['username'].'" style="text-decoration:none;color:white;">Send Message</a></button><br/>';
	}
	else{
		echo '<button class="btn btn-primary" name="update"><a href="my-account.php" style="text-decoration:none;color:white;">Update Account</a></button>';
	}
	
	?>
</form>
<br/><br/>
</div>
</div>
</div>
</div>
<hr/>

<div class="container-fluid">
<div class="row">
<div class="col-xs-1">
</div>
<div class="col-xs-3" style="padding:0 0 5px 20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin:0 20px 20px 0;">

<h2> Follower List: </h2>
<p style="padding-left: 25px;"><?php

if(DB::query('SELECT * FROM followers WHERE username=:username',array(':username'=>$_GET['username']))){

	$follower = DB::query('SELECT * FROM followers WHERE username=:username',
		array(':username'=>$_GET['username']));

	foreach ($follower as $f) {
		echo '<a href="profile.php?username='.$f['follower_name'].'">'.DB::query('SELECT full_name FROM users WHERE username=:username',
		array(':username'=>$f['follower_name']))[0]['full_name'].'</a></br>';
	}
}
else{
	echo 'No one is following';
}

?></p>
</br>
</div>

<div class="col-xs-3" style="padding:0 0 5px 20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin-bottom:20px;">
<h2> Following List: </h2>
<p style="padding-left: 25px;"><?php

if(DB::query('SELECT * FROM followers WHERE follower_name=:followername',array(':followername'=>$_GET['username']))){

	$follower = DB::query('SELECT * FROM followers WHERE follower_name=:followername',
		array(':followername'=>$_GET['username']));

	foreach ($follower as $f) {
		echo '<a href="profile.php?username='.$f['username'].'">'.DB::query('SELECT full_name FROM users WHERE username=:username',
		array(':username'=>$f['username']))[0]['full_name'].'</a></br>';
	}
}
else{
	echo 'No follower';
}

?></p>
</br>
</div>

</div>
</div>




<?php include('footer.php'); ?>