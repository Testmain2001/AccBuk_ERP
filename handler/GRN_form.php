<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":

				// -------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("grn","voucher_type='".$_REQUEST['voucher_type']."'");
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
						
						$grn_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$grno = $result['pono']+1;
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
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$grn_code = $prefix_label."/".$year_code."/".($formattedPono);
						$grno = $result['pono']+1;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$grn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
						$grno = $result['pono']+1;
					}
				}

				// ------------------------------------------------------------------- 

				$id=uniqid();
				$arrValue=array('id'=>$_REQUEST['ad'],'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION		['Client_Id'],'Created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'grn_no'=>$grno,'grn_code'=>$grn_code,'type'=>$_REQUEST['type'],'location'=>$_REQUEST['location'],'voucher_type'=>$_REQUEST['voucher_type'],'purchaseorder_no'=>$_REQUEST['mpids'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'multipid'=>$_REQUEST['mpids'] );

				// print_r($arrValue);
				$insertedId=$utilObj->insertRecord('grn',$arrValue);

				$cnt1=$_REQUEST['cnt'];
				
	
				for($i=0;$i<$cnt1;$i++)
				{
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					// echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
				 	// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$_REQUEST['ad'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i] );

					// print_r($arrValue2);
					$insertedId=$utilObj->insertRecord('grn_details', $arrValue2);

					// ----------------------------------------------------------------------------

					$product=$utilObj->getSingleRow("purchase_order_details"," product='".$_REQUEST['product_array'][$i]."' AND parent_id='".$_REQUEST['purchaseorder_no']."'");

					floatval($product['rm_qty']); 
					$rm_qty = floatval($product['rm_qty'])-floatval($_REQUEST['qty_array'][$i]);

					$strWherep='';
					$strWherep="product='".$_REQUEST['product_array'][$i]."'";
					$arrValuep=array('rm_qty'=>$rm_qty );
					$Updaterec=$utilObj->updateRecord('purchase_order_details', $strWherep, $arrValuep);

		        }

				$dqty=$utilObj->getSum("grn_details","parent_id in(select id from grn where purchaseorder_no='".$_REQUEST['purchaseorder_no']."') ","qty");	

				$sqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where id='".$_REQUEST['purchaseorder_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['purchaseorder_no']."'";
					$arrValuef=array('flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('purchase_order', $strWheref, $arrValuef);
				}

				// ----------------------------------------------------------------------------
				$physical_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$_REQUEST['ad']."' ");
				foreach($physical_batch as $batch1) {

					$arrValue3=array('id'=>uniqid(),'product'=>$batch1['product'],'bat_rate'=>$batch1['bat_rate'],'parent_id'=>$batch1['parent_id'],'ClientID'=>$_SESSION['Client_Id'],'type'=>$batch1['type'],'batchname'=>$batch1['batchname'],'batqty'=>$batch1['quantity'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'location'=>$batch1['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));

					$insertedId1=$utilObj->insertRecord('purchase_batch', $arrValue3);

					$strWhere="parent_id='".$batch1['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

				}

				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
				
			break;


			case "update":
				
				// -------------------------------------------------------------------------

				$mate1=$utilObj->getSingleRow("grn","id='".$_REQUEST['id']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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
							$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
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

					$grn_code = $mate1['grn_code'];
					$grno = $mate1['grn_no'];
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
					
				$arrValue=array('ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'grn_no'=>$grno,'grn_code'=>$grn_code,'type'=>$_REQUEST['type'],'voucher_type'=>$_REQUEST['voucher_type'],'purchaseorder_no'=>$_REQUEST['purchaseorder_no'],'supplier'=>$_REQUEST['supplier'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'multipid'=>$_REQUEST['mpids'] );
				// print_r($arrValue);

				$strWhere="id='".$_REQUEST['id']."' ";
				$Updaterec=$utilObj->updateRecord('grn', $strWhere, $arrValue);
				
				$strWhere="parent_id='".$_REQUEST['id']."' ";
				$Deleterec=$utilObj->deleteRecord('grn_details', $strWhere);
				
				echo $cnt1=$_REQUEST['cnt'];
	
				for($i=0;$i<$cnt1;$i++)
				{
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
					
					$id1=uniqid();
					
					$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rate'=>$_REQUEST['rate_array'][$i]);
					$insertedId=$utilObj->insertRecord('grn_details', $arrValue2);
					// print_r($arrValue2);
				}

				$dqty=$utilObj->getSum("grn_details","parent_id in(select id from grn where purchaseorder_no='".$_REQUEST['purchaseorder_no']."') ","qty");	

				$sqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where id='".$_REQUEST['purchaseorder_no']."') ","qty");

				$remain_qty=$sqty-$dqty;

				if ($remain_qty=="0") {

					$requi_flag="1";
					$strWheref="id='".$_REQUEST['purchaseorder_no']."'";
					$arrValuef=array('flag'=>$requi_flag );
					$Updatere=$utilObj->updateRecord('purchase_order', $strWheref, $arrValuef);
				}
				
				// ------------------------------------------------------------------------------
				$grnbatch=$utilObj->getSingleRow("temp_batch","parent_id = '".$id."'");
				if($grnbatch!=''){

					$strWhere1="parent_id='".$id."' AND product='".$grnbatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere1);

					$grn_batch=$utilObj->getMultipleRow("temp_batch","parent_id = '".$id."'");
					foreach($grn_batch as $batch) {

						$array1=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$batch['ClientID'],'product'=>$batch['product'],'type'=>$batch['type'],'location'=>$batch['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['CreatedAt'],'LastEdited'=>$batch['LastEdited']);

						print_r($array1);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);
						
						$strWhere="parent_id='".$batch['parent_id']."' ";
						$Deleterec=$utilObj->deleteRecord('temp_batch', $strWhere);

					}

				}
				if($Updaterec)
				echo $Msg='Record has been Updated Sucessfully!';

			break;	

	
			case"delete":
			
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{

					$mate1=$utilObj->getSingleRow("grn","id='".$pid."' ");

					if($mate1['type']=='Against_Purchaseorder') {

						$requi_flag="0";
						$strWheref="id='".$mate1['purchaseorder_no']."'";
						$arrValuef=array('flag'=>$requi_flag );
						$Updatere=$utilObj->updateRecord('purchase_order', $strWheref, $arrValuef);
					}

					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('grn', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('grn_details', $strWhere);

					$strWhere="parent_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('purchase_batch', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully!';
			break;


			
		}	
	}
?>
