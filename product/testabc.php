<?php
session_start();
?>

<html>

<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        .text-large{
            font-size: 120%;
        }
    </style>
	
	    <meta charset="utf-8">


</head>

<body>
<form method="post" name="quizform" action="#" enctype="multipart/form-data" >
    <div class="container mt-3">
        
        <div class="row">
            <div class="col">
                <div>
                    <div class="caraousel-inner">
					
<?php 

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

//utf8mb4
	//header('Content-Type: text/html;charset=utf-8'); //<=---- Add here
		
	for($i = 0; $i < sizeof($quiz_string_questions); $i++) {
		
		echo $quiz_string_questions[$i].' <<>>'."<BR>" ;
		
		$quiz_answers = $quiz_string_answers[$i];
		
		$question_id = str_pad($shreni_enrolled,3,'0',STR_PAD_LEFT).'-'.str_pad($quiz_string_questions[$i],3,'0',STR_PAD_LEFT);
		
		
		$sql = "SELECT * FROM `question` where 1 and question_id = '$question_id' ";
		$quiz_questions = $quiz_string_questions[$i];
//echo $sql. "<BR>";
		$result = mysqli_query($conn, $sql);
		//mysqli_query($conn, "SET NAMES 'utf8'");
		$row	= mysqli_fetch_assoc($result);
		$correct_answer	= $row['correct_answer'];
		$question_name	= utf8_decode($row['question_name']);
		echo utf8_decode($row['testq']);
//echo utf8_decode($row['question_name']);
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
		
		//echo $question_name . ' ' . $user_answer . ' ' . $system_answer."<BR>";
		//$showval = $question_name . ' ' . $user_answer . ' ' . $system_answer;
		
?>
		<div class="row mt-12 card-body">
		<input type="text" readonly class="btn btn-primary float-right" value="<?= utf8_decode($question_name).' ABC ';;?>"><br>
		</div>
			
<?php		
		
		
		
	}
	

?>
		
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>


</form>

</body>

</html>