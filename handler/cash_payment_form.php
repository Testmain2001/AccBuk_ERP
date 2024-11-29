<?php 
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
		
		switch($_REQUEST['PTask'])	
		{
        
			case "makepayment":		
				
				// -------------------------------------------------------------------------
					
				$mate1=$utilObj->getSingleRow("cash_payment","voucher_type='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
				$arrValue=array('id'=>$paymentid,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$ppno,'purpay_code'=>$purpay_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'ptype'=>$_REQUEST['type'], 'paymentdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'payment_method'=>$_REQUEST['mode'],'bank_ledger'=>$_REQUEST['bank_ledger'],'balance'=>$_REQUEST['balance'],'cheque_no'=>$_REQUEST['cheque_no'],'amt_pay'=>$_REQUEST['amt_pay'],'narration'=>$_REQUEST['narration'],'Type'=>'Payment','record'=>'Dr' );
				//var_dump($arrValue);die;
				//print_r($arrValue);
				$insertedId=$utilObj->insertRecord('cash_payment', $arrValue);
			
				if($_REQUEST['type']=='Advanced')
				{
						if($_REQUEST['mode']=='cash'){

							$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
							$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
						} else {

							$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
							$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
						}
				}else{
					echo $cnt=$_REQUEST['cnt'];
					// echo $cnt; die();
					for($i=0;$i<$cnt;$i++){
						
						
						$amount=$_REQUEST["bank_array"][$i];
						// $amount1=$_REQUEST["bank1_array"][$i];
						$amount1=0;
						$purchase=$_REQUEST["purchaseid_array"][$i];
						
						if($amount>0){
							$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$amount,'discount'=>$amount1,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
							print_r($arrValue);
							$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
						}
					}
				}
					
				$Msg='Record has been Added Sucessfully! ';
			break;
		
           
		  case"update":

			// ------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("cash_payment","id='".$_REQUEST['id']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
			$Updaterec=$utilObj->updateRecord('cash_payment', $strWhere, $arrValue);	
			
			$strWhere=" parent_id='".$_REQUEST['id']."' ";
			$Deleterec=$utilObj->deleteRecord('cash_payment_details', $strWhere);
			
			if($_REQUEST['type']=='Advanced')
				{
					if($_REQUEST['mode']=='cash'){
						$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
						$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
					}else{
						$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
						$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
					}
				}else{
					$cnt=$_REQUEST['cnt'];
			
					for($i=0;$i<$cnt;$i++){
						
						$amount=$_REQUEST["bank_array"][$i];
						// $amount1=$_REQUEST["bank1_array"][$i];
						$amount1=0;
						$purchase=$_REQUEST["purchaseid_array"][$i];
				
						if($amount>0){
							$arrValue=array('id'=>uniqid(),'parent_id'=>$paymentid,'ClientID'=>$_SESSION['Client_Id'],'purchaseid'=>$purchase,'amount'=>$amount,'discount'=>$amount1,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
							print_r($arrValue);
							$insertedId=$utilObj->insertRecord('cash_payment_details', $arrValue);
						}
					}
				}
            
            
				if($Updaterec)
				echo $Msg='Record has been Updated Sucessfully! ';
				                    
                                        
			break;	
			
			case "delete":
		         
                //echo ">>>>".$_REQUEST['sid'];		
				$mid=$_REQUEST['id'];
				$payment_record=mysqli_query($GLOBALS['con'],"Select * from cash_payment WHERE id ='".$_REQUEST['id']."'  ");
				$payment=mysqli_fetch_array($payment_record);
				$saleid= $payment['supplier'];	
				$mid=explode(',',$mid);
				foreach($mid as $ent)
				{
					$str.=" '".$ent."',";
				}
				$mid=trim($str,",");
   		        $strWhere=" ID IN ($mid)  ";
				$Deleterec=$utilObj->deleteRecord('cash_payment', $strWhere);		
				
				$strWhere=" parent_id IN ($mid) ";
				$Deleterec=$utilObj->deleteRecord('cash_payment_details', $strWhere);	
				
				if($Deleterec)
				echo $Msg='Record has been Deleted Sucessfully! ';	
               //echo "<script>window.top.location='purchase_history.php?id=".$saleid."&Task=history'</script>";
					
			break;
        
		}
		
		
		//echo "<script>window.top.location='purchase_payment.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
			
	
	}
?>