<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				// --------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_invoice","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$$pur_ino_code;
				$pino;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
			
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$pur_ino_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$pino = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$pur_ino_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$pino = $result['pono'];
					}
				}
				else {
		
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$pur_ino_code = $prefix_label."/".$year_code."/".($formattedPono);
						$pino = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
		
						$pur_ino_code = $prefix_label."/".$year_code."/".($result['pono']);
						$pino = $result['pono'];
					}
				}

				// --------------------------------------------------------------------------

				$id=uniqid();
				$arrValue=array('id'=>$_REQUEST['ad'],'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'invoicenumber'=>$pino,'pur_invoice_code'=>$pur_ino_code,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'location'=>$_REQUEST['location'],'supplier'=>$_REQUEST['supplier'],'voucher_type'=>$_REQUEST['voucher_type'],'type'=>$_REQUEST['type'],'purchaseorder_no'=>$_REQUEST['purchaseorder_no'],'other'=>$_REQUEST['other'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'grandtotal'=>$_REQUEST['grandtotal'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'],);
				
				echo ($arrValue);
				$insertedId=$utilObj->insertRecord('purchase_invoice',$arrValue);

				$cnt1=$_REQUEST['cnt'];

				for($i=0;$i<$cnt1;$i++) {
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					if($_REQUEST['type']=='Direct_Purchase') {

						$arrValue2=array('id'=>$id1,'parent_id'=>$_REQUEST['ad'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );
						
						// echo ($arrValue2);
						$insertedId=$utilObj->insertRecord('purchase_invoice_details', $arrValue2);
					} else {

						$arrValue2=array('id'=>$id1,'parent_id'=>$_REQUEST['ad'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );

						// echo ($arrValue2);
						$insertedId=$utilObj->insertRecord('purchase_invoice_details', $arrValue2);
					}
		        }

				$dqty=$utilObj->getSum("purchase_invoice_details","parent_id in(select id from purchase_invoice where purchaseorder_no='".$_REQUEST['purchaseorder_no']."') ","qty");	

				$sqty=$utilObj->getSum("grn_details","parent_id in(select id from grn where id='".$_REQUEST['purchaseorder_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['purchaseorder_no']."'";
					$arrValuef=array('flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('grn', $strWheref, $arrValuef);
				}

				$cntd=$_REQUEST['cntd'];

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$_REQUEST['ad'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// echo ($arrValued);
					$insertedId=$utilObj->insertRecord('purchase_invoice_other_details', $arrValued);
				}

				$cntad=$_REQUEST['cntad'];

				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'purchase_invoice_goods';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['ad'],'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$pur_ino_code,'form_type'=>$form_type );
					print_r($arrValue);

					$insertedId=$utilObj->insertRecord('bill_adjustment', $arrValue);

					$bill_adust=$utilObj->getSingleRow("bill_adjustment","id ='".$_REQUEST["billno_array"][$i]."' ");
					if(!empty($bill_adust)) {

						$purchase=$utilObj->getSum("bill_adjustment","purchaseid='".$bill_adust["id"]."' ","amount");

						$remain = $bill_adust['amount'] - $purchase;
					}

					if($remain==0) {

						$flag = 1;
					} else {

						$flag = 0;
					}

					$strWhere=" id='".$bill_adust['id']."' ";
					$arrValueup=array('flag'=>$flag);
					$Updaterec=$utilObj->updateRecord('bill_adjustment', $strWhere, $arrValueup);
				}

				// -----------------------------------------------------------------------------------------
				$pinvoice_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$_REQUEST['ad']."' ");
				foreach($pinvoice_batch as $batch1) {

					$arrValue3=array('id'=>uniqid(),'product'=>$batch1['product'],'bat_rate'=>$batch1['bat_rate'],'parent_id'=>$batch1['parent_id'],'ClientID'=>$_SESSION['Client_Id'],'type'=>$batch1['type'],'batchname'=>$batch1['batchname'],'batqty'=>$batch1['quantity'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'location'=>$batch1['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));

					$insertedId1=$utilObj->insertRecord('purchase_batch', $arrValue3);

					$strWhere="parent_id='".$batch1['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);
				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				// ---------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_invoice","id='".$_REQUEST['id']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);

							$pur_ino_code = $prefix_label."/".($formattedPono)."/".$year_code;
							$pino = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$pur_ino_code = $prefix_label."/".($result['pono'])."/".$year_code;
							$pino = $result['pono'];
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
				
							$pur_ino_code = $prefix_label."/".$year_code."/".($formattedPono);
							$pino = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$pur_ino_code = $prefix_label."/".$year_code."/".($result['pono']);
							$pino = $result['pono'];
						}
					}
				}
				else {
				
					$pur_ino_code = $mate1['pur_invoice_code'];
					$pino = $mate1['invoicenumber'];
				}

				// ---------------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'invoicenumber'=>$pino,'pur_invoice_code'=>$pur_ino_code,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'supplier'=>$_REQUEST['supplier'],'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'type'=>$_REQUEST['type'],'purchaseorder_no'=>$_REQUEST['purchaseorder_no'],'grandtotal'=>$_REQUEST['grandtotal'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'], );

				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('purchase_invoice', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_invoice_details', $strWhere);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_invoice_other_details', $strWhere);

				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);
				
				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++) {
					
					$id1=uniqid();
					
					// $arrValue2=array('id'=>$id1,'parent_id'=>$id,'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );

					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );
				
					$insertedId=$utilObj->insertRecord('purchase_invoice_details', $arrValue2);
					// print_r($arrValue2);
				}

				$cntd=$_REQUEST['cntd'];

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// echo ($arrValued);
					$insertedId=$utilObj->insertRecord('purchase_invoice_other_details', $arrValued);
				}

				$cnt=$_REQUEST['cntad'];

				for($i=0;$i<$cnt;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'purchase_invoice_goods';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$pur_ino_code,'form_type'=>$form_type );
					print_r($arrValue);

					$insertedId=$utilObj->insertRecord('bill_adjustment', $arrValue);

					$bill_adust=$utilObj->getSingleRow("bill_adjustment","id ='".$_REQUEST["billno_array"][$i]."' ");
					if(!empty($bill_adust)) {

						$purchase=$utilObj->getSum("bill_adjustment","purchaseid='".$bill_adust["id"]."' ","amount");

						$remain = $bill_adust['amount'] - $purchase;
					}

					if($remain==0) {

						$flag = 1;
					} else {

						$flag = 0;
					}

					$strWhere=" id='".$bill_adust['id']."' ";
					$arrValueup=array('flag'=>$flag);
					$Updaterec=$utilObj->updateRecord('bill_adjustment', $strWhere, $arrValueup);
				}

				$dqty=$utilObj->getSum("purchase_invoice_details","parent_id in(select id from purchase_invoice where purchaseorder_no='".$_REQUEST['purchaseorder_no']."') ","qty");	

				$sqty=$utilObj->getSum("grn_details","parent_id in(select id from grn where id='".$_REQUEST['purchaseorder_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['purchaseorder_no']."'";
					$arrValuef=array('flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('grn', $strWheref, $arrValuef);
				}

				// ------------------------------------------------------------------------------
				$invoicebatch=$utilObj->getSingleRow("temp_batch","parent_id = '".$id."'");
				if($invoicebatch!='') {

					$strWhere1="parent_id='".$id."' AND product='".$invoicebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere1);

					$invoice_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$id."'");
					foreach($invoice_batch as $batch) {

						$array1=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$batch['ClientID'],'product'=>$batch['product'],'bat_rate'=>$batch['bat_rate'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited']);

						print_r($array1);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);
						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

					}
				}

				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! ';

			break;	

	
			case"delete":
			
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid) {

					$data=$utilObj->getMultipleRow("bill_adjustment","parent_id ='".$pid."' ");

					$mate1=$utilObj->getSingleRow("purchase_invoice","id='".$pid."' ");

					if($mate1['type']=='Against_Purchaseorder') {

						$requi_flag="0";
						$strWheref="id='".$mate1['purchaseorder_no']."'";
						$arrValuef=array('flag'=>$requi_flag );
						$Updatere=$utilObj->updateRecord('grn', $strWheref, $arrValuef);
					}

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_invoice', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_invoice_details', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_invoice_other_details', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);

					foreach($data as $info) {

						$bill_adust=$utilObj->getSingleRow("bill_adjustment","id ='".$info['purchaseid']."' ");
						
						if(!empty($bill_adust)) {
							
							$purchase=$utilObj->getSum("bill_adjustment","purchaseid='".$bill_adust["id"]."' ","amount");
					
							$remain = $bill_adust['amount'] - $purchase;
						}
					
						if($remain==0) {

							$flag = 1;
						} else {

							$flag = 0;
						}
					
						$strWhere=" id='".$bill_adust['id']."' ";
						$arrValueup=array('flag'=>$flag);
						$Updaterec=$utilObj->updateRecord('bill_adjustment', $strWhere, $arrValueup);
					}
				}
				
				echo $Msg='Record has been Deleted Sucessfully! ';
				
			break;


			
		}	
	}
?>
