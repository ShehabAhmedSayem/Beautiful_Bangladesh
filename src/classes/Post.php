<?php

class Post{

	public static function createPost($postbody, $location, $loggedInUserName, $profileUsername){

		if(strlen($postbody)>160 || strlen($postbody)<1){
			die('Incorrect length');
			//echo 'Incorrect length';
		}

		if($loggedInUserName == $profileUsername){

			DB:: query('INSERT INTO posts VALUES(\'\', :postbody, NOW(), :username, :location, 0,NULL,NULL)',
				array(':postbody'=>$postbody,':username'=>$profileUsername,':location'=>$location));
			$postid = DB::query('SELECT id FROM posts WHERE username=:username ORDER BY id DESC LIMIT 1',
				array(':username'=>$loggedInUserName))[0]['id'];

			if(count(Notify::createNotify($postbody)) != 0){

				foreach(Notify::createNotify($postbody) as $key => $n){

					$s = $loggedInUserName;
					$r = $key;
					
					if($r){
						
						DB:: query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',
						array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"],':seen'=>0,':postid'=>$postid));
					}
				}
			}

			
		
		} else{
			die('You can\'t post in another user\'s page!');
		}
	}


	public static function createImgPost($postbody, $location, $loggedInUserName, $profileUsername){

		if(strlen($postbody)>160){
			die('Incorrect length');
			//echo 'Incorrect length';
		}

		if($loggedInUserName == $profileUsername){

			DB:: query('INSERT INTO posts VALUES(\'\', :postbody, NOW(), :username, :location, 0,NULL,NULL)',
				array(':postbody'=>$postbody,':username'=>$profileUsername,':location'=>$location));
			$postid = DB::query('SELECT id FROM posts WHERE username=:username ORDER BY id DESC LIMIT 1',
				array(':username'=>$loggedInUserName))[0]['id'];

			if(count(Notify::createNotify($postbody)) != 0){

				foreach(Notify::createNotify($postbody) as $key => $n){

					$s = $loggedInUserName;
					$r = $key;
					
					if($r){
						
						DB:: query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',
						array(':type'=>$n["type"], ':receiver'=>$r, ':sender'=>$s, ':extra'=>$n["extra"],':seen'=>0,':postid'=>$postid));	
					}
				}
			}

			return $postid;
		
		} else{
			die('You can\'t post in another user\'s page!');
		}
	}


	public static function likePost($postId, $likerName){

		if(!DB::query('SELECT username FROM post_likes WHERE post_id=:postid AND username=:username ',
				array(':postid'=>$postId,':username'=>$likerName))){
				
			DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid',array(':postid'=>$postId));
			DB::query('INSERT INTO post_likes VALUES(\'\', :postid, :username)',
				array(':postid'=>$postId,':username'=>$likerName));

			Notify::likeNotify($likerName,$postId);
			
			
		} else {

			DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid',array(':postid'=>$postId));
			DB::query('DELETE FROM post_likes WHERE post_id=:postid AND username=:username',
				array(':postid'=>$postId,':username'=>$likerName));

		}
	}

	public static function displayPosts($username,$follower_name){
		
		$dbposts=DB::query('SELECT * FROM posts WHERE username=:username ORDER BY id DESC',
					array(':username'=>$username));
		$posts = "";
		
		
		foreach ($dbposts as $p) {

			$posts.="<div style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);margin-bottom:20px;padding-bottom:1px;'><blockquote>";

			$postpic = DB::query('SELECT * FROM pictures WHERE post_id=:postid',
					array(':postid'=>$p['id']));

			foreach ($postpic as $pp) {
				$posts .= "<img src = '".$pp['picture']."' style='width:200px;height:200px;'>";
			}
			$posts.="<br/>";

			if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND username=:username ',
				array(':postid'=>$p['id'],':username'=>$follower_name))){
				
				$posts .= "<p style='font-style:italic;font-size:30px;'><strong>";

				$posts .= htmlspecialchars($p['body'])."</p><br/><footer>Posted by ".$p['username']." on ".$p['posted_at']."<br/><br/>
					<form method='POST' action='profile.php?username=$username&postid=".$p['id']."'>
						<button type='submit' name='like' ><i class='glyphicon glyphicon-heart' style='color:black;'></i></button>
						<span>".$p['likes']."likes</span>";
					
					$follower_verified = DB::query('SELECT verified FROM users WHERE username=:username',
										array(':username'=>$follower_name))[0]['verified'];
					
					if($username == $follower_name || $follower_verified){
						$posts.=" <button type='submit' name='deletepost'>Delete</button><br/>";
					}
					
					$posts.="</form><br/>";
			
			} else{
				
				$posts .= "<p style='font-style:italic;font-size:30px;'><strong>";

				$posts .= htmlspecialchars($p['body'])."</p><br/><footer>Posted by ".$p['username']." on ".$p['posted_at']."<br/><br/>
					<form method='POST' action='profile.php?username=$username&postid=".$p['id']."'>
						<button type='submit' name='unlike' style='color:#eb3b60;'><i class='glyphicon glyphicon-heart' ></i></button>
						<span>".$p['likes']."likes</span>";
					
					$follower_verified = DB::query('SELECT verified FROM users WHERE username=:username',
										array(':username'=>$follower_name))[0]['verified'];

					if($username == $follower_name || $follower_verified){
						$posts.=" <button type='submit' name='deletepost'>Delete</button><br/>";
					}
					
					$posts.="</form><br/>";
			
			}
			$posts.="</form>
						<form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
						<textarea name='commentbody' rows='1' cols='40'></textarea>
						<button type='submit' name='comment'>Comment</button>
					</form>";
			
			$comments = DB::query('SELECT * FROM comments WHERE post_id=:postid',array(':postid'=>$p['id']));
		
			foreach($comments as $c){
				$posts.= "<hr/>".$c['comment']." - <a href='profile.php?username=".$c['username']."'>".$c['username']."</a>";
			}
			$posts.="</blockquote></div>";
		}
		

		return $posts;
	}


	public static function createQuestion($quesbody, $location, $username){
	
		DB:: query('INSERT INTO questions VALUES(\'\', :quesbody,:location, NOW(), :username)',
				array(':quesbody'=>$quesbody,':username'=>$username,':location'=>$location));
		$quesid = DB::query('SELECT id FROM questions WHERE username=:username ORDER BY id DESC LIMIT 1',
				array(':username'=>$username))[0]['id'];

		$sender = $username;
		$receiver = DB::query('SELECT * FROM followers WHERE follower_name = :followername',array(':followername'=>$username));

		foreach($receiver as $r){
			$rec = $r['username'];
			Notify::questionNotify($quesbody,$sender,$rec,$quesid);
		}		
	}


	public static function displayQuestion($location){
		
		$question = DB::query('SELECT * FROM questions WHERE location_name=:location ORDER BY id DESC',
					array(':location'=>$location));
		$qa = "";


		foreach ($question as $q) {

			$qa.="<blockquote>";

			$qa .= "<p style='text-decoration:underline;'>Question:</p><br/><p style='font-style:italic;font-size:30px;'><strong>";	
			$qa .= htmlspecialchars($q['body']);			
			
			$qa.="</strong></p><br/><p style='text-decoration:underline;'>Your answer:</p><br/></form>
						<form action='q-a.php?&quesid=".$q['id']."' method='post'>
						<textarea name='answerbody' rows='1' cols='40'></textarea>
						<input type='hidden' name='location' 
						value=".htmlspecialchars($_POST['location'], ENT_QUOTES).">
						<button type='submit' name='answer'>Answer</button>
					</form>";
			
			$answers = DB::query('SELECT * FROM answers WHERE ques_id=:quesid',array(':quesid'=>$q['id']));


			if($answers) $qa.="<br/><p style='text-decoration:underline;'>Given Answers:</p><br/>";
			foreach($answers as $c){
				$qa.= "<hr/><p style='font-style:italic;font-size:20px;'>".$c['body']."</p> - answerded by ".$c['username'];
			}
			$qa.="<hr/><hr/></blockquote>";
		}

		return $qa;
	}


}

?>