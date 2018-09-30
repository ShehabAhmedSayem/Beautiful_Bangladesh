<?php
include('./classes/DB.php');
include('./classes/LOG.php');
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Bangladesh</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
.button2 {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    border-radius: 5px;
    padding: 15px 32px;
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

a:link, a:visited {
    color: white;
    text-decoration: none;
}
    </style>
</head>

<?php

if(isset($_POST['confirm'])){
    
    if(isset($_POST['alldevices']))
    {
        DB::query('DELETE FROM login_tokens WHERE username=:username',array(':username'=>LOG::isLoggedIn()));
    } 
    else{
        
        if(isset($_COOKIE['SNID'])){
            DB::query('DELETE FROM login_tokens WHERE token=:token',array(':token'=>sha1($_COOKIE['SNID'])));
        }
        setcookie('SNID','1',time()-3600);
        setcookie('SNID_','1',time()-3600);
    }
}

if(isset($_POST['login']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(DB::query('SELECT username FROM users WHERE username=:username',array(':username'=>$username)))
	{
		if(password_verify($password, DB::query('SELECT password FROM users WHERE username=:username',array(':username'=>$username))[0]['password']))
		{
			
			echo "<h1 style='text-align:center'>You're Logged In</h1>
				  <h3 style='text-align:center'>To go to your timeline click here</h3>
				  <div style='text-align:center'>
				  <button class='button2'><a href='index.php' style='text-decoration:none,color:white;'>Timeline</a></button>
				  </div>";

			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

			DB::query('INSERT INTO login_tokens VALUES(\'\', :token, :username)', array(':token'=>sha1($token), ':username'=>$username));

			setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
			setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
		}
		else echo "Incorrect password!";
	}
	else echo "User not registered!";
}

?>




<body>
    <div class="login-clean">
        <form method="post" action="login.php">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
            <div class="form-group">
                <input class="form-control" type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" id="login" type="submit" name="login" data-bs-hover-animate="shake">Log In</button>
            
            </form>
            <button class="btn btn-primary btn-block"><a href="registration.php">Register</a></button>
    </div>
</body>