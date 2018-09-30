<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');


if(isset($_POST['submit'])){

	$div = $_POST['division'];
	$dist = $_POST['district'];
	$loc = $_POST['location'];

	DB::query('INSERT INTO request VALUES(\'\',:div,:dist,:loc)',array(':div'=>$div,':dist'=>$dist,':loc'=>$loc));

	echo "Please Wait for Admin's Approval";
}
?>

<div style="text-align:center">
<h1>Add Location</h1>
  
  <form action="newlocation.php" method="POST">
 	Select Division:
 	<input type="text" name="division">
 	Select District:
 	<input type="text" name="district">
 	Select location:
 	<input type="text" name="location"> 
  <button class="button2" type="submit" name="submit">SUBMIT</button>
  </form>

</div>