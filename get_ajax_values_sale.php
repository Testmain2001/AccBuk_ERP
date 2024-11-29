<?php include 'config.php';
$utilObj = new util();
$type = $_REQUEST['Type'];
switch ($type) {

	//--------------------------USE IN -Sale Return(1)----------------------------------

	case 'addsalereturnbatch':
		$product_id = $_REQUEST['product'];
		$stock = $_REQUEST['stock'];
		$qty = $_REQUEST['qty'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		// $location = $_REQUEST['location'];
		$task = $_REQUEST['task'];
		$sale = $_REQUEST['sale_invoice_no'];
		$i = 0;
	?>
		<div id="salesinvoicereturnbatch">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<table class="table border-top">

						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="sale_invoice_no" id="sale_invoice_no" value="<?php echo $_REQUEST['sale_invoice_no']; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="location12" id="location12" value="<?php echo $location; ?>">


						<thead>
							<tr>
								<th>Location</th>
								<th>Batch Name</th>
								<th>Stock Quantity</th>
								<th>Quantity</th>
							</tr>
						</thead>

						<tbody>
						<?php
							if ($task != 'update') {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND sale_invoice_no='" . $sale . "' group by purchase_batch");

							} else {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='" . $id . "' AND type='sale_return' ");
							}

							$sumqty = 0;
							foreach ($product as $info) {

								$productinvoice = $utilObj->getSum("sale_batch", "product='" . $product_id . "' AND sale_invoice_no='" . $sale . "' AND type='sale_invoice' AND purchase_batch='" . $info['purchase_batch'] . "'", "quantity");

								$productreturn = $utilObj->getSum("sale_batch", "product='" . $product_id . "' AND sale_invoice_no='" . $sale . "' AND type='sale_return' AND purchase_batch='" . $info['purchase_batch'] . "'", "quantity");

								$loc=$utilObj->getSingleRow("location","id ='".$info['location']."'");

								if ($task != 'update') {
									// $totalstock = $productinvoice + $productreturn;
									$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $loc['id']);
								} else {

									// $totalstock = $productinvoice - $productreturn + $info['quantity'];
									$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $loc['id']);
								}

								if($PTask == 'update') {
									$b_id = $info['purchase_batch'];
								} else {
									$b_id = $info['purchase_batch'];
								}

								$i++;
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<input type="hidden" name="id[]" class="batch_id" value="<?php echo $info['purchase_batch']; ?>">

									<td>
										<input type="text" id="locationname_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="locationname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $loc['name']; ?>" readonly />

										<input type="hidden" name="location_<?php echo $info['purchase_batch']; ?>" id="location_<?php echo $info['purchase_batch']; ?>" value="<?php echo $info['location']; ?>">
									</td>
									<td>
										<?php
											$bname=$utilObj->getSingleRow("purchase_batch","id ='".$info['batchname']."'");
										?>
										<input readonly id="batchname_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batchname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $info['batchname']; ?>" type="hidden" />

										<input readonly id="batchname_name_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batchname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $bname['batchname']; ?>" />
									</td>
									<?php
										if ($task == 'update') {
									?>
										<td>
											<input readonly id="batqty_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batqty_<?php echo $info['purchase_batch']; ?>" value="<?php echo $totalstock; ?>" />
										</td>
										<td>
											<input id="batch_remove_<?php echo $info['purchase_batch']; ?>"
												class="form-control number batch_remove_input"
												name="batch_remove_<?php echo $info['purchase_batch']; ?>"
												onKeyup="getqty('<?php echo $info['purchase_batch']; ?>');"
												value="<?php echo $info['quantity']; ?>" />
										</td>
									<?php } else { ?>
										<td>
											<input readonly id="batqty_<?php echo $info['purchase_batch']; ?>"
												class=" form-control number" name="batqty_<?php echo $info['purchase_batch']; ?>"
												value="<?php echo $totalstock; ?>" />
										</td>
										<td>
											<input id="batch_remove_<?php echo $info['purchase_batch']; ?>"
												class="form-control number batch_remove_input"
												name="batch_remove_<?php echo $info['purchase_batch']; ?>"
												onKeyup="getqty('<?php echo $info['purchase_batch']; ?>');" value="" />
										</td>
									<?php } ?>


								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value="" />
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"
					onClick="savereturnsalebatch();" />
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
		<script>
			function savereturnsalebatch() {
				var tot_qty = $('#total_batch_remove').val();
				var qty = $("#qty").val();

				if (tot_qty == qty) {
					savereturnsalesbatch();
				} else {
					if (qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("Please add quantity in exsiting batch");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("Please remove some quantity from exsiting batch.");
					}
				}
			}

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#total_batch_remove').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();


			});
		</script>
		<?php
		break;

	//--------------------------USE IN -Sale Return(2)----------------------------------

	case 'updatereturnbattch':

		if ($_REQUEST['PTask'] == 'update') {
			$common = $_REQUEST['deliveryid'];
		} else {
			$common = $_REQUEST['common_id'];
		}

		$arrValue1 = array('id' => uniqid(), 'parent_id' => $common, 'ClientID' => $_SESSION['Client_Id'], 'purchase_batch' => $_REQUEST['id'], 'product' => $_REQUEST['product'], 'type' => $_REQUEST['type'], 'batchname' => $_REQUEST['batchname'], 'quantity' => $_REQUEST['batchremove'], 'created' => date("Y-m-d H:i:s"), 'lastedited' => date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

		$insertedId = $utilObj->insertRecord('temp_sale_batch', $arrValue1);

		break;

	//--------------------------USE IN -Delivery challan return(1)----------------------------------
	case 'addchallanreturnbatch':
		$product_id = $_REQUEST['product'];
		$stock = $_REQUEST['stock'];
		$qty = $_REQUEST['qty'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		// $location = $_REQUEST['location'];
		$PTask = $_REQUEST['PTask'];
		$sale = $_REQUEST['challan_no'];
		$i = 0;
	?>
		<div id="saleschallanreturnbatch">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<table class="table border-top">

						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="sale_invoice_no" id="sale_invoice_no" value="<?php echo $_REQUEST['sale_invoice_no']; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">


						<thead>
							<tr>
								<th>Location</th>
								<th>Batch Name</th>
								<th>Stock Quantity</th>
								<th>Quantity</th>

							</tr>
						</thead>

						<tbody>
							<?php
							if ($PTask != 'update') {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND sale_invoice_no='" . $sale . "' group by purchase_batch");

							} else {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='" . $id . "' AND type='delivery_return' ");
							}

							$sumqty = 0;
							foreach ($product as $info) {
								$productinvoice = $utilObj->getSum("sale_batch", "product='" . $product_id . "' AND location = '" . $location . "' AND sale_invoice_no='" . $sale . "' AND type='sale_delivery' AND purchase_batch='" . $info['purchase_batch'] . "'", "quantity");

								$productreturn = $utilObj->getSum("sale_batch", "product='" . $product_id . "' AND location = '" . $location . "' AND sale_invoice_no='" . $sale . "' AND type='delivery_return' AND purchase_batch='" . $info['purchase_batch'] . "'", "quantity");

								$loc=$utilObj->getSingleRow("location","id ='".$info['location']."'");

								if ($PTask != 'update') {
									// $totalstock = $productinvoice + $productreturn;
									$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $loc['id']);
								} else {

									// $totalstock = $productinvoice - $productreturn + $info['quantity'];
									$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $loc['id']);
								}
								$i++;
							?>

								<tr id='row2_<?php echo $i; ?>'>
									<input type="hidden" name="id[]" class="batch_id" value="<?php echo $info['purchase_batch']; ?>">
									<td>
										<input type="text" id="locationname_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="locationname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $loc['name']; ?>" readonly />

										<input type="hidden" name="location_<?php echo $info['purchase_batch']; ?>" id="location_<?php echo $info['purchase_batch']; ?>" value="<?php echo $info['location']; ?>">
									</td>
									<td>
										<?php
											$bname=$utilObj->getSingleRow("purchase_batch","id ='".$info['batchname']."'");
										?>
										<input readonly id="batchname_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batchname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $info['batchname']; ?>" type="hidden" />

										<input readonly id="batchname_name_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batchname_<?php echo $info['purchase_batch']; ?>" value="<?php echo $bname['batchname']; ?>" />
									</td>
									<?php
										if ($PTask == 'update') {
									?>
										<td>
											<input readonly id="batqty_<?php echo $info['purchase_batch']; ?>" class=" form-control number" name="batqty_<?php echo $info['purchase_batch']; ?>" value="<?php echo $totalstock; ?>" />
										</td>
										<td>
											<input id="batch_remove_<?php echo $info['purchase_batch']; ?>"
												class="form-control number batch_remove_input"
												name="batch_remove_<?php echo $info['purchase_batch']; ?>"
												onKeyup="getqty('<?php echo $info['purchase_batch']; ?>');"
												value="<?php echo $info['quantity']; ?>" />
										</td>
									<?php } else { ?>
										<td>
											<input readonly id="batqty_<?php echo $info['purchase_batch']; ?>"
												class=" form-control number" name="batqty_<?php echo $info['purchase_batch']; ?>"
												value="<?php echo $totalstock; ?>" />
										</td>
										<td>
											<input id="batch_remove_<?php echo $info['purchase_batch']; ?>"
												class="form-control number batch_remove_input"
												name="batch_remove_<?php echo $info['purchase_batch']; ?>"
												onKeyup="getqty('<?php echo $info['purchase_batch']; ?>');" value="" />
										</td>
									<?php } ?>


								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value="" />

							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">

						</tbody>

					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"
					onClick="savechallanbatch();" />
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
		<script>
			function savechallanbatch() {
				var tot_qty = $('#total_batch_remove').val();
				
				var qty = $("#qty").val();
				alert(qty);
				if (tot_qty == qty) {
					savechallansbatch();
				} else {
					if (qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("Please add quantity in exsiting batch");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("Please remove some quantity from exsiting batch.");
					}
				}
			}

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#total_batch_remove').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();

			});

			function savechallansbatch() {

				var cnt2 = $("#cnt2").val();
				var product = $("#product").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "delivery_return";
				var batchIds = [];


				$(".batch_id").each(function () {
					batchIds.push($(this).val());
				});

				// Iterate through batch IDs and update data
				for (var i = 0; i < batchIds.length; i++) {

					var id = batchIds[i];
					var batqty = $("#batqty_" + id).val();
					var batchname = $("#batchname_" + id).val();
					var location = $("#location_"+id).val();
					var batchremove = $("#batch_remove_" + id).val();
					jQuery.ajax({
						url: 'get_ajax_values_sale.php',
						type: 'POST',
						data: { Type: 'updatechallanreturnbattch', id: id, deliveryid: deliveryid, batqty: batqty, batchremove: batchremove, product: product, common_id: common_id, batchname: batchname, PTask: PTask, type: type,location:location },
						success: function (data) {
							$('#salechallanreturnbatch').modal('hide');

						},
						error: function (xhr, status, error) {
							console.error("AJAX Error:", status, error);
						}
					});
				}
			}
		</script>
		<?php
		break;


	//--------------------------USE IN -Delivery challan return(2)----------------------------------

	case 'updatechallanreturnbattch':

		if ($_REQUEST['PTask'] == 'update') {
			$common = $_REQUEST['deliveryid'];
		} else {
			$common = $_REQUEST['common_id'];
		}

		$arrValue1 = array('id' => uniqid(), 'parent_id' => $common, 'ClientID' => $_SESSION['Client_Id'], 'purchase_batch' => $_REQUEST['id'], 'product' => $_REQUEST['product'], 'type' => $_REQUEST['type'], 'batchname' => $_REQUEST['batchname'], 'quantity' => $_REQUEST['batchremove'], 'created' => date("Y-m-d H:i:s"), 'lastedited' => date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

		$insertedId = $utilObj->insertRecord('temp_sale_batch', $arrValue1);

		break;
	//--------------------------USE IN -Delivery challan return(3)----------------------------------

	case 'get_deliverychallan':

		$delivery_challan = $utilObj->getSingleRow("delivery_return", " id='" . $_REQUEST['id'] . "' ");
		?>
		<label class="form-label"> Challan No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div>
			<?php if ($_REQUEST['PTask'] == 'view') {
				$readonly = "readonly";
				$sale_invoice_no = $utilObj->getSingleRow("sale_invoice", "id in (select sale_invoice_no from  sale_return where id ='" . $_REQUEST['id'] . "')");
				?>
				<!-- <input type="hidden" id="sale_invoice_no" <?php echo $readonly; ?> name="sale_invoice_no" value="<?php echo $sale_invoice_no['id']; ?>"/>
						<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly; ?>  value="<?php echo $sale_invoice_no['sale_invoiceno']; ?>"/> -->

			<?php } else { ?>
				<select id="challan_no" name="challan_no" <?php echo $disabled; ?> class="select2 form-select "
					data-allow-clear="true" onchange="deliverychallan_return_rowtable();">
					<option value=""> Select Challan No</option>
					<?php
					$record = $utilObj->getMultipleRow("delivery_challan", "customer ='" . $_REQUEST['customer'] . "' AND location ='" . $_REQUEST['location'] . "' AND id not in(Select delivery_challan_no from sale_invoice)");
					foreach ($record as $e_rec) {
						if ($delivery_challan['challan_no'] == $e_rec["id"])
							echo $select = 'selected';
						else
							$select = '';
						echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["challan_no"] . '</option>';
					}
					?>
				</select>

			<?php } ?>
		</div>
		<?php
		break;

	//--------------------------USE IN -Delivery challan return(4)----------------------------------

	case 'deliverychallan_return_rowtable':

		$challan_no = $_REQUEST['challan_no'];
		$account_ledger = $utilObj->getSingleRow("account_ledger", " id='" . $_REQUEST['customer'] . "' ");
		$state = $account_ledger['mail_state'];

		$delivery_challan = $utilObj->getSingleRow("delivery_return", " id='" . $_REQUEST['id'] . "' ");
		if ($_REQUEST['PTask'] == 'view') {
			$readonly = "readonly";
		} else {
			$readonly = " ";
		}
		?>
		<table class="table table-bordered " id="myTable">
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th>
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl"
							style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl"
							style="color:red;">*</span></th>
					<th id="totalth" style="width: 8%;">Rejected Qty</th>
					<th style="width:10%;text-align:center;">Batch <span class="required required_lbl"
							style="color:red;">*</span></th>
					<?php if ($_REQUEST['PTask'] != 'view') { ?>
						<th style="width:2%;text-align:center;"></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				if (($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view')) //
				{
					$record5 = $utilObj->getMultipleRow("delivery_return_details", "parent_id='" . $_REQUEST['id'] . "' order by id  ASC");
				} else if (($challan_no != '' && $_REQUEST['PTask'] == 'Add') || ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view')) {
					//echo "condi 2";
					$record5 = $utilObj->getMultipleRow("delivery_challan_details", "parent_id='" . $challan_no . "' order by id  ASC ");

				} else {
					$record5[0]['id'] = 1;
				}
				foreach ($record5 as $row_demo) {
					if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view') {
						$returnqty = $row_demo['rejectedqty'];
						$total = $row_demo['total'];
						$subtot = $sale_return['subt'];
					} else {
						$returnqty = 0;
						$total = 0;
						$subtot = 0;
					}
					$i++;
					?>
					<tr id='row_<?php echo $i; ?>'>
						<td style="text-align:center;width:2%;">
							<label id="idd_<?php echo $i; ?>" name="idd_<?php echo $i; ?>">
								<?php echo $i; ?>
							</label>
						</td>
						<td style="width: 20%;">
							<?php
							$product = $utilObj->getSingleRow("stock_ledger", " id='" . $row_demo['product'] . "' ");
							?>
							<input type="hidden" id="product_<?php echo $i; ?>" <?php echo $readonly; ?>
								name="product_<?php echo $i; ?>" value="<?php echo $product['id']; ?>" />
							<input type="text" style="width:100%;" class=" form-control" readonly <?php echo $readonly; ?>
								value="<?php echo $product['name']; ?>" />

						</td>
						<td style="width: 10%;">
							<div id='unitdiv_<?php echo $i; ?>'>
								<input type="text" id="unit_<?php echo $i; ?>" class=" form-control required" readonly <?php echo $readonly; ?> name="unit_<?php echo $i; ?>" value="<?php echo $row_demo['unit']; ?>" />
							</div>
						</td>

						<td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i; ?>" class=" form-control number" <?php echo $readonly; ?>
								readonly name="qty_<?php echo $i; ?>" value="<?php echo $row_demo['qty']; ?>" />
						</td>

						<td style="width: 10%;">
							<input type="text" id="rejectedqty_<?php echo $i; ?>" class=" form-control number"
								onKeyUp="Gettotal(this.id);batch_check(this.id);" onBlur="Gettotal(this.id);batch_check(this.id);" <?php echo $readonly; ?>
								name="rejectedqty_<?php echo $i; ?>" value="<?php echo $returnqty; ?>" />
						</td>

						<td style="width: 10%;text-align:center;">
							<?php
							//if ($product['batch_maintainance'] == '1') { ?>
								<div id='divbatch_<?php echo $i; ?>'>
									<button type="button" class="btn btn-light" onClick="check_qty(<?php echo $i; ?>)">
										<?php if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask']=='view') { ?> <i class="fas fa-box fa-lg" style="color: #000000;"></i><?php } else { ?><i class="fas fa-box fa-lg" style="color: #000000;"></i><?php } ?>
									</button>
								</div>
							<?php //} ?>
						</td>

						<?php if ($_REQUEST['Task'] != 'view') { ?>
							<td style='width:2%'>
								<i class="bx bx-trash me-1" id='deleteRow_<?php echo $i; ?>' style="cursor: pointer;"
									onclick="delete_row(this.id);"></i>
							</td>
						<?php } ?>
					</tr>


					<script>

						/*  Gettotal('product_<?php echo $i; ?>');
						Gettotal1('product_<?php echo $i; ?>');
						Getgst('product_<?php echo $i; ?>');
						Gettotgst('product_<?php echo $i; ?>');
						showgrandtotal(); * /
					</script>
				<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
		</table>
		<div class="modal fade" style="max-width=40%; " id="salechallanreturnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style="max-width: 800px; margin-left: 250px;" id="saleschallanreturnbatch">

				</div>
			</div>
		</div>

		<!-- ---------------------------------------- -->
		<!-- <div class="container border border-light p-2 mb-2">
			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label" style="text-align:right;">Amount Before Tax</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="true" class=" form-control col-md-7 smallinput col-xs-12" readonly id="subt" style="width: 137PX;" name="subt" value="<?php echo $subtot; ?>">
					</div>
				</div>
				<div class="col">
					<label for="first-name"  class="control-label" >Transp Cost</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="tran();showgrandtotal();" onBlur="tran();showgrandtotal();" id="transcost" value="<?php if (!empty($sale_return['transcost'])) {
								echo $sale_return['transcost'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="transcost"  >
					</div>
				</div>
				<div class="col">
					<label for="first-name"  class="control-label" >Transp GST (%)</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="tran();showgrandtotal();" onBlur="tran();showgrandtotal();" id="transgst" value="<?php if (!empty($sale_return['transgst'])) {
								echo $sale_return['transgst'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="transgst" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label" >Transp GST Amount</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12" id="transamount" readonly style="width: 112px;" value="<?php if (!empty($sale_return['transamount'])) {
							echo $sale_return['transamount'];
						} else {
							echo '0';
						} ?>" name="transamount" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Total Transportation</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12 number" readonly id="trans" style="width: 137px;" name="trans" value="<?php if (!empty($sale_return['trans'])) {
							echo $sale_return['trans'];
						} else {
							echo '0';
						} ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
			</div>

			<br>

			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label " >Total CGST </label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totcst_amt" style="width: 112px;" name="totcst_amt" value="<?php echo $sale_return['totcst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Total SGST</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totsgst_amt" style="width: 112px;" name="totsgst_amt" value="<?php echo $sale_return['totsgst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Total IGST</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
							<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totigst_amt" style="width: 112px;" name="totigst_amt" value="<?php echo $sale_return['totigst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >TCS/TDS</label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<select class="select2 form-select " data-placeholder="Select TCS/TDS "  style="width:112px" <?php echo $disabled; ?> name="tcs_tds" id="tcs_tds">
							<option></option>
							<option value="TCS" <?php if ($sale_return["tcs_tds"] == 'TCS')
								echo $select = 'selected';
							else
								$select = ''; ?>>TCS</option> 
							<option value="TDS" <?php if ($sale_return["tcs_tds"] == 'TDS')
								echo $select = 'selected';
							else
								$select = ''; ?>>TDS</option> 		
						</select>
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >TCS/TDS (%)</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="showgrandtotal();" onBlur="tran();showgrandtotal();" id="tcs_tds_percen" value="<?php if (!empty($sale_return['tcs_tds_percen'])) {
								echo $sale_return['tcs_tds_percen'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="tcs_tds_percen" >
					</div>
				</div>
			</div>

			<br>

			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label " >TCS TDS Amount</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12" id="tcs_tds_amt" readonly style="width: 112px;" value="<?php if (!empty($sale_return['tcs_tds_amt'])) {
							echo $sale_return['tcs_tds_amt'];
						} else {
							echo '0';
						} ?>" name="tcs_tds_amt" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Other</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class="number form-control col-md-7 smallinput col-xs-12" id="other" style="width: 112px;" name="other" value="<?php if (!empty($sale_return['other'])) {
								echo $sale_return['other'];
							} else {
								echo '0';
							} ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label" >Round-OFF</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12" id="roff" style="width: 112px;" name="roff" value="<?php if (!empty($sale_return['roff'])) {
								echo $sale_return['roff'];
							} else {
								echo '0';
							} ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label" >Narration</label>
					<div class="col-md-6 col-sm-4 col-xs-12">
							<textarea type="text" <?php echo $readonly; ?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 93%; float:right;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $sale_return['otrnar']; ?></textarea>
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label" >Grand Total</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="true" class=" form-control col-md-7 smallinput col-xs-12" readonly id="grandtotal" style="width: 137PX;" name="grandtotal" value="<?php if (!empty($sale_return['grandtotal'])) {
							echo $sale_return['grandtotal'];
						} else {
							echo '0';
						} ?>">
					</div>
				</div>
			</div>
		</div> -->


		<?php
		break;

	//--------------------------USE IN -Transfer Stock(1)----------------------------------

	case 'addtransferbatch':
		$product_id = $_REQUEST['product'];
		$locationout = $_REQUEST['locationout'];
		$stock = $_REQUEST['stock'];
		$qty = $_REQUEST['quantity'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		$location = $_REQUEST['location'];
		$task = $_REQUEST['task'];
		$sale = $_REQUEST['sale_invoice_no'];
		$i = 0;
	?>
		<div id="transfersbatch">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<p>
						Transfer Quantity : <?php echo $qty; ?>
					</p>
					<table class="table border-top">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>">
						<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="sale_invoice_no" id="sale_invoice_no" value="<?php echo $_REQUEST['sale_invoice_no']; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">
						<input type="hidden" name="locationout" id="locationout" value="<?php echo $locationout; ?>">

						<thead>
							<tr>
								<th>Batch Name</th>
								<th>Batch Rate</th>
								<th>Stock Quantity</th>
								<th>Quantity</th>
							</tr>
						</thead>

						<tbody>
						<?php
							if ($task == 'Add') {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND location = '" . $location . "' AND flag='0' AND (type='purchase_invoice' OR type='grn' OR type='transfer_batch_in' OR type='production_in' OR type='packaging_in' OR type='stockj_batch_in')");
							} else if($task == 'update' || $task == 'reset') {

								// $product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='" . $id . "' AND type='transfer_batch_in'");
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='" . $id . "' AND type='transfer_batch_out'");
							} else {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "'   AND location = '" . $location . "' AND flag='0' AND (type='purchase_invoice' OR type='grn' OR type='transfer_batch_in' OR type='production_in' OR type='packaging_in' OR type='stockj_batch_in')");
							}

							$sumqty = 0;

							foreach ($product as $info) {
								if ($task == 'Add') {
									$totalstock = getbatchstock($info['id'], $info['product'], date('Y-m-d'), $info['location']);
									$infoid = $info['id'];
 								} else if($task == 'update' || $task == 'reset'){
									$totalstock = getbatchstock($info['purchase_batch'], $info['product'], date('Y-m-d'), $location);
									$infoid = $info['purchase_batch'];
									$stock = $totalstock+$info['quantity'];
								} else {
									$totalstock = getbatchstock($info['id'], $info['product'], date('Y-m-d'), $info['location']);
									$infoid = $info['id'];
								}
								$i++;

								$tot_sum += $info['quantity'];
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<input type="hidden" name="id[]" class="batch_id"value="<?php echo $infoid; ?>">

									<td>
										<input readonly id="batchname_<?php echo $infoid; ?>" class=" form-control number" name="batchname_<?php echo $infoid; ?>" value="<?php echo $info['batchname']; ?>" />
									</td>

									<td>
										<input readonly id="bat_rate_<?php echo $infoid; ?>" class=" form-control number" name="bat_rate_<?php echo $infoid; ?>" value="<?php echo $info['bat_rate']; ?>" />
									</td>

									<?php
									if ($task == 'update' || $task=='reset') {
										?>
										<td>
											<input readonly id="batqty_<?php echo $infoid; ?>" class=" form-control number" name="batqty_<?php echo $infoid; ?>" value="<?php echo $stock; ?>" />

										</td>
										<td>
											<input id="batch_remove_<?php echo $infoid; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $infoid; ?>" onKeyup="getqty('<?php echo $infoid; ?>');" value="<?php echo $info['quantity']; ?>" />
										</td>
									<?php } else { ?>
										
										<td>
											<input readonly id="batqty_<?php echo $infoid; ?>" class=" form-control number" name="batqty_<?php echo $infoid; ?>" value="<?php echo $totalstock; ?>" />
										</td>
										<td>
											<input id="batch_remove_<?php echo $infoid; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $infoid; ?>" onKeyup="getqty('<?php echo $infoid; ?>');" value="" />
										</td>
									<?php } ?>


								</tr>
							<?php } ?>
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<tr>
							<td></td>
							<td></td>
							<td style="text-align:right">Total Quantity :</td>
							<td>
								<input type="text" class="form-control number" name="total_batch_remove" id="total_batch_remove" value="<?php echo $tot_sum; ?>" readonly/>
							</td>
						</tr>

					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"
					onClick="savestransferbatch();" />
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
		<script>
			function savestransferbatch() {
				var tot_qty = $('#total_batch_remove').val();
				var qty = $("#qty").val();

				if (tot_qty == qty) {
					savetransferbatch();
				} else {
					if (qty > tot_qty) {
						alert("Your batch quantity is doesn't match transfer quantity.");
					} else {
						alert("Your batch quantity is doesn't match transfer quantity.");
					}
				}
			}

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#total_batch_remove').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();
			});

			function savetransferbatch() {
				var cnt2 = $("#cnt2").val();
				var product = $("#product").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "transfer_batch";
				var locationout = $("#locationout").val();;
				var batchIds = [];


				$(".batch_id").each(function () {
					batchIds.push($(this).val());
				});

				// Iterate through batch IDs and update data
				for (var i = 0; i < batchIds.length; i++) {
				
					var id = batchIds[i];
					var batqty = $("#batqty_"+id).val();
					var batchname = $("#batchname_"+id).val();
					var bat_rate = $("#bat_rate_"+id).val();
					var batchremove = $("#batch_remove_"+id).val();

					jQuery.ajax({
						url: 'get_ajax_values_sale.php',
						type: 'POST',
						data: { Type: 'updatestockbattch', id:id,deliveryid:deliveryid,batqty: batqty,batchremove:batchremove,product:product,common_id:common_id,batchname:batchname,bat_rate:bat_rate,PTask:PTask,type:type,locationout:locationout},
						success: function (data) {
							$('#transferbatch').modal('hide');
					
						},
						error: function (xhr, status, error) {
							console.error("AJAX Error:", status, error);
						}
					});	
				}
			}
		</script>
	<?php
	break;

	// -------------------------- USE IN -Transfer Stock Batch Handler --------------------------

	case 'updatestockbattch':

		if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask']=='reset') {
			$common = $_REQUEST['deliveryid'];
		} else {
			$common = $_REQUEST['common_id'];
		}

		$arrValue1 = array('id' => uniqid(), 'parent_id' => $common, 'ClientID' => $_SESSION['Client_Id'], 'purchase_batch' => $_REQUEST['id'], 'product' => $_REQUEST['product'],'bat_rate' => $_REQUEST['bat_rate'], 'type' => $_REQUEST['type'], 'batchname' => $_REQUEST['batchname'], 'location'=>$_REQUEST['locationout'],'quantity' => $_REQUEST['batchremove'], 'created' => date("Y-m-d H:i:s"), 'lastedited' => date("Y-m-d H:i:s"));

		$insertedId = $utilObj->insertRecord('temp_sale_batch', $arrValue1);

	break;
}

?>