<?php

class Image{


	public static function uploadImage($formname, $query, $params){
		$image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));

		//access_token=8161e61f02cc296a024ef2c46aba530b1f42366f
		//refresh_token=9ff445d291b0c0868ee5dac2901f68983d34228e

		$options = array('http'=>array(
					'method'=>"POST",
					'header'=>"Authorization: Bearer 8161e61f02cc296a024ef2c46aba530b1f42366f\n".
					"Content-Type: application/x-www-forum-urlencoded",
					'content'=>$image
					));

		$context = stream_context_create($options);

		$imgurURL = "https://api.imgur.com/3/image";
	
		if($_FILES[$formname]['size'] > 10240000){
			die('Image too big, must be 10MB or less!');
		}

		$response = file_get_contents($imgurURL, false, $context);
		$response = json_decode($response);

		$preparams = array($formname=>$response->data->link);

		$params = $preparams + $params;

		DB::query($query, $params);  
	}
}

?>





