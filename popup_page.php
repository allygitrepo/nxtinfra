<?php

session_start();

		$sql="Select * from alert_msg";
		$query = mysqli_query($con, $sql);
        $msg = mysqli_fetch_array($query);
		$alert_msg = $msg['alert_msg'];
		
?>
<head>
<meta charset="UTF-8">

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->

 <link rel="stylesheet" href="<?php echo $baseurl . "dist/css/AdminLTE.min.css" ?>">
 

<?php 	
		include("footer.php");	
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#myModal").modal('show');
	});
</script>
</head>
<body>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span style="color:red;">Close &times; </span></button>
        <h4 class="modal-title">Flash Message !</h4>
      </div>
	  <br><br>
      <div class="modal-body">
        <div class="alert alert-success">
  <strong style="font-size:14px:"> <?php echo $alert_msg; ?> </strong>
</div>
      </div>
     
    </div>

  </div>
</div>
<!-- Modal -->

</body>
</html>   

