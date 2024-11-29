<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				
				// ----------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("production","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$production_code;
				$pdno;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
						
						$production_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$pdno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$production_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$pdno = $result['pono'];
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$production_code = $prefix_label."/".$year_code."/".($formattedPono);
						$pdno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$production_code = $prefix_label."/".$year_code."/".($result['pono']);
						$pdno = $result['pono'];
					}
				}

				// ----------------------------------------------------------------------------------
				// $id=uniqid();
				$ad=$_REQUEST['ad'];

				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'batch_no'=>$pdno,'production_code'=>$production_code,'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'product'=>$_REQUEST['product'],'unit'=>$_REQUEST['unit'],'qty'=>$_REQUEST['qty'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'bom'=>$_REQUEST['bom'],'rate'=>$_REQUEST['pro_batch_rate'],'total_req'=>$_REQUEST['total_req'],'grand_total'=>$_REQUEST['grand_total'] );

				// print_r($arrValue);
				$insertedId=$utilObj->insertRecord('production',$arrValue);	

				// ---------------------------------Production Batch---------------------------------
				$type="production_in";

				$arrValue1=array('id'=>uniqid(),'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$type,'batchname'=>$_REQUEST['pro_batch_name'],'batqty'=>$_REQUEST['qty'],'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['pro_batch_rate'],'bom_id'=>$_REQUEST['bom'] );

				$insertedId=$utilObj->insertRecord('purchase_batch',$arrValue1);

				$cnt1=$_REQUEST['cnt'];
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// if($_REQUEST['qty_array'][$i]!=0) {
					$id1=uniqid();
					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					$arrValue2=array('id'=>$id1,'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'requiredqty'=>$_REQUEST['requiredqty_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'totalsum'=>$_REQUEST['totalsum_array'][$i] );
					
					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('production_details', $arrValue2);
					// }
		        }	

				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$ad."'");
				foreach($sale_batch as $batch) {

					// $array1=array('id'=>uniqid(),'delivery_id'=>$batch['parent_id'],'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'] );

					// $insertedId=$utilObj->insertRecord('sale_batch', $array1);

					$array1=array('id'=>uniqid(),'delivery_id'=>$ad,'sale_invoice_no'=>$ad,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'],'batch_price'=>$batch['batch_price'],'sub_total'=>$batch['sub_total'],'bat_rate'=>$batch['bat_rate'] );
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

				
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				// --------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("production","id='".$_REQUEST['id']."' ");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."' ");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$production_code;
				$pdno;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 3) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
						
							$production_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$pdno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$production_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$pdno = $result['pono']+1;
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
				
							$production_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$pdno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$production_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$pdno = $result['pono']+1;
						}
					}
				}
				else {
				
					$production_code = $mate1['production_code'];
					$pdno = $mate1['batch_no'];
				}

				// --------------------------------------------------------------------------------------------
			
				$id=$_REQUEST['id'];
				// $_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'batch_no'=>$pdno,'production_code'=>$production_code,'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'product'=>$_REQUEST['product'],'unit'=>$_REQUEST['unit'],'qty'=>$_REQUEST['qty'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'bom'=>$_REQUEST['bom'],'rate'=>$_REQUEST['pro_batch_rate'],'total_req'=>$_REQUEST['total_req'],'grand_total'=>$_REQUEST['grand_total'] );
				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('production', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('production_details', $strWhere);

				
				
				$cnt1=$_REQUEST['cnt'];
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// if($_REQUEST['qty_array'][$i]!=0){
					$id1=uniqid();
					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'requiredqty'=>$_REQUEST['requiredqty_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'totalsum'=>$_REQUEST['totalsum_array'][$i] );

					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('production_details', $arrValue2);
					// }
				}

				$strWhereb="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhereb);

				$type="production_in";

				$arrValue1=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$type,'batchname'=>$_REQUEST['pro_batch_name'],'batqty'=>$_REQUEST['qty'],'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['pro_batch_rate'],'bom_id'=>$_REQUEST['bom'] );

				$insertedId=$utilObj->insertRecord('purchase_batch',$arrValue1);

				$salebatch=$utilObj->getSingleRow("temp_sale_batch","parent_id = '".$id."'");
				if($salebatch!='') {
					$strWhere1="delivery_id='".$_REQUEST['id']."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);
					$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");
					foreach($sale_batch as $batch){
						$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

						$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

						$delivery= $utilObj->getSingleRow("sale_invoice","id='".$id."'");

						$array1=array('id'=>uniqid(),'delivery_id'=>$id,'sale_invoice_no'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>$delivery['date'],'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'],'batch_price'=>$batch['batch_price'],'sub_total'=>$batch['sub_total'],'bat_rate'=>$batch['bat_rate'] );
						
						$insertedId=$utilObj->insertRecord('sale_batch', $array1);

						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_sale_batch', $strWhere);


						if($totalstock == '0'){
							$arrValue = array('flag'=>'1');
							$strWhere="id='".$purchase['id']."'  ";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
						}else{
							$arrValue = array('flag'=>'0');
							$strWhere="id='".$purchase['id']."'  ";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
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
					$Deleterec=$utilObj->deleteRecord('production', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('production_details', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere);
					
					$strWhere="delivery_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
