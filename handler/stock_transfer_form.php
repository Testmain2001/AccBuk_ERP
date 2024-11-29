<?php
include '../config.php';
$utilObj = new util();

if (isset($_REQUEST['PTask'])) {
	switch ($_REQUEST['PTask']) {

		case "Add":

			// -------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("stock_transfer", "voucher_type='" . $_REQUEST['voucher_type'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$stockt_code;
			$stno;

			if (date("m") > 3) {
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {
				$year_code = (date("y") - 1) . "-" . date("y");
			}

			if ($mate3['numbering_digit'] == 'Prefix') {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
					$stno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . ($result['pono'] + 1) . "/" . $year_code;
					$stno = $result['pono'] + 1;
				}
			} else {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
					$stno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($result['pono'] + 1);
					$stno = $result['pono'] + 1;
				}
			}

			// -------------------------------------------------------------------

			$id = $_REQUEST['common_id'];
			$arrValue = array('id' => $id, 'user' => $_SESSION['Ck_User_id'], 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'record_no' => $stno, 'stockt_code' => $stockt_code, 'location' => $_REQUEST['location'], 'voucher_type' => $_REQUEST['voucher_type'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])));
			// echo "hiii";
			// print_r($arrValue);
			$insertedId = $utilObj->insertRecord('stock_transfer', $arrValue);

			$cnt1 = $_REQUEST['cnt'];
			for ($i = 0; $i < $cnt1; $i++) {
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				if ($_REQUEST['tostock_array'][$i] != 0) {
					$id1 = uniqid();
					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					$arrValue2 = array('id' => $id1, 'parent_id' => $id, 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'product' => $_REQUEST['product_array'][$i], 'unit' => $_REQUEST['unit_array'][$i], 'fromstock' => $_REQUEST['fromstock_array'][$i], 'tostock' => $_REQUEST['tostock_array'][$i], 'location' => $_REQUEST['location_array'][$i]);

					// print_r($arrValue2);
					$insertedId = $utilObj->insertRecord('stock_transfer_details', $arrValue2);
				}
			}

			$sale_batch = $utilObj->getMultipleRow("temp_sale_batch", "parent_id = '" . $id . "'");
			foreach ($sale_batch as $batch) {
				if($batch['quantity']>0){
					$array1 = array('id' => uniqid(), 'parent_id' => $id, 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'],'bat_rate' => $batch['bat_rate'], 'type' => 'transfer_batch_in', 'location' => $batch['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'batqty' => $batch['quantity'], 'CreatedAt' => $batch['created'], 'LastEdited' => $batch['lastedited']);
					$insertedId = $utilObj->insertRecord('purchase_batch', $array1);
				}

				$array2 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['id'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'bat_rate' => $batch['bat_rate'],'type' => 'transfer_batch_out', 'location' => $_REQUEST['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
				$insertedId = $utilObj->insertRecord('sale_batch', $array2);

				$strWhere = "parent_id='" . $batch['parent_id'] . "' ";
				$Deleterec = $utilObj->deleteRecord('temp_sale_batch', $strWhere);

				$purchase = $utilObj->getSingleRow("sale_batch", "id = '" . $batch['purchase_batch'] . "'");

				$totalstock = getbatchstock($purchase['id'], $purchase['product'], date('Y-m-d'), $purchase['location']);

				if ($totalstock == '0') {

					$arrValue = array('flag' => '1');
					$strWhere = "id='" . $purchase['id'] . "'  ";
					$Updaterec = $utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
				}
			}
			if ($insertedId)
			echo $Msg = 'Record has been Added Sucessfully! ';
		break;

		case "receive":

			// -------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("stock_transfer", "voucher_type='" . $_REQUEST['voucher_type'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$stockt_code;
			$stno;

			if (date("m") > 3) {
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {
				$year_code = (date("y") - 1) . "-" . date("y");
			}


			if ($mate3['numbering_digit'] == 'Prefix') {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
					$stno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . ($result['pono'] + 1) . "/" . $year_code;
					$stno = $result['pono'] + 1;
				}
			} else {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
					$stno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($result['pono'] + 1);
					$stno = $result['pono'] + 1;
				}
			}

			// -------------------------------------------------------------------

			$id = $_REQUEST['common_id'];
			
			$arrValue = array('id' => $id, 'request_id' => $_REQUEST['id'], 'user' => $_SESSION['Ck_User_id'], 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'record_no' => $stno, 'stockt_code' => $stockt_code, 'location' => $_REQUEST['location'], 'voucher_type' => $_REQUEST['voucher_type'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])));
			//print_r($arrValue);

			$insertedId = $utilObj->insertRecord('stock_transfer', $arrValue);

			
			$cnt1 = $_REQUEST['cnt'];
			for ($i = 0; $i < $cnt1; $i++) {

				$stockrequest=$utilObj->getSingleRow("stock_request","id='".$_REQUEST['id']."'");
				$stockdetail=$utilObj->getMultipleRow("stock_request_details","parent_id='".$_REQUEST['id']."'");
				foreach($stockdetail as $detail) {

					if($detail['tostock']==$_REQUEST['tostock_array'][$i]){
						$flag='1';
					}
				}

				$id1 = uniqid();

				$arrValue2 = array('id' => $id1, 'parent_id' => $id, 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'product' => $_REQUEST['product_array'][$i], 'flag'=>$flag,'unit' => $_REQUEST['unit_array'][$i], 'fromstock' => $_REQUEST['fromstock_array'][$i], 'requested_qty'=>$_REQUEST['requestqty_array'][$i], 'tostock' => $_REQUEST['tostock_array'][$i], 'location' => $_REQUEST['location_array'][$i]);
				// print_r($arrValue2);

				$insertedId = $utilObj->insertRecord('stock_transfer_details', $arrValue2);
				
			}

			$sale_batch = $utilObj->getMultipleRow("temp_sale_batch", "parent_id = '" . $id . "'");
			foreach ($sale_batch as $batch) {

				if($batch['quantity']>0){

					$array1 = array('id' => uniqid(), 'parent_id' => $id, 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => 'transfer_batch_in', 'location' => $batch['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'batqty' => $batch['quantity'], 'CreatedAt' => $batch['created'], 'LastEdited' => $batch['lastedited']);
					$insertedId = $utilObj->insertRecord('purchase_batch', $array1);
				}

				$array2 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['id'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => 'transfer_batch_out', 'location' => $_REQUEST['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
				$insertedId = $utilObj->insertRecord('sale_batch', $array2);

				$strWhere = "parent_id='" . $batch['parent_id'] . "' ";
				$Deleterec = $utilObj->deleteRecord('temp_sale_batch', $strWhere);

				$purchase = $utilObj->getSingleRow("sale_batch", "id = '" . $batch['purchase_batch'] . "'");

				$totalstock = getbatchstock($purchase['id'], $purchase['product'], date('Y-m-d'), $purchase['location']);

				if ($totalstock == '0') {

					$arrValue = array('flag' => '1');
					$strWhere = "id='" . $purchase['id'] . "'  ";
					$Updaterec = $utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
				}
			}

			if ($insertedId)
			echo $Msg = 'Record has been Added Sucessfully! ';

		break;

		case "send":

			// -------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("stock_transfer", "voucher_type='" . $_REQUEST['voucher_type'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$stockt_code;
			$stno;

			if (date("m") > 3) {
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {
				$year_code = (date("y") - 1) . "-" . date("y");
			}


			if ($mate3['numbering_digit'] == 'Prefix') {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
					$stno = $formattedPono;
				} else {

					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . ($result['pono'] + 1) . "/" . $year_code;
					$stno = $result['pono'] + 1;
				}
			} else {

				if ($mate1['voucher_type'] != '') {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$val = $result['pono']+1;
					$formattedPono = sprintf('%0' . $width . 'd', $val);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
					$stno = $formattedPono;
				} else {
					$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
					$result = mysqli_fetch_array($voucher_code);

					$stockt_code = $prefix_label . "/" . $year_code . "/" . ($result['pono'] + 1);
					$stno = $result['pono'] + 1;
				}
			}

			// -------------------------------------------------------------------

			$id = $_REQUEST['common_id'];

			$arrValue = array('id' => $id, 'request_id' => $_REQUEST['request_id'], 'user' => $_SESSION['Ck_User_id'], 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'record_no' => $stno, 'stockt_code' => $stockt_code, 'location' => $_REQUEST['location'], 'voucher_type' => $_REQUEST['voucher_type'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])) );
			// print_r($arrValue);

			$insertedId = $utilObj->insertRecord('stock_transfer', $arrValue);

			$cnt1 = $_REQUEST['cnt'];

			for($i = 0; $i < $cnt1; $i++) {

				$id1 = uniqid();

				$arrValue2 = array('id' => $id1, 'parent_id' => $id, 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'product' => $_REQUEST['product_array'][$i], 'unit' => $_REQUEST['unit_array'][$i], 'fromstock' => $_REQUEST['fromstock_array'][$i], 'requested_qty'=>$_REQUEST['requestqty_array'][$i], 'tostock' => $_REQUEST['tostock_array'][$i], 'location' => $_REQUEST['location_array'][$i]);
				// print_r($arrValue2);

				$insertedId = $utilObj->insertRecord('stock_transfer_details', $arrValue2);

				$streq = $utilObj->getSingleRow("production_requisition_details", "parent_id='".$_REQUEST['request_id']."' AND product='".$_REQUEST['product_array'][$i]."' ");
				$qty=$utilObj->getSum("stock_transfer_details","parent_id in(select id from stock_transfer where request_id='".$_REQUEST['request_id']."') AND product='".$_REQUEST['product_array'][$i]."' ","tostock");

				$tostock = $streq['qty'] - $qty;

				if($tostock==0) {

					$arrValuest = array('flag'=>'1');

					$strWherest = "id='".$streq['id']."' ";
					$Updaterec = $utilObj->updateRecord('production_requisition_details', $strWherest, $arrValuest);
				}
			}

			
			if(empty($stock_requisition)) {

				// echo "success";
				$arrValue = array('requi_flag'=>'1', 'close_date'=>date('Y-m-d', strtotime($_REQUEST['date'])) );

				$strWhere = "id='".$_REQUEST['request_id']."' ";
				$Updaterec = $utilObj->updateRecord('production_requisition', $strWhere, $arrValue);
			}

			$sale_batch = $utilObj->getMultipleRow("temp_sale_batch", "parent_id = '".$id."' ");
			foreach ($sale_batch as $batch) {

				if($batch['quantity']>0) {

					$array1 = array('id' => uniqid(), 'parent_id' => $id, 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => 'transfer_batch_in', 'location' => $batch['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'batqty' => $batch['quantity'], 'CreatedAt' => $batch['created'], 'LastEdited' => $batch['lastedited']);
					$insertedId = $utilObj->insertRecord('purchase_batch', $array1);
				}

				$array2 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['id'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' => 'transfer_batch_out', 'location' => $_REQUEST['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
				$insertedId = $utilObj->insertRecord('sale_batch', $array2);

				$strWhere = "parent_id='" . $batch['parent_id'] . "' ";
				$Deleterec = $utilObj->deleteRecord('temp_sale_batch', $strWhere);

				$purchase = $utilObj->getSingleRow("sale_batch", "id = '" . $batch['purchase_batch'] . "'");

				$totalstock = getbatchstock($purchase['id'], $purchase['product'], date('Y-m-d'), $purchase['location']);

				if ($totalstock == '0') {

					$arrValue = array('flag' => '1');
					$strWhere = "id='" . $purchase['id'] . "'  ";
					$Updaterec = $utilObj->updateRecord('purchase_batch', $strWhere, $arrValue);
				}
			}

			if ($insertedId)
			echo $Msg = 'Record has been Added Sucessfully! ';

		break;


		case "update":

			// -------------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("stock_transfer", "id='" . $_REQUEST['id'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$stockt_code;
			$stno;

			if (date("m") > 3) {
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {
				$year_code = (date("y") - 1) . "-" . date("y");
			}

			if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {

				if ($mate3['numbering_digit'] == 'Prefix') {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$stockt_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
						$stno = $formattedPono;
					} else {

						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$stockt_code = $prefix_label . "/" . ($result['pono'] + 1) . "/" . $year_code;
						$stno = $result['pono'] + 1;
					}
				} else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$stockt_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
						$stno = $formattedPono;
					} else {

						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$stockt_code = $prefix_label . "/" . $year_code . "/" . ($result['pono'] + 1);
						$stno = $result['pono'] + 1;
					}
				}
			} else {

				$stockt_code = $mate1['stockt_code'];
				$stno = $mate1['record_no'];

			}


			// -------------------------------------------------------------------------

			$id = $_REQUEST['id'];
			
			$value = concurrencycontrol($utilObj, $_REQUEST['table'], $_REQUEST['LastEdited']);
			if ($value > 0) {
				echo $Msg = "Concurrency Error Occured";
				break;
			}

			$arrValue = array('LastEdited' => date('Y-m-d H:i:s'), 'record_no' => $stno, 'stockt_code' => $stockt_code, 'location' => $_REQUEST['location'], 'voucher_type' => $_REQUEST['voucher_type'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])));
			//print_r($arrValue);
			$strWhere = "id='" . $_REQUEST['id'] . "'  ";
			$Updaterec = $utilObj->updateRecord('stock_transfer', $strWhere, $arrValue);

			$strWhere = "parent_id='" . $_REQUEST['id'] . "' ";
			$Deleterec = $utilObj->deleteRecord('stock_transfer_details', $strWhere);

			$cnt1 = $_REQUEST['cnt'];
			for ($i = 0; $i < $cnt1; $i++) {
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				if ($_REQUEST['tostock_array'][$i] != 0) {
					$id1 = uniqid();
					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					$arrValue2 = array('id' => $id1, 'parent_id' => $id, 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'product' => $_REQUEST['product_array'][$i], 'unit' => $_REQUEST['unit_array'][$i], 'fromstock' => $_REQUEST['fromstock_array'][$i], 'requested_qty'=>$_REQUEST['requestqty_array'][$i],'tostock' => $_REQUEST['tostock_array'][$i], 'location' => $_REQUEST['location_array'][$i]);
					print_r($arrValue2);
					$insertedId = $utilObj->insertRecord('stock_transfer_details', $arrValue2);
				}
			}

			$salebatch=$utilObj->getSingleRow("temp_sale_batch","parent_id = '".$id."'");
				if($salebatch!=''){
					$strWhere1="delivery_id='".$_REQUEST['id']."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);
				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");
				foreach($sale_batch as $batch){
					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					// $totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					$stock= $utilObj->getSingleRow("stock_transfer_details","parent_id='".$id."'");
					if($batch['quantity']>0){
						$array1=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'bat_rate' => $batch['bat_rate'],'type'=>'transfer_batch_in','location'=>$stock['location'],'date'=>date('Y-m-d', strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['created'],'LastEdited'=>$batch['lastedited']);

						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);
					}

					$array2 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['id'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'],'bat_rate' => $batch['bat_rate'], 'type' =>'transfer_batch_out', 'location' => $_REQUEST['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
					$insertedId = $utilObj->insertRecord('sale_batch', $array2);

					
					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_sale_batch', $strWhere);
				}
			}
			if ($Updaterec)
			echo $Msg = 'Record has been Updated Sucessfully! ';
		break;

		case "reset":

			// -------------------------------------------------------------------------

			$mate1 = $utilObj->getSingleRow("stock_transfer", "id='" . $_REQUEST['id'] . "'");
			$mate3 = $utilObj->getSingleRow("voucher_type", "id='" . $_REQUEST['voucher_type'] . "'");

			$prefix_label = $mate3['prefix_label'];
			$width = $mate3['codewidth'];

			$year_code = "";
			$stockt_code;
			$stno;

			
			if (date("m") > 3) {
				
				$year_code = date("y") . "-" . (date("y") + 1);
			} else {

				$year_code = (date("y") - 1) . "-" . date("y");
			}

			if ($mate1['voucher_type'] != $_REQUEST['voucher_type']) {

				if ($mate3['numbering_digit'] == 'Prefix') {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);
						
						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$stockt_code = $prefix_label . "/" . ($formattedPono) . "/" . $year_code;
						$stno = $formattedPono;
					} else {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$stockt_code = $prefix_label . "/" . ($result['pono'] + 1) . "/" . $year_code;
						$stno = $result['pono'] + 1;
					}
				} else {

					if ($mate1['voucher_type'] != '') {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);
						
						$val = $result['pono']+1;
						$formattedPono = sprintf('%0' . $width . 'd', $val);

						$stockt_code = $prefix_label . "/" . $year_code . "/" . ($formattedPono);
						$stno = $formattedPono;
					} else {
						$voucher_code = mysqli_query($GLOBALS['con'], "Select MAX(numbering_code) AS pono from voucher_type WHERE id ='" . $_REQUEST['voucher_type'] . "'");
						$result = mysqli_fetch_array($voucher_code);

						$stockt_code = $prefix_label . "/" . $year_code . "/" . ($result['pono'] + 1);
						$stno = $result['pono'] + 1;
					}
				}
			} else {

				$stockt_code = $mate1['stockt_code'];
				$stno = $mate1['record_no'];

			}


			// -------------------------------------------------------------------------

			$id = $_REQUEST['id'];
			
			$value = concurrencycontrol($utilObj, $_REQUEST['table'], $_REQUEST['LastEdited']);
			if ($value > 0) {
				echo $Msg = "Concurrency Error Occured";
				break;
			}

			$arrValue = array('LastEdited' => date('Y-m-d H:i:s'), 'record_no' => $stno, 'stockt_code' => $stockt_code, 'location' => $_REQUEST['location'], 'voucher_type' => $_REQUEST['voucher_type'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])));
			// print_r($arrValue);
			$strWhere = "id='" . $_REQUEST['id'] . "'  ";
			$Updaterec = $utilObj->updateRecord('stock_transfer', $strWhere, $arrValue);

			$strWhere = "parent_id='" . $_REQUEST['id'] . "' ";
			$Deleterec = $utilObj->deleteRecord('stock_transfer_details', $strWhere);

			$cnt1 = $_REQUEST['cnt'];
			for ($i = 0; $i < $cnt1; $i++) {
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
				
					$id1 = uniqid();
					// print_r( $_REQUEST['unit_array'][$i]."=".$_REQUEST['qty_array'][$i]."=".$_REQUEST['product_array'][$i]);
					$arrValue2 = array('id' => $id1, 'parent_id' => $id, 'ClientID' => $_SESSION['Client_Id'], 'Created' => date("Y-m-d H:i:s"), 'LastEdited' => date('Y-m-d H:i:s'), 'product' => $_REQUEST['product_array'][$i], 'unit' => $_REQUEST['unit_array'][$i], 'fromstock' => $_REQUEST['fromstock_array'][$i], 'tostock' => $_REQUEST['tostock_array'][$i], 'location' => $_REQUEST['location_array'][$i]);
					print_r($arrValue2);
					$insertedId = $utilObj->insertRecord('stock_transfer_details', $arrValue2);
				
			}

			$salebatch=$utilObj->getSingleRow("temp_sale_batch","parent_id = '".$id."'");
				if($salebatch!=''){
					$strWhere1="delivery_id='".$_REQUEST['id']."' AND product='".$salebatch['product']."'";
					$Deleterec=$utilObj->deleteRecord('sale_batch', $strWhere1);
				$sale_batch=$utilObj->getMultipleRow("temp_sale_batch","parent_id = '".$id."'");
				foreach($sale_batch as $batch){
					$purchase=$utilObj->getSingleRow("purchase_batch","id = '".$batch['purchase_batch']."'");

					// $totalstock = getbatchstock($purchase['id'],$purchase['product'], date('Y-m-d'), $purchase['location']);

					$stock= $utilObj->getSingleRow("stock_transfer_details","parent_id='".$id."'");
					if($batch['quantity']>0){
						$array1=array('id'=>uniqid(),'parent_id'=>$id,'ClientID'=>$batch['ClientID'],'purchase_batch'=>$batch['purchase_batch'],'product'=>$batch['product'],'type'=>'transfer_batch_in','location'=>$stock['location'],'date'=>date('Y-m-d', strtotime($_REQUEST['date'])),'batchname'=>$batch['batchname'],'batqty'=>$batch['quantity'],'CreatedAt'=>$batch['created'],'LastEdited'=>$batch['lastedited']);
						$insertedId=$utilObj->insertRecord('purchase_batch', $array1);
					}

						$array2 = array('id' => uniqid(), 'delivery_id' => $id, 'sale_invoice_no' => $_REQUEST['id'], 'ClientID' => $batch['ClientID'], 'purchase_batch' => $batch['purchase_batch'], 'product' => $batch['product'], 'type' =>'transfer_batch_out', 'location' => $_REQUEST['location'], 'date' => date('Y-m-d', strtotime($_REQUEST['date'])), 'batchname' => $batch['batchname'], 'quantity' => $batch['quantity'], 'created' => $batch['created'], 'lastedited' => $batch['lastedited']);
						$insertedId = $utilObj->insertRecord('sale_batch', $array2);

					
					$strWhere="parent_id='".$batch['parent_id']."' ";
					$Deleterec=$utilObj->deleteRecord('temp_sale_batch', $strWhere);
				}
			}

			if ($Updaterec)
			echo $Msg = 'Record has been Updated Sucessfully! ';

		break;

		case "delete":

			$pids = explode(",", $_REQUEST['id']);
			foreach ($pids as $pid) {

				$data=$utilObj->getMultipleRow("stock_transfer_details","parent_id ='".$pid."' ");
				$mate1=$utilObj->getSingleRow("stock_transfer","id='".$pid."' ");

				$strWhere = "id='".$pid."' ";
				$Deleterec = $utilObj->deleteRecord('stock_transfer', $strWhere);

				$strWhere = "parent_id='".$pid."' ";
				$Deleterec = $utilObj->deleteRecord('stock_transfer_details', $strWhere);

				$strWhere = "delivery_id='".$pid."' ";
				$Deleterec = $utilObj->deleteRecord('sale_batch', $strWhere);

				$strWhere = "parent_id='".$pid."' ";
				$Deleterec = $utilObj->deleteRecord('purchase_batch', $strWhere);

				foreach($data as $info) {

					$flag = 0;
					$reason = '';
					$close_date = '0001-01-01';

					$strWhere=" parent_id='".$mate1["request_id"]."' AND product='".$info['product']."' ";
					$arrValue = array('flag'=>$flag, 'reason'=>$reason );
					$Updaterec=$utilObj->updateRecord('production_requisition_details', $strWhere, $arrValue);

					$strWhere=" id='".$mate1["request_id"]."' ";
					$arrValueup = array('requi_flag'=>$flag, 'close_date'=>$close_date );
					$Updaterec=$utilObj->updateRecord('production_requisition', $strWhere, $arrValueup);
				}

			}

			echo $Msg = 'Record has been Deleted Sucessfully! ';

		break;

	}
}
?>