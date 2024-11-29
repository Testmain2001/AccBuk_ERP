<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("voucher_type","id ='".$id."'"); 
   
} 
?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    	<div class="modal-content p-3 p-md-5">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Add Voucher Type</h3>
				</div>
				<!-- Add role form -->
				
				<form id="" data-parsley-validate class="row g-3" action="../voucher_type_list.php"  method="post" data-rel="myForm">
					
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "voucher_type"; ?>"/>
					
					<div class="col-md-8" style="border-right: 0.5px solid black;">
						<div class="row">
							<div class="col-md-4">
								<label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['name'];?>" onchange="check_name(this.value);" />
							</div> 
						
							<div class="col-md-4">
								<label class="form-label" for="multicol-country">Parent Voucher</label>
								<select id="parent_voucher" name="parent_voucher" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
									<option value="">Select Voucher</option>
									<?php
										$record=$utilObj->getMultipleRow("fixed_vouchertype","1 group by voucher_name");
										foreach($record as $e_rec){
											if($rows['parent_voucher']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["voucher_name"].'</option>';
										}
									?> 
								</select>
							</div> 
						
							<div class="col-md-4">
								<label class="form-label" for="multicol-country"> Numbering </label>
								<select id="numbering" name="numbering" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
									<option value="">Select Voucher</option>
									<option value="Manual"<?php if($rows["numbering"]=="Manual") echo $select="selected"; else $select=""; ?>>Manual</option>
									<option value="Automatic" <?php if($rows["numbering"]=="Automatic") echo $select="selected"; else $select=""; ?>>Automatic</option>
									<option value="Automatic_Manual" <?php if($rows["numbering"]=="Automatic_Manual") echo $select="selected"; else $select=""; ?>>Automatic Manual</option>
								</select>
							</div>
						
							<div class="col-md-4">
								<label class="form-label" for="multicol-country"> Number Digit </label>
								<select id="numbering_digit" name="numbering_digit" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
									<option value="">Select Voucher</option>
									<option value="Prefix" <?php if($rows["numbering_digit"]=="Prefix") echo $select="selected"; else $select=""; ?>>Prefix</option>
									<option value="Suffix_with_date_starting" <?php if($rows["numbering_digit"]=="Suffix_with_date_starting,") echo $select="selected"; else $select=""; ?>>Suffix with date starting</option>
								</select>
							</div>

							<div class="col-md-4">
								<label class="form-label"> Prefix Label Code ( e.g WL ) <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="prefix_label" class="required form-control"  <?php echo $readonly;?> placeholder=" Numbering code" name="prefix_label" value="<?php echo $rows['prefix_label'];?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label"> Width of Numbering code <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="codewidth" class="required form-control"  <?php echo $readonly;?> placeholder=" Numbering code" name="codewidth" value="<?php echo $rows['codewidth']; ?>" onkeyup="get_numcode(this.value);" />
							</div>

							<div class="col-md-4" id="numcode">
								<label class="form-label"> Numbering Code <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="numbering_code" class="required form-control"  <?php echo $readonly;?> placeholder=" Numbering code" name="numbering_code" value="<?php echo $rows['numbering_code']; ?>" maxlength="<?php echo $rows['codewidth']; ?>" />
							</div>

							
						
							<!-- <div class="col-md-4">
								<label class="form-label">Narration <span class="required required_lbl" style="color:red;">*</span> </label>
								<br>
								<input type="radio" id="narration" name="narration" class=" requied form-check-input" value="1" <?php if($rows['narration']=="1" || $rows['narration']=="" )echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="narration" name="narration" class="reqired form-check-input" value="0" <?php if($rows['narration']=="0")echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;No</label>
							</div> -->
							
							<!-- <div class="col-md-4">
								<label class="form-label">Printing Settings<span class="required required_lbl" style="color:red;">*</span> </label>
								<br>
								<input type="radio" id="printing_settings" name="printing_settings" class=" requied form-check-input" value="1" <?php if($rows['printing_settings']=="1" || $rows['printing_settings']=="" )echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="printing_settings" name="printing_settings" class="reqired form-check-input" value="0" <?php if($rows['printing_settings']=="0")echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;No</label>
							</div> -->
							
							<div class="col-md-4">
								<label class="form-label">Scan<span class="required required_lbl" style="color:red;">*</span> </label>
								<br>
								<input type="radio" id="scan" name="scan" class=" requied form-check-input" value="1" <?php if($rows['scan']=="1" || $rows['scan']=="" )echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;Yes</label>
								&nbsp;&nbsp;&nbsp;
								<input type="radio" id="scan" name="scan" class="reqired form-check-input" value="0" <?php if($rows['scan']=="0")echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;No</label>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">
								<label class="form-label">decleration<span class="required required_lbl" style="color:red;">*</span></label>
								<textarea name='decleration' id="decleration" <?php echo $readonly; ?> class="form-control " rows ="5"><?php echo $rows['decleration']; ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-12 text-center">
						<?php 
						if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='') { ?>	
							<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);" />
						<?php } ?>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
						
					</div>
				</form>
			</div>
    	</div>
  	</div>
</div>
<!--/ Add Role Modal -->

<script>

	function get_numcode(val) {

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_numcode',val:val },
			success:function(data)
			{
				$("#numcode").html(data);
			}
		});
	}

	function check_name(val) {
		var table = "voucher_type";
		var col = "name";

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'check_name',val:val,table:table,col:col },
			success:function(data)
			{	
				if(data>0) {
					alert("This Name is already Exist");
					$("#name").val('');
				}
				else { return false; }
			}
		});
	}
</script>