<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("currency","id ='".$id."'"); 
    $pass=decryptIt($rows['password']);
} 
?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Add Currency</h3>
          
        </div>
        <!-- Add role form -->
		
        <form id="" data-parsley-validate class="row g-3" action="../currency_list.php"  method="post" data-rel="myForm">
        
        <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
        <input type="hidden" name="id" id="id" value="<?php echo $rows['id'];?>"/>	
        <input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
        <input type="hidden" name="table" id="table" value="<?php echo "currency"; ?>"/>
			
		      <div class="col-md-6">
            <label class="form-label"> Currency Symbol <span class="required required_lbl" style="color:red;">*</span></label>
            <input type="text" id="currency_symbol" class="required form-control"  <?php echo $readonly;?> placeholder="Currency Symbol" name="currency_symbol" value="<?php echo $rows['currency_symbol'];?>"/>
          </div>
		      <div class="col-md-6">
            <label class="form-label">Formal Name <span class="required required_lbl" style="color:red;">*</span></label>
            <input class="required form-control " type="text" id="formal_name" name="formal_name"  <?php echo $readonly;?> placeholder="Formal Name" value="<?php echo $rows['formal_name'];?>" onchange="check_name(this.value);" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Decimal Places <span class="required required_lbl" style="color:red;">*</span></label>
            <input class="required form-control" type="text" id="decimal_places" name="decimal_places" <?php echo $readonly;?>  placeholder="Decimal Places" value="<?php echo $rows['decimal_places'];?>" />
          </div>
		 
          <div class="col-12 text-center">
          <?php 
            if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='') { ?>	
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
    var table = "currency";
    var col = "formal_name";

    jQuery.ajax({url:'get_ajax_values.php', type:'POST',
      data: { Type:'check_name',val:val,table:table,col:col },
      success:function(data)
      {	
        if(data>0) {
          alert("This Name is already Exist");
          $("#formal_name").val('');
        }
        else { return false; }
      }
    });
  }
</script>