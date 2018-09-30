<?php

class LOG{
	
	public static function isLoggedIn(){

		if(isset($_COOKIE['SNID'])){
	   
	   		if(DB::query('SELECT username FROM login_tokens WHERE token=:token',  
	   			array(':token'=>sha1($_COOKIE['SNID']))))
			{
				$username = DB::query('SELECT username FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))[0]['username'];
			
				if(isset($_COOKIE['SNID_'])){
					return $username;
				}
				else{
					$cstrong = true;
					$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

					DB::query('INSERT INTO login_tokens VALUES(\'\', :token, :username)', array(':token'=>sha1($token), ':username'=>$username));
					DB::query('DELETE FROM login_tokens WHERE token=:token',array(':token'=>sha1($_COOKIE['SNID'])));	
					setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
					setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);

					return $username;
				}
			}
		}
		return false;
	}
}
?>