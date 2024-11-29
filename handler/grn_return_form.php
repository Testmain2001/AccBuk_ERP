<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":

				// -------------------------------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("grn_return","voucher_type='".$_REQUEST['voucher_type']."'");
				$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

				$prefix_label = $mate3['prefix_label'];

				$year_code = "";
				$preturn_code;
				$prno;

				if (date("m") > 4) {
					$year_code = date("y")."-".(date("y")+1);
				} else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
						
						$preturn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$prno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$preturn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
						$prno = $result['pono']+1;
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);
			
						$preturn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$prno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$preturn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$prno = $result['pono']+1;
					}
				}

				// ------------------------------------------------------------------- 
				$ad = $_REQUEST['ad'];

				$id=uniqid();
				$arrValue=array('id'=>$ad,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$prno,'grnreturn_code'=>$preturn_code,'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'grn_order_no'=>$_REQUEST['grn_no'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
				// print_r($arrValue);
				$insertedId=$utilObj->insertRecord('grn_return',$arrValue);

				$cnt1=$_REQUEST['cnt'];
				
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$ad,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'return_qty'=>$_REQUEST['rejectedqty_array'][$i]);
					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('grn_return_details', $arrValue2);
				 
		        }

				$physical_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$ad."' ");

				foreach($physical_batch as $batch) {

					$array1=array('id'=>uniqid(),'parent_id'=>$batch['parent_id'],'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited'] );

					$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					if($totalstock == '0'){

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
				
				// -------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("grn_return","id='".$_REQUEST['id']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
					}
					else {
	
						if ($mate1['voucher_type'] != '') {
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
							$result = mysqli_fetch_array($voucher_code);
				
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
				
					$grn_code = $mate1['grnreturn_code'];
					$grno = $mate1['recordnumber'];
				}

				// -------------------------------------------------------------------------

				$id=$_REQUEST['id'];
				// echo $id; die;
				$_REQUEST['LastEdited']."hiii".$_REQUEST['table'];			//Concurrency Error Checking
				
				$value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					echo $Msg = "Concurrency Error Occured"; 
					break;
				} 

				$arrValue=array('ClientID'=>$_SESSION['Client_Id'],'LastEdited'=>date('Y-m-d H:i:s'),'recordnumber'=>$grno,'grnreturn_code'=>$grn_code,'voucher_type'=>$_REQUEST['voucher_type'],'grn_order_no'=>$_REQUEST['grn_no'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'location'=>$_REQUEST['location'] );
				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('grn_return', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('grn_return_details', $strWhere);
				
				echo $cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();

					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'return_qty'=>$_REQUEST['rejectedqty_array'][$i]);
					$insertedId=$utilObj->insertRecord('grn_return_details', $arrValue2);
					// print_r($arrValue2);
				}

				$salebatch=$utilObj->getSingleRow("temp_batch","parent_id = '".$id."'");
				if($salebatch!=''){

					$strWhere1="parent_id='".$id."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere1);

					$sale_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$id."'");
					
					foreach($sale_batch as $batch) {

						$delivery= $utilObj->getSingleRow("stock_journal","id='".$id."' ");

						$array1=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited']);
						print_r($array1);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);

						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

						$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

						$totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

						if($totalstock == '0'){
							$arrValue = array('flag'=>'1');
							$strWhere="id='".$purchase['id']."'";
							$Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
						} else {
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
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('grn_return', $strWhere);
				
				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('grn_return_details', $strWhere);


				$strWhere="parent_id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere);

				
			}
			
			echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
