<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				
				// -------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("sale_invoice","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$saleino_code;
				$sino;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}

				
				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$saleino_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$sino = $formattedPono;

					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$saleino_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$sino = $result['pono'];
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$saleino_code = $prefix_label."/".$year_code."/".($formattedPono);
						$sino = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$saleino_code = $prefix_label."/".$year_code."/".($result['pono']);
						$sino = $result['pono'];
					}
				}

				// -------------------------------------------------------------------

				$id=$_REQUEST['common_id'];

				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'sale_invoiceno'=>$sino,'saleino_code'=>$saleino_code,'voucher_type'=>$_REQUEST['voucher_type'],'customer'=>$_REQUEST['customer'],'location'=>$_REQUEST['location'],'type'=>$_REQUEST['type'],'pricetype'=>$_REQUEST['pricetype'],'pricelevel'=>$_REQUEST['pricelevel'],'delivery_challan_no'=>$_REQUEST['delivery_challan_no'],'total_quantity'=>$_REQUEST['total_quantity'],'grandtotal'=>$_REQUEST['grandtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'],'record'=>'Dr' );

				$insertedId=$utilObj->insertRecord('sale_invoice',$arrValue);

				$cnt1=$_REQUEST['cnt'];
				$batchUpdated = false;

				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."' ");
				foreach($sale_batch as $batch){

					$array1=array('id'=>uniqid(),'delivery_id'=>$id,'sale_invoice_no'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'],'bat_rate'=>$batch['bat_rate'] );

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
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

					$totalstock = getstock($_REQUEST['product_array'][$i],$_REQUEST['unit_array'][$i], date('Y-m-d'), '', $_REQUEST['location']);
					
					// if($totalstock < $_REQUEST['qty_array'][$i] ) {

					// 	echo "Concurrency error occured";

					// 	$strWhere="id='".$id."' ";
					// 	$Deleterec=$utilObj->deleteRecord('sale_invoice', $strWhere);

					// 	$strWhere="delivery_id='".$id."' ";
					// 	$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere);

					// } else {
					
						if($_REQUEST['qty_array'][$i] > 0) {

							$id1=uniqid();
						
							$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'orderqty'=>$_REQUEST['orderqty_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i],'record'=>'Cr' );
							
							//print_r($arrValue2);
							$insertedId=$utilObj->insertRecord('sale_invoice_details', $arrValue2);
						}
					// }
		        }

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'sale_invoice_goods';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['customer'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$saleino_code,'form_type'=>$form_type );
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

				$cntd=$_REQUEST['cntd'];
				// echo $cntd;

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('sale_invoice_other_details', $arrValued);
				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				// --------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("sale_invoice","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$saleino_code;
				$sino;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 3) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
						
							$saleino_code = $prefix_label."/".($formattedPono)."/".$year_code;
							$sino = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$saleino_code = $prefix_label."/".($result['pono'])."/".$year_code;
							$sino = $result['pono'];
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
				
							$saleino_code = $prefix_label."/".$year_code."/".($formattedPono);
							$sino = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$saleino_code = $prefix_label."/".$year_code."/".($result['pono']);
							$sino = $result['pono'];
						}
					}
				}
				else {
				
					$saleino_code = $mate1['saleino_code'];
					$sino = $mate1['sale_invoiceno'];
				}

				// --------------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}   
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'sale_invoiceno'=>$sino,'saleino_code'=>$saleino_code,'voucher_type'=>$_REQUEST['voucher_type'],'customer'=>$_REQUEST['customer'],'location'=>$_REQUEST['location'],'type'=>$_REQUEST['type'],'pricetype'=>$_REQUEST['pricetype'],'pricelevel'=>$_REQUEST['pricelevel'],'delivery_challan_no'=>$_REQUEST['delivery_challan_no'],'total_quantity'=>$_REQUEST['total_quantity'],'grandtotal'=>$_REQUEST['grandtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'] );

				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('sale_invoice', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_invoice_details', $strWhere);

				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_invoice_other_details', $strWhere);

				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);
				
				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					if($_REQUEST['qty_array'][$i]>0){

						$id1=uniqid();

						$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'orderqty'=>$_REQUEST['orderqty_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );
						// print_r($arrValue2);
						$insertedId=$utilObj->insertRecord('sale_invoice_details', $arrValue2);
					}
				}

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'sale_invoice_goods';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['customer'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$saleino_code,'form_type'=>$form_type );
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

				$cntd=$_REQUEST['cntd'];

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('sale_invoice_other_details', $arrValued);
				}
				
				$salebatch=$utilObj->getSingleRow("temp_sale_batch","parent_id = '".$id."'");

				if($salebatch!='') {
					$strWhere1="delivery_id='".$_REQUEST['id']."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);
					$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");
					foreach($sale_batch as $batch){
						$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

						$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

						$delivery= $utilObj->getSingleRow("sale_invoice","id='".$id."'");

							$array1=array('id'=>uniqid(),'delivery_id'=>$id,'sale_invoice_no'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>$delivery['date'],'batchname'=>$batch['batchname'],'quantity'=>$batch['quantity'],'created'=>$batch['created'],'lastedited'=>$batch['lastedited'],'bat_rate'=>$batch['bat_rate'] );
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
				foreach($pids as $pid) {	

					$data=$utilObj->getMultipleRow("bill_adjustment","parent_id ='".$pid."' ");

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('sale_invoice', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('sale_invoice_details', $strWhere);

					$strWhere="delivery_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere);

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
