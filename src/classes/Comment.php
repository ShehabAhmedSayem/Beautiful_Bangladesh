<?php

class Comment{

	public static function createComment($commentbody, $postid, $username){

		if(strlen($commentbody)>160 || strlen($commentbody)<1){
			die('Incorrect length');
			//echo 'Incorrect length';
		}

		if(!DB::query('SELECT id FROM posts WHERE id = :postid',array(':postid'=>$postid))){
			echo 'Invalid Post Id!';
		} else{
			DB:: query('INSERT INTO comments VALUES(\'\', :postid, NOW(), :comment, :username)',
				array(':postid'=>$postid, ':comment'=>$commentbody,':username'=>$username));
			
			$sender = $username;
			$receiver = DB::query('SELECT username FROM posts WHERE id = :postid',array(':postid'=>$postid))[0]['username'];

			Notify::commentNotify($commentbody,$sender,$receiver,$postid);
		}
	}

	public static function displayComments($postid){

		$comments = DB::query('SELECT * FROM comments WHERE post_id=:postid',array(':postid'=>$postid));
		
		foreach($comments as $c){
			echo "<hr/><span style='font-size:20px;'>".$c['comment']."</span> - <a href='profile.php?username=".$c['username']."'>".$c['username']."</a>";
		}
	}

	public static function createAnswer($answerbody, $quesid, $username){


		if(!DB::query('SELECT id FROM questions WHERE id = :quesid',array(':quesid'=>$quesid))){
			echo 'Invalid Question Id!';
		} else{
			DB:: query('INSERT INTO answers VALUES(\'\', :quesid, :username, :answer)',
				array(':quesid'=>$quesid, ':answer'=>$answerbody,':username'=>$username));
			
			$sender = $username;
			$receiver = DB::query('SELECT username FROM questions WHERE id = :quesid',array(':quesid'=>$quesid))[0]['username'];

			Notify::answerNotify($answerbody,$sender,$receiver,$quesid);
		}
	}

}

?>