<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("stock_group","id ='".$id."'"); 
   
} 
?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Add Stock Group</h3>
          
        </div>
        <!-- Add role form -->
		
          <form id="" data-parsley-validate class="row g-3" action="../stock_group_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "stock_group"; ?>"/>
			
		  <div class="col-md-6">
            <label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
            <input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['name'];?>" onchange="check_name(this.value);"/>
          </div>
		  
		  <div class="col-md-6">
			<label class="form-label" for="multicol-country">Under Group <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="under_group" name="under_group" <?php echo $disabled;?> class=" required select2 form-select " data-allow-clear="true" >
			  
				    <option value="">Select Group</option>
					<option value="Primary" <?php if($rows["under_group"]=="Primary") echo $select="selected"; else $select=""; ?>>Primary</option>
					<?php
					$record=$utilObj->getMultipleRow("stock_group","1 group by name");
					foreach($record as $e_rec){
					if($rows['under_group']==$e_rec["id"]) echo $select='selected'; else $select='';
					
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
					}
				?> 
			</select>
		  </div>

		  
		  
		<div class="col-md-6">
			<label class="form-label">Negative Stock Blocking <span class="required required_lbl" style="color:red;">*</span> </label>
			<br>
			  <input type="radio" id="negative_stk_block" name="negative_stk_block" class="required form-check-input" value="1" <?php if($rows['negative_stk_block']=="1")echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;Yes</label>
			   &nbsp;&nbsp;&nbsp;
			  <input type="radio" id="negative_stk_block" name="negative_stk_block" class="required form-check-input" value="0" <?php if($rows['negative_stk_block']=="0")echo "checked"?> <?php echo $disabled;?>/><label>&nbsp;No</label>
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
	function check_name(val) {
	var table = "stock_group";
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