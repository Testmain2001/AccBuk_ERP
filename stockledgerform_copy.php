<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("stock_ledger","id ='".$id."'"); 
   
} 
?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Add Stock Ledger</h3>
          
        </div>
        <!-- Add role form -->
		
          <form id="" data-parsley-validate class="row g-3" action="../stock_ledger_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "stock_ledger"; ?>"/>
			
		  <div class="col-md-6">
            <label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
            <input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['name'];?>"/>
          </div>
		  
		  <div class="col-md-6">
			<label class="form-label">Under Group </label>
			<select id="under_group" name="under_group" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
			  
				    <option value="">Select Group</option>';
					<option value="Primary" <?php if($rows["under_group"]=="Primary") echo $select="selected"; else $select=""; ?>>Primary</option>';
					<?php 
					$record=$utilObj->getMultipleRow("stock_ledger","1 group by name");
					foreach($record as $e_rec){
					if($rows['under_group']==$e_rec["id"]) echo $select='selected'; else $select='';
					
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
					}
				?> 
			</select>
		  </div>
		  
		<div class="col-md-6">
		<label class="form-label">Set Default Sales Ledger for Sale Invoicing <span class="required required_lbl" style="color:red;">*</span></label>
			<br>
			  <input type="radio" id="sale_invoicing" name="sale_invoicing" class=" requied form-check-input"value="1" <?php if($rows['sale_invoicing']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
			&nbsp;&nbsp;&nbsp;
			  <input type="radio" id="sale_invoicing" name="sale_invoicing" class="requied form-check-input" value="0" <?php if($rows['sale_invoicing']=="0"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;No</label>
		</div>
			
		<div class="col-md-5">
			<div id="unitdiv">
				<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
				<div id="div_unit">
					<select id="unit" name="unit" <?php echo $disabled;?> class="required select2 form-select " data-allow-clear="true" 
					onchange="menuhide();get_unit_formula();">
					  
						<?php 
							echo '<option value="">Select Unit</option>';
							echo '<option value="AddNew">Add New</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 group by unit");
							foreach($record as $e_rec){
							if($rows['unit']==$e_rec["unit"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["unit"].'" '.$select.'>'.$e_rec["unit"].'</option>';
							}
						?> 
					</select>
				</div>
			</div>
        </div>
		<div class="col-md-1" style="padding-top:34px;">
			<img src="images/refresh.png" height="20px" width="20px" onclick="unit_refresh();">
		</div>
		
		
		<div class="col-md-5">
			<div id="altunitdiv">
				<label class="form-label">Alternate Unit </label>
				<div id="div_altunit">
					<select id="alt_unit" name="alt_unit" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true"  onchange="menuhide1();get_unit_formula();">
						<?php 
							echo '<option value="">Select Alternate Unit</option>';
							echo '<option value="AddNew">Add New</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 AND alt_unit!='' group by alt_unit");
							foreach($record as $e_rec){
							if($rows['alt_unit']==$e_rec["alt_unit"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["alt_unit"].'" '.$select.'>'.$e_rec["alt_unit"].'</option>';
							}
						?> 
					</select>
				</div>
			</div>
        </div>
		<div class="col-md-1" style="padding-top:34px;">
			<img src="images/refresh.png" height="20px" width="20px" onclick="unit_alt_refresh();">
		</div>
		
		
		
		<div class="col-md-6" >
			<div class="row" id="formula_div" >
					
			</div>
		</div>

		<div class="col-md-6">
		<label class="form-label">Batch Maintainance <span class="required required_lbl" style="color:red;">*</span></label>
				<br>
			  <input type="radio" id="batch_maintainance" name="batch_maintainance" class=" requied form-check-input"value="1" <?php if($rows['batch_maintainance']=="1"){echo "checked";}?>  <?php echo $disabled;?> /> <label>&nbsp;Yes</label>
				&nbsp;&nbsp;&nbsp;
			  <input type="radio" id="batch_maintainance" name="batch_maintainance" class="requied form-check-input" value="0" <?php if($rows['batch_maintainance']=="0"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;No</label>
			
		</div>
		
		<div class="col-md-6">
		<label class="form-label">Bill of Material <span class="required required_lbl" style="color:red;">*</span></label>
			  <br>
			  <input type="radio" id="bill_of_material" name="bill_of_material" class=" requied form-check-input"value="1" <?php if($rows['bill_of_material']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
				&nbsp;&nbsp;&nbsp;
			  <input type="radio" id="bill_of_material" name="bill_of_material" class="requied form-check-input" value="0" <?php if($rows['bill_of_material']=="0"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;No</label>
		</div>
		
		
		<div class="col-md-6">
		<label class="form-label">Anable Cost Tracking <span class="required required_lbl" style="color:red;">*</span></label>
			  <br>
			  <input type="radio" id="cost_tracking" name="cost_tracking" class=" requied form-check-input"value="1" <?php if($rows['cost_tracking']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
			  &nbsp;&nbsp;&nbsp;
			  <input type="radio" id="cost_tracking" name="cost_tracking" class="requied form-check-input" value="0" <?php if($rows['cost_tracking']=="0"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;No</label>
			
		</div>
		
		<div class="col-md-6">
                
            <label class="form-label">Costing Methods <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="costing_method" name="costing_method" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
                      <option value="">Select Method</option>
                      <option value="std_cost" <?php if($rows["costing_method"]=='std_cost') echo $select='selected'; else $select='';?>>Standard Cost</option>
                      <option value="fifo" <?php if($rows["costing_method"]=='fifo') echo $select='selected'; else $select='';?>>FIFO</option>
                      <option value="lifo" <?php if($rows["costing_method"]=='lifo') echo $select='selected'; else $select='';?>>LIFO</option>
                      <option value="weighted_avg" <?php if($rows["costing_method"]=='weighted_avg') echo $select='selected'; else $select='';?>>Weighted Average</option>
                </select>
        </div>
		
		<div class="col-md-6">
			<label class="form-label">Negative Stock Blocking <span class="required required_lbl" style="color:red;">*</span> </label>
			<br>
			  <input type="radio" id="negative_stk_block" name="negative_stk_block" class=" requied form-check-input" value="1" <?php if($rows['negative_stk_block']=="1")echo "checked"?>  <?php echo $disabled;?>/><label>&nbsp; Yes</label>
			  &nbsp;&nbsp;&nbsp;
			  <input type="radio" id="negative_stk_block" name="negative_stk_block" class="reqired form-check-input" value="0" <?php if($rows['negative_stk_block']=="0")echo "checked"?>  <?php echo $disabled;?>/><label>&nbsp; No</label>
			
		</div>
		
		<div class="col-md-6">
		<label class="form-label">Treat all Sales as New MFG <span class="required required_lbl" style="color:red;">*</span></label>
			  <br>
			  <input type="radio" id="new_mfg" name="new_mfg" class=" requied form-check-input"value="1" <?php if($rows['new_mfg']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
			&nbsp;&nbsp;&nbsp;
			  <input type="radio" id="new_mfg" name="new_mfg" class="requied form-check-input" value="0" <?php if($rows['new_mfg']=="0"){echo "checked";}?>  <?php echo $disabled;?>/> <label>&nbsp;No</label>
		</div>
		
		<div class="col-md-6">
		<label class="form-label">Treat all Purchase as Consumed <span class="required required_lbl" style="color:red;">*</span></label>
			  <br>
			  <input type="radio" id="consumed" name="consumed" class=" requied form-check-input"value="1" <?php if($rows['consumed']=="1"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;Yes</label>
			&nbsp;&nbsp;&nbsp;
			  <input type="radio" id="consumed" name="consumed" class="requied form-check-input" value="0" <?php if($rows['consumed']=="0"){echo "checked";}?>  <?php echo $disabled;?>/><label>&nbsp;No</label>
		</div>
		  
          <div class="col-12 text-center">
            <?php 
			if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
			<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
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
</script>
