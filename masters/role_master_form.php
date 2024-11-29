<?php 
include('handler/role_master_form.php');
?>
<!-- Add Role Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Add New Role</h3>
          <p>Set role permissions</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-3" onsubmit="return false">
		
          <div class="col-12 mb-4">
            <label class="form-label" for="modalRoleName">Role Name</label>
            <input type="text" id="modalRoleName" name="modalRoleName" class="form-control" placeholder="Enter a role name" tabindex="-1" />
          </div>
          <div class="col-12">
            <h4>Role Permissions</h4>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
			     <thead>	
					<tr>
                    <td class="text-nowrap fw-semibold">Permissions</td>
                    <td>
                      <div class="d-flex">
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="create" name='create_select' onchange="get_Check_All(this.id);" value="" <?php echo $readonly;?> <?php echo $disabled;?> />
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="edit" name='edit_select' onchange="get_Check_All(this.id);" value="" <?php echo $readonly;?> <?php echo $disabled;?> />
                          <label class="form-check-label" for="userManagementCreate">
                            Edit
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="delete1" name='delete_select' onchange="get_Check_All(this.id);" value="" <?php echo $readonly;?> <?php echo $disabled;?>/>
                          <label class="form-check-label" for="userManagementCreate">
                            Delete
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="view" name='view_select' onchange="get_Check_All(this.id);" value="" <?php echo $readonly;?> <?php echo $disabled;?>/>
                          <label class="form-check-label" for="userManagementCreate">
                            View Only
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" id="chk_0" name='admin_select' onchange="getCheckAll('chk_0');" value="" <?php echo $readonly;?> <?php echo $disabled;?>/>
                          <label class="form-check-label" for="userManagementCreate">
                           Admin All
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>					
				</thead>
                <tbody>
                  <!--tr>
                    <td class="text-nowrap fw-semibold">Administrator Access <i class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i></td>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" />
                        <label class="form-check-label" for="selectAll">
                          Select All
                        </label>
                      </div>
                    </td>
                  </tr-->
                 
				  <tr>
                    <td class="text-nowrap fw-semibold">User Management</td>
                    <td>
                      <div class="d-flex">
                        <div class="form-check me-3 me-lg-5">
                          <input class="form-check-input" type="checkbox" id="userManagementRead" />
                          <label class="form-check-label" for="userManagementRead">
                            Read
                          </label>
                        </div>
                        <div class="form-check me-3 me-lg-5">
                          <input class="form-check-input" type="checkbox" id="userManagementWrite" />
                          <label class="form-check-label" for="userManagementWrite">
                            Write
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="userManagementCreate" />
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
                      </div>
                    </td>
                  </tr>
				  	
			<?php 
			$i=0;
			//$menu=$utilObj->getMultipleRow("menu","1 order by id ASC");
			$menuid=explode(",",$rows['menu']);										
			$createMenu=explode(",",$rows['createMenu']);	
			$editMenu=explode(",",$rows['editMenu']);
			$deleteMenu=explode(",",$rows['deleteMenu']);
			$viewMenu=explode(",",$rows['viewMenu']);
			//print_r($editedmenu);																				
			
			foreach($menu as $e_rec){
			$i++;				
				
				if (in_array($e_rec['id'], $menuid)){	$menuid_ch = "checked"; }else{ $menuid_ch = "";	}										  
				if (in_array($e_rec['id'], $createMenu)){ $createMenu_ch = "checked"; }else{ $createMenu_ch = ""; }				
				if (in_array($e_rec['id'], $editMenu)){ $editMenu_ch = "checked"; }else{ $editMenu_ch = ""; }
				if (in_array($e_rec['id'], $deleteMenu)){ $deleteMenu_ch = "checked"; }else{ $deleteMenu_ch = ""; }
				if (in_array($e_rec['id'], $viewMenu)){ $viewMenu_ch = "checked"; }else{ $viewMenu_ch = ""; }
				
				echo '<tr>';
				echo '<td >'.$e_rec["name"].'</td>';?>
				<td>
				  <div class="d-flex">
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkEdit"/>
                          <label class="form-check-label" for="userManagementCreate">
                            Edit
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
                          <label class="form-check-label" for="userManagementCreate">
                            Delete
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkView" />
                          <label class="form-check-label" for="userManagementCreate">
                            View Only
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
                          <label class="form-check-label" for="userManagementCreate">
                           Admin All
                          </label>
                        </div>
                      </div>
				</td>	
				<?php
				echo '</tr>';
			if($e_rec['page']=='')
			{	
			//echo"1";
			$submenu=$utilObj->getMultipleRow("submenu","mid='".$e_rec['id']."' order by name ASC ");
			foreach($submenu as $e_rec1){
			$i++;
				//if($e_rec["name"]=='Masters'){ $lb_ap1 = 'Master '; }else{ $lb_ap1=""; }
				//if($e_rec["name"]=='Purchase' || $e_rec["name"]=='Sale'){ $lb_ap2 = 'Transactions Vouchers '; }else{ $lb_ap2=""; }
				//if($e_rec["name"]=='Report'){ $lb_ap3 = 'Reports '; }else{ $lb_ap3=""; }
				 
				if (in_array($e_rec1['id'], $menuid)){	$menuid_ch = "checked"; }else{ $menuid_ch = "";	}										  
				if (in_array($e_rec1['id'], $createMenu)){ $createMenu_ch = "checked"; }else{ $createMenu_ch = ""; }			
				if (in_array($e_rec1['id'], $editMenu)){ $editMenu_ch = "checked"; }else{ $editMenu_ch = ""; }
				if (in_array($e_rec1['id'], $deleteMenu)){ $deleteMenu_ch = "checked"; }else{ $deleteMenu_ch = ""; }
				if (in_array($e_rec1['id'], $viewMenu)){ $viewMenu_ch = "checked"; }else{ $viewMenu_ch = ""; }
									
				echo '<tr>';
				echo '<td>'.$lb_ap1.''.$lb_ap2.''.$lb_ap3.''.$e_rec1["name"].'</td>';?>
				<td>
				  <div class="d-flex">
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkEdit" />
                          <label class="form-check-label" for="userManagementCreate">
                            Edit
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
                          <label class="form-check-label" for="userManagementCreate">
                            Delete
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkView" />
                          <label class="form-check-label" for="userManagementCreate">
                            View Only
                          </label>
                        </div>
						<div class="form-check">
                          <input class="form-check-input" type="checkbox" class="chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
                          <label class="form-check-label" for="userManagementCreate">
                           Admin All
                          </label>
                        </div>
                      </div>
				</td>		
				<?php 
				echo '</tr>';					
				}
				
			}else{
				//echo"2";
				
			}							
		}		
		?>	<input type='hidden' id='chkcount' name='chkcount' value='<?php echo $i; ?>'>	
				  
				  
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->