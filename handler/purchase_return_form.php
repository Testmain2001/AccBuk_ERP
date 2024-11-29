<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
				
				// -----------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_return","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$preturn_code;
				$prno;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
						
						$preturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$prno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$preturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$prno = $result['pono'];
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$preturn_code = $prefix_label."/".$year_code."/".($formattedPono);
						$prno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$preturn_code = $prefix_label."/".$year_code."/".($result['pono']);
						$prno = $result['pono'];
					}
				}

				// --------------------------------------------------------------------------------------

				$ad = $_REQUEST['ad'];

				$id=uniqid();
				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$prno,'preturn_code'=>$preturn_code,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'location'=>$_REQUEST['location'],'supplier'=>$_REQUEST['supplier'],'voucher_type'=>$_REQUEST['voucher_type'],'purchase_invoice_no'=>$_REQUEST['purchase_invoice_no'],'other'=>$_REQUEST['other'],'grandtotal'=>$_REQUEST['grandtotal'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'] );
				//print_r($arrValue);
				$insertedId=$utilObj->insertRecord('purchase_return',$arrValue);	

				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$ad,'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],
					'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'rejectedqty'=>$_REQUEST['rejectedqty_array'][$i],'total'=>$_REQUEST['total_array'][$i]);
					//print_r($arrValue2);

					$insertedId=$utilObj->insertRecord('purchase_return_details', $arrValue2);
				 
		        }

				$cntd=$_REQUEST['cntd'];

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('purchase_return_other_details', $arrValued);
				}

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Dr';
					$otrrcd = 'Cr';
					
					$form_type = 'purchase_invoice_return';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$preturn_code,'form_type'=>$form_type );
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


				$preturn_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$ad."' ");
				foreach($preturn_batch as $batch) {

					$array1=array('id'=>uniqid(),'parent_id'=>$batch['parent_id'],'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited'],'bat_rate'=>$batch['bat_rate'] );

					$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					if($totalstock == '0') {

						$arrValue = array('flag'=>'1');
						$strWhere="id='".$purchase['id']."'  ";
						$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
					}

					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);
				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			
			break;


			case "update":
				
				// ----------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_return","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$preturn_code;
				$prno;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 3) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
						
							$preturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
							$prno = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$preturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
							$prno = $result['pono'];
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);
				
							$preturn_code = $prefix_label."/".$year_code."/".($formattedPono);
							$prno = $formattedPono;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$preturn_code = $prefix_label."/".$year_code."/".($result['pono']);
							$prno = $result['pono'];
						}
					}
				}
				else {
				
					$preturn_code = $mate1['preturn_code'];
					$prno = $mate1['recordnumber'];
				}

				// --------------------------------------------------------------------------------------
				
				$id=$_REQUEST['id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}  

				$parent_id=$_REQUEST['id'];
					
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$prno,'preturn_code'=>$preturn_code,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'supplier'=>$_REQUEST['supplier'],'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'purchase_invoice_no'=>$_REQUEST['purchase_invoice_no'],'other'=>$_REQUEST['other'],'grandtotal'=>$_REQUEST['grandtotal'],'transcost'=>$_REQUEST['transcost'],'transgst'=>$_REQUEST['transgst'],'transamount'=>$_REQUEST['transamount'],'subt'=>$_REQUEST['subt'],'trans'=>$_REQUEST['trans'],'totcst_amt'=>$_REQUEST['totcst_amt'],'totsgst_amt'=>$_REQUEST['totsgst_amt'],'totigst_amt'=>$_REQUEST['totigst_amt'],'tcs_tds'=>$_REQUEST['tcs_tds'],'tcs_tds_percen'=>$_REQUEST['tcs_tds_percen'],'tcs_tds_amt'=>$_REQUEST['tcs_tds_amt'],'roff'=>$_REQUEST['roff'],'otrnar'=>$_REQUEST['otrnar'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'] );
				
				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('purchase_return', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_return_details', $strWhere);

				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);
				
				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'disc'=>$_REQUEST['disc_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'rejectedqty'=>$_REQUEST['rejectedqty_array'][$i],'total'=>$_REQUEST['total_array'][$i]);
				
					$insertedId=$utilObj->insertRecord('purchase_return_details', $arrValue2);
					// print_r($arrValue2);
				}

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Dr';
					$otrrcd = 'Cr';
					
					$form_type = 'purchase_invoice_return';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$preturn_code,'form_type'=>$form_type );
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

				$salebatch=$utilObj->getSingleRow("temp_batch","parent_id = '".$parent_id."'");
				if($salebatch!=''){

					$strWhere1="parent_id='".$parent_id."' AND product='".$salebatch['product']."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere1);

					$sale_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$parent_id."' ");
					foreach($sale_batch as $batch) {

						$delivery= $utilObj->getSingleRow("stock_journal","id='".$parent_id."' ");

						$array1=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited'],'bat_rate'=>$batch['bat_rate'] );
						print_r($array1);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

						$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

						$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

						if($totalstock == '0'){
							$arrValue = array('flag'=>'1');
							$strWhere="id='".$purchase['id']."' ";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
						} else {
							$arrValue = array('flag'=>'0');
							$strWhere="id='".$purchase['id']."' ";
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
					$data=$utilObj->getMultipleRow("bill_adjustment","parent_id ='".$pid."' ");

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_return', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_return_details', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_return_other_details', $strWhere);

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
