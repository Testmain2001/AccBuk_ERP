<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("group_master","id ='".$id."'"); 
   
} 
?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      	<div class="modal-body ">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
			<div class="text-center mb-4">
				<h3 class="role-title">Add Group</h3>
			</div>
			<!-- Add role form -->
			
			<form id="" data-parsley-validate class="row g-3" action="../group_master_list.php"  method="post" data-rel="myForm">
				
				<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
				<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
				<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
				<input type="hidden"  name="table" id="table" value="<?php echo "group_master"; ?>"/>
					
				<div class="col-md-6">
					<label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['group_name'];?>" onchange="check_name(this.value);"/>
				</div>
				
				<div class="col-md-6">
					<label class="form-label" for="multicol-country">Parent Group <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="parent_group" name="parent_group" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_nature_group();get_grp_id(this.value);get_act_group(this.value);">
					
						<?php
							echo  '<option value="" >Select Group</option>';				
							echo  '<option value="Primary" >Primary</option>';		
							$record=$utilObj->getMultipleRow("group_master","1");
							foreach($record as $e_rec){
								if($rows['parent_group']==$e_rec["group_name"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["group_name"].'" '.$select.'>'.$e_rec["group_name"].'</option>';
							}
						?> 
					</select>
				</div>
				<input type="hidden"  name="act_group" id="act_group" value="<?php echo $rows['act_group']; ?>"/>
			
				<div id = "group_div">
					
				</div>

				<input type="hidden" name="grp_id" id="grp_id" value="">

				<!-- <div class="col-md-6">
					<div  id="group_div" >
						<label class="form-label">Nature of Group </label>
						<input type="text" id="nature" class="form-control" readonly <?php echo $readonly;?> placeholder="Nature of Group" name="group" value="<?php echo $rows['nature'];?>"/>
					</div>
				</div> -->
				
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

		var table = "group_master";
		var col = "group_name";

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

	function get_act_group(val) {


		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_act_group',val:val },
			success:function(data)
			{
				$("#act_group").val(data);
			}
		});
	}


</script>