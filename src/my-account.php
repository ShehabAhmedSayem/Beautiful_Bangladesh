<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');
include('./classes/Image.php');


if(LOG::isLoggedIn()){	
	$username = LOG::isLoggedIn();
}
else{
	die('Not Logged in!');
}


if(isset($_POST['submit'])){

	if($_FILES['profileimg']['size'] != 0){
		
		Image::uploadImage('profileimg', 'UPDATE users SET profileimg = :profileimg WHERE username = :username',
			array(':username'=>$username));
	}

	if($_FILES['coverphoto']['size'] != 0){
		
		Image::uploadImage('coverphoto', 'UPDATE users SET coverphoto = :coverphoto WHERE username = :username',
			array(':username'=>$username));
	}
		
	
	if(strlen($_POST['fullname'])>0){
	
		DB::query('UPDATE users SET full_name = :fullname WHERE username = :username',
			array(':username'=>$username,':fullname'=>$_POST['fullname']));
	}

	if(strlen($_POST['about'])>0){
	
		DB::query('UPDATE users SET about = :about WHERE username = :username',
			array(':username'=>$username,':about'=>$_POST['about']));
	}

	echo 'Account updated!';
}

?>

<h1 style="margin-left:120px;">U P D A T E <br/>A C C O U N T</h1><hr/>
<br/>
<br/>
<div style="margin-left:200px;">
<form action="my-account.php" method="POST" enctype="multipart/form-data">
	<h4>Upload a profile picture:</h4>
	<input type="file" name="profileimg">
	<br/>
	<h4>Upload a cover photo:</h4>
	<input type="file" name="coverphoto">
	<br/>
	<h4>Your Full Name:</h4>
	<input type="text" name="fullname">
	<br/>
	<h4>About Yourself:</h4>
	<textarea name="about" rows="8" cols="80"></textarea>
	<br/>
	<br/>
	<button class="btn btn-primary" type="submit" name="submit">Submit</button>
</form>
</div>

<?php include('footer.php'); ?>