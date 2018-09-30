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

		if(isset($_POST['deletepost'])){

			if(DB::query('SELECT id FROM posts WHERE id=:postid',
				array(':postid'=>$_GET['postid']))){
				
				DB::query('DELETE FROM comments WHERE post_id=:postid',
					array(':postid'=>$_GET['postid']));
				DB::query('DELETE FROM post_likes WHERE post_id=:postid',
					array(':postid'=>$_GET['postid']));
				DB::query('DELETE FROM pictures WHERE post_id=:postid',
					array(':postid'=>$_GET['postid']));
				DB::query('DELETE FROM posts WHERE id=:postid',
					array(':postid'=>$_GET['postid']));
				
				echo "post deleted";
			}

		}

		if(isset($_POST['post'])){
			
			if($_FILES['postimg']['size'] == 0){
				
				Post::createPost($_POST['postbody'], $_POST['location'], LOG::isLoggedIn(), $username);
			
			} else{
				$postid = Post::createImgPost($_POST['postbody'], $_POST['location'], LOG::isLoggedIn(), $username);
				Image::uploadImage('postimg','INSERT INTO pictures VALUES(\'\', :postimg, :postid)',
					array(':postid'=>$postid));
			}
		}

		if(isset($_POST['comment'])){
			Comment::createComment($_POST['commentbody'], $_GET['postid'], $follower_name);
		}

		if(isset($_GET['postid']) && !isset($_POST['deletepost']) && !isset($_POST['comment'])){
			
			Post::likePost($_GET['postid'], $follower_name);
		}

		$posts = Post::displayPosts($username, $follower_name);

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
</br>
</br>

<div class="container-fluid">
<div class="row">
<div class="col-xs-3" style="background-color:#f8f8f8;margin-left:50px;padding:10px 0 10px 20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);">
<h4>|| About ||</h4>
</br>
<p style="padding-left: 25px;"><?php
echo DB::query('SELECT about FROM users WHERE username=:username',
		array(':username'=>$_GET['username']))[0]['about'];
?></p>
</br>

<h4>|| Follower ||</h4>
<p style="padding-left: 25px;"><?php

$count = 0;
if(DB::query('SELECT * FROM followers WHERE username=:username',array(':username'=>$_GET['username']))){

	$follower = DB::query('SELECT * FROM followers WHERE username=:username',
		array(':username'=>$_GET['username']));

	foreach ($follower as $f) {
		$count++;
	}
	echo $count.' people';
}
else{
	echo 'No one is following';
}

?></p>
</br>

<h4>|| Following ||</h4>
<p style="padding-left: 25px;"><?php

$count = 0;
if(DB::query('SELECT * FROM followers WHERE follower_name=:followername',array(':followername'=>$_GET['username']))){

	$follower = DB::query('SELECT * FROM followers WHERE follower_name=:followername',
		array(':followername'=>$_GET['username']));

	foreach ($follower as $f) {
		$count++;
	}
	echo $count.' people';
}
else{
	echo 'No follower';
}

?></p>
</br>

<h4><a href="followlist.php?username=<?php echo $_GET['username'];?>">Check Follower List</a></h4>

</div>
<div class="col-xs-8" style="margin-left:10px;">
<?php

	if($username == LOG::isLoggedIn()){
		echo '<h2>New Post</h2>
			<form method="POST" action="profile.php?username='.$username.'"enctype="multipart/form-data">
			<div>
				<textarea name="postbody" rows="8" cols="80"></textarea>
			</div>
			<br/>
			<div>
			<select name="location" required>
		      <option value="">Select Location</option>';
		      $results = DB::query("SELECT * FROM location",array());

		      foreach ($results as $location){
		        echo '<option value="'.$location["name"].'">'.$location["name"].'</option>';
		      }
		    echo '</select>
		    </div>
			<br/>Upload an image:
			<br/>
			<input type="file" name="postimg">
			<br/>
			<button class="btn btn-primary" type="submit" name="post">Post</button>

			<button class="btn btn-primary" name="NewPlace"><a href="newlocation.php" style="text-decoration:none;color:white;">Create new location</a></button>
		</form>';
	}

?>
<br/>

<div class="posts">
	<?php echo $posts; ?>
</div>
</div>
</div>
</div>



<?php include('footer.php'); ?>