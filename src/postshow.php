<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');
include('./classes/Post.php');
include('./classes/Image.php');
include('./classes/Notify.php');
include('./classes/Comment.php');


$username = LOG::isLoggedIn();

if(isset($_POST['comment'])){
	Comment::createComment($_POST['commentbody'], $_GET['postid'], $username);
}

if(isset($_POST['like']) || isset($_POST['unlike'])){
			
	Post::likePost($_GET['postid'], $username);
}

if(isset($_POST['answer'])){
  
  $location = $_POST['location'];

  Comment::createAnswer($_POST['answerbody'], $_GET['quesid'], $username);

}

if($_GET['postid']!=0){

	

	$p=DB::query('SELECT * FROM posts WHERE id=:postid',
					array(':postid'=>$_GET['postid']))[0];
	
	$posts="";

	$posts.="<blockquote>";

	$postpic = DB::query('SELECT * FROM pictures WHERE post_id=:postid',
				array(':postid'=>$p['id']));

	foreach ($postpic as $pp) {
		$posts .= "<img src = '".$pp['picture']."' style='width:200px;height:200px;'>";
	}

	$posts.="<br/>";

	if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND username=:username ',
				array(':postid'=>$p['id'],':username'=>$username))){
				
			$posts .= "<p style='font-style:italic;font-size:30px;'><strong>";

			$posts .= htmlspecialchars($p['body'])."</p><br/><footer>Posted by ".$p['username']." on ".$p['posted_at']."<br/><br/>
				<form method='POST' action='postshow.php?postid=".$p['id']."&quesid=0'>
					<button type='submit' name='like' ><i class='glyphicon glyphicon-heart' style='color:black;'></i></button>
				<span>".$p['likes']."likes</span></form><br/>";
			
			} else{
				
				$posts .= "<p style='font-style:italic;font-size:30px;'><strong>";

				$posts .= htmlspecialchars($p['body'])."</p><br/><footer>Posted by ".$p['username']." on ".$p['posted_at']."<br/><br/>
					<form method='POST' action='postshow.php?postid=".$p['id']."&quesid=0'>
						<button type='submit' name='unlike' style='color:#eb3b60;'><i class='glyphicon glyphicon-heart' ></i></button>
						<span>".$p['likes']."likes</span></form><br/>";
			
			}

			$posts.="</form>
						<form action='postshow.php?postid=".$p['id']."&quesid=0' method='post'>
						<textarea name='commentbody' rows='1' cols='40'></textarea>
						<button type='submit' name='comment'>Comment</button>
					</form>";
			
			$comments = DB::query('SELECT * FROM comments WHERE post_id=:postid',array(':postid'=>$p['id']));
		
			foreach($comments as $c){
				$posts.= "<hr/>".$c['comment']." - <a href='profile.php?username=".$c['username']."'>".$c['username']."</a>";
			}
			$posts.="</blockquote>";
		echo $posts;
}
else{

	$q=DB::query('SELECT * FROM questions WHERE id=:quesid',
					array(':quesid'=>$_GET['quesid']))[0];
	
	$qa="<h2 style='margin-left:50px;'>".$q['location_name']."</h2><hr/>";

	$qa.="<blockquote style='margin-left:150px;'>";

	$qa .= "<p style='text-decoration:underline;'>Question:</p><br/><p style='font-style:italic;font-size:30px;'><strong>";	
	$qa .= htmlspecialchars($q['body']);			
			
	$qa.="</strong></p><br/><p style='text-decoration:underline;'>Your answer:</p><br/></form>
			<form action='postshow.php?postid=0&quesid=".$q['id']."' method='post'>
				<textarea name='answerbody' rows='1' cols='40'></textarea>
				<input type='hidden' name='location' 
				value=".htmlspecialchars($q['location_name'], ENT_QUOTES).">
				<button type='submit' name='answer'>Answer</button>
			</form>";
			
	$answers = DB::query('SELECT * FROM answers WHERE ques_id=:quesid',array(':quesid'=>$q['id']));


	if($answers) $qa.="<br/><p style='text-decoration:underline;'>Given Answers:</p><br/>";
	foreach($answers as $c){
		$qa.= "<hr/><p style='font-style:italic;font-size:20px;'>".$c['body']."</p> - answerded by <a href='profile.php?username=".$c['username']."'>".$c['username']."</a>";
	}
	$qa.="<hr/><hr/></blockquote>";
	echo $qa;
}

include('footer.php');

?>