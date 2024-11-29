<?php
	include("header.php");
	$task = $_REQUEST['PTask'];
	if ($task == '') {
		$task = 'Add';
	}
	if ($_REQUEST['PTask'] == 'view') {
		$readonly = "readonly";
		$disabled = "disabled";
	} else {
		$readonly = "";
		$disabled = "";
	}
	$common_id=uniqid();
?>

<div class="container-xxl flex-grow-1 container-p-y ">

	<div class="row">
		<div class="col-md-3">
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Transfer</h4>
		</div>
		<div class="col-md-2">
		<?php if((CheckCreateMenu())==1) { ?>
			<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>

		<?php if((CheckDeleteMenu())==1){ ?>
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		</div>
	</div>
	<!-- Invoice List Table -->

	<div id="u_table" style="display:block">
		<div class="card">
			<div class="card-datatable table-responsive pt-0">

				<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
					<thead>
						<tr>
							<th width="5%"><input type='checkbox' value='0' id='select_all' onclick="select_all();" />
								Sr.No.</th>
							<th>Record No.</th>
							<th>Date</th>
							<th>Voucher type</th>
							<th>From Location</th>
							<?php if ((CheckEditMenu()) == 1) { ?>
								<th>Actions</th>
							<?php } ?>
						</tr>
					</thead>

					<tbody>
					<?php
						$i = 1;
						$data = $utilObj->getMultipleRow("stock_transfer", "1");
						foreach ($data as $info) {

						$href = 'stock_transfer_list.php?id=' . $info['id'] . '&PTask=view';
						//$d1=$rows=$utilObj->getCount("delivery_challan","saleorder_no ='".$info['id']."'");
						if ($d1 > 0) {
							$dis = "disabled";
						} else {
							$dis = "";
						}
						$productnm = $utilObj->getSingleRow("stock_ledger", "id='" . $info['product'] . "'");
						$loc = $utilObj->getSingleRow("location", "id='" . $info['location'] . "'");

					?>
							<tr>
								<td width="5%" class='controls'><input type='checkbox' <?php echo $dis; ?>
										class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>' />
									&nbsp&nbsp
									<?php echo $i; ?>
								</td>
								<td><a href="<?php echo $href; ?>">
										<?php echo $info['record_no']; ?>
									</a> </td>
								<td>
									<?php echo $info['date']; ?>
								</td>
								<td> <?php echo $info['stockt_code']; ?> </td>
								<td> <?php echo $loc['name']; ?> </td>
								<td>
								<?php
									//echo $d1;
									if ($d1 == 0) {
								?>
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
										data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
									<div class="dropdown-menu">
										<?php if ((CheckEditMenu()) == 1) { ?>
											<a class="dropdown-item"
												href="stock_transfer_list.php?id=<?php echo $info['id']; ?>&PTask=update"><i
													class="bx bx-edit-alt me-1"></i> Edit</a>
										<?php } ?>
										<?php if ((CheckDeleteMenu()) == 1) { ?>
											<a class="dropdown-item"
												href="stock_transfer_list.php?id=<?php echo $info['id']; ?>&PTask=delete"><i
													class="bx bx-trash me-1"></i> Delete</a>
										<?php } ?>
									</div>
								<?php } ?>
								</td>
							</tr>
							<?php
							$i = $i + 1;
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
		$date = date('d-m-Y');
		if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view' || $_REQUEST['PTask'] == 'reset') {
			
			$id = $_REQUEST['id'];
			$rows = $utilObj->getSingleRow("stock_transfer", "id ='" . $id . "'");
			$record_no = $rows['record_no'];
			$date = date('d-m-Y', strtotime($rows['date']));
			$location = $rows['location'];
			$request_id = $rows['request_id'];
		} else if ($_REQUEST['PTask'] == 'receive' ) {

			$id = $_REQUEST['id'];
			$rows = $utilObj->getSingleRow("stock_request", "id ='" . $id . "'");
			$date = date('d-m-Y', strtotime($rows['date']));
			$location = $rows['location'];
			$request_id = $rows['id'];
		} else if ($_REQUEST['PTask'] == 'send' ) {

			$id = $_REQUEST['id'];
			$rows = $utilObj->getSingleRow("production_requisition", "id ='" . $id . "'");
			// $date = date('d-m-Y', strtotime($rows['date']));
			$date = date('d-m-Y');
			$request_id = $rows['id'];
		} else {

			$rows = null;
		}
	?>

	<div class="container-xxl flex-grow-1 container-p-y " style=" background-color: white; padding: 30px; background: #ffffff; display:none" id="u_form">
		<div class="row form-validate">
			<div class="col-12">
				<div class="card">
					<div class="card-body ">
						<form id="" data-parsley-validate class="row g-3" action="../stock_transfer_list.php" method="post" data-rel="myForm">
							<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>" />
							<input type="hidden" name="id" id="id" value="<?php echo $rows['id']; ?>" />
							<input type="hidden" name="request_id" id="request_id" value="<?php echo $request_id; ?>" />
							<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>" />
							<input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited']; ?>" />
							<input type="hidden" name="table" id="table" value="<?php echo "stock_transfer"; ?>" />

							<div class="col-md-4">
								<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
								<select id="voucher_type" name="voucher_type" <?php echo $disabled; ?> class="required form-select select2" data-allow-clear="true" onchange="get_stockt_code();">
									<option value="">Select</option>
									<?php
										$data = $utilObj->getMultipleRow("voucher_type", "parent_voucher=10 group by id");
										foreach ($data as $info) {

											if ($info["id"] == $rows['voucher_type']) {
												
												echo $select = "selected";
											} else {

												echo $select = "";
											}
											echo '<option value="' . $info["id"] . '" ' . $select . '>' . $info["name"] . '</option>';
										}
									?>
								</select>
							</div>

							<div class="col-md-2">
								<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly; ?> placeholder="Record No." name="record_no" value="<?php echo $record_no; ?>" />
							</div>

							<div class="col-md-2">
								<label class="form-label">Stock Transfer Date<span class="required required_lbl"
										style="color:red;">*</span></label>
								<input type="text" class="form-control flatpickr" id="date" name="date" required
									value="<?php echo $date; ?>" <?php echo $disabled; ?> />
							</div>

							<div class="col-md-4">
								<label class="form-label">From location<span class="required required_lbl" style="color:red;">*</span></label>
								<select id="location" name="location" onchange="get_locationwise_productstock();" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" style="width:100%;">
								<?php
									echo '<option value="">Select</option>';
									$record = $utilObj->getMultipleRow("location", "1 ");
									foreach ($record as $e_rec) {

										if ($location == $e_rec["id"]) echo $select = 'selected';
										else $select = '';
										echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
									}
								?>
								</select>
							</div>

							<h4 class="role-title">Material Stock Locationwise</h4>
							<div id="table_div" style="overflow: hidden;">


							</div>

							<div class="col-12 text-center">
								<?php
								if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == '' || $_REQUEST['PTask'] == 'receive' || $_REQUEST['PTask'] == 'reset' || $_REQUEST['PTask'] == 'send') { ?>
									<!-- <input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit" onClick="mysubmit(0);" /> -->
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit" onClick="savedata();" />
								<?php } ?>
								<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
									aria-label="Close" onClick="remove_urldata(0);">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	//include("form/stock_transfer_form.php");
	?>

</div>
<!--/ Content -->

<script>
	window.onload = function () {
		$("#date").flatpickr({
			dateFormat: "d-m-Y"
		});
	}

	
	function get_bstock(this_id) {

		var id=this_id.split("_");
		id=id[1];

		var product = $("#product_"+id).val();
		var location = $("#location").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_bstock',id:id,product:product,location:location},
			success:function(data) {

				$("#fromstock_"+id).val(data);
				$(this).next().focus();
			}
		});
	}

	// function check_remark(this_id) {

	// 	var id=this_id.split("_");
	// 	id=id[1];

	// 	var remark = $("#remark_"+id).val();
	// 	if(remark == 'Remark') {

	// 		$("#divremark_"+id).css('display', 'block');
	// 		$("#remark_"+id).css('display', 'none');
	// 	}
	// }

	function check_remark(buttonId, divId) {
		
		var button = document.getElementById(buttonId);
		var element = document.getElementById(divId);

		if (element.style.display === "none") {

			element.style.display = "flex"; // Show the input group
			button.style.display = "none"; // Hide the "Remark" button
		}
	}

	function get_unit(this_id)
	{	

		var id=this_id.split("_");
		id=id[1];

		var product = $("#product_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_unit',id:id,product:product},
			success:function(data)
			{	
				// alert(data);
				$("#unitdiv_"+id).html(data);	
				$(this).next().focus();
			}
		});	
	}

	function get_stockt_code() {

		// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
		// $result=mysqli_fetch_array($getinvno);
		// $grn_no=$result['pono']+1;

		var voucher_type = $("#voucher_type").val();

		jQuery.ajax({
			url: 'get_ajax_values.php', type: 'POST',
			data: { Type: 'get_stockt_code', voucher_type: voucher_type },
			success: function (data) {
				//alert(data);
				$("#record_no").val(data);
			}
		});

	}

	function get_locationwise_productstock() {

		var PTask = $("#PTask").val();
		var id = $("#id").val();
		var location = $("#location").val();
		jQuery.ajax({ url: 'get_ajax_values.php', type: 'POST',
			data: { Type: 'get_locationwise_productstock', location: location, id: id, PTask: PTask },
			success: function (data) {

				$("#table_div").html(data);
				$(".select2").select2();
			}
		});
	}

	function get_unit(this_id) {
		var id = this_id.split("_");
		id = id[1];
		var product = $("#product_" + id).val();
		jQuery.ajax({
			url: 'get_ajax_values.php', type: 'POST',
			data: { Type: 'get_unit', id: id, product: product },
			success: function (data) {
				$("#unitdiv_" + id).html(data);
				$(this).next().focus();
			}
		});
	}
	function stock_check(rid) {

		var did = rid.split("_");
		var rid = did[1];
		//alert(rid);	
		var fromstock = jQuery("#fromstock_" + rid).val();
		var tostock = jQuery("#tostock_" + rid).val();

		if (parseFloat(tostock) > parseFloat(fromstock)) {
			$("#tostock_" + rid).val('');
			alert("stock should not grather than availiable stock");
			return false;
		}
	}
