<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				// -------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_order","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];
				$width = $mate3['codewidth'];

				$year_code = "";
				$grn_code;
				$grno;

				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$val = $result['pono']+1;
						// $formattedPono = sprintf('%04d', $val);
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$grn_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$grno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$grn_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$grno = $result['pono'];
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						$val = $result['pono']+1;
						// $formattedPono = sprintf('%04d', $val);
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$grn_code = $prefix_label."/".$year_code."/".($formattedPono);
						$grno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$grn_code = $prefix_label."/".$year_code."/".($result['pono']);
						$grno = $result['pono'];
					}
				}

				// -------------------------------------------------------------------

				$id=uniqid();
				
				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'order_no'=>$grno,'order_code'=>$grn_code,'voucher_type'=>$_REQUEST['voucher_type'],'type'=>$_REQUEST['type'],'location'=>$_REQUEST['location'],'requisition_no'=>$_REQUEST['requisition_no'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'other'=>$_REQUEST['other'],'grandtotal'=>$_REQUEST['grandtotal'],'totdiscount'=>$_REQUEST['totdiscount'],'totaltaxable'=>$_REQUEST['totaltaxable'],'cgstledger'=>$_REQUEST['cgstledger'],'cgstamt'=>$_REQUEST['cgstamt'],'sgstledger'=>$_REQUEST['sgstledger'],'sgstamt'=>$_REQUEST['sgstamt'],'igstledger'=>$_REQUEST['igstledger'],'igstamt'=>$_REQUEST['igstamt'],'subtotgst'=>$_REQUEST['subtotgst'],'totserviceamt'=>$_REQUEST['totserviceamt'],'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'] );

				// print_r($arrValue);
				$insertedId=$utilObj->insertRecord('purchase_order',$arrValue);
				$cnt1=$_REQUEST['cnt'];
				
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					$flag="0";

					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rm_qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'taxable'=>$_REQUEST['taxable_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i] );

					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('purchase_order_details', $arrValue2);
				 
		        }

				$dqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where requisition_no='".$_REQUEST['requisition_no']."') ","qty");	

				$sqty=$utilObj->getSum("purchase_requisition_details","parent_id in(select id from purchase_requisition where id='".$_REQUEST['requisition_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['requisition_no']."'";
					$arrValuef=array('requi_flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('purchase_requisition', $strWheref, $arrValuef);
				}

				$cntd=$_REQUEST['cntd'];
				// echo $cntd;

				for($j=0;$j<$cntd;$j++) {

					$id2 = uniqid();

					$arrValued=array('id'=>$id2,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'ledger'=>$_REQUEST['serviceledger_array'][$j],'servicecgst'=>$_REQUEST['servicecgst_array'][$j],'servicesgst'=>$_REQUEST['servicesgst_array'][$j],'serviceigst'=>$_REQUEST['serviceigst_array'][$j],'serviceamt'=>$_REQUEST['serviceamt_array'][$j] );

					// print_r($arrValued);
					$insertedId=$utilObj->insertRecord('purchase_order_other_details', $arrValued);
				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';

			break;


			case "update":
				 
				// -------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_order","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$grn_code="";
				$grno="";

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 4) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
						
							$grn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$grno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$grn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$grno = $result['pono']+1;
						}
					} else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
				
							$grn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$grno = $result['pono']+1;
						} else {

							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$grn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$grno = $result['pono']+1;
						}
					}
				} else {
					
					$grn_code = $mate1['order_code'];
					$grno = $mate1['order_no'];
				}

				// -------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			// Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured";
					break;
				}   
					
				$arrValue=array('Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'order_no'=>$_REQUEST['order_no'],'type'=>$_REQUEST['type'],'location'=>$_REQUEST['location'],'requisition_no'=>$_REQUEST['requisition_no'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'bill_to'=>$_REQUEST['bill_to'],'ship_to'=>$_REQUEST['ship_to'],'state_name'=>$_REQUEST['state_name'],'state_code'=>$_REQUEST['state_code'],'pos_state'=>$_REQUEST['pos_state'],'order_no'=>$grno,'order_code'=>$grn_code,'voucher_type'=>$_REQUEST['voucher_type'] );
				// print_r($arrValue);

				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('purchase_order', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_order_details', $strWhere);
				
				$cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'cgst'=>$_REQUEST['cgst_array'][$i],'sgst'=>$_REQUEST['sgst_array'][$i],'igst'=>$_REQUEST['igst_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i],'ledger'=>$_REQUEST['ledger_array'][$i]);

					$insertedId=$utilObj->insertRecord('purchase_order_details', $arrValue2);
					print_r($arrValue2);
				}

				$dqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where requisition_no='".$_REQUEST['requisition_no']."') ","qty");	

				$sqty=$utilObj->getSum("purchase_requisition_details","parent_id in(select id from purchase_requisition where id='".$_REQUEST['requisition_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['requisition_no']."'";
					$arrValuef=array('requi_flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('purchase_requisition', $strWheref, $arrValuef);
				}

				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! ';

			break;	

	
			case"delete":
			
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid) {

					$mate1=$utilObj->getSingleRow("purchase_order","id='".$pid."' ");

					if($mate1['type']=='Against_Requisition') {

						$requi_flag="0";
						$strWheref="id='".$mate1['requisition_no']."'";
						$arrValuef=array('requi_flag'=>$requi_flag );
						$Updatere=$utilObj->updateRecord('purchase_requisition', $strWheref, $arrValuef);
					}

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_order', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_order_details', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_order_other_details', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 

			break;
			
		}	
	}
?>
