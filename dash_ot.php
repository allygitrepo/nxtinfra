<?php							
	
	include('dbcon.php');
	
	$doc_type	= 'AP';
	
?>		

<?php $i = $menu_id[2]; 

//if ( $dashboard[$i] =='Y' ){
if ( $dashboard =='Y' ){ ?>

	<body onload="getcountP(); getpendingAP();">
 
            <!-- /.box-header -->
            <div class="box-body">
		
		<!-- /.TASK Dashboard -->		
		<?php include "dash_task.php"; ?>	
		
	<div class="form-group">
		<div class="col-sm-3" style="float:left; margin-top: 10px; " id="myp">
			 <!--<a href="#" class="btn btn-lg btn-primary" data-toggle="tab" onclick="getpendingAP()" ><?php echo $pcnt; ?><br> My Pending</a>-->
			 <a href="#" class="btn btn-lg btn-warning123" style="background-color:#6E96D3;color:white;" data-toggle="tab" onclick="getcountP();getpendingAP();" > My Pending</a>
			 
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; ">
			 <a href="#" class="btn btn-lg btn-info" data-toggle="tab" onclick="getcountPA()" > All Pending</a>
		</div>
		
<!--		<div class="col-sm-8" style="float:left; margin-top: 10px; ">
			 <span>.</span>
			 <a href="#" class="btn btn-lg " data-toggle="tab" > &nbsp;</a>
		</div>
		
	</div>
	
	<div class="form-group">	-->
		<div class="col-sm-3" style="float:left; margin-top: 10px; ">
			 <a href="#" class="btn btn-lg btn-success" data-toggle="tab" onclick="getcountA()" >&nbsp; Approved &nbsp;</a>
		</div>
		<div class="col-sm-3" style="float:left; margin-top: 10px; ">
			 <a href="#" class="btn btn-lg btn-danger" data-toggle="tab" onclick="getcountR()" >&nbsp;&nbsp; Rejected &nbsp;</a>
		</div>
		
		
	<!--	<div class="col-sm-2" style="float:left; margin-top: 10px; margin-left: 1px;">
			<h3> &nbsp; </h3>
		</div>
	-->	
	</div>	
		
		
			
	</div>
</body>
	
<?php } ?>	