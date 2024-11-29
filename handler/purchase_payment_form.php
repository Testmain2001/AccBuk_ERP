<?php 
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
		
		switch($_REQUEST['PTask'])	
		{
        
			case "makepayment":		
				
				// -------------------------------------------------------------------------
					
				$mate1=$utilObj->getSingleRow("purchase_payment","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$purpay_code;
				$ppno;

				if (date("m") > 4) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}

				if ($mate3['numbering_digit'] == 'Prefix') {
			
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
			
						$purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$ppno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$ppno = $result['pono']+1;
					}
				}
				else {
		
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
			
						$purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$ppno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
		
						$purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$ppno = $result['pono']+1;
					}
				}

				// -------------------------------------------------------------------------

				$var=$_REQUEST['date'];
				$date = str_replace('/', '-', $var);
				$id=$_REQUEST['id'];
				
				
				$paymentid=uniqid();
				$arrValue=array('id'=>$paymentid,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$ppno,'purpay_code'=>$purpay_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'paymentdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'payment_method'=>$_REQUEST['mode'],'bank_ledger'=>$_REQUEST['bank_ledger'],'balance'=>$_REQUEST['balance'],'cheque_no'=>$_REQUEST['cheque_no'],'amt_pay'=>$_REQUEST['amt_pay'],'narration'=>$_REQUEST['narration'],'Type'=>'Payment','record'=>'Dr' );
				//var_dump($arrValue);die;
				//print_r($arrValue);
				$insertedId=$utilObj->insertRecord('purchase_payment', $arrValue);
			
				// if($_REQUEST['type']=='Advanced') {

				// 	$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
				// 	$insertedId=$utilObj->insertRecord('purchase_payment_info', $arrValue);
						
				// } else {
					$cnt=$_REQUEST['cnt'];

					for($i=0;$i<$cnt;$i++) {

						$form_type = 'purchase_payment';

						$rcd = 'Dr';
						$otrrcd = 'Cr';

						$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($_REQUEST["invodate_array"][$i])),'invoamt'=>$_REQUEST["totalinvo_array"][$i]);
						// print_r($arrValue);

						$insertedId=$utilObj->insertRecord('purchase_payment_details', $arrValue);

						$arrValuead=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($_REQUEST["invodate_array"][$i])),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$purpay_code,'form_type'=>$form_type );

						$insertedId=$utilObj->insertRecord('bill_adjustment', $arrValuead);

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
				// }
					
				echo $Msg='Record has been Added Sucessfully! ';
			break;
		
           
		  	case"update":

			// ------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("purchase_payment","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$purpay_code;
				$ppno;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 4) {
						$year_code = date("y")."-".(date("y")+1);
					} else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
						
							$purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$ppno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$ppno = $result['pono']+1;
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
				
							$purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$ppno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$ppno = $result['pono']+1;
						}
					}
				}
				else {
				
					$purpay_code = $mate1['purpay_code'];
					$ppno = $mate1['recordnumber'];
				}

			// ------------------------------------------------------------------------------------------
		    
				//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
				echo $Msg = "Concurrency Error Occured"; 
					break;
				}
				
				$var=$_REQUEST['date'];
				$date = str_replace('/', '-', $var);
				$paymentid=$_REQUEST['id'];
				
				
				$arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$ppno,'purpay_code'=>$purpay_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'ptype'=>$_REQUEST['type'], 'paymentdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'payment_method'=>$_REQUEST['mode'],'bank_ledger'=>$_REQUEST['bank_ledger'],'balance'=>$_REQUEST['balance'],'cheque_no'=>$_REQUEST['cheque_no'],'amt_pay'=>$_REQUEST['amt_pay'],'narration'=>$_REQUEST['narration'],'Type'=>'Payment','record'=>'Dr' );
				
				$strWhere=" id='".$_REQUEST['id']."'";
				print_r($arrValue);
				$Updaterec=$utilObj->updateRecord('purchase_payment', $strWhere, $arrValue);	
				
				$strWhere=" parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_payment_details', $strWhere);
				
				// if($_REQUEST['type']=='Advanced')
				// 	{
				// 		if($_REQUEST['mode']=='cash'){
				// 			$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr');
				// 			$insertedId=$utilObj->insertRecord('purchase_payment_details', $arrValue);
				// 		}else{
				// 			$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr');
				// 			$insertedId=$utilObj->insertRecord('purchase_payment_details', $arrValue);
				// 		}
				// 	}else{
				// 		$cnt=$_REQUEST['cnt'];
				
				// 		for($i=0;$i<$cnt;$i++){
							
				// 			$amount=$_REQUEST["bank_array"][$i];
				// 			$amount1=$_REQUEST["bank1_array"][$i];
				// 			$purchase=$_REQUEST["purchaseid_array"][$i];
					
				// 			if($amount>0){
				// 				$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$amount,'discount'=>$amount1,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr');
				// 				print_r($arrValue);
				// 				$insertedId=$utilObj->insertRecord('purchase_payment_details', $arrValue);
				// 			}
				// 		}
				// 	}

				$cnt=$_REQUEST['cnt'];

				for($i=0;$i<$cnt;$i++) {

					
					if($_REQUEST["type_array"][$i]=='PO') {

						$rcd = 'Dr';
						$otrrcd = 'Cr';
					} else {

						$rcd = 'Cr';
						$otrrcd = 'Dr';
					}
					
					$form_type = 'purchase_payment';

					$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($_REQUEST["invodate_array"][$i])),'invoamt'=>$_REQUEST["totalinvo_array"][$i]);
					print_r($arrValue);

					$insertedId=$utilObj->insertRecord('purchase_payment_details', $arrValue);

					$arrValuead=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'supplier'=>$_REQUEST['supplier'],'purchaseid'=>$_REQUEST["billno_array"][$i],'amount'=>$_REQUEST["payamt_array"][$i],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>$rcd,'otr_record'=>$otrrcd,'type'=>$_REQUEST["type_array"][$i],'invodate'=>date('Y-m-d',strtotime($_REQUEST["invodate_array"][$i])),'invoamt'=>$_REQUEST["totalinvo_array"][$i],'total_amt'=>$_REQUEST['totalvalue'],'voucher_code'=>$purpay_code,'form_type'=>$form_type );

					$insertedId=$utilObj->insertRecord('bill_adjustment', $arrValuead);

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
				echo $Msg='Record has been Updated Sucessfully! ';
				                    
                                        
			break;	
			
			case "delete":
		         
                //echo ">>>>".$_REQUEST['sid'];		
				// $mid=$_REQUEST['id'];
				// $payment_record=mysqli_query($GLOBALS['con'],"Select * from purchase_payment WHERE id ='".$_REQUEST['id']."'  ");
				// $payment=mysqli_fetch_array($payment_record);
				// $saleid= $payment['supplier'];	
				// $mid=explode(',',$mid);
				// foreach($mid as $ent)
				// {
				// 	$str.="'".$ent."',";
				// }
				// $mid=trim($str,",");
   		        // $strWhere=" ID IN ($mid)  ";
				// $Deleterec=$utilObj->deleteRecord('purchase_payment', $strWhere);		
				
				// $strWhere=" parent_id IN ($mid)  ";
				// $Deleterec=$utilObj->deleteRecord('purchase_payment_details', $strWhere);	

				// $strWhere=" parent_id IN ($mid)  ";
				// $Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);
				
				$pids=explode(",",$_REQUEST['id']);

				foreach($pids as $pid) {

					$data=$utilObj->getMultipleRow("bill_adjustment","parent_id ='".$pid."' ");

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_payment', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_payment_details', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('bill_adjustment', $strWhere);

					// -------------------------------------------------------------------
					
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
				
				if($Deleterec)
				echo $Msg='Record has been Deleted Sucessfully! ';
					
			break;
        
		}
	
	}
?>