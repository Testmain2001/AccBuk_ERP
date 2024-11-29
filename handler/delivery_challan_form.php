<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$id=$_REQUEST['common_id'];
				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),
				'challan_no'=>$_REQUEST['challan_no'],'customer'=>$_REQUEST['customer'],'saleorder_no'=>$_REQUEST['saleorder_no'],'total_quantity'=>$_REQUEST['total_quantity'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])) );
				
				$insertedId=$utilObj->insertRecord('delivery_challan',$arrValue);	

				$cnt1=$_REQUEST['cnt'];

				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");
				foreach($sale_batch as $batch){

					$array1=array('id'=>uniqid(),'delivery_id'=>$id,'sale_invoice_no'=>$id,'ClientID'=>$batch['ClientID'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'],'purchase_batch'=>$batch['purchase_batch'] );
					$insertedId=$utilObj->insertRecord('sale_batch', $array1);
					
					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_sale_batch', $strWhere);

					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					if($totalstock == '0'){

						$arrValue = array('flag'=>'1');
						$strWhere="id='".$purchase['id']."'  ";
						$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);

					}
				}

				for($i=0;$i<$cnt1;$i++)
				{
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					// $totalstock = getstock($_REQUEST['product_array'][$i],$_REQUEST['unit_array'][$i], date('Y-m-d'), '', $_REQUEST['location']);
					
					// if($totalstock < $_REQUEST['qty_array'][$i] ) {

					// 	echo "Concurrency error occured";

					// 	$strWhere="id='".$id."' ";
					// 	$Deleterec=$utilObj->deleteRecord('delivery_challan', $strWhere);

					// 	$strWhere="delivery_id='".$id."' ";
					// 	$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere);
						
					// } else {
				 		// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
						if($_REQUEST['qty_array'][$i]>=0) {
							$id1=uniqid();
							$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i]);
							print_r($arrValue2);
							$insertedId=$utilObj->insertRecord('delivery_challan_details', $arrValue2);
						}
					// }
		        }	
				
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				 
				$id=$_REQUEST['id'];
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),
				'challan_no'=>$_REQUEST['challan_no'],'customer'=>$_REQUEST['customer'],'saleorder_no'=>$_REQUEST['saleorder_no'],'total_quantity'=>$_REQUEST['total_quantity'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])) );
				//print_r($arrValue);

				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('delivery_challan', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('delivery_challan_details', $strWhere);

				$strWhere1="delivery_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);
				
				$cnt1=$_REQUEST['cnt'];
		


				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");

				foreach($sale_batch as $batch){
					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					$delivery= $utilObj->getSingleRow("delivery_challan","id='".$id."'");

					
					$array1=array('id'=>uniqid(),'delivery_id'=>$id,'sale_invoice_no'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>$delivery['date'],'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited']);
					$insertedId=$utilObj->insertRecord('sale_batch', $array1);
					
					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_sale_batch', $strWhere);


					if($totalstock == '0'){
					$arrValue = array('flag'=>'1');
					$strWhere="id='".$purchase['id']."'  ";
					$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
					}
					
					
				}

				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";


					$totalstock = getstock($_REQUEST['product_array'][$i],$_REQUEST['unit_array'][$i], date('Y-m-d'), '', $_REQUEST['location']);
				
					if($totalstock < $_REQUEST['qty_array'][$i] ) {

						echo "Concurrency error occured";

						$strWhere="id='".$id."' ";
						$Deleterec=$utilObj->deleteRecord('delivery_challan', $strWhere);

						$strWhere="delivery_id='".$id."' ";
						$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere);


					} else {
					
						if($_REQUEST['qty_array'][$i]>=0){
							$id1=uniqid();
							$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i]);
							//print_r($arrValue2);
							$insertedId=$utilObj->insertRecord('delivery_challan_details', $arrValue2);
						}
					}
				}

				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! '; 				
			break;	

	
		case"delete":
		
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('delivery_challan', $strWhere);
				
				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('delivery_challan_details', $strWhere);

				$strWhere1="delivery_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);

				
			}
			
			echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
