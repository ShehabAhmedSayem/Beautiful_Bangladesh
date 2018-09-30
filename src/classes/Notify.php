<?php

class Notify{
	public static function createNotify($text = "", $postid = 0){
		

		$text = explode(" ",$text);
		$notify = array();

		foreach ($text as $word) {
			
			if(substr($word, 0, 1) == '@'){
				$notify[substr($word, 1)] = array('type'=>1, 
					'extra'=>'{ "postbody": "'.htmlentities(implode($text, " ")).'"}');
			} 
		}

		return $notify;
	}

	public static function likeNotify($sender,$postid){
		$receiver = DB::query('SELECT username FROM posts WHERE id=:postid',array(':postid'=>$postid))[0]['username'];
		DB::query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',array(':type'=>2, ':receiver'=>$receiver, ':sender'=>$sender, ':extra'=>"",':seen'=>0,':post_id'=>$postid));
	}

	public static function commentNotify($commentbody,$sender,$receiver,$postid){

		DB:: query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',
						array(':type'=>3, ':receiver'=>$receiver, ':sender'=>$sender, ':extra'=>$commentbody,':seen'=>0,':post_id'=>$postid));
	}

	public static function questionNotify($quesbody,$sender,$receiver,$postid){

		DB:: query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',
						array(':type'=>4, ':receiver'=>$receiver, ':sender'=>$sender, ':extra'=>$quesbody,':seen'=>0,':post_id'=>$postid));
	}

	public static function answerNotify($answerbody,$sender,$receiver,$postid){

		DB:: query('INSERT INTO notifications VALUES(\'\', :type, :receiver, :sender, :extra, :seen, :post_id)',
						array(':type'=>5, ':receiver'=>$receiver, ':sender'=>$sender, ':extra'=>$answerbody,':seen'=>0,':post_id'=>$postid));
	}
}

?>