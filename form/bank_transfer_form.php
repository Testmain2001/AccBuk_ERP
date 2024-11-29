<?php
	if( $_REQUEST['PTask']=='view'){
		$disabled="disabled";
	} else {
		$disabled="";
	}

	if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
	{
		$parent_id=$_REQUEST['parent_id'];
		$jornal_entry=mysqli_query($GLOBALS['con'],"Select * from bank_transfer WHERE parent_id ='".$parent_id."'");
		$row=mysqli_fetch_array($jornal_entry);
		// var_dump($payment1);
		$date=date('d-m-Y',strtotime($row['date']));
		$invno=$row['voucher_code']; 
		
	} else {

		$date=date('d-m-Y');
		
		// $getinvno=mysqli_query($GLOBALS['con'],"Select max(recordnumber) as pono from bank_transfer WHERE ClientID ='".$_SESSION['Client_Id']."'");
		// $row=mysqli_fetch_array($getinvno);
		// $invno=$row['pono']+1; 
	}
?>

<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true" style="width:100% !IMPORTANT;">

  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
		<div class="modal-body ">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
			<div class="text-center mb-4">
			  <h3 class="role-title">Bank Transfer</h3>
			  
			</div>
			<!-- Add role form -->
			
			  <form id="" data-parsley-validate class="row g-3" action="../bank_transfer_list.php"  method="post" data-rel="myForm">
				
				<input type='hidden' name="PTask" id="PTask" value="<?php echo $task;?>">						  
				<input type='hidden' name="parent_id" id="parent_id" value="<?php echo $_REQUEST['parent_id'];?>">
				<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $payment1['LastEdited'];?>"/>
				<input type="hidden"  name="table" id="table" value="<?php echo "bank_transfer"; ?>"/>
					
				        <div class="col-md-4">
							<label class="form-label"> Date <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
						</div>

						<div class="col-md-4">
							<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_transfer_code();">
								<option value="">Select</option>
								<?php	
									$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=8 group by id"); 
									foreach($data as $info){
										if($info["id"]==$row['voucher_type']){echo $select="selected";}else{echo $select="";}
										echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
									}  
								?>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">Record No  <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="invoiceno No." name="recordnumber" value="<?php echo $invno;?>"/>
						</div>
						<!--------------------------------------------------------------------------------------------->
						<div class=" text-nowrap" style=""><!--table-responsive-->

							<table id="myTable" class="table  table-bordered table-hover  table-sm "  style="width:100%; margin-right: 60px;">
                               <thead><tr>
								<th style="width:10%">Dr/Cr</th>
								<th style="width:10%"><span class="icon icon-triangle-ns"></span>Account</th>
								<th style="width:10%"><span class="icon icon-triangle-ns"></span>Debit Amount</th>
								<th style="width:10%"><span class="icon icon-triangle-ns"></span>Credit Amount</th>     
								</tr>
                               </thead>
							   <tbody>
							   <?php 
                                $i=0;
								if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ 
									$bank_transfer=$utilObj->getMultipleRow("bank_transfer"," parent_id='".$_REQUEST['parent_id']."' order by id asc "); 	
								}else{
									$bank_transfer[0]['id']=1;						
									// $date=date('d/m/Y');
								}
								foreach( $bank_transfer as $row)
								{
									$i++;
									?>
							
								<tr id="row_<?php echo $i;?>">
								<td style="width:10%">
									<select id="record_<?php echo $i;?>" name="record_<?php echo $i;?>" onchange="show_amountfield(this.id);" class="required form-select select2 "  data-placeholder="Select Account No." style="width:100%"  <?php echo $disabled;?> <?php echo $readonly;?> >
									<option value="">Select Dr/Cr</option>
									<option value="Dr" <?php  if($row['record']=='Dr'){ echo "selected";}else{ echo"";}?> >Dr</option>
									<option value="Cr" <?php  if($row['record']=='Cr'){ echo "selected";}else{ echo"";} ?>>Cr</option>
									</select>
								</td>
								<td style="width:10%">
									<select id="account_<?php echo $i;?>" name="account_<?php echo $i;?>" class="required form-select select2 "  data-placeholder="Select Account No."  style="width:100%"  <?php echo $disabled;?> <?php echo $readonly;?> >
										<option value=''>select</option>			 
									<?php 
										
										$Account=$utilObj->getMultipleRow("account_ledger","group_name=8 OR group_name=7 OR group_name=22 group by name");   
										foreach($Account as $a_rec){                
											if($row['account']==$a_rec['id']) $select='selected'; else $select='';
											echo  '<option value="'.$a_rec["id"].'" '.$select.'>'.$a_rec["name"].'</option>';
										}
									?>
									</select>
								</td>
								<td style="width:10%">
								<input type="text" name='debit_amount_<?php echo $i;?>'  value="<?php echo $row["debit_amount"];?>" class=" form-control col-md-7 col-xs-12"  onchange="addRow(this.id);"  <?php echo $disabled;?> readonly <?php echo $readonly;?>  id="debit_amount_<?php echo $i;?>" <?php echo $disabled;?>>
								</td>

								<td style="width:10%">
								<input type="text" name='credit_amount_<?php echo $i;?>'  value="<?php echo $row["credit_amount"];?>" class=" form-control col-md-7 col-xs-12"  onchange="addRow(this.id);" <?php echo $disabled;?>  readonly <?php echo $readonly;?> id="credit_amount_<?php echo $i;?>" <?php echo $disabled;?>>
								</td>
								</tr>
							<?php }?>
							</tbody>
						    <input type="hidden" value="<?php echo $i;?>" id="cnt" name="cnt" >
							<tfoot>
							<td style="width:10%" colspan="2">
							Total
							</td>
							<td style="width:10%" >
								<input type="text" name='total_of_debitamt' readonly value="<?php echo $row["total_of_debitamt"];?>" class=" form-control col-md-7 col-xs-12"  id="total_of_debitamt" <?php echo $disabled;?>>
							</td>
							<td style="width:10%" >
								<input type="text" name='total_of_creditamt' readonly value="<?php echo $row["total_of_creditamt"];?>" class=" form-control col-md-7 col-xs-12"  id="total_of_creditamt" <?php echo $disabled;?>>
							</td>
							</tfoot>
						</table>
					</div>
				<div class="col-12 text-center">
					<?php 
					if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''|| $_REQUEST['PTask']=='makepayment'){?>	
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

window.onload=function(){
	$("#date").flatpickr({
	dateFormat: "d-m-Y"
	});
}

function get_transfer_code() {

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_transfer_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#recordnumber").val(data);
		}
	});

}
</script>
<script>
function show_amountfield(rid){
	var id=rid.split("_");
	var rid=id[1];	
	var record=jQuery("#record_"+rid).val();
	//alert(record);
	if(record=="Cr"){
		$('#debit_amount_'+rid).attr('readonly', 'readonly');
		$('#debit_amount_'+rid).removeClass("required");
		
		$('#credit_amount_'+rid).removeAttr('readonly');
		$('#credit_amount_'+rid).addClass("required");
	}else{
		$('#debit_amount_'+rid).removeAttr('readonly');
		$('#debit_amount_'+rid).addClass("required");
		$('#credit_amount_'+rid).attr('readonly', 'readonly');
		$('#credit_amount_'+rid).removeClass("required");
	}
	
}