</script>
<script>

	function hideshow() {

		var PTask = $("#PTask").val();
		if (document.getElementById('u_form').style.display == "none" || PTask == 'send' ) {

			document.getElementById('u_form').style.display = "block";
			document.getElementById('u_table').style.display = "none";
			$('#demo-form2').show();
			$("#add_new").val("Show List");
			$("#date").flatpickr({
				dateFormat: "d-m-Y"
			});
			// get_locationwise_productstock();
		} else {

			document.getElementById('u_form').style.display = "none";
			document.getElementById('u_table').style.display = "block";
			$(".add_new").val("Add New");
			$('#demo-form2').show();
			window.location = "stock_transfer_list.php";
		}

	}

	<?php
	if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'receive' || $_REQUEST['PTask'] == 'reset' || $_REQUEST['PTask'] == 'view') { ?>

		window.onload = function () {

			document.getElementById("add_new").click();
			$("#add_new").val("Show List");
			// hideshow();
			get_locationwise_productstock();
		};
	<?php } elseif ($_REQUEST['PTask'] == 'send') { ?>

		window.onload = function () {

			document.getElementById("add_new").click();
			$("#add_new").val("Show List");
			// hideshow();
			// get_locationwise_productstock();
		};
	<?php } ?>

	<?php
	if ($_REQUEST['PTask'] == 'delete') { ?>
		window.onload = function () {
			var r = confirm("Are you sure to delete?");
			if (r == true) {
				deletedata("<?php echo $_REQUEST['id']; ?>");
			}
			else {
				window.location = "stock_transfer_list.php";
			}

		};
	<?php } ?>
	function CheckDelete() {

		var val = '';
		$('input[type="checkbox"]').each(function () {
			if (this.checked == true && this.value != 'on') {
				val += this.value + ",";
			}
		});
		if (val == '') {
			alert('Please Select Atleast 1 record!!!!');
		}
		else {
			val = val.substring(0, val.length - 1);
			window.location = "stock_transfer_list.php?PTask=delete&id=" + val;

		}
	}

	function mysubmit(a) {
		return _isValidpopup(a);
	}

	function remove_urldata() {
		window.location = "stock_transfer_list.php";
	}

	function savedata() {
		var PTask = $("#PTask").val();
		var table = $("#table").val();
		var LastEdited = $("#LastEdited").val();
		var id = $("#id").val();
		var request_id = $("#request_id").val();
		var common_id = $("#common_id").val();
		var cnt = $("#cnt").val();

		var record_no = $("#record_no").val();
		var date = $("#date").val();
		var location = $("#location").val();
		var voucher_type = $("#voucher_type").val();


		var unit_array = [];
		var product_array = [];
		var fromstock_array = [];
		var requestqty_array = [];
		var tostock_array = [];
		var location_array = [];


		for (var i = 1; i <= cnt; i++) {
			var unit = $("#unit_" + i).val();
			var product = $("#product_" + i).val();
			var fromstock = $("#fromstock_" + i).val();
			var requestqty = $("#requestqty_" + i).val();
			var tostock = $("#tostock_" + i).val();
			var location1 = $("#location_" + i).val();

			product_array.push(product);
			unit_array.push(unit);
			fromstock_array.push(fromstock);
			requestqty_array.push(requestqty);
			tostock_array.push(tostock);
			location_array.push(location1);


		}

		jQuery.ajax({
			url: 'handler/stock_transfer_form.php', type: 'POST',
			data: { PTask: PTask, table: table, LastEdited: LastEdited, id: id, common_id:common_id, cnt: cnt, record_no: record_no, date: date, location: location, voucher_type: voucher_type, unit_array: unit_array, product_array: product_array, fromstock_array: fromstock_array, requestqty_array:requestqty_array,tostock_array: tostock_array, location_array: location_array,request_id:request_id },
			success: function (data) {
				if (data != "") {

					window.location = 'stock_transfer_list.php';
				} else {

					alert('error in handler');
				}
			}
		});
	}


	function deletedata(id) {

		var PTask = "<?php echo $_REQUEST['PTask']; ?>";

		jQuery.ajax({
			url: 'handler/stock_transfer_form.php', type: 'POST',
			data: { PTask: PTask, id: id },
			success: function (data) {
				if (data != "") {
					//alert(data);					
					window.location = 'stock_transfer_list.php';
				} else {
					alert('error in handler');
				}
			}
		});
	}

	function select_all() {

		// select all checkboxes
		$("#select_all").change(function () {  //"select all" change

			var status = this.checked; // "select all" checked status
			$('.checkboxes').each(function () { //iterate all listed checkbox items
				if (this.disabled == false) {
					this.checked = status; //change ".checkbox" checked status
					//alert(this.disabled);
				}
			});
		});

		//uncheck "select all", if one of the listed checkbox item is unchecked
		$('.checkboxes').change(function () { //".checkbox" change

			if (this.checked == false) { //if this item is unchecked
				$("#select_all")[0].checked = false; //change "select all" checked status to false
			}
		});

	}



	function check_qty(i) {
		var quantity = $("#rejectedqty_" + i).val();
		var PTask = $("#PTask").val();

		if (quantity == '') {
			alert('please enter quantity first . . . !');

		} else {
			gettransferbatch(i, quantity, PTask);
		}
	}

	function gettransferbatch(i, quantity, task) {
		
		var qty = $("#tostock_" + i).val();
		var locationout = $("#location_" + i).val();
		var product = $("#product_" + i).val();
		var stock = $("#stock_" + i).val();
		var common_id = $("#common_id").val();
		var location = $("#location").val();
		var challan_no = $("#challan_no").val();
		var id = $("#id").val();

		if(qty!='' ) { 
			if(qty!='0') {
				jQuery.ajax({
					url: 'get_ajax_values_sale.php',
					type: 'POST',
					data: { Type: 'addtransferbatch', location: location, locationout:locationout, challan_no: challan_no, common_id: common_id, stock: stock, qty: qty, product: product, id: i, quantity: qty, task: task, id: id },
					success: function (data) {
						$('#transfersbatch').html(data);
						$('#transferbatch').modal('show');

					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			} else {
				alert("Please enter quantity . . . !");
			}
		} else {
			alert("Please enter quantity . . . !");
		}
		
	}

	function getqty(id) {
		var batqty = parseFloat($("#batqty_" + id).val(), 10);
		var batchrmv = parseFloat($("#batch_remove_" + id).val(), 10);
		if (batqty < batchrmv) {
			alert('Quantity is not greater than batch quantity');
			$("#batch_remove_" + id).val('');
		}
	}

	function delete_row(rwcnt)
	{
		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cnt").val();	
		if(count>1)
		{
			var r=confirm("Are you sure!");
			if (r==true)
			{		
				
				$("#row_"+rwcnt).remove();
					
				for(var k=rwcnt; k<=count; k++)
				{
					var newId=k-1;
					
					jQuery("#row_"+k).attr('id','row_'+newId);
					
					jQuery("#idd_"+k).attr('name','idd_'+newId);
					jQuery("#idd_"+k).attr('id','idd_'+newId);
					jQuery("#idd_"+newId).html(newId); 
					
					jQuery("#product_"+k).attr('name','product_'+newId);
					jQuery("#product_"+k).attr('id','product_'+newId);
					
					jQuery("#unit_"+k).attr('name','unit_'+newId);
					jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
					jQuery("#qty_"+k).attr('id','qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
					jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
				}
				jQuery("#cnt").val(parseFloat(count-1)); 
			}
		}
		else 
		{
			alert("Can't remove row Atleast one row is required");
			return false;
		}	 
	}		  		  
			  
	function addRow(tableID) 
	{ 
		var count=$("#cnt").val();
		var location =$("#location").val();
		var PTask =$("#PTask").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
	   
		cell1 += "<td style='width:10%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);get_bstock(this.id);' style='width:210px;'>\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
				foreach($record as $e_rec) {
					echo "<option value='".$e_rec['id']."' >".$e_rec['name']."</option>";
				}
			?>
		</select></td>";

		cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";

		cell1 += "<td style='width:10%'><input name='fromstock_"+i+"' id='fromstock_"+i+"'  readonly class='form-control required' type='text'/></td>";
		
		if(PTask=='receive') {
			cell1 += "<td style='width:10%'><input name='requestqty_"+i+"' id='requestqty_"+i+"'  readonly class='form-control required' type='text'/></td>";
		}

		if(PTask=='reset') {
			cell1 += "<td style='width:10%'><input name='requestqty_"+i+"' id='requestqty_"+i+"'  readonly class='form-control required' type='text'/></td>";
		}

		if(PTask=='send') {
			cell1 += "<td style='width:10%'><input name='requestqty_"+i+"' id='requestqty_"+i+"'  readonly class='form-control required' type='text'/></td>";
		}
		
		cell1 += "<td style='width:10%'><input name='tostock_"+i+"' id='tostock_"+i+"'   class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:10%' ><select name='location_"+i+"' class='select2 form-select' id='location_"+i+"' ' style='width:210px;'>\
			<option value=''>Select</option>\
			<?php
				// $record = $utilObj->getMultipleRow("location", "id !='"+location+"' ");
				$record = $utilObj->getMultipleRow("location", "1");
				foreach($record as $e_rec) {
					echo "<option value='".$e_rec['id']."' >".$e_rec['name']."</option>";
				}
			?>
		</select></td>";

		cell1 += "<td style='width:10%;text-align:center;'><div id='divbatch_"+i+"'>\
			<button type='button' class='btn btn-primary btn-sm' onClick='check_qty("+i+")'>Add Batch</button>\
		</div></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";


		$("#myTable").append(cell1);
		$("#cnt").val(i);
		// $("#particulars_"+i).select2();
		$(".select2").select2();
		 
	}



</script>


<!-- Footer -->
<?php
include("footer.php");
?>