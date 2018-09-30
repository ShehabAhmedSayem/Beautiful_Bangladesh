<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');

if(isset($_POST['search'])){

    $toSearch = explode (" ", $_POST['searchbox']);

    if(count($toSearch) == 1){
        $toSearch = str_split($toSearch[0],2);
    }

    $paramsarray = array(':username'=>'%'.$_POST['searchbox'].'%');
    $whereclause = "";
    
    for($i = 0; $i < count($toSearch); $i++){
        $whereclause .= " OR username LIKE :u$i ";
        $paramsarray[":u$i"] = $toSearch[$i]; 
    }

    $users = DB::query('SELECT users.full_name FROM users WHERE users.username LIKE :username'.$whereclause.'',$paramsarray);

    echo '<div style="margin-left:100px;"><h1>SEARCH LIST</h1><hr>';

    for($i=0; $i<count($users); $i++){
    	
    	$username = DB::query('SELECT username from users WHERE full_name=:fullname',
			array(':fullname'=>$users[$i]['full_name']))[0]['username'];

 	    echo '<a href="profile.php?username='.$username.'">'.$users[$i]['full_name'].'</a></br><hr>';
	}
	echo '</div>';
}


include('footer.php'); ?>
