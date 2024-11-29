
<?php		
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
  $id=$_REQUEST['id'];
  $rows=$utilObj->getSingleRow("employee","id ='".$id."'"); 
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
          <h3 class="role-title">Add Employee</h3>
        </div>
    
        <form id="" data-parsley-validate class="row g-3" action="../employee_master_list.php"  method="post" data-rel="myForm">
      
          <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
          <input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
          <input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
          <input type="hidden"  name="table" id="table" value="<?php echo "employee"; ?>"/>
      
          <div class="col-md-3">
            <label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
            <input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Name" name="name" value="<?php echo $rows['name'];?>"/>
          </div>

          <div class="col-md-3">
            <label class="form-label">Mobile No. <span class="required required_lbl" style="color:red;">*</span></label>
            <input class="form-control mobile" type="text" id="mobile" name="mobile"  <?php echo $readonly;?>  placeholder="Mobile No." maxlength="10" value="<?php echo $rows['mobile'];?>" onchange="check_name(this.value);"/>
          </div>

          <div class="col-md-3">
            <label class="form-label">Email <span class="required required_lbl" style="color:red;">*</span></label>
            <input class="required form-control" type="text" id="email" name="email" <?php echo $readonly;?>  placeholder="Email" value="<?php echo $rows['email']; ?>" />
          </div>
      
          <div class="col-md-3">
            <div class="form-password-toggle">
              <label class="form-label">Password <span class="required required_lbl" style="color:red;">*</span></label>
              <div class="input-group input-group-merge">
                <input class="required form-control" type="password" id="password" name="password" <?php echo $readonly;?> placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="multicol-password2" value="<?php echo $pass;?>" maxlength="8" />
                <span class="input-group-text cursor-pointer" id="multicol-password2"><i class="bx bx-hide"></i></span>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <label class="form-label" for="multicol-country">Role <span class="required required_lbl" style="color:red;">*</span></label>
            <select id="role" name="role" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" data-mdb-filter="true">
              
              <?php 
              echo  '<option value="" >Select Role</option>';
                $record=$utilObj->getMultipleRow("role_master","1");
                foreach($record as $e_rec){
                if($rows['role']==$e_rec["id"]) echo $select='selected'; else $select='';
                echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["role"].'</option>';
                }
              ?> 
            </select>
          </div>
          
          <input type="hidden" name="multiloc" id="multiloc" value="<?php echo $rows['multiloc']; ?>">
          <div class="col-md-3">
            <label class="form-label" for="multicol-country">Location <span class="required required_lbl" style="color:red;">*</span></label>
            <select id="loc" name="loc" class="select2 form-select required" data-allow-clear="true" data-mdb-filter="true" onchange="getmultipid();" multiple >
              <?php
                $place = explode(",",$rows['multiloc']);

                echo  '<option value="" >Select Role</option>';
                $record=$utilObj->getMultipleRow("location","1");
                foreach($record as $e_rec) {
                  // if($rows['role']==$e_rec["id"]) echo $select='selected'; else $select='';
                  // echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';

                  if(in_array($e_rec["id"],$place)) { $select='selected';} else $select='';
							    echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
                }
              ?> 
            </select>
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

  function getmultipid() {

    var pids = $("#loc").val();
    $("#multiloc").val(pids);

  }

  function check_name(val) {

    var table = "employee";
    var col = "mobile";

    jQuery.ajax({url:'get_ajax_values.php', type:'POST',
      data: { Type:'check_name',val:val,table:table,col:col },
      success:function(data)
      {	
        if(data>0) {
          alert("This Mobile no is already Exist");
          $("#mobile").val('');
        }
        else { return false; }
      }
    });

  }
</script>


