<?php		
	if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
	{
		$id=$_REQUEST['id'];
		$rows=$utilObj->getSingleRow("stock_ledger","id ='".$id."' "); 
	} 
?>

<style>
    .container-with-border {
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 10px;
    }
</style>

<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Add Stock Ledger</h3>
				</div>
				
				<form id="" data-parsley-validate class="row g-3" action="../stock_ledger_list.php"  method="post" data-rel="myForm">
					
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "stock_ledger"; ?>"/>
					
					<div class="container-with-border">
						<div class="row">
							<h5>General Information</h5>
							<div class="col-md-4">
								<label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="name" class="required form-control" <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['name'];?>" onchange="check_name(this.value);" />
							</div>
							
							<div class="col-md-2">
								<label class="form-label"> Re-Order Level<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="reorderlvl" class="required form-control" <?php echo $readonly;?> placeholder="Re-Order Level" name="reorderlvl" value="<?php echo $rows['reorderlvl'];?>" />
							</div>

							<div class="col-md-3">
								<label class="form-label">Under Group <span class="required required_lbl" style="color:red;">*</span> </label>
								<select id="under_group" name="under_group" <?php echo $disabled;?> class="select2 form-select" data-allow-clear="true">
									<option value="">Select Group</option>
									<?php 
										$record = $utilObj->getMultipleRow("stock_group", "1 group by name");
										foreach($record as $e_rec) {
											$select = ($rows['under_group'] == $e_rec["id"]) ? 'selected' : '';
											echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
										}
									?>
								</select>
							</div>

							<div class="col-md-3">
								<label class="form-label">Category </label>
								<select id="cat_group" name="cat_group" <?php echo $disabled;?> class="select2 form-select" data-allow-clear="true">
									<option value="">Select Group</option>
									<?php
										$record = $utilObj->getMultipleRow("stock_category_master", "1 group by cat_name");
										foreach($record as $e_rec) {
											$select = ($rows['cat_id'] == $e_rec["id"]) ? 'selected' : '';
											echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cat_name"].'</option>';
										}
									?> 
								</select>
							</div>

							<div class="col-md-2">
								<div id="unitdiv">
									<label class="form-label">Unit<span class="required required_lbl" style="color:red;">*</span></label>
									<div id="div_unit">
										<select id="unit" name="unit" <?php echo $disabled;?> class="required select2 form-select" data-allow-clear="true" onchange="menuhide();get_unit_formula();">
											<option value="">Select Unit</option>
											<?php 
												$record = $utilObj->getMultipleRow2("unit_details", "1 group by Quantity");
												foreach($record as $e_rec) {
													$select = ($rows['unit'] == $e_rec["UQC_Code"]) ? 'selected' : '';
													echo '<option value="'.$e_rec["UQC_Code"].'" '.$select.'>'.$e_rec["UQC_Code"].'</option>';
												}
											?>
										</select>
									</div>
								</div>
							</div>
						
							<div class="col-md-2">
								<div id="altunitdiv">
									<label class="form-label">Alternate Unit </label>
									<div id="div_altunit">
										<select id="alt_unit" name="alt_unit" <?php echo $disabled;?> class="select2 form-select" data-allow-clear="true" onchange="menuhide1();get_unit_formula();">
											<option value="">Select Alternate Unit</option>
											<?php 
												$record = $utilObj->getMultipleRow2("unit_details", "1 AND Quantity!='' group by Quantity");
												foreach($record as $e_rec) {
													$select = ($rows['alt_unit'] == $e_rec["UQC_Code"]) ? 'selected' : '';
													echo '<option value="'.$e_rec["UQC_Code"].'" '.$select.'>'.$e_rec["UQC_Code"].'</option>';
												}
											?>
										</select>
									</div>
								</div>
							</div>
						
							<div class="col-md-4">
								<div class="row" id="formula_div">
									<!-- Additional content can go here -->
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="col-md-2"></div> -->
					<div class="container-with-border">
						<div class="row">
							<h5>Other Settings</h5>
							<!-- <div class="col-md-2">
								<label class="form-label">Batch Maintainance <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="batch_maintainance" name="batch_maintainance" class=" requied form-check-input"value="1" <?php if($rows['batch_maintainance']=="1"){echo "checked";} ?>  <?php echo $disabled;?> /> <label>&nbsp;Yes</label>
									&nbsp;&nbsp;&nbsp;
								<input type="radio" id="batch_maintainance" name="batch_maintainance" class="requied form-check-input" value="0" <?php if($rows['batch_maintainance']=="0" || $rows['batch_maintainance']==""){echo "checked";}?>  <?php echo $disabled;?>  /><label>&nbsp;No</label>
							</div> -->
							
							<div class="col-md-2">
								<label class="form-label">Bill of Material <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="bill_of_material" name="bill_of_material" class=" requied form-check-input"value="1" <?php if($rows['bill_of_material']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="bill_of_material" name="bill_of_material" class="requied form-check-input" value="0" <?php if($rows['bill_of_material']=="0" || $rows['bill_of_material']==""){echo "checked";}?>  <?php echo $disabled;?>  /><label>&nbsp;No</label>
							</div>
							
							
							<div class="col-md-2">
							<label class="form-label">Enable Cost Tracking <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="cost_tracking" name="cost_tracking" class=" requied form-check-input"value="1" <?php if($rows['cost_tracking']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="cost_tracking" name="cost_tracking" class="requied form-check-input" value="0" <?php if($rows['cost_tracking']=="0" || $rows['cost_tracking']==""){echo "checked";}?>  <?php echo $disabled;?>  /><label>&nbsp;No</label>
								
							</div>
							
							<!-- <div class="col-md-2">
								<label class="form-label">Negative Stock Blocking <span class="required required_lbl" style="color:red;">*</span> </label>
								<br>
								<input type="radio" id="negative_stk_block" name="negative_stk_block" class=" requied form-check-input" value="1" <?php if($rows['negative_stk_block']=="1" || $rows['negative_stk_block']=="")echo "checked"?>  <?php echo $disabled;?> /><label>&nbsp; Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="negative_stk_block" name="negative_stk_block" class="reqired form-check-input" value="0" <?php if($rows['negative_stk_block']=="0")echo "checked"?>  <?php echo $disabled;?>/><label>&nbsp; No</label>
							</div> -->
							
							<div class="col-md-2">
								<label class="form-label">Treat all Sales as New MFG <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="new_mfg" name="new_mfg" class=" requied form-check-input"value="1" <?php if($rows['new_mfg']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="new_mfg" name="new_mfg" class="requied form-check-input" value="0" <?php if($rows['new_mfg']=="0" || $rows['new_mfg']==""){echo "checked";}?>  <?php echo $disabled;?>  /> <label>&nbsp;No</label>
							</div>
							
							<div class="col-md-2">
								<label class="form-label">Treat all Purchase as Consumed <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="consumed" name="consumed" class=" requied form-check-input"value="1" <?php if($rows['consumed']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="consumed" name="consumed" class="requied form-check-input" value="0" <?php if($rows['consumed']=="0" || $rows['consumed']==""){echo "checked";}?>  <?php echo $disabled;?> /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
								<label class="form-label">MFG Date Maintainance<span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="mfg_maintainance" name="mfg_maintainance" class=" requied form-check-input"value="1" <?php if($rows['mfg_maintainance']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="mfg_maintainance" name="mfg_maintainance" class="requied form-check-input" value="0" <?php if($rows['mfg_maintainance']=="0" || $rows['mfg_maintainance']==""){echo "checked";}?>  <?php echo $disabled;?> /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
								<label class="form-label">EXP Date Maintainance<span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="exp_maintainance" name="exp_maintainance" class=" requied form-check-input"value="1" <?php if($rows['exp_maintainance']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="exp_maintainance" name="exp_maintainance" class="requied form-check-input" value="0" <?php if($rows['exp_maintainance']=="0" || $rows['exp_maintainance']==""){echo "checked";}?>  <?php echo $disabled;?> /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
									
								<label class="form-label">Costing Methods <span class="required required_lbl" style="color:red;">*</span></label>
								<select id="costing_method" name="costing_method" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
									<option value="">Select Method</option>
									<option value="std_cost" <?php if($rows["costing_method"]=='std_cost') echo $select='selected'; else $select='';?>>Standard Cost</option>
									<option value="fifo" <?php if($rows["costing_method"]=='fifo') echo $select='selected'; else $select='';?> selected >FIFO</option>
									<option value="lifo" <?php if($rows["costing_method"]=='lifo') echo $select='selected'; else $select='';?>>LIFO</option>
									<option value="weighted_avg" <?php if($rows["costing_method"]=='weighted_avg') echo $select='selected'; else $select='';?>>Weighted Average</option>
								</select>
							</div>
						</div>
					</div>

					<div class="container-with-border">
						<div class="row">
							<h5>Tax Setting</h5>
							<div class="col-md-2">
								<label class="form-label"> Description</label>
								<input type="text" id="description" class="form-control"  <?php echo $readonly;?> placeholder="Description" name="description" value="<?php echo $rows['description'];?>"/>
							</div>

							<div class="col-md-2">
								<label class="form-label"> HSN/SAC</label>
								<input type="text" id="hsn_sac" class="form-control"  <?php echo $readonly;?> placeholder="Enter HSN/SAC" name="hsn_sac" value="<?php echo $rows['hsn_sac'];?>"/>
							</div>

							<div class="col-md-2">
								<label class="form-label">Is non-GST Goods<span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="non_gst" name="non_gst" class=" requied form-check-input"value="1" onclick="show_taxdetails();" <?php if($rows['non_gst']=="1") { echo "checked"; } ?> <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="non_gst" name="non_gst" class="requied form-check-input" value="0" onclick="edit_taxdetails();" <?php if($rows['non_gst']=="0" || $rows['non_gst']=="") {echo "checked"; } ?>  <?php echo $disabled; ?>  /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
								<label class="form-label"> Calculation Type </label>
								<input type="text" id="cal_type" class="form-control"  <?php echo $readonly;?> placeholder="Enter Type" name="cal_type" value="<?php 
								if($rows['cal_type']=='') {

									echo 'On Value';
								} else {

									echo $rows['cal_type'];
								} ?>" />
							</div>

							<div class="col-md-2">
								<label class="form-label"> Taxability </label>
								<select id="taxability" name="taxability" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="show_gst();">
									<option value="">Select Method</option>
									<option value="Taxable" <?php if($rows["taxability"]=='Taxable') echo $select='selected'; else $select='';?>>Taxable</option>
									<option value="Exempt" <?php if($rows["taxability"]=='Exempt') echo $select='selected'; else $select='';?>>Exempt</option>
									<option value="export_with_tax" <?php if($rows["taxability"]=='export_with_tax') echo $select='selected'; else $select='';?>>Export with tax</option>
									<option value="export_without_tax" <?php if($rows["taxability"]=='export_without_tax') echo $select='selected'; else $select='';?>>Export without tax</option>
								</select>
							</div>

							<div class="col-md-2">
								<label class="form-label"> Is Reverse charge applicable <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="rev_charge" name="rev_charge" class="form-check-input"value="1" <?php echo $disabled;?> <?php if($rows['rev_charge']=="1"){echo "checked";}?>  <?php echo $disabled;?> /><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="rev_charge" name="rev_charge" class="form-check-input" value="0" <?php echo $disabled;?> <?php if($rows['rev_charge']=="0" || $rows['rev_charge']==""){echo "checked";}?>  <?php echo $disabled;?>  /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
								<label class="form-label"> Is In eligible for Input Credit <span class="required required_lbl" style="color:red;">*</span></label>
								<br>
								<input type="radio" id="ineligible_input" name="ineligible_input" class="form-check-input"value="1" <?php echo $disabled;?> <?php if($rows['ineligible_input']=="1"){echo "checked";}?> /><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="ineligible_input" name="ineligible_input" class="form-check-input" value="0" <?php echo $disabled;?> <?php if($rows['ineligible_input']=="0" || $rows['ineligible_input']==""){echo "checked";}?>  /><label>&nbsp;No</label>
							</div>

							<div class="col-md-2">
								<div id="altunitdiv">
									<label class="form-label">IGST</label>
									<div id="div_altunit">
										<select id="igst" name="igst" <?php echo $disabled;?> class="select2 form-select " onchange="get_gst_data(this.value);get_sgst_data(this.value);">
										<?php 
											echo '<option value="">Select IGST</option>';
											//echo '<option value="AddNew">Add New</option>';
											$record=$utilObj->getMultipleRow("gst_data","1 AND igst!='' group by igst");
											foreach($record as $e_rec){
												if($rows['igst']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["igst"].'</option>';
											}
										?> 
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div id="altunitdiv">
									<label class="form-label">CGST </label>
									<div id="div_altunit">
										<select id="cgst" name="cgst" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
										<?php 
											echo '<option value="">Select CGST</option>';
											// echo '<option value="AddNew">Add New</option>';
											$record=$utilObj->getMultipleRow("gst_data","1 AND cgst!='' group by cgst");
											foreach($record as $e_rec) {
												if($rows['cgst']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cgst"].'</option>';
											}
										?> 
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<div id="altunitdiv">
									<label class="form-label">SGST </label>
									<div id="div_altunit">
										<select id="sgst" name="sgst" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
										<?php 
											echo '<option value="">Select SGST</option>';
											// echo '<option value="AddNew">Add New</option>';
											$record=$utilObj->getMultipleRow("gst_data","1 AND sgst!='' group by sgst");
											foreach($record as $e_rec){
											if($rows['sgst']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sgst"].'</option>';
											}
										?> 
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<label class="form-label"> CESS </label>
								<input type="text" id="cess" class="form-control"  <?php echo $readonly;?> placeholder="Enter" name="cess" value="<?php echo $rows['cess'];?>"/>
							</div>

							<div class="col-sm-3"> 
								<label class="form-label">Sales Local</label>
								<select class="select2 form-select" id="sale_local" name="sale_local" >
									<option value="">Select Sales Local</option>
									<option value="na" <?php if($rows["sale_local"]=='na') echo $select='selected'; else $select='';?>>N/A</option>
									<?php
										$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
										foreach($record as $e_rec){
											if($rows['sale_local']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
										}
									?>
								</select>
							</div>

							<div class="col-sm-3">
								<label class="form-label" >Purchase Local</label>
								<select class="select2 form-select" id="purchase_local" name="purchase_local" >
									<option value="">Select Purchase Local</option>
									<option value="na" <?php if($rows["purchase_local"]=='na') echo $select='selected'; else $select='';?>>N/A</option>
									<?php
										$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
										foreach($record as $e_rec){
											if($rows['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
										}
									?>
								</select>
							</div>

							<div class="col-sm-3">
								<label class="form-label">Sales Outstate</label>
								<select class="select2 form-select" id="sale_outstate" name="sale_outstate" >
									<option value="">Select Sales Outstate</option>
									<option value="na" <?php if($rows["sale_outstate"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
									<?php
										$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
										foreach($record as $e_rec){
											if($rows['sale_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
										}
									?>
								</select>
							</div>

							<div class="col-sm-3">
								<label class="form-label">Purchase Outstate</label>
								<select class="select2 form-select" id="purchase_outstate" name="purchase_outstate" >
									<option value="">Select Purchase Outstate</option>
									<option value="na" <?php if($rows["purchase_outstate"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
									<?php
										$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
										foreach($record as $e_rec) {
											if($rows['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
							
					<div class="col-12 text-center" style = "margin-top: 35px;">
						<?php 
							if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
							<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);" style = "margin-right: 20px;"/>
						<?php } ?>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
					</div>
				</form>
				<!--/ Add role form -->
			</div>
		</div>
	</div>
</div>
<!--/ Add Role Modal -->
<script>

	

function get_gst_data(id) {
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_gst_data',id: id},
		success:function(data)
		{	
			$("#cgst").html(data);
		}
	});
}

function get_sgst_data(id) {
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_sgst_data',id: id},
		success:function(data)
		{	
			$("#sgst").html(data);
		}
	});
}

function menuhide()
{

	var unit = $("#unit").val();
		if (unit=='AddNew')
	{
		setTimeout(function()
		{
			$("#div_unit").html('<input type="text" class="form-control"  placeholder="Enter Unit" name="unit" id="unit" onkeyup="get_unit_formula();">');
			$("#unit").focus();
		},1);
	}
}
function menuhide1()
{
	var unit = $("#alt_unit").val();
		if (unit=='AddNew')
	{
		setTimeout(function()
		{
			$("#div_altunit").html('<input type="text" class="form-control"  placeholder="Enter Alternate Unit" name="alt_unit" id="alt_unit" onkeyup="get_unit_formula();">');
			$("#alt_unit").focus();
		},1);
	}
}
function unit_refresh()
{	
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'unit_refresh'},
		success:function(data)
		{	
			$("#unitdiv").html(data);
		}
	});	
}

function unit_alt_refresh()
{	
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'unit_alt_refresh'},
		success:function(data)
		{	
			$("#altunitdiv").html(data);
		}
	});	
}	

function get_unit_formula()
{
	
	var unit = $("#unit").val();
	var altunit = $("#alt_unit").val();
	var id = $("#id").val();

	var PTask = $("#PTask").val();

	if(unit!='' && altunit!='')
	{
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_unit_formula',unit:unit,altunit:altunit,id:id,PTask:PTask},
			success:function(data)
			{	
				$("#formula_div").html(data);
			}
		});	
	}
}

function show_taxdetails() {

	$('#cal_type').prop('readonly', true);
	$('#taxability').prop('readonly', true);
	$('#rev_charge').prop('disabled', true);
	$('#ineligible_input').prop('disabled', true);
	$('#igst').prop('disabled', true);
	$('#cgst').prop('disabled', true);
	$('#sgst').prop('disabled', true);
	$('#cess').prop('readonly', true);

}

function edit_taxdetails() {
	
	$('#cal_type').prop('readonly', false);
	$('#taxability').prop('readonly', false);
	$('#rev_charge').prop('disabled', false);
	$('#ineligible_input').prop('disabled', false);
	$('#igst').prop('disabled', false);
	$('#cgst').prop('disabled', false);
	$('#sgst').prop('disabled', false);
	$('#cess').prop('readonly', false);

}

function show_gst() {

	var taxability = $("#taxability").val();

	if(taxability == 'Taxable' || taxability == 'export_with_tax') {
		$('#igst').prop('disabled', false);
		$('#cgst').prop('disabled', false);
		$('#sgst').prop('disabled', false);
		$('#cess').prop('readonly', false);
	} else {
		$('#igst').prop('disabled', true);
		$('#cgst').prop('disabled', true);
		$('#sgst').prop('disabled', true);
		$('#cess').prop('readonly', true);
	}

}

function check_name(val) {

	var table = "stock_ledger";
	var col = "name";

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'check_name',val:val,table:table,col:col },
		success:function(data)
		{	
			if(data>0) {
				alert("This name is already exist");
				$("#name").val('');
			}
			else { return false; }
		}
	});

}

</script>
