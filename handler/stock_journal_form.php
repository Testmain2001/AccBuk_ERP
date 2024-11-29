<?php 
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
		
		switch($_REQUEST['PTask'])	
		{
			case "Add":		
			
				$var=$_REQUEST['date'];
				$date = str_replace('/', '-', $var);
				$id=$_REQUEST['id'];
				$ad = $_REQUEST['ad'];

				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
				//var_dump($arrValue);die;
				//print_r($arrValue);
				$insertedId=$utilObj->insertRecord('stock_journal', $arrValue);
			
				$cnt=$_REQUEST['cnt'];
				for($i=0;$i<$cnt;$i++) {
					
					if($_REQUEST["type_array"][$i]=='production'){

						$inqty = $_REQUEST["qty_array"][$i];
						$outqty = 0;
					} else {

						$inqty = 0;
						$outqty = $_REQUEST["qty_array"][$i];
					}

					$arrValue1=array('id'=>uniqid(),'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$_REQUEST["type_array"][$i],'product'=>$_REQUEST["product_array"][$i],'unit'=>$_REQUEST["unit_array"][$i],'location'=>$_REQUEST["location_array"][$i],'stock'=>$_REQUEST["stock_array"][$i],'inqty'=>$inqty,'outqty'=>$outqty,'rate'=>$_REQUEST["rate_array"][$i],'amount'=>$_REQUEST["amount_array"][$i]);
					// print_r($arrValue1);

					$insertedId=$utilObj->insertRecord('stock_journal_details', $arrValue1);
					
				}

				// -----------------------------------------------------------------------------
				$physical_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$ad."' ");
				foreach($physical_batch as $batch) {

					$array1=array('id'=>uniqid(),'parent_id'=>$batch['parent_id'],'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'bat_rate'=>$batch['bat_rate'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited'] );

					$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					if($totalstock == '0') {

						$arrValue = array('flag'=>'1');
						$strWhere="id='".$purchase['id']."' ";
						$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
					}

					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

				}
				echo $Msg='Record has been Added Sucessfully! ';
				
			break;
		
           
		  	case"update":
		    
				// echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}
				
				$var=$_REQUEST['date'];
				$date = str_replace('/', '-', $var);
				$parent_id=$_REQUEST['id'];
				
				
				$arrValue=array('updateduser'=>$_SESSION['Ck_User_id'],'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
				$strWhere=" id='".$_REQUEST['id']."' AND ClientID='".$_SESSION['Client_Id']."' ";
				//print_r($arrValue);
				$Updaterec=$utilObj->updateRecord('stock_journal', $strWhere, $arrValue);	
				
				$strWhere=" parent_id='".$_REQUEST['id']."' AND ClientID='".$_SESSION['Client_Id']."' ";
				$Deleterec=$utilObj->deleteRecord('stock_journal_details', $strWhere);
				
				$cnt=$_REQUEST['cnt'];
				for($i=0;$i<$cnt;$i++){
					
					if($_REQUEST["type_array"][$i]=='production'){

						$inqty = $_REQUEST["qty_array"][$i];
						$outqty = 0;
					} else {

						$inqty = 0;
						$outqty = $_REQUEST["qty_array"][$i];
					}

					$arrValue1=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$_REQUEST["type_array"][$i],'product'=>$_REQUEST["product_array"][$i],'unit'=>$_REQUEST["unit_array"][$i],'location'=>$_REQUEST["location_array"][$i],'stock'=>$_REQUEST["stock_array"][$i],'inqty'=>$inqty,'outqty'=>$outqty,'rate'=>$_REQUEST["rate_array"][$i],'amount'=>$_REQUEST["amount_array"][$i]);

					//print_r($arrValue1);
					$insertedId=$utilObj->insertRecord('stock_journal_details', $arrValue1);
					
				}

				$salebatch=$utilObj->getSingleRow("temp_batch","parent_id = '".$parent_id."'");
				if($salebatch!=''){

					$strWhere1="parent_id='".$parent_id."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere1);

					$sale_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$parent_id."'");
					foreach($sale_batch as $batch) {

						$delivery= $utilObj->getSingleRow("stock_journal","id='".$parent_id."' ");

						$array1=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'bat_rate'=>$batch['bat_rate'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited']);

						print_r($array1);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

						$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

						$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

						if($totalstock == '0'){
							$arrValue = array('flag'=>'1');
							$strWhere="id='".$purchase['id']."'";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
						} else {
							$arrValue = array('flag'=>'0');
							$strWhere="id='".$purchase['id']."'  ";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
						}
					}
				}
				
			
				if($Updaterec)
				echo $Msg='Record has been Updated Sucessfully! ';
				                    
                                        
			break;	
			
			case "delete":
		         
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('stock_journal', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('stock_journal_details', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 

			break;
        
		}
		
		
		// echo "<script>window.top.location='purchase_payment.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
			
	
	}
?>