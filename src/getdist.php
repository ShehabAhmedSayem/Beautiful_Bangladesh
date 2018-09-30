<?php
include('./classes/DB.php');
include('./classes/LOG.php');

if(!empty($_POST["div_name"])){
	$div = $_POST["div_name"];
	$results = DB::query('SELECT * FROM district WHERE div_name =:div_name',array(':div_name'=>$div));

	foreach($results as $dist){
?>
	<option value="<?php echo $dist["dist_name"]?>"><?php echo $dist["dist_name"]?></option>
			
<?php
	}
}

?>




<div class="division">
		<label>Division</label>
		<select name="division" onchange="getName(this.value);">
			<option value="">Select division</option>
			
			<?php
				$results = DB::query('SELECT DISTINCT div_name FROM district',array());
				
				foreach($results as $div){

			?>
			
			<option value="<?php echo $div["div_name"]?>"><?php echo $div["div_name"]?></option>
			
			<?php
				}
			?>

		</select>
	</div>

	<div class="district">
		<label>District</label>
		<select name="district" id="dist">
			<option value="">Select district</option>
			<option value=""></option>
		</select>
	</div>

	<div class="location">
		<label>Location</label>
		<select name="location" id="loc">
			<option value="">Select location</option>
			<option value=""></option>
		</select>
	</div>







<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous">
</script>

<script>
	function getName(val){
		//ajax funtion
		$.ajax({
			type: "POST",
			url: "getdist.php",
			data: "div_name="+val,
			success function(data){
				$("#dist").html(data);
			}
		});
	}
</script>