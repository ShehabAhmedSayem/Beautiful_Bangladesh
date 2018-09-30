<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('./classes/Post.php');
include('./classes/Comment.php');
include('./classes/Notify.php');


$showTimeline = false;

if(LOG::isLoggedIn()){
	
	$username = LOG::isLoggedIn();

	$showTimeline = true;
}
else{

	header('Location: login.php');
}

include('header.php');

if(isset($_GET['postid'])){
	Post::likePost($_GET['postid'], $username);
}

if(isset($_POST['comment'])){
	Comment::createComment($_POST['commentbody'], $_GET['postId'], $username);
}

/*
if(isset($_POST['search'])){

	$toSearch = explode (" ", $_POST['searchbox']);

	if(count($toSearch) == 1){
		$toSearch = str_split($toSearch[0],2);
	}

	$paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');
	$whereclause = "";
	
	for($i = 0; $i < count($toSearch); $i++){
		$whereclause .= " OR username LIKE :u$i ";
		$paramsarray[":u$i"] = $toSearch[$i]; 
	}

	$users = DB::query('SELECT users.username FROM users WHERE users.username LIKE :username'.$whereclause.'',$paramsarray);
	echo '<pre>';
	print_r($users);
	echo '</pre>';

	$paramsarray = array(':body'=>'%'.$_POST['searchbox'].'%');
	$whereclause = "";
	
	for($i = 0; $i < count($toSearch); $i++){
		
		if($i % 2){
			$whereclause .= " OR body LIKE :p$i ";
			$paramsarray[":p$i"] = $toSearch[$i]; 
		}
	}

	$posts = DB::query('SELECT posts.body FROM posts WHERE posts.body LIKE :body'.$whereclause.'',$paramsarray);
	echo '<pre>';
	print_r($posts);
	echo '</pre>';
}
*/

?>


<br/><br/>
<h1 style="margin-left:120px;">T I M E L I N E</h1><hr/>
<br/>
<br/>
<div class="row">
<div class="col-xs-2" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin-left:50px;padding:10px 10px;">
<h4>Suggested People:</h4>
</br>

<?php	

  
  
  //$max = ('SELECT max(id) from users');
  $id = rand(1, 7);
  //echo $max;
  for($i = 0; $i < 3 ; $i++){
  	
    $people = DB::query('SELECT full_name FROM users Where id=:id',array(':id'=>$id))[0]['full_name'];
    $peoplename  = DB::query('SELECT username FROM users Where id=:id',array(':id'=>$id))[0]['username'];
    echo '<p><a href="profile.php?username='.$peoplename.'">'.$people.'</a></p>';
    if($id==7) $id=1;
    else $id++;
  }
?>
 
</div>
<div class="col-xs-8" style="margin-left:50px;margin-right:0px;">
<?php

$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, posts.posted_at, users.`username` FROM users, posts, followers
				WHERE posts.username = followers.username 
				AND users.username = posts.username 
				AND follower_name = :username
				ORDER BY posts.likes DESC',array(':username'=>$username));
$check = 0;

foreach($followingposts as $p){
	if($check == 0){
		$check=1;
		echo '<div style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin-bottom:20px;"><blockquote style="background-color:#f8f8f8;">';
	}
	else{
		$check=0;
		echo '<div style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin-bottom:20px;"><blockquote>';
	}

	echo "<p style='font-style:italic;font-size:25px;'>";
	
	echo $p['body']."</p><br/><footer>Posted by <a href='profile.php?username=".$p['username']."'>".$p['username']."</a> on ".$p['posted_at'];

	echo "<br/><br/><form method='POST' action='index.php?postid=".$p['id']."'>";
	
	if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND username=:username',
		array(':postid'=>$p['id'],':username'=>$username))){
	
		echo "<button type='submit' name='like' ><i class='glyphicon glyphicon-heart' style='color:black;'></i></button>";
	
	} else {
		echo "<button type='submit' name='unlike' style='color:#eb3b60;'><i class='glyphicon glyphicon-heart' ></i></button>";
	}
	echo "<span>  ".$p['likes']." likes</span><br/><br/>
		</form>
		<form action='index.php?postId=".$p['id']."' method='post'>
			<textarea name='commentbody' rows='1' cols='40'></textarea>
			<button type='submit' name='comment'>Comment</button>
		</form>";
		
		Comment::displayComments($p['id']);
		
		echo "<hr/><br/></footer></blockquote></div>";	
}

?>
</div>
</div>
<?php include('footer.php'); ?>