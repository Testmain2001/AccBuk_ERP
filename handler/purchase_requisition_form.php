<?php
include '../config.php';
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$id=uniqid();

				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'requisition_by'=>$_REQUEST['requisition_by'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));

				$insertedId=$utilObj->insertRecord('purchase_requisition',$arrValue);	

				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rm_qty'=>$_REQUEST['qty_array'][$i]);
					$insertedId=$utilObj->insertRecord('purchase_requisition_details', $arrValue2);

					// $product=$utilObj->getSingleRow("purchase_requisition_details"," product='".$_REQUEST['product_array'][$i]."' ");
					// echo $rm_qty = $product['rm_qty']-$_REQUEST['qty_array'][$i]; 
					
					// $strWhere("product='".$_REQUEST['product_array'][$i]."'");
					// $arrValuep=array('rm_qty'=>$rm_qty );
					// $Updaterec=$utilObj->updateRecord('purchase_requisition_details', $strWhere, $arrValuep);
				 	// print_r($arrValue2);

				 
		        }	
					if($insertedId)
					// echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				 
				//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				}   
					
					$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'requisition_by'=>$_REQUEST['requisition_by'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
					$strWhere="id='".$_REQUEST['id']."'  ";
					$Updaterec=$utilObj->updateRecord('purchase_requisition', $strWhere, $arrValue);
					
					$strWhere="parent_id='".$_REQUEST['id']."' ";
				    $Deleterec=$utilObj->deleteRecord('purchase_requisition_details', $strWhere);
					
					$cnt1=$_REQUEST['cnt'];
		
					for($i=0;$i<$cnt1;$i++)
					{
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
						
						$id1=uniqid();
						
						 $arrValue2=array('id'=>$id1,'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i]);
						 $insertedId=$utilObj->insertRecord('purchase_requisition_details', $arrValue2);
					 //print_r($arrValue2);
					}
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":
		
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_requisition', $strWhere);
				
				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_requisition_details', $strWhere);
			}
			
			echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
