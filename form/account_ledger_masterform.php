
<!-- Add Role Modal -->
<div class="modal fade  form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<!--div  class="modal fade form-validate show" id="addRecordModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog"-->
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="remove_urldata();" aria-label="Close"></button>
        <div class="text-center mb-4">
        	<h3 class="role-title">Add New Ledger </h3>
        </div>
        <!-- Add role form -->
        <form id="" data-parsley-validate class="row g-3" action="../account_ledger_masterlist.php"  method="post" data-rel="myForm">

			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "account_ledger";?>"/>

      
			<div class="col-md-3">
				<label class="form-label" for="formValidationSelect2">Basic Group Name <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="group_name" name="group_name" onchange="getfield(this.value);show_mailingdetails();" <?php echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<?php
						$data=$utilObj->getMultipleRow("group_master","1 group by id");
						foreach($data as $info) {
							if($info["id"]==$rows['group_name']){echo $select="selected";}else{echo $select="";}
							echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["group_name"].'</option>';
						}  
					?>
				</select>
			</div>

			<input type="hidden" name="actgrp" id="actgrp" value="<?php echo $rows['actgrp']; ?>">
		  
			<div class="col-md-3">
				<label class="form-label"> Name <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="name" class="required form-control"  <?php echo $readonly;?> placeholder=" Account Name" name="name"  value="<?php echo $rows['name']; ?>" onchange="check_name(this.value);" />
			</div>

			<div class="col-md-3" id="interst_calculation_div" style="display:none">
				<label class="form-label" for="formValidationName"> Interest Calculations <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="interst" name="interst" class="required form-control" <?php echo $readonly;?>  placeholder="Interest Calculations" value="<?php echo $rows['interst']; ?>"  />
			</div>
			
			<div class="col-md-3 only_debtorscreditors"  style="display:none">
				<label class="form-label" for="formValidationName"> Credit Limits (Value in Rs) <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="credit_limit" name="credit_limit" class="required form-control"  <?php echo $readonly;?> placeholder="Credit Limits" value="<?php echo $rows['credit_limit']; ?>"  />
			</div>
		  
			<div class="col-md-3 only_debtorscreditors" style="display:none">
				<label class="form-label" for="formValidationName"> Credit Period (Value in Days)<span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="credit_period" name="credit_period" class="required form-control" <?php echo $readonly;?>  placeholder="Credit Period"  value="<?php echo $rows['credit_period']; ?>" />
			</div>

			<div class="col-md-3 only_debtorscreditors" style="display:none">
				<label class="form-label" for="formValidationName">Bank Payment details<span class="required required_lbl" style="color:red;">*</span></label>
				<br>
				<input type="radio" id="yes" name="bank_paydetails" onclick="show_paydetails()" <?php echo $disabled;?> class="required form-check-input" value="1" <?php if($rows['bank_paydetails']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="bank_paydetails" onclick="show_paydetails()" <?php echo $disabled;?> class="required form-check-input" value="0" <?php if($rows['bank_paydetails']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>

			<div class="col-md-3 only_debtorscreditors"  style="display:none">
				<label class="form-label">Price Level <span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="price_level" onclick="show_pricelvl()" <?php echo $disabled;?> class="required form-check-input" value="1" <?php if($rows['price_level']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="price_level" onclick="no_pricelvl()" <?php echo $disabled;?> class="required form-check-input" value="0" <?php if($rows['price_level']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>
        
			<div class="col-md-3 only_debtorscreditors" style="display:none">
				<label class="form-label" for="">Price Level Name</label>
				<select id="price_level_group" name="price_level_group" <?php  echo $disabled ;?> class=" form-select select2" data-allow-clear="true">
				<option value="">Select</option>
				<?php
			
					$data=$utilObj->getMultipleRow("pricelist","1 group by price_level"); 
					foreach($data as $info){
						if($info["price_level"]==$rows['price_level_id']){echo $select="selected";}else{echo $select="";}
						echo  '<option value="'.$info["price_level"].'" '.$select.'>'.$info["price_level"].'</option>';
					}
				?>
				</select>
			</div>

			<div class="col-12">
				<hr class="mt-0" />
			</div>
			
			<div class="col-md-3 ">
				<label class="form-label" for="formValidationName"> Inventory Allocation  <span class="required required_lbl" style="color:red;">*</span> </label> <br>
				<!-- <input type="text" id="inventory_allocation" name="inventory_allocation" class="required form-control" <?php echo $readonly;?> placeholder="Inventory Allocation" value="<?php echo $rows['inventory_allocation']; ?>"  /> -->

				<input type="radio" id="yes" name="inventory_allocation" <?php echo $disabled;?> class="price_level required form-check-input" value="1" <?php if($rows['inventory_allocation']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="inventory_allocation" <?php echo $disabled;?> class=" price_level required form-check-input" value="0" <?php if($rows['inventory_allocation']=="0" || $rows['inventory_allocation']=="")echo "checked"?>/><label>&nbsp; No</label>
			</div>

			<div class="col-md-3 ">
				<label class="form-label" for="formValidationName"> Cost Tracking <span class="required required_lbl" style="color:red;">*</span> </label> <br>
				<!-- <input type="text" id="cost_tracking" name="cost_tracking" class=" requird form-control"  <?php echo $readonly;?> placeholder=" Cost Tracking"  value="<?php echo $rows['cost_tracking']; ?>" /> -->

				<input type="radio" id="yes" name="cost_tracking" <?php echo $disabled;?> class="price_level required form-check-input" value="1" <?php if($rows['cost_tracking']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="cost_tracking" <?php echo $disabled;?> class=" price_level required form-check-input" value="0" <?php if($rows['cost_tracking']=="0" || $rows['cost_tracking']=="")echo "checked"?>/><label>&nbsp; No</label>
			</div>
		  
		  
			<!-- <div class="col-md-6 PL_group" style="display:none">
				<label class="form-label">Opening Balances Field <span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="opening_balance" onclick="show_taxdetails();" <?php echo $disabled;?> class=" required form-check-input" value="1" <?php if($rows['opening_balance']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="opening_balance" onclick="edit_taxdetails();" <?php echo $disabled;?> class="required form-check-input" value="0" <?php if($rows['opening_balance']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div> -->

			<div class="col-md-3 PL_group" style="display:none">
                
            	<label class="form-label">Opening Balance Method <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="op_method" name="op_method" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
					<option value="">Select Method</option>
					<option value="Credit" <?php if($rows["op_method"]=='Credit') echo $select='selected'; else $select='';?>>Credit</option>
					<option value="Debit" <?php if($rows["op_method"]=='Debit') echo $select='selected'; else $select='';?>>Debit</option>
                </select>
        	</div>

			<div class="col-md-3 PL_group" style="display:none">
				<label class="form-label"> Opening balance </label>
				<input type="text" id="op_balance" class="form-control"  <?php echo $readonly;?> placeholder="Enter Type" name="op_balance" value="<?php echo $rows['op_balance'];?>"/>
        	</div>
			
			<div class="col-md-3 PL_group" style="display:none" >
				<label class="form-label">Mailing Details<span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="mailing"  <?php echo $disabled;?> onclick="show_mailingdetails();" class=" required form-check-input" value="1" <?php if($rows['mailing']=="1" )echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="mailing" <?php echo $disabled;?> onclick="show_mailingdetails();" class="required form-check-input" value="0" <?php if($rows['mailing']=="0" || $rows['mailing']=="")echo "checked"?>/><label>&nbsp; No</label>
			</div>
			
			<div class="col-md-3 bank" style="display:none">
				<label class="form-label">Bank Reconciliation <span class="required required_lbl" style="color:red;">*</span>  </label>
				<br>
				<input type="radio" id="yes" name="bank_reconcilation"  <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="1" <?php if($rows['bank_reconcilation']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="bank_reconcilation" <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="0" <?php if($rows['bank_reconcilation']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>

			<div class="col-md-3 gsttax_div" style="display:none">
				<label class="form-label">Bill wise details <span class="required required_lbl" style="color:red;">*</span>  </label>
				<br>
				<input type="radio" id="yes" name="bill_wise_details"  <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="1" <?php if($rows['bill_wise_details']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="bill_wise_details" <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="0" <?php if($rows['bill_wise_details']=="0" || $rows['bill_wise_details']=="")echo "checked"?>/><label>&nbsp; No</label>
			</div>
			
			<div class="col-md-6 bank" style="display:none">
			<label class="form-label">Cheque Book Register <span class="required required_lbl" style="color:red;">*</span>  </label>
			<br>
				<input type="radio" id="yes" name="cheque_book_registor"  <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="1" <?php if($rows['cheque_book_registor']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="cheque_book_registor" <?php echo $disabled;?> class="  bank_bankOD form-check-input required" value="0" <?php if($rows['cheque_book_registor']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>
			
			
			<div class="col-md-6 bank" style="display:none">
				<label class="form-label">Cheque Book Printing <span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="cheque_book_printing" <?php echo $disabled;?> class="   bank_bankOD form-check-input required" value="1" <?php if($rows['cheque_book_printing']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="cheque_book_printing" <?php echo $disabled;?> class="  bank_bankOD form-check-input  required" value="0" <?php if($rows['cheque_book_printing']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>
			
			<div class="col-md-3 tdstax_div" style="display:none">
				<label class="form-label">TDS Tax Details <span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="tds_tax_details" <?php echo $disabled;?> onclick="show_tdstax_details();" class=" required form-check-input tds_tax_detail" value="1" <?php if($rows['tds_tax_details']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="tds_tax_details" <?php echo $disabled;?> onclick="show_tdstax_details();" class="required form-check-input tds_tax_detail" value="0" <?php if($rows['tds_tax_details']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>

			<div class="col-md-3 tdstax_div" style="display:none">
				<label class="form-label">Linking with Inventory<span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="linking_inventory" <?php echo $disabled;?> class=" required form-check-input tds_tax_detail" value="1" <?php if($rows['linking_inventory']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="linking_inventory" <?php echo $disabled;?> class="required form-check-input tds_tax_detail" value="0" <?php if($rows['linking_inventory']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>
			
			<div class="col-md-6 gsttax_div" style="display:none">
				<label class="form-label">GST Tax Allocation <span class="required required_lbl" style="color:red;">*</span> </label>
				<br>
				<input type="radio" id="yes" name="gst_tax_allocation" <?php echo $disabled;?> onclick="show_gsttaxdetails();" class=" required form-check-input" value="1" <?php if($rows['gst_tax_allocation']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" id="no" name="gst_tax_allocation" <?php echo $disabled;?> onclick="show_gsttaxdetails();"  class="required form-check-input" value="0" <?php if($rows['gst_tax_allocation']=="0")echo "checked"?>/><label>&nbsp; No</label>
			</div>

			<div class="col-md-3 gstledger_div" style="display:none">
				<label class="form-label">GST Ledger Type <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="gst_ledger_type" name="gst_ledger_type" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
					<option value="">Select Method</option>
					<option value="Default" <?php if($rows["gst_ledger_type"]=='Default') echo $select='selected'; else $select='';?>>Default</option>
					<option value="Manual" <?php if($rows["gst_ledger_type"]=='Manual') echo $select='selected'; else $select='';?>>Manual</option>
                </select>
			</div>

			<div class="col-md-3 gstledger_div" style="display:none">
				<label class="form-label">GST Ledger Usage <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="gst_ledger_usage" name="gst_ledger_usage" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
					<option value="">Select Method</option>
					<option value="Purchase" <?php if($rows["gst_ledger_usage"]=='Purchase') echo $select='selected'; else $select='';?>>Purchase</option>
					<option value="Sale" <?php if($rows["gst_ledger_usage"]=='Sale') echo $select='selected'; else $select='';?>>Sale</option>
                </select>
			</div>

			<div class="col-md-3 gstledger_div" style="display:none">
				<label class="form-label">GST <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="gst_type" name="gst_type" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
					<option value="">Select Method</option>
					<option value="IGST" <?php if($rows["gst_type"]=='IGST') echo $select='selected'; else $select='';?>>IGST</option>
					<option value="CGST" <?php if($rows["gst_type"]=='CGST') echo $select='selected'; else $select='';?>>CGST</option>
					<option value="SGST" <?php if($rows["gst_type"]=='SGST') echo $select='selected'; else $select='';?>>SGST</option>
                </select>
			</div>
			
			<!-- --------------------- Mailling Details Feilds --------------------- -->
			<div  id="mailing_details_div" class="" style="display:none;">
				<div class="col-12">
					<hr class="mt-0" />
					<h6 class="fw-semibold">Mailing Details</h6>
					
				</div>
				<div class="row" id="address_div">
					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> Name For Print  <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_nameforprint" name="mail_nameforprint" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter Name For Print"  value="<?php echo $rows['mail_nameforprint']; ?>" />
					</div>

					<?php $i=0;
					if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {
						$add=$utilObj->getMultipleRow("account_ledger_address"," al_id='".$rows['id']."' "); 
									
					} else {
						$add[0]['ID']=1;						
						//$date=date('d/m/Y');
					}
					foreach( $add as $adds)
					{
						$i++;
				?>
					<div class="col-md-4 " id="add_div<?php echo $i;?>">
						<label class="form-label" id="Address_<?php echo $i; ?>">Address<?php echo $i; ?> <span class="required required_lbl" style="color:red;">*</span></label>
						<br>
						<textarea id="mail_address<?php echo $i; ?>" name="mail_address<?php echo $i; ?>" class="  form-control mailing_field" placeholder="Enter Address<?php echo $i; ?>"> <?php echo $adds['address']; ?></textarea>
						
						<?php if($_REQUEST['PTask']!='view') { ?>
							<i class='fa fa-close' id='deleteRow_<?php echo $i;?>' onclick='deleterow(this.id)' style='cursor: pointer;'></i>
						<?php } ?>
					</div> 
				<?php } ?>
				   <input type="hidden" id="address_cnt" name="address_cnt" value="<?php echo $i; ?>">
				</div>
				<?php if($_REQUEST['PTask']!='view') { ?>
					<button type="button" class="btn1 btn-warning addbtn btn-xs "  id="addmore" onclick="add_address('address_div');">Add more</button>
				<?php } ?>
			   
				<div class="row">
				   	<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> State  <span class="required required_lbl" style="color:red;">*</span> </label>
						<select id="mail_state" name="mail_state" <?php  echo $disabled ;?> class="  mailing_field form-select select2" data-allow-clear="true">
						  	<option value="">Select</option>
							<?php
								$data=$utilObj->getMultipleRow("states","1 group by id"); 
								foreach($data as $info) {
									if($info["code"]==$rows['mail_state']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["code"].'" '.$select.'>'.$info["name"].'</option>';
								}
							?>
						</select>
					</div> 
				  
					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> PIN  <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_pin" name="mail_pin" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter PIN No"  value="<?php echo $rows['mail_pin']; ?>" />
					</div>
				  
					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> Contact No.1. <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_contactno1" name="mail_contactno1" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter Contact No.1"  value="<?php echo $rows['mail_contactno1']; ?>" />
					</div> 
					
					<div class="col-md-4 ">
						<label class="form-label" for=""> Contact No.2. </label>
						<input type="text" id="mail_contactno2" name="mail_contactno2" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter Contact No.2"  value="<?php echo $rows['mail_contactno1']; ?>" />
					</div>
					
					<br>
					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> Email <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_emailno" name="mail_emailno" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter Email NO"  value="<?php echo $rows['mail_emailno']; ?>" />
					</div> 

					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> PAN No. <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_panno" name="mail_panno" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter PAN NO."  value="<?php echo $rows['mail_panno']; ?>" />
					</div>
				  
					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> GST No. <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_gstno" name="mail_gstno" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter GST NO"  value="<?php echo $rows['mail_gstno']; ?>" />
					</div>	

					<div class="col-md-4 ">
						<label class="form-label" for="formValidationName"> FSSAI No. <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="mail_fassaino" name="mail_fassaino" class="  form-control mailing_field"  <?php echo $readonly;?> placeholder=" Enter FSSAI No."  value="<?php echo $rows['mail_fassaino']; ?>" />
					</div>
				</div>
			</div>
				
			<!-- ---------------------------- Bank Payment Details ---------------------------- -->
			<div id="bank_paydetails_div" class="" style="display:none;">
				<div class="col-12">
				    <hr class="mt-0" />
					<h6 class="fw-semibold">Bank Payment Details</h6>
				</div>

				<div class="row">
					<div class="col-md-3">
						<label class="form-label" for="formValidationName">Bank Account No<span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="bank_acc_no" name="bank_acc_no" class="form-control" <?php echo $readonly;?> placeholder="Enter Account no"  value="<?php echo $rows['bank_acc_no']; ?>" />
					</div>

					<div class="col-md-3">
						<label class="form-label" for="formValidationName">Bank Name<span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="bank_name" name="bank_name" class="form-control" <?php echo $readonly;?> placeholder="Enter Bank Name"  value="<?php echo $rows['bank_name']; ?>" />
					</div>

					<div class="col-md-3">
						<label class="form-label" for="formValidationName">IFSC Code<span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="ifsc" name="ifsc" class="form-control" <?php echo $readonly;?> placeholder="Enter code"  value="<?php echo $rows['ifsc']; ?>" />
					</div>

					<div class="col-md-3">
						<label class="form-label" for="formValidationName">Branch Name<span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="branch_name" name="branch_name" class="form-control" <?php echo $readonly;?> placeholder="Enter Account no" value="<?php echo $rows['branch_name']; ?>" />
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-3">
						<label class="form-label" for="formValidationName">UPI ID<span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="upi_id" name="upi_id" class="form-control" <?php echo $readonly;?> placeholder="Enter Account no"  value="<?php echo $rows['upi_id']; ?>" />
					</div>

					<div class="col-md-3">
						<label class="form-label" for="formValidationName">UPI Mobile No<span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="upi_mob_no" name="upi_mob_no" class="form-control" <?php echo $readonly;?> placeholder="Enter Account no"  value="<?php echo $rows['upi_mob_no']; ?>" />
					</div>
				</div>
			</div>

			<!-- ---------------------------- TDS TAX Details Fields ---------------------------- -->
			<div  id="tdstax_details_div" class=" " style="display:none;">
				<div class="col-12">
				    <hr class="mt-0" />
					<h6 class="fw-semibold">TDS TAX Details</h6>
				</div>
			
				<div class="row">
					<div class="col-md-6 ">
						<label class="form-label" for="formValidationName"> TDS Deductor <span class="required required_lbl" style="color:red;">*</span> </label>
						<input type="text" id="tdstax_deductor" name="tdstax_deductor" class="  form-control tdstax_field"  <?php echo $readonly;?> placeholder=" Enter TDS Deductor"  value="<?php echo $rows['tdstax_deductor']; ?>" />
					</div>
					<div class="col-md-6">
						<label class="form-label" for="formValidationSelect2">Deductee Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="tdstax_deducteetype" name="tdstax_deducteetype" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">

							<option value="">Select</option>
							<option  value="Company Resident" <?php if($rows['tdstax_deducteetype']=='Company Resident'){ echo 'selected';}else{ echo ' ';} ?> >Company Resident</option>
							<option  value="Company Non Resident" <?php if($rows['tdstax_deducteetype']=='Company Non Resident'){ echo 'selected';}else{ echo ' ';} ?> >Company Non Resident</option>
							<option  value="Co-Operative Society Resident" <?php if($rows['tdstax_deducteetype']=='Co-Operative Society Resident'){ echo 'selected';}else{ echo ' ';} ?> >Co-Operative Society Resident</option>
							<option  value="Co-Operative Society Non Resident" <?php if($rows['tdstax_deducteetype']=='Co-Operative Society Non Resident'){ echo 'selected';}else{ echo ' ';} ?> >Co-Operative Society Non Resident</option>
							<option  value="Individual / HUF Resident" <?php if($rows['tdstax_deducteetype']=='Individual / HUF Resident'){ echo 'selected';}else{ echo ' ';} ?> >Individual / HUF Resident</option>
							<option  value="Individual / HUF Non Resident" <?php if($rows['tdstax_deducteetype']=='Individual / HUF Non Resident'){ echo 'selected';}else{ echo ' ';} ?> >Individual / HUF Non Resident</option>
							<option  value="Partnership" <?php if($rows['tdstax_deducteetype']=='Partnership'){ echo 'selected';}else{ echo ' ';} ?> >Partnership</option>
							<option  value="Local Authority" <?php if($rows['tdstax_deducteetype']=='Local Authority'){ echo 'selected';}else{ echo ' ';} ?> >Local Authority</option>
							<option  value="Government" <?php if($rows['tdstax_deducteetype']=='Government'){ echo 'selected';}else{ echo ' ';} ?> >Government</option>
							<option  value="Association of Person" <?php if($rows['tdstax_deducteetype']=='Association of Person'){ echo 'selected';}else{ echo ' ';} ?> >Association of Person</option>
							<option  value="Body of Individuals" <?php if($rows['tdstax_deducteetype']=='Body of Individuals'){ echo 'selected';}else{ echo ' ';} ?> >Body of Individuals</option>
								
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="form-label">TDS Deduction entry <span class="required required_lbl" style="color:red;">*</span> </label>
						<br>
						<input type="radio" id="yes" name="tdstax_tds_deductionentry" <?php echo $disabled;?> class="  form-check-input tdstax_field" value="1" <?php if($rows['tdstax_tds_deductionentry']=="1")echo "checked"?>/><label>&nbsp; Yes</label>
						&nbsp;&nbsp;&nbsp;
						<input type="radio" id="no" name="tdstax_tds_deductionentry" <?php echo $disabled;?> class=" form-check-input tdstax_field" value="0" <?php if($rows['tdstax_tds_deductionentry']=="0")echo "checked"?>/><label>&nbsp; No</label>
					</div>
				</div>
			</div> 
        <!-- ---------------------------------- gst tax fields ---------------------------------- -->
		<div id="gsttax_details_div" class=" " style="display:none;">
			<div class="col-12">
				<hr class="mt-0" />
				<h6 class="fw-semibold">GST TAX Details</h6>
			</div>
		
			<div class="row">
				<div class="col-md-3">
					<label class="form-label">GST Applicable  <span class="required required_lbl" style="color:red;">*</span> </label>
					<br>
					<input type="radio" id="yes" name="gsttax_gst_applicable" <?php echo $disabled;?> class="  form-check-input gsttax_field" value="1" <?php if($rows['gsttax_gst_applicable']=="1" || $rows['gsttax_gst_applicable']=="")echo "checked"?>/><label>&nbsp; Yes</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" id="no" name="gsttax_gst_applicable" <?php echo $disabled;?> class=" form-check-input gsttax_field" value="0" <?php if($rows['gsttax_gst_applicable']=="0")echo "checked"?>/><label>&nbsp; No</label>
				</div>
				<div class="col-md-3">
					<label class="form-label" for="formValidationSelect2">Calculate from<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="gsttax_calculatefrom" name="gsttax_calculatefrom" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">
					<option value="">Select</option>
						<option value="Ledger" <?php if($rows['gsttax_calculatefrom']=='Ledger'){ echo 'selected';}else{ echo ' ';} ?> >Ledger</option>
						<option  value="Stock Item" <?php if($rows['gsttax_calculatefrom']=='Stock Item'){ echo 'selected';}else{ echo ' ';} ?> >Stock Item</option>
						<option  value="both" <?php if($rows['gsttax_calculatefrom']=='both'){ echo 'selected';}else{ echo ' ';} ?> >Both Stock item & Ledger</option>
					</select>
				</div>

				<div class="col-md-3">
					<label class="form-label">Description</label>
					<input type="text" id="description" class="form-control"  <?php echo $readonly;?> placeholder="Description" name="description" value="<?php echo $rows['description'];?>"/>
				</div>

				<div class="col-md-3">
					<label class="form-label"> HSN/SAC</label>
					<input type="text" id="hsn_sac" class="form-control"  <?php echo $readonly;?> placeholder="Enter HSN/SAC" name="hsn_sac" value="<?php echo $rows['hsn_sac'];?>"/>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3">
					<label class="form-label"> Calculation Type <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="cal_type" class="form-control"  <?php echo $readonly;?> placeholder="Enter Type" name="cal_type" value="<?php 
					if($rows['cal_type']=='')
					{
						echo 'On Value';
					}
					else {
						echo $rows['cal_type'];
					}
					?>"/>
				</div>

				<div class="col-md-3">
					<label class="form-label"> Taxability <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="taxability" name="taxability" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="show_gst();">
						<option value="">Select Method</option>
						<option value="Taxable" <?php if($rows["taxability"]=='Taxable') echo $select='selected'; else $select='';?>>Taxable</option>
						<option value="Exempt" <?php if($rows["taxability"]=='Exempt') echo $select='selected'; else $select='';?>>Exempt</option>
						<option value="export_with_tax" <?php if($rows["taxability"]=='export_with_tax') echo $select='selected'; else $select='';?>>Export with tax</option>
						<option value="export_without_tax" <?php if($rows["taxability"]=='export_without_tax') echo $select='selected'; else $select='';?>>Export without tax</option>
					</select>
				</div>

				<div class="col-md-3">
					<label class="form-label"> Is Reverse charge applicable <span class="required required_lbl" style="color:red;">*</span></label>
					<br>
					<input type="radio" id="rev_charge" name="rev_charge" class="form-check-input"value="1" <?php echo $disabled;?> <?php if($rows['rev_charge']=="1"){echo "checked";}?>  <?php echo $disabled;?> /><label>&nbsp;Yes</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" id="rev_charge" name="rev_charge" class="form-check-input" value="0" <?php echo $disabled;?> <?php if($rows['rev_charge']=="0" || $rows['rev_charge']==""){echo "checked";}?> <?php echo $disabled;?> /><label>&nbsp;No</label>
				</div>

				<div class="col-md-3">
					<label class="form-label"> Is In eligible for Input Credit <span class="required required_lbl" style="color:red;">*</span></label>
					<br>
					<input type="radio" id="ineligible_input" name="ineligible_input" class="form-check-input"value="1" <?php echo $disabled;?> <?php if($rows['ineligible_input']=="1"){echo "checked";}?> /><label>&nbsp;Yes</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" id="ineligible_input" name="ineligible_input" class="form-check-input" value="0" <?php echo $disabled;?> <?php if($rows['ineligible_input']=="0" || $rows['ineligible_input']==""){echo "checked";}?>  /><label>&nbsp;No</label>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-3">
					<div id="altunitdiv">
						<label class="form-label">IGST</label>
						<div id="div_altunit">
							<select id="igst" name="igst" <?php echo $disabled;?> class="select2 form-select " onchange="get_gst_data_ledger(this.value);get_sgst_data_ledger(this.value);">
							<?php 
								echo '<option value="">Select IGST</option>';
								// echo '<option value="AddNew">Add New</option>';
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

				<div class="col-md-3">
					<div id="altunitdiv">
						<label class="form-label">CGST </label>
						<div id="div_altunit">
							<select id="cgst" name="cgst" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php 
								echo '<option value="">Select CGST</option>';
								//echo '<option value="AddNew">Add New</option>';
								$record=$utilObj->getMultipleRow("gst_data","1 AND cgst!='' group by cgst");
								foreach($record as $e_rec){
									if($rows['cgst']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cgst"].'</option>';
								}
							?> 
							</select>
						</div>
					</div>
				</div>

				<div class="col-md-3">
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

				<div class="col-md-3">
					<label class="form-label"> CESS </label>
					<input type="text" id="cess" class="form-control" <?php echo $readonly;?> placeholder="Enter" name="cess" value="<?php echo $rows['cess'];?>"/>
				</div>
			</div>
		</div> 
        <!-- ---------------------------------------------------------------------------------------- -->
       
			<div class="col-12 text-center">
				<?php if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='') { ?>	
					<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit" onClick="mysubmit(0);" />
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

function get_gst_data_ledger(id) {
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_gst_data_ledger',id: id},
		success:function(data)
		{	
			$("#cgst").html(data);
		}
	});
}

function get_sgst_data_ledger(id) {
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_sgst_data_ledger',id: id},
		success:function(data)
		{	
			$("#sgst").html(data);
		}
	});
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


// --------------------------------basic grup field hideshow-----------------------------------------------------
function getfield(id) {
	
	var group_name = "";
	var PTask = $("#PTask").val();

	if(PTask=='update') {

		// group_name = $("#group_name").val();
		group_name = $("#actgrp").val();
		
		// Sundry Debtors || Sundry Debtors || Bank OD A/c
		if(group_name=='Sundry Debtors' || group_name=='Sundry Creditors' || group_name=='Bank OD A/c') {
			document.getElementById('interst_calculation_div').style.display="block"
			$("#interst").addClass("required");
		}else{
			document.getElementById('interst_calculation_div').style.display="none"
			$("#interst").removeClass("required");
		}

		// group_name = Sundry Debtors || Sundry Debtors
		if(group_name=='Sundry Debtors' || group_name=='Sundry Creditors') {

			$('.only_debtorscreditors').css('display', 'block');
			$("#credit_limit").addClass("required");
			$("#credit_period").addClass("required");
			$(".price_level").addClass("required");

			// var price_level = $("#price_level").val();
			// if(price_level==1) {
			// 	$("#price_level_group").addClass("required");
			// } else {
			// 	$("#price_level_group").removeClass("required");
			// }
			
		} else {
			$('.only_debtorscreditors').css('display', 'none');
			$("#credit_limit").removeClass("required");
			$("#credit_period").removeClass("required");
			$(".price_level").removeClass("required");
			// $("#price_level_group").removeClass("required");
		}

		// group_name = Bank Accounts || Bank OD A/c
		if(group_name == 'Bank Accounts' || group_name == 'Bank OD A/c') {
			
			$('.bank').css('display', 'block');
			$(".bank_bankOD").addClass("required");
			// $(".bank_reconcilation").addClass("required");
			// $("#cheque_book_registor").addClass("required");
			// $("#cheque_book_printing").addClass("required");
			
		}else{
			// alert("hii");
			$('.bank').css('display', 'none');
			$(".bank_bankOD").removeClass("required");
			// $("#bank_reconcilation").removeClass("required");
			// $("#cheque_book_registor").removeClass("required");
			// $("#cheque_book_printing").removeClass("required");
			
		}
		
		// group_name = Purchase Accounts || Sales Accounts || Direct Expenses || Direct Incomes || Indirect Expenses || Indirect Incomes
		// var arr = new Array("27", "28", "29", "30", "31", "32");
		var arr = new Array("Purchase Accounts", "Sales Accounts", "Direct Expenses", "Direct Incomes", "Indirect Expenses", "Indirect Incomes");

		if(arr.includes(group_name)) {
			
			// alert('none');
			$('.PL_group').css('display', 'none');
			$("#opening_balance").removeClass("required"); 
			$("#costing_method").removeClass("required");
			$("#cal_type").removeClass("required");
			$("#mailing").removeClass("required");
			
		} else {
			// alert('block');
			$('.PL_group').css('display', 'block');
			$("#opening_balance").addClass("required");
			$("#costing_method").addClass("required");
			$("#cal_type").addClass("required");
			$("#mailing").addClass("required");
			
		}

		// var arr = new Array("27", "28", "29", "30", "31", "32");
		// if(arr.includes(group_name)) {
		// 	// alert('group_name');
		// 	$('.PL_group').css('display', 'none');
		// 	// $('.PL_group_mailing').css('display', 'block');
			
		// 	$("#opening_balance").removeClass("required"); 
		// 	$("#costing_method").removeClass("required");
		// 	$("#cal_type").removeClass("required");
		// 	$("#mailing").removeClass("required");
			
		// } else {
		// 	// alert('block');
		// 	$('.PL_group').css('display', 'block');
		// 	// $('.PL_group_mailing').css('display', 'block');
		// 	$("#opening_balance").addClass("required");
		// 	$("#costing_method").addClass("required");
		// 	$("#cal_type").addClass("required");
		// 	$("#mailing").addClass("required");
			
		// }

		// group_name = Purchase Accounts || Sales Accounts || Direct Expenses || Direct Incomes || Indirect Expenses || Indirect Incomes || Sundry Debtors
		if(arr.includes(group_name)||group_name=='Sundry Creditors') {

			// alert('none');
			$('.tdstax_div').css('display', 'block');
			$(".tds_tax_detail").addClass("required");
		} else {
			
			// alert('block');
			$('.tdstax_div').css('display','none');
			$(".tds_tax_detail").removeClass("required");
			
		}

		// group_name = Branches || Drawings || Capital Account || Deductions || Reserves & Surplus || Current Assets || Bank Accounts || Cash-in-hand || Deposits (Asset) || Loans & Advances (Asset) || Employee Advances || Other Advances || Stock-in-hand || Sundry Debtors || Current Liabilities || Duties & Taxes || Provisions || Sundry Creditors || Fixed Assets || Investments || Loans (Liability) || Bank OD A/c || Secured Loans || Unsecured Loans || Misc. Expenses (ASSET) || Suspense A/c || Purchase Accounts || Sales Accounts || Direct Expenses || Direct Incomes || Indirect Expenses || Indirect Incomes || Sundry Debtors
		// var arrB = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14" , "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30","31", "32");
		var arrB = new Array("Branches", "Drawings", "Capital Account", "Deductions", "Reserves & Surplus", "Current Assets", "Bank Accounts", "Cash-in-hand", "Deposits (Asset)", "Loans & Advances (Asset)", "Employee Advances", "Other Advances", "Stock-in-hand", "Sundry Debtors", "Current Liabilities", "Duties & Taxes", "Provisions", "Sundry Creditors", "Fixed Assets", "Investments", "Loans (Liability)", "Bank OD A/c", "Secured Loans", "Unsecured Loans", "Misc. Expenses (Asset)", "Suspense A/c", "Purchase Accounts", "Sales Accounts", "Direct Expenses", "Direct Incomes", "Indirect Expenses", "Indirect Incomes", "Sundry Debtors");

		if(arrB.includes(group_name) ) {

			// alert('none');
			$('.gsttax_div').css('display', 'block');
			$("#gst_tax_allocation").addClass("required");
			$("#bill_wise_details").addClass("required");
		} else {

			// alert('block');
			$('.gsttax_div').css('display', 'block');
			$("#gst_tax_allocation").removeClass("required");
			$("#bill_wise_details").removeClass("required");
		} 

		// Duties & Taxes
		// var arrG = new Array("16");
		var arrG = new Array("Duties & Taxes");

		if(arrG.includes(group_name) ){
			// alert('none');
			$('.gstledger_div').css('display', 'block');
			$("#gst_ledger_type").addClass("required");
			$("#gst_ledger_usage").addClass("required");
			
		}else{
			// alert('block');
			$('.gstledger_div').css('display', 'none');
			$("#gst_ledger_type").removeClass("required");
			$("#gst_ledger_usage").removeClass("required");
			
		}

	} else {
	
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getfield',id: id},
			success:function(data)
			{
				$("#actgrp").val(data);
				group_name = data;
				
				// alert(group_name);
				
				if(group_name=='Sundry Debtors' || group_name=='Sundry Creditors' || group_name=='Bank OD A/c') {

					document.getElementById('interst_calculation_div').style.display="block"
					$("#interst").addClass("required");
				} else {

					document.getElementById('interst_calculation_div').style.display="none"
					$("#interst").removeClass("required");
				}

				// Sundry Debtors || Sundry Creditors
				if(group_name=='Sundry Debtors' || group_name=='Sundry Creditors') {

					$('.only_debtorscreditors').css('display', 'block');
					$("#credit_limit").addClass("required");
					$("#credit_period").addClass("required");
					$(".price_level").addClass("required");
					var price_level = $("#price_level").val();

					// if(price_level==1) {
					// 	$("#price_level_group").addClass("required");
					// } else {
					// 	$("#price_level_group").removeClass("required");
					// }
					
				}else{
					$('.only_debtorscreditors').css('display', 'none');
					$("#credit_limit").removeClass("required");
					$("#credit_period").removeClass("required");
					$(".price_level").removeClass("required");
					// $("#price_level_group").removeClass("required");
				}

				if(group_name == 'Bank Accounts' || group_name == 'Bank OD A/c') {
					
					$('.bank').css('display', 'block');
					$(".bank_bankOD").addClass("required");
					/* $(".bank_reconcilation").addClass("required");
					$("#cheque_book_registor").addClass("required");
					$("#cheque_book_printing").addClass("required"); */
					
				}else{
					// alert("hii");
					$('.bank').css('display', 'none');
					$(".bank_bankOD").removeClass("required");
				/* $("#bank_reconcilation").removeClass("required");
					$("#cheque_book_registor").removeClass("required");
					$("#cheque_book_printing").removeClass("required"); */
					
				}
				
				// var arr = new Array("27", "28", "29", "30", "31", "32");
				var arr = new Array("Purchase Accounts", "Sales Accounts", "Direct Expenses", "Direct Incomes", "Indirect Expenses", "Indirect Incomes");
				if(arr.includes(group_name)) {
					// alert('none');
					$('.PL_group').css('display', 'none');
					$("#opening_balance").removeClass("required"); 
					$("#costing_method").removeClass("required");
					$("#cal_type").removeClass("required");
					$("#mailing").removeClass("required");
					
				} else {
					// alert('block');
					$('.PL_group').css('display', 'block');
					$("#opening_balance").addClass("required");
					$("#costing_method").addClass("required");
					$("#cal_type").addClass("required");
					$("#mailing").addClass("required");
					
				}

				if(arr.includes(group_name)||group_name=='Sundry Creditors' ) {
					// alert('none');
					$('.tdstax_div').css('display', 'block');
					$(".tds_tax_detail").addClass("required");
					
				} else {
					// alert('block');
					$('.tdstax_div').css('display','none');
					$(".tds_tax_detail").removeClass("required");
					
				}
				

				// var arrB = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14" , "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30","31", "32");
				var arrB = new Array("Branches", "Drawings", "Capital Account", "Deductions", "Reserves & Surplus", "Current Assets", "Bank Accounts", "Cash-in-hand", "Deposits (Asset)", "Loans & Advances (Asset)", "Employee Advances", "Other Advances", "Stock-in-hand", "Sundry Debtors", "Current Liabilities", "Duties & Taxes", "Provisions", "Sundry Creditors", "Fixed Assets", "Investments", "Loans (Liability)", "Bank OD A/c", "Secured Loans", "Unsecured Loans", "Misc. Expenses (Asset)", "Suspense A/c", "Purchase Accounts", "Sales Accounts", "Direct Expenses", "Direct Incomes", "Indirect Expenses", "Indirect Incomes", "Sundry Debtors");

				if(arrB.includes(group_name) ){
					// alert('none');
					$('.gsttax_div').css('display', 'block');
					$("#gst_tax_allocation").addClass("required");
					$("#bill_wise_details").addClass("required");
					
				}else{
					//alert('block');
					$('.gsttax_div').css('display', 'block');
					$("#gst_tax_allocation").removeClass("required");
					$("#bill_wise_details").removeClass("required");
					
				} 

				// var arrG = new Array("16");
				var arrG = new Array("Duties & Taxes");
				if(arrG.includes(group_name) ){
					// alert('none');
					$('.gstledger_div').css('display', 'block');
					$("#gst_ledger_type").addClass("required");
					$("#gst_ledger_usage").addClass("required");
					
				}else{
					// alert('block');
					$('.gstledger_div').css('display', 'none');
					$("#gst_ledger_type").removeClass("required");
					$("#gst_ledger_usage").removeClass("required");
					
				}
			}
		});

	}

	// var group_name = $("#group_name").val();
	// alert(group_name);
	 
}


// ------------------------ mailing details field function ------------------------
	function show_mailingdetails(){

		var group_name = $("#group_name").val();
		var mail=$('input[name="mailing"]:checked').val();
		
		if(mail==1) {
			$('#mailing_details_div').css('display', 'block');
			$(".mailing_field").addClass("required");
		} else {
			$('#mailing_details_div').css('display', 'none');
			$(".mailing_field").removeClass("required");
		}
	}

	function add_address(add_div){ 

		var count=$("#address_cnt").val();	
		var i=parseFloat(count)+parseFloat(1);
		// alert('hii'+i);
		var cell1=" <div class='col-md-4 ' id='add_div"+i+"'><label class='form-label' id='Address_"+i+"'>Address"+i+" <span class='required required_lbl' style='color:red;'>*</span> </label><br><textarea  id='mail_address"+i+"' name='mail_address"+i+"' class=' requird form-control'  placeholder='Enter Address"+i+"' > </textarea>";
		cell1 += "<i class='fa fa-close' id='deleteRow_"+i+"' style='cursor: pointer;' onclick='deleterow(this.id);'></i></div>";
		// alert(cell1);
		$("#address_div").append(cell1);
		$("#address_cnt").val(i);
		// $("#myTable3").append(cell1);
		
	} 
	function deleterow(did){
		
		var rid=did.split("_");
		var did=rid[1];	
		//alert('deleterowid='+did);
		
		
		var cnt=$("#address_cnt").val();
		if(cnt!=1){
			$("#add_div"+did).remove();	
			//alert("cnt-"+cnt);
			for(var k=did-1;k<=cnt;k++){
				
			var id=parseFloat(k+1);
				//alert(id+"="+k);
			//$("#Address_"+id).text('Address'+k);
			jQuery("#Address_"+id).text('Address'+k);
			jQuery("#Address_"+id).attr('id','Address_'+k);
			jQuery("#deleteRow_"+id).attr('id','deleteRow_'+k);
			
			jQuery("#mail_address"+id).attr('name','mail_address'+k);
			jQuery("#mail_address"+id).attr('id','mail_address'+k);
			jQuery("#add_div"+id).attr('id','add_div'+k);
			
			}
			//alert('last_cnt='+cnt);
			jQuery("#address_cnt").val(parseFloat(cnt-1));
		}else{
			alert('Atleast 1 Address Field Is Required !! ');
		}
	}

// ------------------------------ tds tax details feild function ------------------------------
 
	function show_tdstax_details(){
		var group_name = $("#group_name").val();//alert(group_name);
		var tdstax=$('input[name="tds_tax_details"]:checked').val()    
		if(tdstax==1) {
			$('#tdstax_details_div').css('display', 'block');
			$(".tdstax_field").addClass("required");

			// Sundry Debtors
			if(group_name==18){
				$('.tdstax_deductor').css('display', 'block');
			}else{
				$('.tdstax_deductor').css('display', 'none');
				$("#tdstax_deductor").removeClass("required");
			}
		} else {
			$('#tdstax_details_div').css('display', 'none');
			$(".tdstax_field").removeClass("required");
		}
	}


// ---------------------------- GST TAX DETAILS ----------------------------
function show_gsttaxdetails(){
	var gsttax=$('input[name="gst_tax_allocation"]:checked').val()    
	if(gsttax==1){
		$('#gsttax_details_div').css('display', 'block');
		$(".gsttax_field").addClass("required");
		
		
	}else{
		$('#gsttax_details_div').css('display', 'none');
		$(".gsttax_field").removeClass("required");
	}
}
<?php
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'){?>	
	// window.onload=function(){
	// 	getfield();
	// 	show_mailingdetails();
	// 	show_tdstax_details();
	// 	show_gsttaxdetails()
	// };
<?php } ?>

function show_taxdetails() {

	$('#op_balance').prop('readonly', false);
	$('#op_method').prop('disabled', false);

}

function edit_taxdetails() {

	$('#op_balance').prop('readonly', true);
	$('#op_method').prop('disabled', true);

}

function show_pricelvl() {

	$('#price_level_group').prop('disabled', false);

}

function no_pricelvl() {

	$('#price_level_group').prop('disabled', true);

}

function show_paydetails() {

	var payd = $('input[name="bank_paydetails"]:checked').val();

	if(payd == 1){
		$('#bank_paydetails_div').css('display', 'block');

	}else{
		$('#bank_paydetails_div').css('display', 'none');

	}

}

function check_name(val) {
	var table = "account_ledger";
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
