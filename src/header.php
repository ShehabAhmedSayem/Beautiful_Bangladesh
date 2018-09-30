<?php


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Bangladesh</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Dark.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/untitled.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <style>

        input[type=text] {
            width: 200px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            margin: 15px 0px 0 0px;
            border-radius: 4px;
            font-size: 12px;
            background-color: white;
            padding: 5px 5px 5px 10px;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }

        input[type=text]:focus {
            color:black;
        }

        .tool{
            position:relative;
            display: inline-block;
        }

        .tool .tooltiptext{

            visibility: hidden;
            width: 120px;
            background-color: #333333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 3px 0;
            
            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            bottom: 75%;
            left: 50%;
            margin-left: -60px;
    
            /* Fade in tooltip - takes 1 second to go from 0% to 100% opac: */
            opacity: 0;
            transition: opacity 1s;
        }
        
        .tool:hover .tooltiptext{
            visibility: visible;
            opacity: 1;
        }

    </style>
</head>

<body> <!--style="background-image: url('assets/img/852.jpg');background-repeat: no-repeat;
    background-size: cover;">-->
    
    <div>
        <nav class="navbar navbar-default hidden-xs navigation-clean" style="background-color:#194d33;padding-top:15px;">
            <div class="container">
                <div class="navbar-header"><a class="navbar-brand navbar-link" style="color:#e6e6e6;font-size:26px;" href="login.php"><i class="icon ion-ios-navigate" style="color:tomato"></i> Beautiful Bangladesh</a>
                </div>

                <div class="collapse navbar-collapse" id="navcol-1">
              
                    
                    <ul class="nav navbar-nav hidden-xs hidden-sm navbar-right">

                        <li role="presentation" style="padding-right:100px;padding-left:0px;margin-left:0px;">
                        <div>
                            <form action="searchlist.php" method="POST">
                                <input type="text" name="searchbox" value="">
                                <button class="btn btn-primary" type="submit" name="search" style="padding:4px;">Search</button>
                            </form>
                        </div>
                        </li>

                        <li class="tool" role="presentation" style="padding-right:10px;">
                            <a href="index.php">
                                <img  src="assets/img/timeline.png">
                                <span class="tooltiptext">Home</span>
                            </a>
                        </li>
                        
                        <li class="tool" role="presentation" style="padding-right:10px;">
                            <a href="profile.php?username=<?php echo LOG::isLoggedIn();?>">
                                <img src="assets/img/profile.png">
                                <span class="tooltiptext">Profile</span>
                            </a>
                        </li>
                        
                        <li class="tool" role="presentation" style="padding-right:10px;">
                            <a href="my-messages.php">
                                <img src="assets/img/message.png">
                                <span class="tooltiptext">Message</span>
                            </a>
                        </li>
                        
                        <li class="tool" class="dropdown" style="padding-right:10px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="label label-pill label-danger count" style="border-radius:10px;">
                                
                                </span>
                            <img src="assets/img/Notify.png">
                            <span class="tooltiptext">Notification</span>
                            </a>
                            <ul class="dropdown-menu"></ul>
                        </li>
                        
                        <li class="tool" role="presentation" style="padding-right:10px;">
                            <a href="selection.php">
                                <img src="assets/img/question.png">
                                <span class="tooltiptext">Question</span>
                            </a>
                        </li>
                        
                        <li class="tool" role="presentation" style="padding-right:10px;">
                            <a href="logout.php">
                                <img src="assets/img/log.png">
                                <span class="tooltiptext">Logout</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>