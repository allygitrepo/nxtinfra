<?php
include("../header.php");
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Products
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
				
              <h3 class="box-title">List of Products</h3>
			<?php
				$targetpage = "product.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
			?> 
				<span class="pull-right"><a href="product.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create Product</a></span>
				<!-- Ruchi started-->
				<span class="pull-right">
					<a href="product_export.php?sub=list" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				<!-- Ruchi ended-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
	
    <table id="prtable123" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Question</th>
			<th>User Answer</th>
			<th>system Answer</th>
			
		</tr>
	</thead>
<tbody>
<?php
	
	header('Content-Type: text/html;charset=utf-8'); //<=---- Add here
	
	require 'conn.php';
	$student_id = 19195;
	$student_id = 16760;
	$sql = "SELECT * FROM `student` where student_id = '$student_id'";
echo $sql. "<BR>";	
	$result = mysqli_query($conn,$sql);
    $row	= mysqli_fetch_assoc($result);
    $shreni_enrolled		= $row['shreni_enrolled'];
	
	
	$sql = "SELECT * FROM `quiz_attempt` where student_id = '$student_id' ";
//echo $sql. "<BR>";	
	$result = mysqli_query($conn,$sql);
    $row	= mysqli_fetch_assoc($result);
    $quiz_string_answers	= explode(',',$row['quiz_string_answers']);
	$quiz_string_questions	= explode(',',$row['quiz_string_questions']);
	
	for($i = 0; $i < sizeof($quiz_string_questions); $i++) {
		
	//	echo $quiz_string_questions[$i].' <<>>'."<BR>" ;
		
		$quiz_answers = $quiz_string_answers[$i];
		
		$question_id = str_pad($shreni_enrolled,3,'0',STR_PAD_LEFT).'-'.str_pad($quiz_string_questions[$i],3,'0',STR_PAD_LEFT);
		$sql = "SELECT * FROM `question` where 1 and question_id = '$question_id' ";
		$quiz_questions = $quiz_string_questions[$i];
//echo $sql. "<BR>";
		$result = mysqli_query($conn,$sql);
		$row	= mysqli_fetch_assoc($result);
		$correct_answer	= $row['correct_answer'];
	echo	$question_name	= utf8_decode($row['question_name']);
		$question_optiona	= $row['question_optiona'];
		$question_optionb	= $row['question_optionb'];
		$question_optionc	= $row['question_optionc'];
		$question_optiond	= $row['question_optiond'];
	
		if($quiz_answers==1){
			$user_answer = $question_optiona;
		}	
		else if($quiz_answers==2){
			$user_answer = $question_optionb;
		}
		else if($quiz_answers==3){
			$user_answer = $question_optionc;
		}
		else if($quiz_answers==4){
			$user_answer = $question_optiond;
		}
		
		if($correct_answer==1){
			$system_answer = $question_optiona;
		}	
		else if($correct_answer==2){
			$system_answer = $question_optionb;
		}
		else if($correct_answer==3){
			$system_answer = $question_optionc;
		}
		else if($correct_answer==4){
			$system_answer = $question_optiond;
		}
	
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "product.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="20%"><?php echo $question_name;?></td>
		<td width="20"><?php echo $user_answer;?></td>
		<td width="20%"><?php echo $system_answer;?></td>
		
    </tr>
	</a>
	
	<?php }?>
	
</tbody> 
</table>

<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  echo $paginate;
?>


	</div>
    </div>
</div>	
</section>
  </div>

  <!-- /.content-wrapper -->

</div>


<?php 	
		include("../footer.php");	
?>
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

</body>
</html>
