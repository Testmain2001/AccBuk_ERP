<?php 
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
		
		switch($_REQUEST['PTask'])	
		{
        case "makepayment":		
		
		// --------------------------------------------------------------------------------

		$mate1=$utilObj->getSingleRow("cash_receipt","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";
		$saler_code;
		$srno;

		if (date("m") > 4) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				$saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
				$srno = $result['pono']+1;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
				$srno = $result['pono']+1;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				$saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
				$srno = $result['pono']+1;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
				$srno = $result['pono']+1;
			}
		}

		// --------------------------------------------------------------------------------
		$var=$_REQUEST['date'];
        $date = str_replace('/', '-', $var);
		$id=$_REQUEST['id'];
		
		
		$parent_id=uniqid();
		$arrValue=array('id'=>$parent_id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$srno,'saler_code'=>$saler_code,'voucher_type'=>$_REQUEST['voucher_type'],'location'=>$_REQUEST['location'],'customer'=>$_REQUEST['customer'],'ptype'=>$_REQUEST['type'], 'receiptdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'payment_method'=>$_REQUEST['mode'],'bankid'=>$_REQUEST['bankid'],'balance'=>$_REQUEST['balance'],'cheque_no'=>$_REQUEST['cheque_no'],'amt_pay'=>$_REQUEST['amt_pay'],'narration'=>$_REQUEST['narration'],'Type'=>'Payment');
		//var_dump($arrValue);die;
		print_r($arrValue);
	   	$insertedId=$utilObj->insertRecord('cash_receipt', $arrValue);
       
		   if($_REQUEST['type']=='Advanced')
			 {
					if($_REQUEST['mode']=='cash'){
					$arrValue=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
					$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
					}else{
						$arrValue=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
						$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
					}
			 }else{
				echo $cnt=$_REQUEST['cnt'];
			//echo $cnt; die();
				for($i=0;$i<$cnt;$i++){
					
					
				     $amount=$_REQUEST["bank_array"][$i];
					$amount1=$_REQUEST["bank1_array"][$i];
					$sale=$_REQUEST["saleid_array"][$i];
					
					if($amount>0){
						$arrValue=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$amount,'discount'=>$amount1,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
						print_r($arrValue);
						$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
					}
				 }
			 }
			 
		$Msg='Record has been Added Sucessfully! ';
		 break;
		
           
		  case"update":
		    
			// ------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("cash_receipt","id='".$_REQUEST['id']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$saler_code;
				$srno;

				if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
					
					if (date("m") > 4) {
						$year_code = date("y")."-".(date("y")+1);
					} 
					else {
						$year_code = (date("y")-1)."-".date("y");
					}
					
	
					if ($mate3['numbering_digit'] == 'Prefix') {
						
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
						
							$saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$srno = $result['pono']+1;
						// 	$grno = $_REQUEST['grn_no'];
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
							$srno = $result['pono']+1;
						}
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
				
							$saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$srno = $result['pono']+1;
						} 
						
						else {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
	
							$saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
							$srno = $result['pono']+1;
						}
					}
				}
				else {
				
					$saler_code = $mate1['saler_code'];
					$srno = $mate1['recordnumber'];
				}

			// -------------------------------------------------------------------------------------------

			//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
			 $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
			if($value>0)
			{
			echo $Msg = "Concurrency Error Occured"; 
				break;
			}
			
			$var=$_REQUEST['date'];
			$date = str_replace('/', '-', $var);
			$parent_id=$_REQUEST['id'];
			
			
		  $arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$srno,'saler_code'=>$saler_code,'voucher_type'=>$_REQUEST['voucher_type'],'location'=>$_REQUEST['location'],'customer'=>$_REQUEST['customer'],'ptype'=>$_REQUEST['type'], 'receiptdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'payment_method'=>$_REQUEST['mode'],'bankid'=>$_REQUEST['bankid'],'balance'=>$_REQUEST['balance'],'cheque_no'=>$_REQUEST['cheque_no'],'amt_pay'=>$_REQUEST['amt_pay'],'narration'=>$_REQUEST['narration'],'Type'=>'Payment');
			$strWhere=" id='".$_REQUEST['id']."' ";
			print_r($arrValue);
			$Updaterec=$utilObj->updateRecord('cash_receipt', $strWhere, $arrValue);	
			
			$strWhere=" parent_id='".$_REQUEST['id']."'";
			$Deleterec=$utilObj->deleteRecord('cash_receipt_details', $strWhere);
			
				if($_REQUEST['type']=='Advanced')
				 {
						if($_REQUEST['mode']=='cash'){
						$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
						$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
						}else{
							$arrValue=array('id'=>uniqid(),'parent_id'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$_REQUEST['amt_pay'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
							$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
						}
				 }else{
					 $cnt=$_REQUEST['cnt'];
				
					for($i=0;$i<$cnt;$i++){
						
						  $amount=$_REQUEST["bank_array"][$i];
					$amount1=$_REQUEST["bank1_array"][$i];
					$sale=$_REQUEST["saleid_array"][$i];
					
					if($amount>0){
						$arrValue=array('id'=>uniqid(),'parent_id'=>$parent_id,'ClientID'=>$_SESSION['Client_Id'],'saleid'=>$sale,'amount'=>$amount,'discount'=>$amount1,'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
						print_r($arrValue);
						$insertedId=$utilObj->insertRecord('cash_receipt_details', $arrValue);
					}
					 }
				 }
            
            
				if($Updaterec)
				echo $Msg='Record has been Updated Sucessfully! ';
				                    
                                        
			break;	
			
			 case "delete":
		         
                //echo ">>>>".$_REQUEST['sid'];		
				$mid=$_REQUEST['id'];
				$payment_record=mysqli_query($GLOBALS['con'],"Select * from cash_receipt WHERE id ='".$_REQUEST['id']."'  ");
				$payment=mysqli_fetch_array($payment_record);
				$saleid= $payment['supplier'];	
				$mid=explode(',',$mid);
				foreach($mid as $ent)
				{
					$str.="'".$ent."',";
				}
				$mid=trim($str,",");
   		        $strWhere=" ID IN ($mid)";
				$Deleterec=$utilObj->deleteRecord('cash_receipt', $strWhere);		
				
				 $strWhere=" parent_id IN ($mid)";
				$Deleterec=$utilObj->deleteRecord('cash_receipt_details', $strWhere);	
				
				if($Deleterec)
				echo $Msg='Record has been Deleted Sucessfully! ';	
               	// echo "<script>window.top.location='purchase_history.php?id=".$saleid."&Task=history'</script>";
					
			break;
        
		}
		
		
		// echo "<script>window.top.location='purchase_payment.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
			
	
	}
?>