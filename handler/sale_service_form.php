<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":

				// --------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("sale_invoice_service","voucher_type='".$_REQUEST['voucher_type']."' ");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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

				// ------------------------------------------------------------------- 
				$ad = uniqid();

				$id=uniqid();
				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$prno,'voucher_code'=>$preturn_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'pos_state'=>$_REQUEST['pos_state'],'cgst_ledger'=>$_REQUEST['cgst_ledger'],'sgst_ledger'=>$_REQUEST['sgst_ledger'],'igst_ledger'=>$_REQUEST['igst_ledger'],'cgst_amt'=>$_REQUEST['cgst_amt'],'sgst_amt'=>$_REQUEST['sgst_amt'],'igst_amt'=>$_REQUEST['igst_amt'],'gst_subtotal'=>$_REQUEST['gst_subtotal'],'grandtotal'=>$_REQUEST['grandtotal'],'record'=>'Dr' );
				// print_r($arrValue);

				$insertedId=$utilObj->insertRecord('sale_invoice_service',$arrValue);

				$cnt1=$_REQUEST['cnt1'];
				
				for($i=0;$i<$cnt1;$i++) {

					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'service_ledger'=>$_REQUEST['service_ledger_array'][$i],'service_amt'=>$_REQUEST['service_amt_array'][$i],'service_subtotal'=>$_REQUEST['service_subtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
					// print_r($arrValue2);

					$insertedId=$utilObj->insertRecord('sale_invoice_service_details', $arrValue2);
		        }

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'sale_invoice_service';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$pur_ino_code,'form_type'=>$form_type );
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

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
				
			break;


			case "update":
				
				// -------------------------------------------------------------------------
				
				$mate1=$utilObj->getSingleRow("sale_invoice_service","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$grn_code="";
				$grno="";

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 3) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {

							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);

							$grn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$grno = $result['pono']+1;
						}
						else {

							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$grn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$grno = $result['pono']+1;
						}
					}
					else {

						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
							$formattedPono = sprintf('%0' . $width . 'd', $val);

							$grn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$grno = $result['pono']+1;
						}
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$grn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$grno = $result['pono']+1;
						}
					}
				}
				else {
				
					$grn_code = $mate1['voucher_code'];
					$grno = $mate1['recordnumber'];
				}

				// -------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				// echo $id; die;
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			// Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				} 

				$arrValue=array('user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$grno,'voucher_code'=>$grn_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'pos_state'=>$_REQUEST['pos_state'],'cgst_ledger'=>$_REQUEST['cgst_ledger'],'sgst_ledger'=>$_REQUEST['sgst_ledger'],'igst_ledger'=>$_REQUEST['igst_ledger'],'cgst_amt'=>$_REQUEST['cgst_amt'],'sgst_amt'=>$_REQUEST['sgst_amt'],'igst_amt'=>$_REQUEST['igst_amt'],'gst_subtotal'=>$_REQUEST['gst_subtotal'],'grandtotal'=>$_REQUEST['grandtotal'],'record'=>'Dr' );
				// print_r($arrValue);

				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('sale_invoice_service', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('sale_invoice_service_details', $strWhere);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);
				
				$cnt1=$_REQUEST['cnt1'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();

					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'service_ledger'=>$_REQUEST['service_ledger_array'][$i],'service_amt'=>$_REQUEST['service_amt_array'][$i],'service_subtotal'=>$_REQUEST['service_subtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );

					$insertedId=$utilObj->insertRecord('sale_invoice_service_details', $arrValue2);
					// print_r($arrValue2);
				}

				$cntad=$_REQUEST['cntad'];
				for($i=0;$i<$cntad;$i++) {

					$rcd = 'Cr';
					$otrrcd = 'Dr';
					
					$form_type = 'sale_invoice_service';

					if($_REQUEST["invodate_array"][$i]=='') {

						$invodate = date('Y-m-d');
					} else {

						$invodate = $_REQUEST["invodate_array"][$i];
					}

					$arrValue=array('id'=>uniqid(),'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($invodate)),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$pur_ino_code,'form_type'=>$form_type );
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
				

				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully!'; 	
			break;	

	
			case"delete":
		
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid) {

				$data=$utilObj->getMultipleRow("bill_adjustment","parent_id ='".$pid."' ");

				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('sale_invoice_service', $strWhere);

				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('sale_invoice_service_details', $strWhere);

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
