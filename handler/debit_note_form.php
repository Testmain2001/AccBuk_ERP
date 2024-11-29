<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":

				// ---------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("debitnote_acc","voucher_type='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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

				// --------------------------------------------------------------------------- 
				$ad = uniqid();

				$id=uniqid();
				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$prno,'voucher_code'=>$preturn_code,'voucher_type'=>$_REQUEST['voucher_type'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'pos_state'=>$_REQUEST['pos_state'],'cgst_ledger'=>$_REQUEST['cgst_ledger'],'sgst_ledger'=>$_REQUEST['sgst_ledger'],'igst_ledger'=>$_REQUEST['igst_ledger'],'cgst_amt'=>$_REQUEST['cgst_amt'],'sgst_amt'=>$_REQUEST['sgst_amt'],'igst_amt'=>$_REQUEST['igst_amt'],'gst_subtotal'=>$_REQUEST['gst_subtotal'],'grandtotal'=>$_REQUEST['grandtotal'],'record'=>'Dr' );

				// print_r($arrValue);
				$insertedId=$utilObj->insertRecord('debitnote_acc',$arrValue);

				$cnt1=$_REQUEST['cnt1'];
				
				for($i=0;$i<$cnt1;$i++) {
					
					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'service_ledger'=>$_REQUEST['service_ledger_array'][$i],'service_amt'=>$_REQUEST['service_amt_array'][$i],'service_subtotal'=>$_REQUEST['service_subtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
					// print_r($arrValue2);

					$insertedId=$utilObj->insertRecord('debitnote_acc_details', $arrValue2);
		        }

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
				
			break;


			case "update":
				
				// ---------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("debitnote_acc","id='".$_REQUEST['id']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);

							$val = $result['pono']+1;
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
				}
				else {
				
					$grn_code = $mate1['voucher_code'];
					$grno = $mate1['recordnumber'];
				}

				// ---------------------------------------------------------------------------

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
				$Updaterec=$utilObj->updateRecord('debitnote_acc', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('debitnote_acc_details', $strWhere);
				
				echo $cnt1=$_REQUEST['cnt1'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();

					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'service_ledger'=>$_REQUEST['service_ledger_array'][$i],'service_amt'=>$_REQUEST['service_amt_array'][$i],'service_subtotal'=>$_REQUEST['service_subtotal'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'record'=>'Cr' );
					$insertedId=$utilObj->insertRecord('debitnote_acc_details', $arrValue2);
					// print_r($arrValue2);
				}

				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully!'; 				
			break;	

	
			case"delete":
		
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('debitnote_acc', $strWhere);
					
					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('debitnote_acc_details', $strWhere);
					
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;
			
		}	
	}
?>
