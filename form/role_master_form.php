
<!-- Add Role Modal -->
<div class="modal fade  form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<!--div  class="modal fade form-validate show" id="addRecordModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog"-->
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Add Role </h3>
         
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" class="row g-3" data-parsley-validate action="../role_master_list.php" method="post" data-rel="myForm" >
		<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>
			<input type="hidden"  name="menuselect1" id="menuselect1" value="<?php echo $rows['edited'];?>"/>
				
			<input type="hidden"  name="menuselect" id="menuselect" value="<?php echo $rows['menu'];?>"/>
			<input type="hidden"  name="createMenu" id="createMenu" value="<?php echo $rows['createMenu'];?>"/>			
			<input type="hidden"  name="editMenu" id="editMenu" value="<?php echo $rows['editMenu'];?>"/>
			<input type="hidden"  name="deleteMenu" id="deleteMenu" value="<?php echo $rows['deleteMenu'];?>"/>
			<input type="hidden"  name="viewMenu" id="viewMenu" value="<?php echo $rows['viewMenu'];?>"/>
		
			<div class="col-12 mb-4">
				<label class="form-label" for="modalRoleName">Role </label>
				<input type="text" id="role" name="role" class="form-control" placeholder="Enter a role name" tabindex="-1" value="<?php echo $rows['role'];?>" onchange="check_name(this.value);"/>
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
						<div class="d-flex" style = "justify-content: space-between; ";>
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
           
			<?php 
			$i=0;
			$menu=$utilObj->getMultipleRow("menu","1 order by id ASC");
			$menuid=explode(",",$rows['menu']);
			$createMenu=explode(",",$rows['createMenu']);
			$editMenu=explode(",",$rows['editMenu']);
			$deleteMenu=explode(",",$rows['deleteMenu']);
			$viewMenu=explode(",",$rows['viewMenu']);
			//print_r($editedmenu);																				
			
			foreach($menu as $e_rec) {

				$i++;

				if (in_array($e_rec['id'], $menuid)) { $menuid_ch = "checked"; } else { $menuid_ch = ""; }
				if (in_array($e_rec['id'], $createMenu)){ $createMenu_ch = "checked"; }else{ $createMenu_ch = ""; }
				if (in_array($e_rec['id'], $editMenu)){ $editMenu_ch = "checked"; }else{ $editMenu_ch = ""; }
				if (in_array($e_rec['id'], $deleteMenu)){ $deleteMenu_ch = "checked"; }else{ $deleteMenu_ch = ""; }
				if (in_array($e_rec['id'], $viewMenu)){ $viewMenu_ch = "checked"; }else{ $viewMenu_ch = ""; }
				
				echo '<tr>';
				echo '<td >'.$e_rec["name"].'</td>';?>
				<td>
				  <div class="d-flex" style = "justify-content: space-between; ";>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
						  
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkEdit"/>
                          <label class="form-check-label" for="userManagementCreate">
                            Edit
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
                          <label class="form-check-label" for="userManagementCreate">
                            Delete
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="checkView" />
                          <label class="form-check-label" for="userManagementCreate">
                            View Only
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class=" form-check-input chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
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
				  <div class="d-flex" style = "justify-content: space-between; ";>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
                          <label class="form-check-label" for="userManagementCreate">
                            Create
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class=" form-check-input chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkEdit" />
                          <label class="form-check-label" for="userManagementCreate">
                            Edit
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
                          <label class="form-check-label" for="userManagementCreate">
                            Delete
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="checkView" />
                          <label class="form-check-label" for="userManagementCreate">
                            View Only
                          </label>
                        </div>
						<div class="form-check">
                          <input  type="checkbox" class="form-check-input chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec1['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
                          <label class="form-check-label" for="userManagementCreate">
                           Admin All
                          </label>
                        </div>
                      </div>
				</td>		
				<?php 
				echo '</tr>';	
				//------------show submenu of submenu--------------------------------
				
						if($e_rec1['page']=='')
						{	
						//echo"1";
						$submenu1=$utilObj->getMultipleRow2("sub_child","mid='".$e_rec1['id']."' order by name ASC ");
						//var_dump($submenu1);
						foreach($submenu1 as $e_rec2){
						$i++;
							//if($e_rec["name"]=='Masters'){ $lb_ap1 = 'Master '; }else{ $lb_ap1=""; }
							//if($e_rec["name"]=='Purchase' || $e_rec["name"]=='Sale'){ $lb_ap2 = 'Transactions Vouchers '; }else{ $lb_ap2=""; }
							//if($e_rec["name"]=='Report'){ $lb_ap3 = 'Reports '; }else{ $lb_ap3=""; }
							 
							if (in_array($e_rec2['id'], $menuid)){	$menuid_ch = "checked"; }else{ $menuid_ch = "";	}										  
							if (in_array($e_rec2['id'], $createMenu)){ $createMenu_ch = "checked"; }else{ $createMenu_ch = ""; }			
							if (in_array($e_rec2['id'], $editMenu)){ $editMenu_ch = "checked"; }else{ $editMenu_ch = ""; }
							if (in_array($e_rec2['id'], $deleteMenu)){ $deleteMenu_ch = "checked"; }else{ $deleteMenu_ch = ""; }
							if (in_array($e_rec2['id'], $viewMenu)){ $viewMenu_ch = "checked"; }else{ $viewMenu_ch = ""; }
												
							echo '<tr>';
							echo '<td>'.$lb_ap1.''.$lb_ap2.''.$lb_ap3.''.$e_rec2["name"].'</td>';?>
							<td>
							  <div class="d-flex" style = "justify-content: space-between; ";>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec2['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
									  <label class="form-check-label" for="userManagementCreate">
										Create
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class=" form-check-input chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec2['id']; ?>" <?php echo $disabled; ?> name="checkEdit" />
									  <label class="form-check-label" for="userManagementCreate">
										Edit
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec2['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
									  <label class="form-check-label" for="userManagementCreate">
										Delete
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec2['id']; ?>" <?php echo $disabled; ?> name="checkView" />
									  <label class="form-check-label" for="userManagementCreate">
										View Only
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec2['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
									  <label class="form-check-label" for="userManagementCreate">
									   Admin All
									  </label>
									</div>
								  </div>
							</td>		
							<?php 
							echo '</tr>';
							
							
							if($e_rec2['page']=='')
							{	
							//echo"1";
							$submenu2=$utilObj->getMultipleRow2("subsub_child","mid='".$e_rec2['id']."' order by name ASC ");
							//var_dump($submenu1);
							foreach($submenu2 as $e_rec3){
							$i++;
							//if($e_rec["name"]=='Masters'){ $lb_ap1 = 'Master '; }else{ $lb_ap1=""; }
							//if($e_rec["name"]=='Purchase' || $e_rec["name"]=='Sale'){ $lb_ap2 = 'Transactions Vouchers '; }else{ $lb_ap2=""; }
							//if($e_rec["name"]=='Report'){ $lb_ap3 = 'Reports '; }else{ $lb_ap3=""; }
							 
							if (in_array($e_rec3['id'], $menuid)){	$menuid_ch = "checked"; }else{ $menuid_ch = "";	}										  
							if (in_array($e_rec3['id'], $createMenu)){ $createMenu_ch = "checked"; }else{ $createMenu_ch = ""; }			
							if (in_array($e_rec3['id'], $editMenu)){ $editMenu_ch = "checked"; }else{ $editMenu_ch = ""; }
							if (in_array($e_rec3['id'], $deleteMenu)){ $deleteMenu_ch = "checked"; }else{ $deleteMenu_ch = ""; }
							if (in_array($e_rec3['id'], $viewMenu)){ $viewMenu_ch = "checked"; }else{ $viewMenu_ch = ""; }
												
							echo '<tr>';
							echo '<td>'.$lb_ap1.''.$lb_ap2.''.$lb_ap3.''.$e_rec3["name"].'</td>';?>
							<td>
							  <div class="d-flex" style = "justify-content: space-between; ";>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkCreate_<?php echo $i; ?> create" <?php echo $createMenu_ch ?> value="<?php echo $e_rec3['id']; ?>" <?php echo $disabled; ?> name="checkCreate" />
									  <label class="form-check-label" for="userManagementCreate">
										Create
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class=" form-check-input chkEdit_<?php echo $i; ?> edit" <?php echo $editMenu_ch ?> value="<?php echo $e_rec3['id']; ?>" <?php echo $disabled; ?> name="checkEdit" />
									  <label class="form-check-label" for="userManagementCreate">
										Edit
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkDelete_<?php echo $i; ?> delete1" <?php echo $deleteMenu_ch ?> value="<?php echo $e_rec3['id']; ?>" <?php echo $disabled; ?> name="checkDelete" />
									  <label class="form-check-label" for="userManagementCreate">
										Delete
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chkView_<?php echo $i; ?> view" <?php echo $viewMenu_ch ?> value="<?php echo $e_rec3['id']; ?>" <?php echo $disabled; ?> name="checkView" />
									  <label class="form-check-label" for="userManagementCreate">
										View Only
									  </label>
									</div>
									<div class="form-check">
									  <input  type="checkbox" class="form-check-input chk_<?php echo $i; ?> same" <?php echo $menuid_ch ?> value="<?php echo $e_rec3['id']; ?>" <?php echo $disabled; ?> name="check" id="chk_<?php echo $i;?>" onchange="getcheck('chk_<?php echo $i;?>');" />
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
							
						}else{
							//echo"2";
							
						}

				//-----------------------------------------------------------------------
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
            <!--button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button-->
			<?php if($_REQUEST['Task']=='' || $_REQUEST['Task']=='update') {?>
				<input type="button" class="btn btn-primary me-sm-3 me-1" name="Add" id="Add" value="Submit" onClick="mysubmit(0);"/>
			<?php }?>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);">Cancel</button>
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

		var table = "role_master";
		var col = "role";

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'check_name',val:val,table:table,col:col },
			success:function(data)
			{	
				if(data>0) {
					alert("This Name is already Exist");
					$("#role").val('');
				}
				else { return false; }
			}
		});

	}
</script>
