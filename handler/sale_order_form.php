<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$id=uniqid();

				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),
				'order_no'=>$_REQUEST['order_no'],'customer'=>$_REQUEST['customer'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'grandtotal'=>$_REQUEST['grandtotal'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'] );

				$insertedId=$utilObj->insertRecord('sale_order',$arrValue);

				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),
					'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i]);
					
					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('sale_order_details', $arrValue2);
		        }

				$cntd=$_REQUEST['cntd'];
				// echo $cntd;

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('sale_order_other_details', $arrValued);
				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				 
				$id=$_REQUEST['id'];
				// $_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				// $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				// if($value>0)
				// {
				// 	echo $Msg = "Concurrency Error Occured"; 
				// 	break;
				// }   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'order_no'=>$_REQUEST['order_no'],'customer'=>$_REQUEST['customer'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'grandtotal'=>$_REQUEST['grandtotal'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'] );
				// print_r($arrValue);

				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('sale_order', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_order_details', $strWhere);

				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_order_other_details', $strWhere);
				
				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),
					'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );
					//print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('sale_order_details', $arrValue2);
					//print_r($arrValue2);

				}

				$cntd=$_REQUEST['cntd'];
				// echo $cntd;

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('sale_order_other_details', $arrValued);
				}

				if($Updaterec) 
				// echo $Msg='Record has been Updated Sucessfully! ';		
			break;	

	
		case"delete":
		
			$pids=explode(",",$_REQUEST['id']);
			
			foreach($pids as $pid)
			{
				echo $strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('sale_order', $strWhere);
				
				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('sale_order_details', $strWhere);
			}
			
			echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
