<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				
				// --------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("bank_transfer","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$pur_ino_code;
				$pino;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
			
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$pur_ino_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$pino = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$pur_ino_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$pino = $result['pono']+1;
					}
				}
				else {
		
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$pur_ino_code = $prefix_label."/".$year_code."/".($formattedPono);
						$pino = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
		
						$pur_ino_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$pino = $result['pono']+1;
					}
				}

				// --------------------------------------------------------------------------

				$id=uniqid();

				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue=array('id'=>$id1,'parent_id'=>$id,'user'=>$_SESSION['Ck_User_id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'recordnumber'=>$pino,'voucher_type'=>$_REQUEST['voucher_type'],'voucher_code'=>$pur_ino_code,'total_of_debitamt'=>$_REQUEST['total_of_debitamt'],'total_of_creditamt'=>$_REQUEST['total_of_creditamt'],'record'=>$_REQUEST['record_array'][$i],'account'=>$_REQUEST['account_array'][$i],'debit_amount'=>$_REQUEST['debit_amount_array'][$i],'credit_amount'=>$_REQUEST['credit_amount_array'][$i]);

					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('bank_transfer', $arrValue);
				 
		        }

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				// ---------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("bank_transfer","parent_id='".$_REQUEST['parent_id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$$pur_ino_code;
				$pino;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 3) {
						$year_code = date("y")."-".(date("y")+1);
					} else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);

							$pur_ino_code = $prefix_label."/".($formattedPono)."/".$year_code;
							$pino = $formattedPono;
						}
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$pur_ino_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$pino = $result['pono']+1;
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
							
							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);

							$pur_ino_code = $prefix_label."/".$year_code."/".($formattedPono);
							$pino = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$pur_ino_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$pino = $result['pono']+1;
						}
					}
				}
				else {
				
					$pur_ino_code = $mate1['voucher_code'];
					$pino = $mate1['recordnumber'];
				}

				// ---------------------------------------------------------------------------------

				$id=$_REQUEST['parent_id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
					
					$strWhere="parent_id='".$_REQUEST['parent_id']."' ";
				    $Deleterec=$utilObj->deleteRecord('bank_transfer', $strWhere);
					
					$cnt1=$_REQUEST['cnt'];
		
					for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue=array('id'=>$id1,'parent_id'=>$id,'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'recordnumber'=>$pino,'voucher_code'=>$pur_ino_code,'total_of_debitamt'=>$_REQUEST['total_of_debitamt'],'total_of_creditamt'=>$_REQUEST['total_of_creditamt'],'record'=>$_REQUEST['record_array'][$i],'account'=>$_REQUEST['account_array'][$i],'debit_amount'=>$_REQUEST['debit_amount_array'][$i],'credit_amount'=>$_REQUEST['credit_amount_array'][$i],);

					print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('bank_transfer', $arrValue);
				 
		        }	
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 				
			break;	

	
			case"delete":
			
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('bank_transfer', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully!';
				
			break;
			
		}	
	}
?>