</script>
<script>
    function addRow(rid) 
	{ 
		var total_debit=total_credit=camt=damt=0;
		var id=rid.split("_");
		rid=id[1];
        var count=$("#cnt").val();	
        var camt_array=[];
        var damt_array=[];
        for(var i=1;i<=count;i++){
            var record=$('#record_'+i).val();
            
            if(record=="Cr"){
                camt=$('#credit_amount_'+i).val();
                if(camt==null||camt==''){camt=0;}
                camt_array.push(camt);
                total_credit=parseFloat(total_credit)+parseFloat(camt);
            }else{
                damt=$('#debit_amount_'+i).val();
                if(damt==null||damt==''){damt=0;}
                damt_array.push(damt);
                total_debit=parseFloat(total_debit)+parseFloat(damt);
                
            }
        
            if(total_credit==total_debit){
                //alert('hello');
                var k=i;
                for(var j=k+1;j<=count;j++){
                //delete_row('credit_amount_'+i);
                        $("#row_"+j).remove();
                        
                }
                //alert("cnt-"+i)
                    $("#cnt").val(i);
                    break;
            }
        }
        
        var A=camt_array.includes(0);
        var B=damt_array.includes(0);
        //alert(A+'='+B);
        //alert("total_debit="+total_debit+"total_credit="+total_credit);
        $("#total_of_debitamt").val(total_debit);
        $("#total_of_creditamt").val(total_credit);
        if(parseFloat(total_debit)>parseFloat(total_credit)){
            record="Cr";
        }else if(parseFloat(total_debit)<parseFloat(total_credit)){
            record="Dr";
        }else{
            record="0";
        }
        
        if(parseFloat(record)!=0 &&( parseFloat(A)!=0&&parseFloat(B)!=0 )){
            var i=parseFloat(count)+parseFloat(1);
                
            var cell1="<tr id='row_"+i+"'>";
            
            cell1 += "<td style='width:10%' ><input name='record_"+i+"' id='record_"+i+"'  value='"+record+"'  readonly class='form-control required' type='text'/></td>";
            
            cell1 += "<td style='width:10%' ><select name='account_"+i+"'   class='select2 form-select required'  id='account_"+i+"'  >\
                                <option value=''>Select</option>\
                                <?php
									$record=$utilObj->getMultipleRow("account_ledger","group_name=8 group by name");  
                                    foreach($record as $e_rec){	
                                    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
                                    }
                                        
                                ?>
                                </select></td>";
            
            cell1 += "<td style='width:10%'><input name='debit_amount_"+i+"' id='debit_amount_"+i+"'   onchange='addRow(this.id);' class='form-control required' type='text'/></td>";
            
            cell1 += "<td style='width:10%'><input name='credit_amount_"+i+"' id='credit_amount_"+i+"' onchange='addRow(this.id);'   class='form-control required' type='text'/></td>";

            
            cell1 += "</tr>";
            
            $("#myTable").append(cell1);
            $("#cnt").val(i);
            $("#particulars_"+i).select2(); 
            
            show_amountfield('record_'+i); 
        }
            
    }
       		  
			
