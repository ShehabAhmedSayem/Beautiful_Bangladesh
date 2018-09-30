<?php
include('./classes/DB.php');

if(!empty($_POST["name"])){
	$name = $_POST["name"];

	$results = DB::query('SELECT * FROM location WHERE dist_name=:name',array(':name'=>$name));

    foreach ($results as $location) {
    ?>
      <option value="<?php echo $location['name']; ?>"> <?php echo $location['name']; ?> </option>
   	<?php
    }
}

?>