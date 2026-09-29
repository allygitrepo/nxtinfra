<?php

session_start();
include("../header.php");
$modulePath = "budget/monthly_budget_chart.php?sub=list";

include("../dbcon.php");

if($_POST['comp_id']){
	$comp_id = $_POST['comp_id'];
}	


if($_GET['sub']=='list'){
	$comp_id = '';
}	
?>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

		<section class="content-header">
		  <h1>
        Monthly Budget Chart 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Monthly Budget Chart</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="monthly_budget_chart.php?sub=pdf" method="post">
                      
							<div class="form-group">
								
								<label class="col-lg-1 control-label">Company&nbsp;</label>
								<div class="col-md-5">
								<select class="form-control" name="comp_id" id="company_ID" autocomplete="off" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 and comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
								</div>
							
                                		
								<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								<a href="monthly_budget_chart.php?sub=list" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");


if($_POST['comp_id']){
	$comp_id = $_POST['comp_id'];
	$sqla = " and comp_code in (select comp_code from company where comp_id in ($comp_id) ) ";
}
else {
	$sqla = " and comp_code in (select comp_code from company where comp_id in ($comid) ) ";
}	
$sql = " SELECT yyyy_mm,round(sum(month_budget),0) as month_budget , round(sum(used_budget),0) as used_budget FROM `dashboard_budget_data` where 1 $sqla group BY `yyyy_mm` ";
$result = mysqli_query($con, $sql);
echo mysqli_error($con);
   
	$result = mysqli_query($con, $sql);
?>
<html>

 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <button id="change-chart">Change to Classic</button>
    <br><br>
    <div id="chart_div" style="width: 1170px; height: 540px;"></div>
   

<script>  
      google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {

        var button = document.getElementById('change-chart');
        var chartDiv = document.getElementById('chart_div');

        var data = google.visualization.arrayToDataTable([
			['Monthly', 'Monthly Budget', 'Used Budget'],
           <?php 
				while($row = mysqli_fetch_assoc($result)){
					echo "['".$row["yyyy_mm"]."', ".$row["month_budget"].", ".$row["used_budget"]."],";
				}
			?>

/* ['Galaxy', 'Distance', 'Brightness'],
          ['Canis Major Dwarf', 8000, 23.3],
          ['Sagittarius Dwarf', 24000, 4.5],
          ['Ursa Major II Dwarf', 30000, 14.3],
          ['Lg. Magellanic Cloud', 50000, 0.9],
          ['Bootes I', 60000, 13.1] */
        ]);

        var materialOptions = {
          width: 1090,
          chart: {
            title: 'Monthly Budget ',
            subtitle: 'Monthly Budget'
          },
          series: {
            //0: { axis: 'distance' }, // Bind series 0 to an axis named 'distance'.
            //1: { axis: 'brightness' } // Bind series 1 to an axis named 'brightness'.
          },
          axes: {
            y: {
              distance: {label: ''}, // Left y-axis.
              brightness: {side: 'right', label: 'apparent magnitude'} // Right y-axis.
            }
          }
        };

        var classicOptions = {
          width: 1090,
          series: {
            0: {targetAxisIndex: 0},
            1: {targetAxisIndex: 1}
          },
          title: 'Monthly Budget ',
          vAxes: {
            // Adds titles to each axis.
            0: {title: ''}//,
            //1: {title: 'apparent magnitude'}
          }
        };

        function drawMaterialChart() {
          var materialChart = new google.charts.Bar(chartDiv);
          materialChart.draw(data, google.charts.Bar.convertOptions(materialOptions));
          button.innerText = 'Change to Classic';
          button.onclick = drawClassicChart;
        }

        function drawClassicChart() {
          var classicChart = new google.visualization.ColumnChart(chartDiv);
          classicChart.draw(data, classicOptions);
          button.innerText = 'Change to Material';
          button.onclick = drawMaterialChart;
        }

        drawMaterialChart();
    };
</script>
	
		
			</div>
		</div>
		

</div>	

<?php
	include("../footer.php");	
	
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

</script>