</script>
<script>
    /* function delete_row(rwcnt)
	{
	var id=rwcnt.split("_");
	rwcnt=id[2];
	var count=$("#cnt").val();	
	if(count>1)
	{
		var r=confirm("Are you sure!");
		if (r==true)
		{		
			
			$("#row_"+rwcnt).remove();
				  
                for(var k=rwcnt; k<=count; k++)
                {
					var newId=k-1;
					
					jQuery("#row_"+k).attr('id','row_'+newId);
					
        			jQuery("#record_"+k).attr('name','record_'+newId);
        			jQuery("#record_"+k).attr('id','record_'+newId);
					
					jQuery("#account_"+k).attr('name','account_'+newId);
        			jQuery("#account_"+k).attr('id','account_'+newId);
					
					jQuery("#debit_amount_"+k).attr('name','debit_amount_'+newId);
        			jQuery("#debit_amount_"+k).attr('id','debit_amount_'+newId);
					
					jQuery("#credit_amount_"+k).attr('name','credit_amount_'+newId);
        			jQuery("#credit_amount_"+k).attr('id','credit_amount_'+newId);
					
					//jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
			 }
				 jQuery("#cnt").val(parseFloat(count-1)); 
		}
	}
	else 
	{
		alert("Can't remove row Atleast one row is required");
		return false;
	}	 
}		 	 */
</script>
             



