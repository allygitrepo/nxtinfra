<?php session_start();
	include('../dbcon.php');
	include('../baseurl.php');
		
?>

<?php
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
//echo $id;		
	if($id =='E'){	
		$value ='<div class="form-group">
							<div class="col-sm-3">
							</div>

							<div class="col-sm-3" style="padding-top: 6px;">
								<label class="control-label">&nbsp;Document </label><br>
								<input type="radio" class="minimal"  name="viewm" id="viewm" value="V">View &nbsp;&nbsp;
                            	<input type="radio" class="minimal"  name="viewm" id="viewm" value="L" >Mail &nbsp;&nbsp;
							</div>
					</div>';
	}
       
	   echo $value;

	
?>


	