<?php
include '../config.php';
$utilObj = new util();

if (isset($_REQUEST['PTask'])) {
	switch ($_REQUEST['PTask']) {
		case "Add":

			// -------------------------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("delivery_return", "voucher_type='" . $_REQUEST['voucher_type'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$sreturn_code;
			$srno;

			if (date("m") > 3) {
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {
				$year_code = (date("y") - 1) . "-" . date("y");
			}


			if ($mate3['numbering_digit'] == 'Prefix') {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$sreturn_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
					$srno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$sreturn_code = $prefix_label . "/" . ($result['pono']) . "/" . $year_code;
					$srno = $result['pono'];
				}
			} else {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$sreturn_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
					$srno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$sreturn_code = $prefix_label . "/" . $year_code . "/" . ($result['pono']);
					$srno = $result['pono'];
				}
			}

			// -------------------------------------------------------------------------------------

			$id = $_REQUEST['common_id'];
			$arrValue = array('id' => $id, 'user' => $_SESSION['Ck_User_id'], 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'recordnumber' => $srno, 'sreturn_code' => $sreturn_code, 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'location' => $_REQUEST['location'], 'customer' => $_REQUEST['customer'], 'voucher_type' => $_REQUEST['voucher_type'], 'challan_no' => $_REQUEST['challan_no'], 'other' => $_REQUEST['other'], 'grandtotal' => $_REQUEST['grandtotal'], 'transcost' => $_REQUEST['transcost'], 'transgst' => $_REQUEST['transgst'], 'transamount' => $_REQUEST['transamount'], 'subt' => $_REQUEST['subt'], 'trans' => $_REQUEST['trans'], 'totcst_amt' => $_REQUEST['totcst_amt'], 'totsgst_amt' => $_REQUEST['totsgst_amt'], 'totigst_amt' => $_REQUEST['totigst_amt'], 'tcs_tds' => $_REQUEST['tcs_tds'], 'tcs_tds_percen' => $_REQUEST['tcs_tds_percen'], 'tcs_tds_amt' => $_REQUEST['tcs_tds_amt'], 'roff' => $_REQUEST['roff'], 'otrnar' => $_REQUEST['otrnar']);
			//print_r($arrValue);
			$insertedId = $utilObj->insertRecord('delivery_return', $arrValue);

			$cnt1 = $_REQUEST['cnt'];

			for ($i = 0; $i < $cnt1; $i++) {
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

				$id1 = uniqid();

				// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);

				$arrValue2 = array(
					'id' => $id1,
					'parent_id' => $id,
					'Created' => date("Y-m-d H:i:s"),
					'LastEdited' => date('Y-m-d H:i:s'),
					'ClientID' => $_SESSION['Client_Id'],
					'product' => $_REQUEST['product_array'][$i],
					'unit' => $_REQUEST['unit_array'][$i],
					'cgst' => $_REQUEST['cgst_array'][$i],
					'sgst' => $_REQUEST['sgst_array'][$i],
					'igst' => $_REQUEST['igst_array'][$i],
					'qty' => $_REQUEST['qty_array'][$i],
					'rate' => $_REQUEST['rate_array'][$i],
					'disc' => $_REQUEST['disc_array'][$i],
					'taxable' => $_REQUEST['taxable_array'][$i],
					'rejectedqty' => $_REQUEST['rejectedqty_array'][$i],
					'total' => $_REQUEST['total_array'][$i]
				);
				//print_r($arrValue2);
				$insertedId = $utilObj->insertRecord('delivery_return_details', $arrValue2);

			}


			$sale_batch = $utilObj->getMultipleRow("temp_sale_batch", "parent_id = '" . $id . "'");
			foreach ($sale_batch as $batch) {

				$array1 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['challan_no'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => $batch['type'], 'location' => $batch['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
				$insertedId = $utilObj->insertRecord('sale_batch', $array1);

				$strWhere = "parent_id='" . $batch['parent_id'] . "' ";
				$Deleterec = $utilObj->deleteRecord('temp_sale_batch', $strWhere);

				$purchase = $utilObj->getSingleRow("sale_batch", "id = '" . $batch['purchase_batch'] . "'");

				// $totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

				// if($totalstock == '0'){

				// $arrValue = array('flag'=>'1');
				// $strWhere="id='".$purchase['id']."'  ";
				// $Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);

				// }
			}
			if ($insertedId)
			$Msg = 'Record has been Added Sucessfully! ';

		break;


		case "update":

			// -------------------------------------------------------------------------------------

			$mate1=$utilObj->getSingleRow("sale_return","id='".$_REQUEST['id']."'");
			$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$sreturn_code;
			$srno;

			if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {
				
				if (date("m") > 3) {
					$year_code = date("y")."-".(date("y")+1);
				} 
				else {
					$year_code = (date("y")-1)."-".date("y");
				}
				

				if ($mate3['numbering_digit'] == 'Prefix') {
					
					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
					
						$sreturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
						$srno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$sreturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
						$srno = $result['pono'];
					}
				}
				else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);
			
						$sreturn_code = $prefix_label."/".$year_code."/".($formattedPono);
						$srno = $formattedPono;
					} 
					
					else {
						$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
						$result = mysqli_fetch_array($voucher_code);

						$sreturn_code = $prefix_label."/".$year_code."/".($result['pono']);
						$srno = $result['pono'];
					}
				}
			}
			else {
			
				$sreturn_code = $mate1['sreturn_code'];
				$srno = $mate1['recordnumber'];
			}
			// -------------------------------------------------------------------------

			$id = $_REQUEST['id'];

			if ($value > 0) {
				echo $Msg = "Concurrency Error Occured";
				break;
			}

			$arrValue = array('LastEdited' => date('Y-m-d H:i:s'), 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'customer' => $_REQUEST['customer'], 'location' => $_REQUEST['location'],'recordnumber' => $srno, 'sreturn_code' => $sreturn_code, 'challan_no' => $_REQUEST['challan_no'], 'other' => $_REQUEST['other'], 'grandtotal' => $_REQUEST['grandtotal'], 'transcost' => $_REQUEST['transcost'], 'transgst' => $_REQUEST['transgst'], 'transamount' => $_REQUEST['transamount'], 'subt' => $_REQUEST['subt'], 'trans' => $_REQUEST['trans'], 'totcst_amt' => $_REQUEST['totcst_amt'], 'totsgst_amt' => $_REQUEST['totsgst_amt'], 'totigst_amt' => $_REQUEST['totigst_amt'], 'tcs_tds' => $_REQUEST['tcs_tds'], 'tcs_tds_percen' => $_REQUEST['tcs_tds_percen'], 'tcs_tds_amt' => $_REQUEST['tcs_tds_amt'], 'roff' => $_REQUEST['roff'], 'otrnar' => $_REQUEST['otrnar'],'voucher_type' => $_REQUEST['voucher_type'] );

			$strWhere = "id='" . $id . "'  ";
			$Updaterec = $utilObj->updateRecord('delivery_return', $strWhere, $arrValue);

			$strWhere = "parent_id='" . $_REQUEST['id'] . "' ";
			$Deleterec = $utilObj->deleteRecord('delivery_return_details', $strWhere);

			$cnt1 = $_REQUEST['cnt'];

			for ($i = 0; $i < $cnt1; $i++) {
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

				$id1 = uniqid();

				$arrValue2 = array(
					'id' => $id1,
					'parent_id' => $id,
					'Created' => date("Y-m-d H:i:s"),
					'LastEdited' => date('Y-m-d H:i:s'),
					'ClientID' => $_SESSION['Client_Id'],
					'product' => $_REQUEST['product_array'][$i],
					'unit' => $_REQUEST['unit_array'][$i],
					'cgst' => $_REQUEST['cgst_array'][$i],
					'sgst' => $_REQUEST['sgst_array'][$i],
					'igst' => $_REQUEST['igst_array'][$i],
					'qty' => $_REQUEST['qty_array'][$i],
					'rate' => $_REQUEST['rate_array'][$i],
					'disc' => $_REQUEST['disc_array'][$i],
					'taxable' => $_REQUEST['taxable_array'][$i],
					'rejectedqty' => $_REQUEST['rejectedqty_array'][$i],
					'total' => $_REQUEST['total_array'][$i]
				);

				$insertedId = $utilObj->insertRecord('delivery_return_details', $arrValue2);

			}

			$salebatch = $utilObj->getSingleRow("temp_sale_batch", "parent_id = '" . $id . "'");
			if ($salebatch != '') {
				$strWhere1 = "delivery_id='" . $_REQUEST['id'] . "' AND product='" . $salebatch['product'] . "'";
				$Deleterec = $utilObj->deleteRecord('sale_batch', $strWhere1);
				$sale_batch = $utilObj->getMultipleRow("temp_sale_batch", "parent_id = '" . $id . "'");
				foreach ($sale_batch as $batch) {
					$purchase = $utilObj->getSingleRow("purchase_batch", "id = '" . $batch['purchase_batch'] . "'");

					// $totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					$delivery = $utilObj->getSingleRow("delivery_return", "id='" . $id . "'");

					$array1 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $id, 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => $batch['type'], 'location' => $batch['location'], 'date' => $delivery['date'], 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
					$insertedId = $utilObj->insertRecord('sale_batch', $array1);


					$strWhere = "parent_id='" . $batch['parent_id'] . "' ";
					$Deleterec = $utilObj->deleteRecord('temp_sale_batch', $strWhere);


					// if($totalstock == '0'){
					// $arrValue = array('flag'=>'1');
					// $strWhere="id='".$purchase['id']."'  ";
					// $Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
					// }else{
					// $arrValue = array('flag'=>'0');
					// $strWhere="id='".$purchase['id']."'  ";
					// $Updaterec=$utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
					// }
				}
			}


			if ($Updaterec)
				$Msg = 'Record has been Updated Sucessfully! ';
			break;


		case "delete":

			$pids = explode(",", $_REQUEST['id']);
			foreach ($pids as $pid) {
				$strWhere = "id='" . $pid . "' ";
				$Deleterec = $utilObj->deleteRecord('delivery_return', $strWhere);

				$strWhere = "parent_id='" . $pid . "' ";
				$Deleterec = $utilObj->deleteRecord('delivery_return_details', $strWhere);


				$strWhere = "delivery_id='" . $pid . "' ";
				$Deleterec = $utilObj->deleteRecord('sale_batch', $strWhere);
			}

			$Msg = 'Record has been Deleted Sucessfully! ';
			break;



	}
}
?>