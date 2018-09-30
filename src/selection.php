<?php
include('./classes/LOG.php');
include('./classes/DB.php');
include('header.php');

?>
<head>
  <script
  src="http://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
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

.sel{
    
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

</style>
</head>

<div style="text-align:center">
<h1>Ask Any Info</h1>

	<h3>Select location you want to know about:</h3>
  
  <form action="q-a.php" method="POST">
  <div class="district">
    <select class="sel" name="district" required onchange = "getDist(this.value);">
      <option value="">Select District</option>
      <!--populate from database-->
      <?php
      
      $results = DB::query('SELECT * FROM district',array());

      foreach ($results as $district) {
      ?>
        <option value="<?php echo $district['dist_name']; ?>"> <?php echo $district['dist_name']; ?> </option>
      <?php
      }
      ?>
    </select>
  </div>
  <br/>

  <div class="location">
    <select class="sel" name="location" id="loc_list" required>
      <option value="">Select Location</option>
      <!--populate from database-->
      <option value=""></option>
    </select>
  </div>
  <br/>
  <button class="button2" type="submit" name="ok">SUBMIT</button>
  </form>

</div>


  <script type="text/javascript">
  
  function getDist(val){
  
    $.ajax({

      type: "POST",
      url: "getdata.php",
      data: "name="+val,
      success: function(data){
          $("#loc_list").html(data);
      }

    });
}

function getloc(val){
    
    window.location = 'q-a.php?loc_name='+val;
    return 0;
}

</script>

<?php include('footer.php'); ?>