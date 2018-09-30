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
if(isset($_POST['register']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
    $fullname = $_POST['fullname'];

	if(!DB::query('SELECT username FROM users WHERE username=:username',array(':username'=>$username)))
	{
		if(strlen($username)>=3 && strlen($username)<=32)
		{
			if(preg_match('/[a-zA-Z0-9_]+/', $username))
			{
				if(strlen($password)>=5 && strlen($password)<=60)
				{
					if(filter_var($email,FILTER_VALIDATE_EMAIL))
					{
						if(!DB::query('SELECT email FROM users WHERE email=:email',array(':email'=>$email)))
						{	
							DB::query('INSERT INTO users VALUES (\'\',:username,:password,:email,\'0\',\'\',:fullname,\'\',\'\')',
								array(':username'=>$username, ':password'=>password_hash($password,PASSWORD_BCRYPT), ':email'=>$email, ':fullname'=>$fullname));
							echo "<h1 style='text-align:center'>You're Registered</h1>
				  <h3 style='text-align:center'>Click to login</h3>
				  <div style='text-align:center'>
				  <button class='button2'><a href='login.php' style='text-decoration:none,color:white;'>Log In</a></button>
				  </div>";
						}
						else echo "Email is already in use!";
					}
					else echo "Invalid email!";
				}
				else echo "Invalid password! Length issue!";
			}
			else echo "Username's character is not supported!";
		}
		else echo "Invalid username! Length issue!";
	}
	else echo "Username exists!";
}

?>


<body>
<div class="login-clean">
        <form method="post" action="registration.php">
            <h2 class="sr-only">Registration Form</h2>
            <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
            <div class="form-group">
                <input class="form-control" type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" id="fullname" name="fullname" placeholder="Fullname" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input class="form-control" type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" id="register" type="submit" name="register" data-bs-hover-animate="shake">Register</button>
                </div>
        </form>
</div>
        
</body>