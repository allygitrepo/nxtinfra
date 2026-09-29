<?php

include("../header.php");
$modulePath = "budget/adjust_budget.php?sub=list";


	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Recalculate Used Budget
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
               			<?php 
				//echo $_POST['project'].'<> ';
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] ){
					$_SESSION['project_a'] = $_POST['project'];
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					
				}
				
				if(empty($_POST['budget_head']) ){
					$_SESSION['budget_head_a'] = 'All';
				}
				
				if( empty($_POST['budget_name'])){
					$_SESSION['budget_name_a'] = 'All';
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a']){
					$project_v = $_SESSION['project_a'];
					$budget_name_v = $_SESSION['budget_name_a'];
					$budget_head_v = $_SESSION['budget_head_a'];
					
				}
	
				if (!empty($_GET['reset']) || !empty($_SESSION['reset']) ) {
					$_SESSION['project_a'] = '';
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
				
				$comid  = $_SESSION['comid'];
				
	//	echo $project_v. ' >< '. $account_year_v;
			?>
					<form class="form-horizontal" action="adjust_used_budget.php?sub=pdf" method="post">
                      
						<div class="form-group">
							
							<div class="col-sm-3">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="project" required >
                             		<option value=""> Select </option>
									<option value="All" <?php echo ($project_v == 'All')?'selected="selected"':'';?> > All </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Budget Name</label>
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
									<option value="All" <?php echo ($budget_name_v == 'All')?'selected="selected"':'';?> > All </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>

							
							<div class="col-md-4">
									<label class="control-label">Budget Head</label>
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
									<option value="All" <?php echo ($budget_head_v == 'All')?'selected="selected"':'';?> > All </option>
									
										<?php $sql = "select * from sma_budget_category order by category ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_head_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['category'];?></option>
										<?php } ?>
									</select>
							</div>
						</div>
												
						<div class="form-group">
							<div class="col-xs-3">
							</div>
							
							<div class="col-xs-1">
                                		
								<input class="btn btn-success" target="_blank" type="submit" onclick="reprocess()" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
								
							
							</div>
							
							<div class="col-xs-1">
                                		
								<a href="<?php echo $baseurl . "dashboard.php"?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
							
							</div>

						</div>
						
				</form>

			</div>
			
		</div>	
    <div class="box">
	
	<span id ="reprocess">
	
	</span>
    
	</div>
    </div>
</div>	

    <?php }?>

<?php 	
		include("../footer.php");	

		
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}

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


	function getlocation(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getbudget(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}

	function getbalbugdget(){
		var total_budget =  document.getElementById('total_budget').value;
		//var used_budget =  document.getElementById('used_budget').value;
		
		var balance_budget = total_budget - used_budget;
		
		//$('#balance_budget').attr('readonly', true);
		document.getElementById('balance_budget').value=balance_budget;
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Budget should be less then total budget...");
			//var used_budget = 0;
			document.getElementById('used_budget').value=0;
			
		}
		
	}	

	function reprocess(){
		
		$('#reprocess').html('Please Wait...');
		
	}
	
</script>


</body>
</html>
