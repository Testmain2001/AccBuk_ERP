
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php

// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch");
// $result=mysqli_fetch_array($getinvno);
// $record_no=$result['pono']+1;  

$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("dispatch","id ='".$id."'");
    $record_no=$rows['record_no'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	//$grandtotal=$rows['grandtotal'];
	
} else{
	$rows=null;
}

?>
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Dispatch</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="../sale_invoice_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "dispatch"; ?>"/>
			    
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_dis_code();">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=13 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>

					<div class="col-md-4">
						<label class="form-label">Record No. <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="record_no" value="<?php echo $record_no;?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label"> Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>
					
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=13 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">Customer<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"  onchange="get_saleinvoice_fordispatch();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
							foreach($data as $info){
								if($info["id"]==$rows['customer']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" onchange="get_saleinvoice_fordispatch();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
					<div class="col-md-4" id="dispatch_div">
					
					</div>
				
		  
		
			
		
          <h4 class="role-title">Material Details</h4>
		  <?php 
		 $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
		  $state= $account_ledger['mail_state'];
		?>
		
        <div id="table_div" style="overflow: hidden;">
		
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

function get_dis_code() {
	
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_dis_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#record_no").val(data);	
			// $(this).next().focus();
		}
	});

}

function  get_saleinvoice_fordispatch(){
	//alert('hii');
	var customer =$("#customer").val();
	var location =$("#location").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	if( customer==''&& location==''){
		alert('Please Select customer And Location !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_saleinvoice_fordispatch',PTask:PTask,id:id,customer:customer,location:location},
		success:function(data)
		{	
		    // alert(data);
			$("#dispatch_div").html(data);	
			if(PTask=="update"||PTask=='view'||PTask=='Add'){
				saleinvoice_fordispatch_rowtable();
				
			}
		}
	}); 
}

function saleinvoice_fordispatch_rowtable(){
	//alert("hii");
	var sale_invoice_no =$("#sale_invoice_no").val();
	// alert(saleorder_no);
	var customer =$("#customer").val();
	var location =$("#location").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	if(customer==''){
		alert('Please Select customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'saleinvoice_fordispatch_rowtable',PTask:PTask,id:id,customer:customer,location:location,sale_invoice_no:sale_invoice_no},
		success:function(data)
		{	
		    //alert(data);
			$("#table_div").html(data);	
		}
	}); 
}

function get_unit(this_id)
{	
	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_unit',id:id,product:product},
		success:function(data)
		{	
			$("#unitdiv_"+id).html(data);	
			$(this).next().focus();
		}
	});	
}
	
function get_totalqty()
{
	var cnt=jQuery("#cnt").val();
	//alert(cnt);
	var grandtotal=0;	
	for(var i=1; i<=cnt;i++)
	{	
		var qty= jQuery("#qty_"+i).val();
		if(qty==''){ qty=0;}
		grandtotal = parseFloat(grandtotal)+parseFloat(qty);
	}
	//alert(grandtotal);
	jQuery("#total_quantity").val(parseFloat(grandtotal).toFixed(2));	
}

function stock_check()
{
	var cnt=jQuery("#cnt").val();
	var stock_chk=0;	
	for(var i=1; i<=cnt;i++)
	{	
		var qty= jQuery("#qty_"+i).val();
		var remainqty= jQuery("#remainqty_"+i).val();
		var stock= jQuery("#stock_"+i).val();
		//alert("qty="+qty+'stock='+stock);
		if(parseFloat(qty)>parseFloat(stock)||parseFloat(qty)>parseFloat(remainqty)){ stock_chk++;}
	}
	//alert("chk="+stock_chk);
	if(stock_chk<=0){
		$("#submit_div").html('<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>');	
	}else{
		$("#submit_div").html('<span style="color:red;">Quantity Should Not Gratter Than Stock  Or Invoice Quantity!!!</span><br>');	
	}
}
</script>

