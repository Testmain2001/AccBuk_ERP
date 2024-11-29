<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				
				// -------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("dispatch","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$dis_code;
				$dsno;

				if (date("m") > 4) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$dis_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$dsno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$dis_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$dsno = $result['pono']+1;
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
			
						$dis_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$dsno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$dis_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$dsno = $result['pono']+1;
					}
				}

				// -------------------------------------------------------------------

				$id=uniqid();
				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$dsno,'dis_code'=>$dis_code,'voucher_type'=>$_REQUEST['voucher_type'],'customer'=>$_REQUEST['customer'],'location'=>$_REQUEST['location'],'sale_invoice_no'=>$_REQUEST['sale_invoice_no'],'total_quantity'=>$_REQUEST['total_quantity'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
				//print_r($arrValue);
				 $insertedId=$utilObj->insertRecord('dispatch',$arrValue);	

				 $cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 // print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i]);
					//print_r($arrValue2);
					 $insertedId=$utilObj->insertRecord('dispatch_details', $arrValue2);
				 
		        }	
					if($insertedId)
					echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				// -------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("dispatch","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$dis_code;
				$dsno;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 4) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
						
							$dis_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$dsno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$dis_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$dsno = $result['pono']+1;
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
				
							$dis_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$dsno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$dis_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$dsno = $result['pono']+1;
						}
					}
				}
				else {
				
					$dis_code = $mate1['dis_code'];
					$dsno = $mate1['record_no'];
				}
				

				// -------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$dsno,'dis_code'=>$dis_code,'voucher_type'=>$_REQUEST['voucher_type'],'customer'=>$_REQUEST['customer'],'location'=>$_REQUEST['location'],'sale_invoice_no'=>$_REQUEST['sale_invoice_no'],'total_quantity'=>$_REQUEST['total_quantity'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
				print_r($arrValue);
					 $strWhere="id='".$_REQUEST['id']."'  ";
					$Updaterec=$utilObj->updateRecord('dispatch', $strWhere, $arrValue);
					
					$strWhere="parent_id='".$_REQUEST['id']."'";
				    $Deleterec=$utilObj->deleteRecord('dispatch_details', $strWhere);
					
					echo $cnt1=$_REQUEST['cnt'];
		
					for($i=0;$i<$cnt1;$i++)
					{
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						
						$id1=uniqid();
						
					 // print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
						
						$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i]);
						//print_r($arrValue2);
						 $insertedId=$utilObj->insertRecord('dispatch_details', $arrValue2);
					 
					}	
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 				
			break;	

	
		case"delete":
		
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('dispatch', $strWhere);
				
				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('dispatch_details', $strWhere);
			}
			
			echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
