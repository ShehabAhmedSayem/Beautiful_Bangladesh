<?php
//fetch.php;

if(isset($_POST["view"]))
{
 include(".\classes\DB.php");
 include(".\classes\LOG.php");

 $username = LOG::isLoggedIn();

 if($_POST["view"] != '')
 {
    DB::query('UPDATE notifications SET seen=1 WHERE receiver=:username AND seen=0',array(':username'=>$username));
 }

 $result = DB::query('SELECT * FROM notifications WHERE receiver=:username ORDER BY id DESC LIMIT 5',array(':username'=>$username));
 $output = '';
 
 if($result){

  foreach ($result as $n) {

    $postid = $n['post_id'];

    $output .= "<li>";
    
      if($n['type'] == 1){

        $output.= "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." mentioned you in a post!";

      }
      else if($n['type'] == 2){

        $output.= "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." liked your post!";
      }
      else if($n['type'] == 3){

        $output.= "<a href='postshow.php?postid=".$postid."&quesid=0'>".$n['sender']." commented on your post!";
      }
      else if($n['type'] == 4){

        $output.= "<a href='postshow.php?postid=0&quesid=".$postid."'>".$n['sender']." has posted a question!";
      }
      else if($n['type'] == 5){

        $output.= "<a href='postshow.php?postid=0&quesid=".$postid."'>".$n['sender']." answered to your question!";
      }

    $output.="</a>
   </li>
   <li class='divider'></li>";
  }
 }
 else
 {
  $output .= '<li><a href="#" class="text-bold text-italic">No Notification Found</a></li>';
 }

 $output.="</li><a href='notify.php' style='text-decoration:none;padding-left:40%;'>See All</a></li>";
 
 $count = 0;

 $result_1 = DB::query('SELECT * FROM notifications WHERE receiver=:username AND seen=0',array(':username'=>$username));
 
 foreach($result_1 as $r) $count++;

 $data = array(
  'notification'   => $output,
  'unseen_notification' => $count
 );
 echo json_encode($data);
}
?>