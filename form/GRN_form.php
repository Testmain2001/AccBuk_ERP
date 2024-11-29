
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php

// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn");
// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
// $result=mysqli_fetch_array($getinvno);
// $grn_no=$result['pono']+1; 	

$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("grn","id ='".$id."'");

    $grn_no=$rows['grn_code'];	
    $requisition_no=$rows['requisition_no'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	
	if($requisition_no!='')
	{		
		if($readonly!="readonly"){
			$read="readonly";
		}
	}else{
		$read=" ";
	}
} 
?>
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      	<div class="modal-body ">
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
			<div class="text-center mb-4">
				
				<h3 class="role-title">GRN(Goods Receipt Notes)</h3>
			
			</div>
			<!-- Add role form -->
			
			<form id="" data-parsley-validate class="row g-3" action="../GRN_list.php"  method="post" data-rel="myForm">
				
				<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
				<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
				<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
				<input type="hidden"  name="table" id="table" value="<?php echo "grn"; ?>"/>
					
				<div class="col-md-4">
					<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_voucher_code();">
					<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=9 group by id"); 
							foreach($data as $info){
								if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>

				<div class="col-md-4">
					<label class="form-label">GRN No. <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="grn_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="grn_no" value="<?php echo $grn_no;?>"/>
				</div>

				<div class="col-md-4">
					<label class="form-label">GRN Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
				</div>
				
				

				<div class="col-md-4">
					<label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"  onchange="find_state();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
							foreach($data as $info){
								if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location" onchange="chk_type();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
						<?php 
							echo '<option value="">Select Location</option>';
							$record=$utilObj->getMultipleRow("location","1");
							foreach($record as $e_rec)
							{
								if($rows['location']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-4">
					<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="type" name="type"  onchange="chk_type();" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">
					<option value="">Select</option>
						
								<option  value="Direct_Purchase" <?php if($rows['type']=='Direct_Purchase'){ echo 'selected';}else{ echo ' ';} ?> >Direct Purchase</option>
								<option  value="Against_Purchaseorder" <?php if($rows['type']=='Against_Purchaseorder'){ echo 'selected';}else{ echo ' ';} ?> >Against Purchase Order</option>
					</select>
				</div>
				<div class="col-md-4" id="purchase_order_div">
				</div>
			
			<h4 class="role-title">Material Details</h4>
				<?php 
					$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
					$state= $account_ledger['mail_state'];
				?>
			<input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
			<div id="table_div" style="overflow: hidden;">
			
		
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

window.onload=function(){
	$("#date").flatpickr({
	dateFormat: "d-m-Y"
	});
}
function find_state(){
	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'find_state',supplier:supplier},
			success:function(data)
			{	
			//alert(data);
				$("#state").val(data);	
			}
		}); 
}

function purchaseorder_rowtable()
{	

  
   var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var location =$("#location").val();
	var purchaseorder_no =$("#purchaseorder_no").val();
	
	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}
	if(location==''){
		alert('Please Select Location !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'purchaseorder_rowtable',type:type,id:id,PTask:PTask,purchaseorder_no:purchaseorder_no,supplier:supplier,location:location},
			success:function(data)
			{	
			//alert(data);
				$("#table_div").html(data);	
			}
		}); 
			
}
function chk_type()
{	

    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type = $("#type").val();
	var location =$("#location").val();
	if(type=="Against_Purchaseorder"){
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_purchaseorderno',type:type,id:id,PTask:PTask,location:location},
			success:function(data)
			{	
				$("#purchase_order_div").html(data);	
				var purchaseorder_no =$("#purchaseorder_no").val();
				//alert("rr"+requisition_no);
				if(PTask=='update'&&purchaseorder_no!=null || PTask=='view'|| PTask=='Add'){
					purchaseorder_rowtable();
				}
			}
		});	
	}else if(type=="Direct_Purchase"){
		
		$("#purchase_order_div").html(" ");	
		var purchaseorder_no =$("#purchaseorder_no").val();
		if((purchaseorder_no==null&&PTask!='')|| PTask=='view'){
			purchaseorder_rowtable();
		}
	}			
}
function get_unit(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	//alert(id);
	//var cnt = $("#cnt").val();
	var product = $("#product_"+id).val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'get_unit',id:id,product:product},
				success:function(data)
				{	
					//alert(data);
					$("#unitdiv_"+id).html(data);	
					$(this).next().focus();
				}
			});	
}

function get_voucher_code() {

	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_voucher_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#grn_no").val(data);	
			// $(this).next().focus();
		}
	});

}
</script>
<script>
              
	function delete_row(rwcnt)
	{
	var id=rwcnt.split("_");
	rwcnt=id[1];
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
					
				 	jQuery("#idd_"+k).attr('name','idd_'+newId);
        			jQuery("#idd_"+k).attr('id','idd_'+newId);
					jQuery("#idd_"+newId).html(newId); 
					
        			jQuery("#product_"+k).attr('name','product_'+newId);
        			jQuery("#product_"+k).attr('id','product_'+newId);
        			
					jQuery("#unit_"+k).attr('name','unit_'+newId);
        			jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
        			jQuery("#qty_"+k).attr('id','qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
        			jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
			 }
				 jQuery("#cnt").val(parseFloat(count-1)); 
		}
	}
	else 
	{
		alert("Can't remove row Atleast one row is required");
		return false;
	}	 
}		  		  
			  
			  function addRow(tableID) 
			  { 
				var count=$("#cnt").val();	
				var state=$("#state").val();	

				var i=parseFloat(count)+parseFloat(1);

                var cell1="<tr id='row_"+i+"'>";
				
				cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
			   
				cell1 += "<td style='width:20%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
                                    <option value=''>Select</option>\
									<?php
								     	$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
										foreach($record as $e_rec){	
									    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
										}
									   		
                                    ?>
                                  </select></td>";
           
			  	cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";
				
                cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number' type='text'/></td>";
			
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>

