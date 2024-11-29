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
?>

<div class="container-xxl flex-grow-1 container-p-y ">

	<div class="row">
		<div class="col-md-3">
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Request</h4>
		</div>
		<!-- <div class="col-md-2">
			<?php //if ((CheckCreateMenu()) == 1) { ?> <input type="button" class="add_new btn btn-primary btn-sm  "
					onclick="hideshow();" id="add_new" name="add_new" value="Add New" />
			<?php //} ?>
			<?php //if ((CheckDeleteMenu()) == 1) { ?>
				<button class=" btn btn-danger  btn-sm" onclick="CheckDelete();">Delete</button>
			<?php //} ?>
		</div> -->
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
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$i = 1;
						$data = $utilObj->getMultipleRow("stock_request", "1");
						foreach ($data as $info) {

							$href = 'stock_request_list.php?id=' . $info['id'] . '&PTask=view';
							if ($d1 > 0) {
								$dis = "disabled";
							} else {
								$dis = "";
							}

							$productnm = $utilObj->getSingleRow("stock_ledger", "id='" . $info['product'] . "'");
							$stocktransfer = $utilObj->getSingleRow("stock_transfer", "request_id='" . $info['id'] . "'");
							
							$flagArray = array();
							$status = 'Pending';

							$stockdetails = $utilObj->getMultipleRow("stock_transfer_details","parent_id='" . $stocktransfer['id'] . "'");

							foreach($stockdetails as $details) {

								$flagValue = $details['flag'];
								$flagArray[] = $flagValue;
								if (in_array(0, $flagArray)) {
									$status = 'Pending';
								} else {
									$status = 'Completed';
								}
							}
							
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
									<?php echo date('d-m-Y',strtotime($info['date'])); ?>
								</td>
								<td>
									<?php echo $status;  ?>
								</td>
								<!-- <td> <?php echo $info['date']; ?> </td> -->
								<td>
								<?php if($stocktransfer['id']==''){?>
                                <a href="stock_transfer_list.php?id=<?php echo $info['id']; ?>&PTask=receive">
								<input type="button" class="add_new btn btn-primary btn-sm" id="add_new" name="add_new" value="Accept" />
								</a>
								<?php }else{?>
								<a href="stock_transfer_list.php?id=<?php echo $stocktransfer['id']; ?>&PTask=reset">
								<input type="button" class="add_new btn btn-primary btn-sm" id="add_new" name="add_new" value="Reset" />
							<?php }?>
								</a>
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


</div>
<!--/ Content -->

<script>


function get_stockt_code() {
	
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_stockt_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#record_no").val(data);
		}
	});

}

function get_locationwise_productstock()
{	
 
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var location = $("#location").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_locationwise_productstock',location:location,id:id,PTask:PTask},
		success:function(data)
		{	
		    //alert(data);
			$("#table_div").html(data);	
		}
	});	
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
			$("#unitdiv_"+id).html(data);	
			$(this).next().focus();
		}
	});	
} 
function stock_check(rid){
		
    var did=rid.split("_");	
	var rid=did[1];	
	//alert(rid);	
	var fromstock=jQuery("#fromstock_"+rid).val(); 
	var tostock=jQuery("#tostock_"+rid).val(); 
	 
	if(parseFloat(tostock)>parseFloat(fromstock))
		{
			$("#tostock_"+rid).val('');
			alert("stock should not grather than availiable stock");
			return false;				
		}
}
</script>

<!-- Footer -->
<?php
include("footer.php");
?>