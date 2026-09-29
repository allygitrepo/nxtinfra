<?php

	$csv_file=$_FILES['updfile']['tmp_name'];
				if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						for ($c=0; $c < 13; $c++){
							fgetcsv($handle);
						}
						
						$prev_dated = '';
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

								
								$srno			 		= $col[0];
								//$transaction_date 		= date('Y-m-d', strtotime($col[1]));
								$transaction_date 		= $col[1];
								$value_date  			= $col[2];
								$cheque_ref_no		    = $col[3];
								$transaction_remarks    = $col[4];
								$withdrawal_amount		= str_replace(',','',$col[5]);
								$dr_cr					= $col[6];
								$balance_amount			= $col[7];
								$branch					= $col[8];
								
								$withdrawal_amount		= str_replace(' ','',$withdrawal_amount);
								
								if(substr($transaction_remarks,0,4)!= 'NEFT' && substr($transaction_remarks,0,4)!= 'RTGS'){
									continue;
								}	
								
								$matched_flag = '';
								
								$transaction_posted_date= str_replace('/','-',$transaction_date);
								$transaction_date		= str_replace('/','-',$transaction_date);
								
								$myArray = explode('/', $transaction_remarks);
								$party_name_match = $myArray['3'];
							
					echo $party_name_match. ' <<>> ' . $value_date. ' ' . $transaction_date. ' ' . $withdrawal_amount. "<BR>";
							$sql = " INSERT INTO bank_transactions( `file_name`, `company_id`, `srno`, `trans_id`, `value_date`, `transaction_date`, `transaction_posted_date`, `cheque_ref_no`, `transaction_remarks`, `withdrawal_amount`, `deposit_amount`, `balance_amount`) 
									VALUES ( '$updfile', '$company_id', '$srno', '$trans_id', '$value_date', '$transaction_date', '$transaction_posted_date', 
									'$cheque_ref_no', '$transaction_remarks', '$withdrawal_amount', '$deposits_amount', '$balance_amount' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);
							$bank_transactions_id = mysqli_insert_id($con);
							
							
						//	exit();
							//SELECT trim(utr_no), total_amount_paid FROM `payment_header` where REPLACE(utr_no,' ','') like REPLACE('INF/NEFT/ICICN42025011058157586/SCBL0036057/Professional Fees/ArvindMahajan',' ','') and total_amount_paid >= '90000';
						
							$sql = " SELECT * FROM `payment_header` 
										where 1 and company_id = '$company_id' 
												and total_amount_paid = '$withdrawal_amount' ";
												//and REPLACE(utr_no,' ','') like REPLACE('$transaction_remarks',' ','') " ;					
echo $sql. "<BR>";
							$q2 	= mysqli_query($con, $sql);
							$rwaffect = mysqli_affected_rows($con);
echo	$rwaffect."<BR>";
							echo mysqli_error($con);
							while($r2 = mysqli_fetch_array($q2)){
								
								$py_id	 			= $r2['id'];	
								$paid_to 			= $r2['paid_to'];
								$st_flag 			= $r2['st_flag'];
								$cash_bank_name		= $r2['cash_bank_name'];
								$paid_date			= date('d-m-Y', strtotime($r2['paid_date']));
								$utr_no				= $r2['utr_no'];
								$total_amount_paid	= $r2['total_amount_paid'];
								$cheque_no			= $r2['cheque_no'];
								
								$matched_flag = '';
								
								if($st_flag=='S' || $st_flag=='D' || $st_flag=='R' || $st_flag=='C'){
									$sql = " SELECT * FROM `sma_party_mst` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rwaffect = mysqli_affected_rows($con);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['party_name'];
								}
								else if($st_flag=='T'){
									$sql = " SELECT * FROM `sma_user` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rwaffect = mysqli_affected_rows($con);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['username'];
								}
								
							//echo	$st_flag. ' ' . $rwaffect.' '. $utr_no. ' '. $party_name."<BR>";
								
								//echo $transaction_posted_date . ' == ' .$paid_date .' '. $party_name_match. ' >><< ' . $party_name. "<BR>";
								if( substr(str_replace(' ','',trim(strtolower($party_name_match))),0,9) == substr(str_replace(' ','',trim(strtolower($party_name) )),0,9) 
									&&
									$transaction_posted_date == $paid_date ){
									echo	'<== '.$rwaffect.' '. $utr_no. ' '. $party_name.' ' . $paid_date."<BR>";
									
									$sql = "UPDATE bank_transactions SET match_id = '$py_id', matched_date = now() 
											WHERE id = '$bank_transactions_id' ";
									mysqli_query($con, $sql);
								echo $sql. "<BR>";	
									$sql = "UPDATE payment_header SET match_id = '$bank_transactions_id', matched_date = now() 
											WHERE id = '$py_id' "; // utr_no = '$transaction_remarks'
									mysqli_query($con, $sql);
									
								echo $sql. "<BR>";
									$matched_flag = 'Y';
									
								}
						}
						
						if(empty($matched_flag)){
							
							$party_name_match = substr(str_replace(' ','',trim(strtolower($party_name_match))),0,9);
							$sql = " SELECT * FROM `sma_party_mst` where 1 and SUBSTRING(LOWER(REPLACE(party_name,' ','')),1,9)  like '%$party_name_match%' ";
						echo $sql. "<BR>";	
							$qry 	= mysqli_query($con, $sql);
							$rwaffect = mysqli_affected_rows($con);
							$rw2 = mysqli_fetch_array($qry);
							$party_id 			= $rw2['id'];
							$party_name 			= $rw2['party_name'];
							$transaction_posted_date			= date('Y-m-d', strtotime($transaction_posted_date));
							$sql = " SELECT sum(total_amount_paid) as total_amount_paid FROM `payment_header` 
										where 1 and company_id = '$company_id' 
												and paid_date = '$transaction_posted_date'
												and paid_to = '$party_id' ";
									//and total_amount_paid = '$withdrawal_amount' 
						echo $sql. "<BR>";			
							$qry 	= mysqli_query($con, $sql);
							$rwaffect = mysqli_affected_rows($con);
							$rw2 = mysqli_fetch_array($qry);
							$total_amount_paid 			= $rw2['total_amount_paid'];
							
							if($total_amount_paid == $withdrawal_amount ){
								echo "Matched Records...<BR>";
							}	
												
						}		

				}
						
								
			}

		exit('######123');
		echo "<script>alert('Bank UTR Imported...');window.location.href='import_utr_bank.php?sub=list;</script>";
				
?>			