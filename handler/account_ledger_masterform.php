<?php 
include '../config.php'; 
$utilObj=new util();
$output_dir="Upload/";

	if(isset($_REQUEST['PTask']))
	{
		switch($_REQUEST['PTask'])	
		{ 
			case "Add": 
				
				$id=uniqid(); 
			
				$arrValue=array('id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'group_name'=>$_REQUEST['group_name'],'actgrp'=>$_REQUEST['actgrp'],'name'=>$_REQUEST['name'],'interst'=>$_REQUEST['interst'],'credit_limit'=>$_REQUEST['credit_limit'],'credit_period'=>$_REQUEST['credit_period'],'price_level'=>$_REQUEST['price_level'],'price_level_id'=>$_REQUEST['price_level_group'],'inventory_allocation'=>$_REQUEST['inventory_allocation'],'cost_tracking'=>$_REQUEST['cost_tracking'],'opening_balance'=>$_REQUEST['opening_balance'],'op_balance'=>$_REQUEST['op_balance'],'op_method'=>$_REQUEST['op_method'],'mailing'=>$_REQUEST['mailing'],'bank_reconcilation'=>$_REQUEST['bank_reconcilation'],'bill_wise_details'=>$_REQUEST['bill_wise_details'],'cheque_book_registor'=>$_REQUEST['cheque_book_registor'],'cheque_book_printing'=>$_REQUEST['cheque_book_printing'],'tds_tax_details'=>$_REQUEST['tds_tax_details'],'linking_inventory'=>$_REQUEST['linking_inventory'],'gst_tax_allocation'=>$_REQUEST['gst_tax_allocation'],'mail_nameforprint'=>$_REQUEST['mail_nameforprint'],'mail_state'=>$_REQUEST['mail_state'],'mail_pin'=>$_REQUEST['mail_pin'],'mail_contactno1'=>$_REQUEST['mail_contactno1'],'mail_contactno2'=>$_REQUEST['mail_contactno2'],'mail_emailno'=>$_REQUEST['mail_emailno'],'mail_panno'=>$_REQUEST['mail_panno'],'mail_gstno'=>$_REQUEST['mail_gstno'],'mail_fassaino'=>$_REQUEST['mail_fassaino'],'tdstax_deductor'=>$_REQUEST['tdstax_deductor'],'tdstax_deducteetype'=>$_REQUEST['tdstax_deducteetype'],'tdstax_tds_deductionentry'=>$_REQUEST['tdstax_tds_deductionentry'],'gsttax_gst_applicable'=>$_REQUEST['gsttax_gst_applicable'],'gsttax_calculatefrom'=>$_REQUEST['gsttax_calculatefrom'],'description'=>$_REQUEST['description'],'hsn_sac'=>$_REQUEST['hsn_sac'],'cal_type'=>$_REQUEST['cal_type'],'taxability'=>$_REQUEST['taxability'],'rev_charge'=>$_REQUEST['rev_charge'],'ineligible_input'=>$_REQUEST['ineligible_input'],'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'cess'=>$_REQUEST['cess'],'gst_ledger_type'=>$_REQUEST['gst_ledger_type'],'gst_ledger_usage'=>$_REQUEST['gst_ledger_usage'],'gst_type'=>$_REQUEST['gst_type'],'bank_paydetails'=>$_REQUEST['bank_paydetails'],'bank_acc_no'=>$_REQUEST['bank_acc_no'],'bank_name'=>$_REQUEST['bank_name'],'ifsc'=>$_REQUEST['ifsc'],'branch_name'=>$_REQUEST['branch_name'],'upi_id'=>$_REQUEST['upi_id'],'upi_mob_no'=>$_REQUEST['upi_mob_no'] );

				$insertedId=$utilObj->insertRecord('account_ledger', $arrValue);
				
				$mail=$_REQUEST['mail_address'];
			    foreach($mail as $mail_add1){
					
					if($mail_add1!='0'){	
						$arrValue1=array('id'=>uniqid(),'al_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'address'=>$mail_add1);
						$insertedId1=$utilObj->insertRecord('account_ledger_address', $arrValue1);
					}
				} 
				//die;
				if($insertedId1)
				echo $Msg='Record has been Added Sucessfully! ';
			break;
			
			case "update": 
			 
				echo $Msg =$_REQUEST['LastEdited'].$_REQUEST['table'];//Concurrency Error Checking
				$id=$_REQUEST['id'];
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				}  
				
				$arrValue=array('ClientID'=>$_SESSION['Client_Id'],'LastEdited'=>date('Y-m-d H:i:s'),'group_name'=>$_REQUEST['group_name'],'actgrp'=>$_REQUEST['actgrp'],'name'=>$_REQUEST['name'],'interst'=>$_REQUEST['interst'],'credit_limit'=>$_REQUEST['credit_limit'],'credit_period'=>$_REQUEST['credit_period'],'price_level'=>$_REQUEST['price_level'],'price_level_id'=>$_REQUEST['price_level_group'],'inventory_allocation'=>$_REQUEST['inventory_allocation'],'cost_tracking'=>$_REQUEST['cost_tracking'],'opening_balance'=>$_REQUEST['opening_balance'],'op_balance'=>$_REQUEST['op_balance'],'op_method'=>$_REQUEST['op_method'],'mailing'=>$_REQUEST['mailing'],'bank_reconcilation'=>$_REQUEST['bank_reconcilation'],'bill_wise_details'=>$_REQUEST['bill_wise_details'],'cheque_book_registor'=>$_REQUEST['cheque_book_registor'],'cheque_book_printing'=>$_REQUEST['cheque_book_printing'],'tds_tax_details'=>$_REQUEST['tds_tax_details'],'linking_inventory'=>$_REQUEST['linking_inventory'],'gst_tax_allocation'=>$_REQUEST['gst_tax_allocation'],'mail_nameforprint'=>$_REQUEST['mail_nameforprint'],'mail_state'=>$_REQUEST['mail_state'],'mail_pin'=>$_REQUEST['mail_pin'],'mail_contactno1'=>$_REQUEST['mail_contactno1'],'mail_contactno2'=>$_REQUEST['mail_contactno2'],'mail_emailno'=>$_REQUEST['mail_emailno'],'mail_panno'=>$_REQUEST['mail_panno'],'mail_gstno'=>$_REQUEST['mail_gstno'],'mail_fassaino'=>$_REQUEST['mail_fassaino'],'tdstax_deductor'=>$_REQUEST['tdstax_deductor'],'tdstax_deducteetype'=>$_REQUEST['tdstax_deducteetype'],'tdstax_tds_deductionentry'=>$_REQUEST['tdstax_tds_deductionentry'],'gsttax_gst_applicable'=>$_REQUEST['gsttax_gst_applicable'],'gsttax_calculatefrom'=>$_REQUEST['gsttax_calculatefrom'],'description'=>$_REQUEST['description'],'hsn_sac'=>$_REQUEST['hsn_sac'],'cal_type'=>$_REQUEST['cal_type'],'taxability'=>$_REQUEST['taxability'],'rev_charge'=>$_REQUEST['rev_charge'],'ineligible_input'=>$_REQUEST['ineligible_input'],'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'cess'=>$_REQUEST['cess'],'gst_ledger_type'=>$_REQUEST['gst_ledger_type'],'gst_ledger_usage'=>$_REQUEST['gst_ledger_usage'],'gst_type'=>$_REQUEST['gst_type'],'bank_paydetails'=>$_REQUEST['bank_paydetails'],'bank_acc_no'=>$_REQUEST['bank_acc_no'],'bank_name'=>$_REQUEST['bank_name'],'ifsc'=>$_REQUEST['ifsc'],'branch_name'=>$_REQUEST['branch_name'],'upi_id'=>$_REQUEST['upi_id'],'upi_mob_no'=>$_REQUEST['upi_mob_no'] );	
				
				$strWhere="id='".$_REQUEST['id']."' ";
				$Updaterec=$utilObj->updateRecord('account_ledger', $strWhere, $arrValue); 
				
				$strWhere1="al_id='".$id."' ";
				$Deleterec=$utilObj->deleteRecord('account_ledger_address', $strWhere1);
				
				$mail=$_REQUEST['mail_address'];
				 
				//$mail_add=explode(",",$mail);
				
				foreach($mail as $mail_add1){
					
					if($mail_add1!='0'){	
						$arrValue1=array('id'=>uniqid(),'al_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'address'=>$mail_add1);
						$insertedId1=$utilObj->insertRecord('account_ledger_address', $arrValue1);
					}
				}
				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! '; 
			break;
			
			case"delete":	
			//echo "caswe_del->".$_REQUEST['id'];
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('account_ledger', $strWhere);
				
				$strWhere1="al_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('account_ledger_address', $strWhere1);
			}
			
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;

			//echo "<script>window.top.location='role_master_list.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
		}
	}
?>