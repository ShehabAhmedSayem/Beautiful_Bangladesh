<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');
include('./classes/Post.php');
include('./classes/Comment.php');
include('./classes/Notify.php');

if(LOG::isLoggedIn()){
	
	$username = LOG::isLoggedIn();
}
else{
	echo 'Not Logged in!';
}


if(isset($_POST['ok'])){
  $location = $_POST['location'];

$qa = Post::displayQuestion($location);

  
echo "<br/><div class='container-fluid'>
          <div class='row'>
          <div class='col-sm-1'></div>
            <div class='col-sm-2' style='background-color:lightgrey;text-align:center;border-radius:5px;'>
              <h1 style='text-align:center;'>".$location."</h1>
            </div>";
  
}


if(isset($_POST['answer'])){
  
  $location = $_POST['location'];

  Comment::createAnswer($_POST['answerbody'], $_GET['quesid'], $username);

  $qa = Post::displayQuestion($location);

  
  echo "<br/><div class='container-fluid'>
          <div class='row'>
          <div class='col-sm-1'></div>
            <div class='col-sm-2' style='background-color:lightgrey;text-align:center;border-radius:5px;'>
              <h1 style='text-align:center;'>".$location."</h1>
            </div>";
}


if(isset($_POST['ask'])){  

  $location = $_POST['location'];
  
  Post::createQuestion($_POST['quesbody'], $location, LOG::isLoggedIn());  


  $qa = Post::displayQuestion($location);

  
  echo "<br/><div class='container-fluid'>
          <div class='row'>
          <div class='col-sm-1'></div>
            <div class='col-sm-2' style='background-color:lightgrey;border-radius:5px;'>
              <h2 style='text-align:center;'>".$location."</h2>
            </div>";
}


?>

<head>
  <style>
  .button2 {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    border-radius: 5px;
    padding: 10px 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    -webkit-transition-duration: 0.4s; /* Safari */
    transition-duration: 0.4s;
}

.button2:hover {
    box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
}

</style>
</head>

    
    <div class="col-sm-9">
      <form method="POST" action="q-a.php" enctype="multipart/form-data">
        <div>
          <h3>Ask anything about this place:</h3>
          <textarea name="quesbody" rows="8" cols="80"></textarea>
        </div>
        <input type="hidden" name="location" value="<?php echo htmlspecialchars($_POST['location'], ENT_QUOTES);?>">
        <br/>
        <button class="button2" type="submit" name="ask">Ask</button>
      </form>
      <br/>

      <div id="qa">
        <?php echo $qa; ?>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>