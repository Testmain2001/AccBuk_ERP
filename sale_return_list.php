<?php 
 include("header.php");
 //include("handler/sale_return_form.php");
$task=$_REQUEST['PTask'];
if($task==''){ $task='Add';}
if($_REQUEST['PTask']=='view')
{
$readonly="readonly";
$disabled="disabled";
}
else
{
$readonly="";
$disabled="";
}
$common_id = uniqid();
?>

<div class="container-xxl flex-grow-1 container-p-y ">


<div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Return</h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1) { ?>
		<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
			<i class="fas fa-plus-circle fa-lg"></i>
		</button>
	<?php } ?>

	<?php if((CheckDeleteMenu())==1){ ?>
		<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
			<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
		</button>
	<?php } ?>
	</div>
</div>
            
<!-- <div class="row">     
	<div class="col-md-3">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Return</h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1){  ?>
	<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button>
	<?php } ?>
	</div>
</div> -->
<!-- Invoice List Table -->

<div id="u_table" style="display:block">
<div class="card">
  <div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
			<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp; Sr.No.</th>
			<th>Date</th>
			<th>Record No</th>
			<th>Voucher Type</th>
			<th >Customer</th>
			<th >Location</th>
			<th >Sale Invoice No.</th>
			<th>Product</th>
			<th>Unit</th>
			<th>Quantity</th>
			<th>Return Qty</th>
			<th >Total Amount</th>
			<th >User</th>
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			$data=$utilObj->getMultipleRow("sale_return","1");
			foreach($data as $info){
				    $i++;$j=0;
					$href= 'sale_return_list.php?id='.$info['id'].'&PTask=view';
					//$d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}
					$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
					$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
					$sale_invoice=$utilObj->getSingleRow("sale_invoice","id='".$info['sale_invoice_no']."'");
					$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
					$data1=$utilObj->getMultipleRow("sale_return_details","parent_id='".$info['id']."'");
					foreach($data1 as $info1)
					{
						$j++;
						$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
						if($j==1){
								$rowspan=Count($data1);
								$hidetd="";
						}else{
								$rowspan=1;
								$hidetd="hidetd";
						}  	
		
		
					?>
					<tr>
					<td width='3%' class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp;<?php echo $i; ?></td> 
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><a href="<?php echo $href; ?>"><?php echo $info['recordnumber']; ?></a> </td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $customer['name']; ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $sale_invoice['sale_invoiceno']; ?></td>
					<td><?php echo $product['name']; ?></td>
					<td><?php echo $info1['unit']; ?></td>
					<td><?php echo $info1['qty']; ?></td>
					<td><?php echo $info1['rejectedqty']; ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['grandtotal']; ?></td>
					<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
				    <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
					
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
						<!--div class="dropdown"-->
					<?php 
					//echo $d1;
					if($d1<=0){?>
						  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
						  <div class="dropdown-menu">
						  <?php if((CheckEditMenu())==1) {  ?>
						   <a class="dropdown-item" href="sale_return_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
						   <?php } ?>
						   <?php if((CheckDeleteMenu())==1){ ?>
							<a href="#" class="dropdown-item" data-id="<?php echo $info['id']; ?>" onclick="deletedata('<?php echo $info['id']; ?>')">
								<i class="bx bx-trash me-1"></i> Delete
							</a>

							<?php } ?>
						  </div>
						<!--/div-->
					<?php }?>
					<?php if($info['Created']!='')
							{
								//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
								$username = mysqli_fetch_array($query);
								$created=date('d-m-Y h:i A',strtotime($info['Created']));	
								$user = $username['fname'] . "  ".  $username['lname'];
								$createuser = "Created : ".$user." ".$created;
							}
							else{
								$createuser="";	
							}	
							
							
							if($info['updateduser']!='')
							{
								
								$username = mysqli_fetch_array($query);											
								$created=date('d-m-Y h:i A',strtotime($info['LastEdited']));	
								$user = $username['fname'] . "  ".  $username['lname'];
								$createuser.= "&#10; Updated : ".$user." ".$created;
							}
							else{
								$createuser.="";	
							}
							?>
							<a $dasable ata-content='clock' title='<?php echo $createuser;?>' class='popovers' data-placement='top' style='color:brown;' data-trigger='hover'  href='#' ><i class='fa fa-clock-o' ></i></a>
					</td>
					</tr>
					<?php 
				}
		
		} ?>
	  </tbody>
	   </table>
  </div>
</div>
</div>

<?php

// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_return");
// $result=mysqli_fetch_array($getinvno);
// $recordnumber=$result['pono']+1; 

$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("sale_return","id ='".$id."'");
    $recordnumber=$rows['recordnumber'];	   
	$date=date('d-m-Y',strtotime($rows['date']));	
	if($requisition_no!='')
	{		
		if($readonly!="readonly"){
			$read="readonly";
		}
	} else {

		$read=" ";
	}
} 
?>
 	<div  style=" display:none" id="u_form">
		<div class="row form-validate">
			<div class="col-12">
				<div class="card">
				  	<div class="card-body " >
		
						<form id="demo-form2" data-parsley-validate class="row g-3" action="sale_return_list.php"  method="post" data-rel="myForm">
							
							<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
							<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
							<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
							<input type="hidden"  name="table" id="table" value="<?php echo "sale_return"; ?>"/>
							<input type="hidden"  name="common_id" id="common_id" value="<?php echo $common_id;?>"/>   
							
							<div class="col-md-2">
								<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
								<select id="voucher_type" name="voucher_type"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_sreturn_code();">
									<option value="">Select</option>
									<?php	
										$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=4 group by id"); 
										foreach($data as $info){
											if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
											echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
										}  
									?>
								</select>
							</div>

									<div class="col-md-2">
										<label class="form-label">Record No. <span class="required required_lbl" style="color:red;">*</span></label>
										<input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $recordnumber;?>"/>
									</div>

									<div class="col-md-2">
										<label class="form-label">Return Date <span class="required required_lbl" style="color:red;">*</span></label>
										<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
									</div>
									
									<div class="col-md-4">
										<label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
										<select id="customer" name="customer" onchange="get_address();get_pos(this.value); " <?php echo $disabled; ?> class="required form-select select2" data-allow-clear="true">
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

									<div class="col-md-2">
										<label class="form-label">POS State<span class="required required_lbl" style="color:red;">*</span></label>
										<select id="pos_state" name="pos_state" onchange="saleinvoice_forsalereturn_rowtable();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true">
											<?php
												echo '<option value="">Select Location</option>';
												$record=$utilObj->getMultipleRow("states","1");
												foreach($record as $e_rec) {
													
													if($rows['pos_state']==$e_rec["id"]) echo $select='selected'; else $select='';
													echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
												}
											?>  
										</select>
									</div>
									
									<div class="col-md-2">
										<label class="form-label">State <span class="required required_lbl" style="color:red;">*</span></label>
										<input type="text" id="state_name" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="state_name" value="<?php echo $rows['state_name']; ?>" />
									</div>

									<div class="col-md-2">
										<label class="form-label">State Code <span class="required required_lbl" style="color:red;">*</span></label>
										<input type="text" id="state_code" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="state_code" value="<?php echo $rows['state_code']; ?>"/>
									</div>

									<div class="col-md-4">
										<label class="form-label">Bill To<span class="required required_lbl" style="color:red;">*</span></label>
										<textarea name='bill_to' id="bill_to" placeholder="Bill Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " rows ="2" ><?php echo $rows['bill_to'];?></textarea>
									</div>

									<div class="col-md-4">
										<label class="form-label">Ship To<span class="required required_lbl" style="color:red;">*</span></label>
										<textarea name='ship_to' id="ship_to" placeholder="Ship Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " rows ="2" ><?php echo $rows['ship_to']; ?></textarea>
									</div>

									<!-- <div class="col-md-4">
										<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
										<select id="location" name="location" onchange="get_saleinvoice();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
									</div> -->
									
									<div class="col-md-4" id="sale_invoice_div">
									</div>
						
							<h4 class="role-title">Material Details</h4>
							<?php 
								$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
								$state= $account_ledger['mail_state'];
							?>
							<input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/>
							

							<div id="table_div" style="overflow: hidden;">
							
						
							</div>
							
							<div class="col-12 text-center" style="display:block;" id="adjustform">
								<?php 
									if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
								?>	
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="adjustentry();"/>
								<?php } ?>
								<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
							
							</div>

							<div id="table_adjust" style="overflow: hidden;">
						

							</div>

							<div class="col-12 text-center" style="display:none;" id="submitform">
								<?php 
									if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
								?>	
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?>
								<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
							
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /FormValidation -->
		</div>

	</div>
</div>
          <!--/ Content -->
		  

<script>
function hideshow()
{ 
	if(document.getElementById('u_form').style.display=="none")
	{
		document.getElementById('u_form').style.display="block"
		document.getElementById('u_table').style.display="none"
		//document.getElementById('button').style.display="none"
		//$('#demo-form2').hide();
		$('#demo-form2').show();
		$("#add_new").val("Show List");
	}
	else
	{
		document.getElementById('u_form').style.display="none"
		document.getElementById('u_table').style.display="block"
		$(".add_new").val("Add New");
		$('#demo-form2').show();		
		window.location="sale_return_list.php";
		
	}
	
}	


<?php 
if($_REQUEST['PTask']=='update'||$_REQUEST['Task']=='view'){?>
	window.onload=function(){

		saleinvoice_forsalereturn_rowtable();
		adjustentry();
		// get_saleinvoice();
	}
		hideshow();
		get_table();
		 
<?php }
?>

<?php
if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		    deletedata("<?php echo $_REQUEST['id'];?>");
		 }
		else
		{
			window.location="sale_return_list.php";
		}
  
};
<?php } ?>	

function CheckDelete()
{
	    
	var val='';
	$('input[type="checkbox"]').each(function()
	{	
		if(this.checked==true && this.value!='on')
		{
			val +=this.value+",";
			
			deletedata(val);
		}
	});
	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
			val = val.substring(0, val.length - 1);
			window.location="sale_return_list.php"; 
			
	}
}

function mysubmit(a)
{
	// return _isValid2(a);
	savedata();
}

function remove_urldata()
{	 
	window.location="sale_return_list.php";
} 
 
function savedata()
{
	
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var common_id = $("#common_id").val();
	var cnt = $("#cnt").val();
	var cntd = $("#cntd").val();
	// alert(common_id);

	var bill_to = $("#bill_to").val();
	var ship_to = $("#ship_to").val();
	var state_name = $("#state_name").val();
	var state_code = $("#state_code").val();
	var pos_state = $("#pos_state").val();
	
 	var recordnumber = $("#recordnumber").val();
	var date = $("#date").val();
	var customer = $("#customer").val();
	var location = $("#location").val();
	var voucher_type = $("#voucher_type").val();
	var sale_invoice_no = $("#sale_invoice_no").val();

	var grandtotal = $("#grandtot").val();
	var totdiscount = $("#totdiscount").val();
	var totaltaxable = $("#totaltaxable").val();
	var cgstledger = $("#cgstledger").val();
	var cgstamt = $("#cgstamt").val();
	var sgstledger = $("#sgstledger").val();
	var sgstamt = $("#sgstamt").val();
	var igstledger = $("#igstledger").val();
	var igstamt = $("#igstamt").val();
	var subtotgst = $("#subtotgst").val();
	var totserviceamt = $("#totserviceamt").val();

	
    //alert(tcs_tds_percen);
	
    var product_array=[];
	var unit_array=[];
	var cgst_array=[];
	var sgst_array=[];
	var igst_array=[];
	var qty_array=[];
	var rate_array=[];
	var disc_array=[];
	var taxable_array=[];
	var rejectedqty_array=[];
	var total_array=[];
	
		
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var cgst = $("#cgst_"+i).val();	
		var sgst = $("#sgst_"+i).val();	
		var igst = $("#igst_"+i).val();	
		var qty = $("#qty_"+i).val();	
		var rate = $("#rate_"+i).val();	
		var disc = $("#disc_"+i).val();	
		var taxable = $("#taxable_"+i).val();	
		var rejectedqty = $("#rejectedqty_"+i).val();	
		var total = $("#total_"+i).val();	
		
		product_array.push(product);
		unit_array.push(unit);
		cgst_array.push(cgst);
		sgst_array.push(sgst);
		igst_array.push(igst);
		qty_array.push(qty);
		rate_array.push(rate);
		disc_array.push(disc);
		taxable_array.push(taxable);
		rejectedqty_array.push(rejectedqty);
		total_array.push(total);
	
	} 

	var serviceledger_array=[];
	var servicecgst_array=[];
	var servicesgst_array=[];
	var serviceigst_array=[];
	var serviceamt_array=[];

	for(var j=1;j<=cntd;j++)
	{
		var serviceledger = $("#serviceledger_"+j).val();	
		var servicecgst = $("#servicecgst_"+j).val();
		var servicesgst = $("#servicesgst_"+j).val();
		var serviceigst = $("#serviceigst_"+j).val();	
		var serviceamt = $("#serviceamt_"+j).val();
		
		serviceledger_array.push(serviceledger);
		servicecgst_array.push(servicecgst);
		servicesgst_array.push(servicesgst);
		serviceigst_array.push(serviceigst);
		serviceamt_array.push(serviceamt);
	}

	var cntad = $("#cntad").val();
	var totalvalue = $("#totalvalue").val();
	var type_array=[];
	var billno_array=[];
	var invodate_array=[];
	var totalinvo_array=[];
	var pendingamt_array=[];
	var payamt_array=[];

	for(var i=1; i<=cntad; i++) {

		var typead = $("#type_"+i).val();
		var billno = $("#billno_"+i).val();
		var invodate = $("#invodate_"+i).val();
		var totalinvo = $("#totalinvo_"+i).val();
		var pendingamt = $("#pendingamt_"+i).val();
		var payamt = $("#payamt_"+i).val();
			
		type_array.push(typead);
		billno_array.push(billno);
		invodate_array.push(invodate);
		totalinvo_array.push(totalinvo);
		pendingamt_array.push(pendingamt);
		payamt_array.push(payamt);
	}

		
	jQuery.ajax({url:'handler/sale_return_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,common_id:common_id,cnt:cnt,recordnumber:recordnumber,date:date,customer:customer,location:location,voucher_type:voucher_type,sale_invoice_no:sale_invoice_no,grandtotal:grandtotal,product_array:product_array,unit_array:unit_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,qty_array:qty_array,rate_array:rate_array,disc_array:disc_array,taxable_array:taxable_array,rejectedqty_array:rejectedqty_array,totdiscount:totdiscount,totaltaxable:totaltaxable,cgstledger:cgstledger,cgstamt:cgstamt,sgstledger:sgstledger,sgstamt:sgstamt,igstledger:igstledger,igstamt:igstamt,subtotgst:subtotgst,totserviceamt:totserviceamt,cntd:cntd,serviceledger_array:serviceledger_array,servicecgst_array:servicecgst_array,servicesgst_array:servicesgst_array,serviceigst_array:serviceigst_array,serviceamt_array:serviceamt_array,bill_to:bill_to,ship_to:ship_to,state_name:state_name,state_code:state_code,pos_state:pos_state,type_array:type_array,billno_array:billno_array,invodate_array:invodate_array,totalinvo_array:totalinvo_array,pendingamt_array:pendingamt_array,payamt_array:payamt_array,cntad:cntad,totalvalue:totalvalue },
		success:function(data)
		{	
			if(data!="")
			{	
				// $("#handler_data").val(data);
				// alert(data);
				alert('Record has been Added Successfully!!');
				window.location='sale_return_list.php';
			}else{
				alert('error in handler');
			}
		}
	});		
// }	
}


function deletedata(id){
		
	var PTask =	"delete";
	var r=confirm("Are you sure to delete?");
	if (r==true)
	{

		jQuery.ajax({url:'handler/sale_return_form.php', type:'POST',
			data: { PTask:PTask,id:id},
			success:function(data)
			{	
				if(data!="")
				{
					//alert(data);					
					window.location='sale_return_list.php';
				}else{
					window.location='sale_return_list.php';
				}
			}
		});
	}else{

		window.location='sale_return_list.php';
	}
   
}

window.onload=function(){
	$("#date").flatpickr({
		dateFormat: "d-m-Y"
	});
}

function get_sreturn_code() {
	
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_sreturn_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#recordnumber").val(data);	
			// $(this).next().focus();
		}
	});

}

function find_state(){
	var customer =$("#customer").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'find_state',customer:customer},
			success:function(data)
			{	
				// alert(data);
				$("#state").val(data);	
			}
		}); 
}

function get_saleinvoice()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var customer =$("#customer").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_saleinvoice',id:id,PTask:PTask,customer:customer},
		success:function(data)
		{	
			$("#sale_invoice_div").html(data);	
			$(".select2").select2();
			if(PTask=='update'||PTask=='view'||PTask=='Add'){
				saleinvoice_forsalereturn_rowtable();
			}
		}
	});
}

function saleinvoice_forsalereturn_rowtable()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var sale_invoice_no =$("#sale_invoice_no").val();
	var customer = $("#customer").val();
	var supplier = $("#pos_state").val();

	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'saleinvoice_forsalereturn_rowtable',type:type,id:id,PTask:PTask,sale_invoice_no:sale_invoice_no,customer:customer,supplier:supplier },
		success:function(data)
		{	
			$("#table_div").html(data);	
		}
	}); 
			
} 

function select_all(){	

	//select all checkboxes
	$("#select_all").change(function(){  //"select all" change

		var status = this.checked; // "select all" checked status
		$('.checkboxes').each(function(){ //iterate all listed checkbox items
			if(this.disabled==false)
			{
				this.checked = status; //change ".checkbox" checked status
				//alert(this.disabled);
			}
		});
	});

	//uncheck "select all", if one of the listed checkbox item is unchecked
	$('.checkboxes').change(function(){ //".checkbox" change

		if(this.checked == false){ //if this item is unchecked
			$("#select_all")[0].checked = false; //change "select all" checked status to false
		}
	});

}

function Gettotal(rid)
	{
     var table = document.getElementById('myTable');
     var rowCount = table.rows.length;
    var count=parseFloat(rowCount-1);
 	var did=rid.split("_");
	var rid=did[1]; 
	var val=0;
	var total=0;
	var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	var qty=jQuery("#qty_"+rid).val();
	var rate=jQuery("#rate_"+rid).val();
   // var vat=jQuery("#vat_"+rid).val();
	
	 
	 
	  
	 
    var rejct=jQuery("#rejectedqty_"+rid).val();
    
    if (parseFloat(qty)<parseFloat(rejct))
    {
    	$('#rejectedqty_'+rid).val("");
    	alert("Rejected Quantity is more than Quantity");
    	return false;
    }
    
	if(floatRegex.test(rejct) && floatRegex.test(rate)){

	var cal =parseFloat(rejct*rate);
    
    total=parseFloat(cal);	
    
	// cgst_per=total*tax_cgst/100;
	
	// $(".exc").val(cgst_per);
	
	//alert(tax_igst);
	
	} 
	
	jQuery("#total_"+rid).val(total.toFixed(2));
	
	if(total<0)
	{
	jQuery("#"+rid).val(val);
	alert('Please Enter valid entry!');
	//Gettotal(rid);
	jQuery("#"+rid).focus("");
	}
	//var cnt=jQuery("#cnt").val();
	var subtotal=0;        
    var Grandvat=0;
	var cgst_per=0;
	var sgst_per=0;
	var igst_per=0;
	
	for(var i=1; i<=count;i++)
	{
		var	cgst=parseFloat(jQuery("#cgst_"+i).val());
		var sgst=parseFloat(jQuery("#sgst_"+i).val());
		var igst=parseFloat(jQuery("#igst_"+i).val());
		
		//alert(cgst+'=='+sgst+'=='+igst);
		if(jQuery("#total_"+i).val()!='' && floatRegex.test(jQuery("#total_"+i).val()))
			
		    subtotal = parseFloat(subtotal)+parseFloat(jQuery("#total_"+i).val());
            cgst_per = parseFloat(cgst_per)+ parseFloat((jQuery("#total_"+i).val()* cgst)/100);
		    sgst_per = parseFloat(sgst_per)+ parseFloat((jQuery("#total_"+i).val()* sgst)/100);
		    igst_per = parseFloat(igst_per)+ parseFloat((jQuery("#total_"+i).val()* igst)/100);
         		 
        	
	}
	
	if(subtotal==''){ subtotal=0;}
	if(cgst_per==''){ cgst_per=0;}
	if(sgst_per==''){ sgst_per=0;}
	if(igst_per==''){ igst_per=0;}
	jQuery("#subt").val(subtotal.toFixed(2));
	jQuery("#totcst_amt").val(cgst_per.toFixed(2));
	jQuery("#totsgst_amt").val(sgst_per.toFixed(2));
	jQuery("#totigst_amt").val(igst_per.toFixed(2));
	
	
	showgrandtotal();
	}
	
	function showgrandtotal()
	{
	//alert('hii');
	var table = document.getElementById('myTable');
     var rowCount = table.rows.length;
    var count=parseFloat(rowCount-1);
	var finaltotal=0;
	var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	var regex=/^-?\d+(\.\d+)?$/;
	var grandtotal=0;
	
	var total_vat=0;
	var total_g=0;
	var subt=jQuery("#subt").val();
	grandtotal = parseFloat(subt);
	
	var disc=jQuery("#disc").val();
	if(disc==''){ disc=0;}
	if(floatRegex.test(disc)){
		var discval=(subt*disc)/100;
			grandtotal = parseFloat(grandtotal)-parseFloat(discval);
	}
	var exc=jQuery("#totcst_amt").val();
	if(exc==''){ exc=0;}
	if(floatRegex.test(exc)){
		//var excval=(grandtotal+exc);
			grandtotal = parseFloat(grandtotal)+parseFloat(exc);
	}
	
	var vatval=jQuery("#totsgst_amt").val();
	if(vatval==''){ vatval=0;}
	if(floatRegex.test(vatval)){
		//var vatvalamt=(grandtotal+vatval);
			grandtotal = parseFloat(grandtotal)+parseFloat(vatval);
	}
	
	var cst=jQuery("#totigst_amt").val();
	if(cst==''){ cst=0;}
	if(floatRegex.test(cst)){
	//	var cstval=(grandtotal+cst);
			grandtotal = parseFloat(grandtotal)+parseFloat(cst);
	}
	
	var trans=jQuery("#trans").val();	
	if(trans==''){ trans=0;}
	if(floatRegex.test(trans)){
	grandtotal = parseFloat(grandtotal)+parseFloat(trans);
	}
	
	var other=jQuery("#other").val();
	if(other==''){ other=0;}
	if(floatRegex.test(other)){
	grandtotal = parseFloat(grandtotal)+parseFloat(other);
	}
	
	var roff=jQuery("#roff").val();	
	if(roff==''){ roff=0;}
	if(regex.test(roff)){
	grandtotal = parseFloat(grandtotal)+parseFloat(roff);
	}
		
			
	jQuery("#grandtotal").val(grandtotal.toFixed(2));
    
	}


		function tran()
			{
						var amount1=$("#transcost").val();
						var amount2=$("#transgst").val();

						if(amount1=="")
						{
							amount1=0;
						}
						if(amount2=="")
						{
							amount2=0;
						}

						var total1=(amount1*amount2)/100;
						$("#transamount").val(total1);

						var total2 =parseFloat(amount1)+parseFloat(total1);
						$("#trans").val(total2);

						//$("#transamount").val(($("#transcost").val()*$("#transgst").val())/100);
						//$("#trans").val(($("#transcost").val())+(($("#transcost").val()*$("#transgst").val())/100));
						//alert('hi');

				}

// function check_qty(i) {

// 	var quantity = $("#qty_"+i).val();
// 	var PTask = $("#PTask").val();

// 	if (quantity == '' || quantity=='0') {
// 		alert ('please enter quantity first . . . !');

// 	} else {
// 		getreturnbatch(i,quantity);
// 	}
// }

function savereturnsalesbatch() {

	var cnt2 = $("#cnt2").val();
	var product = $("#product").val();
	var common_id = $("#common_id").val();
	var PTask = $("#PTask").val();
	var deliveryid = $("#id").val();
	var type = "sale_return";
	var batchIds = [];


	$(".batch_id").each(function () {
		batchIds.push($(this).val());
	});

	// Iterate through batch IDs and update data
	for (var i = 0; i < batchIds.length; i++) {
	
		var id = batchIds[i];
		var batqty = $("#batqty_"+id).val();
		var batchname = $("#batchname_"+id).val();
		var location = $("#location_"+id).val();
		var batchremove = $("#batch_remove_"+id).val();
		jQuery.ajax({
			url: 'get_ajax_values_sale.php',
			type: 'POST',
			data: { Type: 'updatereturnbattch', id:id,deliveryid:deliveryid,batqty: batqty,batchremove:batchremove,product:product,common_id:common_id,batchname:batchname,PTask:PTask,type:type,location:location },
			success: function (data) {
				$('#saleinvoicereturnbatch').modal('hide');
				console.log(data);
		
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error:", status, error);
			}
		});	
	}
}

function getqty(id){

	var batqty = parseFloat($("#batqty_" + id).val(), 10);
	var batchrmv = parseFloat($("#batch_remove_" + id).val(), 10);
	if(batqty<batchrmv){

		alert('Quantity is not greater than batch quantity');
		$("#batch_remove_"+id).val('');
	}

}


	// ---------------------------------------------------------------------------------

	function addRowdetail(tableID) 
	{
		var count=$("#cntd").val();	
		var state=$("#pos_state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row2_"+i+"'>";
		
		cell1 += "<td style='width:2%;><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
		
		cell1 += "<td style='width:15%;'><div id='ledgerdiv_"+i+" ?>'><select name='serviceledger_"+i+"' class='select2 form-select' id='serviceledger_"+i+"' onchange='getservice(this.id);' >\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
				foreach($record as $e_rec) {	
					echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
			?>
		</select></div></td>";

		cell1 += "<td style='width:10%'></td>";

		cell1 += "<td style='width:10%'></td>";

		if(state==27) {
			cell1 += "<td style='width:7%'><input name='servicecgst_"+i+"' id='servicecgst_"+i+"' class='form-control number' type='text' readonly/></td>";
			cell1 += "<td style='width:7%'><input name='servicesgst_"+i+"' id='servicesgst_"+i+"' class='form-control number' type='text' readonly/></td>";
		} else {
			cell1 += "<td style='width:7%'><input name='serviceigst_"+i+"' id='serviceigst_"+i+"' class='form-control number' type='text' readonly/></td>";
		}

		cell1 += "<td style='width:10%'></td>";
		
		cell1 += "<td style='width:10%'></td>";

		cell1 += "<td style='width:10%'><input name='serviceamt_"+i+"' id='serviceamt_"+i+"' class='tdalign form-control number' type='text' value='0' onkeyup='servicegstsum(this.id);servicetotgst("+i+");' />\
		<input type='hidden' name='serviceigstamt_"+i+"' id='serviceigstamt_"+i+"' value='' >\
		<input type='hidden' name='servicecgstamt_"+i+"' id='servicecgstamt_"+i+"' value='' >\
		<input type='hidden' name='servicesgstamt_"+i+"' id='servicesgstamt_"+i+"' value='' ></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";


		$("#dtable").append(cell1);
		$("#cntd").val(i);

		$("#product_"+i).select2({
			dropdownParent: $('#ledgerdiv_'+i)
		});

		
	}


    function gettotgst(id) {

        var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;

        var gst_subtot = 0;
        var grandtotal = 0;

        var state = $("#pos_state").val();
        var service_subtotal = $("#service_subtotal").val();
        var totaltaxable = 0;

		$("[id^='taxable_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totaltaxable += quant;
		});

		$("#totaltaxable").val(totaltaxable.toFixed(2));

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='rowcgstamt_']").each(function() {
                
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst += quant;
                
            });

			// alert(totcgst);
            $("#cgsttot").val(totcgst.toFixed(2));
            // $("#cgstamt").val(totcgst);

            // Assuming batqty1_id elements are input fields
            $("[id^='rowsgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst += quant;
            });

			// alert(totsgst);
            $("#sgsttot").val(totsgst.toFixed(2));
            // $("#sgstamt").val(totsgst);

            // gst_subtot = totcgst+totsgst;

            // $("#gst_subtotal").val(gst_subtot);

        // } else {

            // Assuming batqty1_id elements are input fields
            $("[id^='rowigstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totigst += quant;
            });

			// alert(totigst);
            $("#igsttot").val(totigst.toFixed(2));
            // $("#igstamt").val(totigst);

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);

		gettotalgstrate();

    }

	function getrowgst(this_id) {

        var id=this_id.split("_");
        id=id[1];

		// alert("Hello");
        var qty = $("#qty_"+id).val();
        var rate = $("#rate_"+id).val();

		var total = parseFloat(rate*qty);
		$("#taxable_"+id).val(total.toFixed(2));
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var igst=$("#igst_"+id).val();
		var igst_amt=parseFloat(total*igst)/100;

        var sgst=$("#sgst_"+id).val();
		var sgst_amt=parseFloat(total*sgst)/100;

        var cgst=$("#cgst_"+id).val();
		var cgst_amt=parseFloat(total*cgst)/100;

		// alert(igst_amt);
		// alert(cgst_amt);
		// alert(sgst_amt);

        $("#rowigstamt_"+id).val(igst_amt.toFixed(2));
        $("#rowsgstamt_"+id).val(sgst_amt.toFixed(2));
        $("#rowcgstamt_"+id).val(cgst_amt.toFixed(2));

		// gettotgst(id);

    }

	function servicegstsum(this_id) {

        var id=this_id.split("_");
        id=id[1];

        var total = $("#serviceamt_"+id).val();
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var igst=$("#serviceigst_"+id).val();
		var igst_amt=parseFloat(total*igst)/100;

        var sgst=$("#servicesgst_"+id).val();
		var sgst_amt=parseFloat(total*sgst)/100;

        var cgst=$("#servicecgst_"+id).val();
		var cgst_amt=parseFloat(total*cgst)/100;

		// alert(igst_amt);
		// alert(cgst_amt);
		// alert(sgst_amt);

        $("#serviceigstamt_"+id).val(igst_amt.toFixed(2));
        $("#servicesgstamt_"+id).val(sgst_amt.toFixed(2));
        $("#servicecgstamt_"+id).val(cgst_amt.toFixed(2));

		// gettotgst(id);

    }

	function servicetotgst(id) {

        var totcgst1 = $("#cgstamt").val();
        var totsgst1 = $("#sgstamt").val();
        var totigst1 = $("#igstamt").val();

        var totcgst2 = 0;
        var totsgst2 = 0;
        var totigst2 = 0;

        var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;
		var totserviceamt = 0;


        var gst_subtot = 0;
        var grandtotal = 0;
		
        var state = $("#pos_state").val();

		$("[id^='serviceamt_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totserviceamt += quant;
		});

		$("#totserviceamt").val(totserviceamt.toFixed(2));

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='servicecgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst2 += quant;
            });

			// totcgst = parseFloat(totcgst1)+parseFloat(totcgst2);
            $("#totservicecgst").val(totcgst2.toFixed(2));

            // Assuming batqty1_id elements are input fields
            $("[id^='servicesgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst2 += quant;
            });

			// totsgst = parseFloat(totsgst1)+parseFloat(totsgst2);
            $("#totservicesgst").val(totsgst2.toFixed(2));

            // gst_subtot = totcgst+totsgst;

            // $("#gst_subtotal").val(gst_subtot);

        // } else {

            // Assuming batqty1_id elements are input fields
            $("[id^='serviceigstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totigst2 += quant;
            });

			// totigst = parseFloat(totigst1)+parseFloat(totigst2);
            $("#totserviceigst").val(totigst2.toFixed(2));

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);
		
		gettotalgstrate();

    }

	function gettotalgstrate() {

		var totcgst1 = $("#cgsttot").val();
        var totsgst1 = $("#sgsttot").val();
        var totigst1 = $("#igsttot").val();

		var totcgst2 = $("#totservicesgst").val();
        var totsgst2 = $("#totservicesgst").val();
        var totigst2 = $("#totserviceigst").val();

		var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;

        // alert(totcgst1);
        // alert(totsgst1);
        // alert(totigst1);

        // alert(totcgst);
        // alert(totsgst);
        // alert(totigst);

		totcgst = parseFloat(totcgst1)+parseFloat(totcgst2);
		totsgst = parseFloat(totsgst1)+parseFloat(totsgst2);
		totigst = parseFloat(totigst1)+parseFloat(totigst2);

		$("#cgstamt").val(totcgst.toFixed(2));
		$("#sgstamt").val(totsgst.toFixed(2));
		$("#igstamt").val(totigst.toFixed(2));

		getsubtotgst();

	}

	function getsubtotgst() {

		var totcgst = $("#cgstamt").val();
        var totsgst = $("#sgstamt").val();
        var totigst = $("#igstamt").val();

		var totaltaxable = $("#totaltaxable").val();
		var totserviceamt = $("#totserviceamt").val();

		// alert(totcgst);
		// alert(totsgst);
		// alert(totigst);
		// alert(totaltaxable);
		// alert(totserviceamt);
		
		var state = $("#pos_state").val();

		var subtotgst = 0;
		var grandtot = 0;

		if(state==27) {

			// alert()
			subtotgst = parseFloat(totsgst)+parseFloat(totcgst);
			$("#subtotgst").val(subtotgst.toFixed(2));

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot.toFixed(2));

		} else {

			subtotgst = parseFloat(totigst);
			$("#subtotgst").val(subtotgst.toFixed(2));

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot.toFixed(2));

		}

	}

	function getservice(this_id) {

		var id=this_id.split("_");
        id=id[1];

		var service_ledger = $("#serviceledger_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'getservice',service_ledger:service_ledger },
            success:function(data)
            {	
                // alert(data);
                var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#serviceigst_"+id).val(bdid[0]);
                jQuery("#servicesgst_"+id).val(bdid[1]);
                jQuery("#servicecgst_"+id).val(bdid[2]);
            }
        }); 

	}

	function get_discamt() {

		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

		var disc = $("#totdiscount").val();
        var total = $("#totaltaxable").val();

		var discamt=parseFloat(total*disc)/100;
		var disctottaxable = parseFloat(total)-parseFloat(discamt);

		$("#totaltaxable").val(disctottaxable.toFixed(2));

	}

	function addRow(tableID) 
    { 
        var count=$("#cnt").val();	
        var state=$("#pos_state").val();	

        var i=parseFloat(count)+parseFloat(1);

        var cell1="<tr id='row_"+i+"'>";

        cell1 += "<td style='width:1%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";

        cell1 += "<td style='width:15%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);check_batch_invoice(this.id);get_ledger(this.id,"+state+");get_gstdata(this.id)' >\
            <option value=''>Select</option>\
            <?php
                $record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
                foreach($record as $e_rec){	
                    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
                }
                    
            ?>
        </select></td>";
        
        cell1 += "<td style='width:10%' ><select id='ledger_"+i+"' name='ledger_"+i+"' class='select2 form-select' >\
        <option value=''>Select Ledger</option>\
            <?php
                $record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
                foreach($record as $e_rec){	
                echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
                }
                    
            ?>
        </select></td>";

        // cell1 += "<td style='width:10%'><div id='batch2_"+i+"'><button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#purinvoicebatch'>Add Batch</button></div></td>";

        cell1 += "<td style='width:5%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";

        if(state==27) {

            cell1 += "<td style='width:3%'><input name='cgst_"+i+"' id='cgst_"+i+"'   class='form-control number' type='text'/></td>";
            cell1 += "<td style='width:3%'><input name='sgst_"+i+"' id='sgst_"+i+"'   class='form-control number' type='text'/></td>";
        } else {

            cell1 += "<td style='width:3%'><input name='igst_"+i+"' id='igst_"+i+"'   class='form-control number' type='text'/></td>";
        }

        cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'  class='form-control number' type='text'/></td>";

        cell1 += "<td style='width:4%;text-align:center;'><div id='batch2_"+i+"' ></div></td>";

        cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'   onkeyup='getrowgst(this.id);gettotgst("+i+");'   class='form-control number' type='text'/>\
            <input type='hidden' name='rowcgstamt_"+i+"' id='rowcgstamt_"+i+"' value='' >\
            <input type='hidden' name='rowsgstamt_"+i+"' id='rowsgstamt_"+i+"' value='' >\
            <input type='hidden' name='rowigstamt_"+i+"' id='rowigstamt_"+i+"' value='' >\
        <input type='hidden' name='res_"+i+"' id='res_"+i+"' value=''></td>";

        // cell1 += "<td style='width:5%'><input name='disc_"+i+"' id='disc_"+i+"'    onkeyup=''  class='form-control number' type='text' value='0' /></td>";

        cell1 += "<td style='width:10%'><input name='taxable_"+i+"' id='taxable_"+i+"'  onkeyup='' class='form-control number tdalign' type='text' value='0' /></td>";

        // cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'  onchange='Getgst(this.id);showgrandtotal();' onkeyup='showgrandtotal();' onblur='showgrandtotal();' class='form-control number' type='text' value='0' /></td>";

        cell1 += "<td style='width:1%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

        $("#myTable").append(cell1);
        $("#cnt").val(i);
        // $("#product_"+i).select2(); 
        // $("#ledger_"+i).select2(); 
        // $(".select2").select2();

        $("#product_"+i).select2({
            dropdownParent: $('#table_div')
        });

        $("#ledger_"+i).select2({
            dropdownParent: $('#table_div')
        });

    }


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
                    
                    jQuery("#cgst_"+k).attr('name','cgst_'+newId);
                    jQuery("#cgst_"+k).attr('id','cgst_'+newId);
                    
                    jQuery("#sgst_"+k).attr('name','sgst_'+newId);
                    jQuery("#sgst_"+k).attr('id','sgst_'+newId);
                    
                    jQuery("#igst_"+k).attr('name','igst_'+newId);
                    jQuery("#igst_"+k).attr('id','igst_'+newId);
                    
                    jQuery("#qty_"+k).attr('name','qty_'+newId);
                    jQuery("#qty_"+k).attr('id','qty_'+newId);
                    
                    jQuery("#rate_"+k).attr('name','rate_'+newId);
                    jQuery("#rate_"+k).attr('id','rate_'+newId);	
                    
                    // jQuery("#disc_"+k).attr('name','disc_'+newId);
                    // jQuery("#disc_"+k).attr('id','disc_'+newId);
                    
                    jQuery("#taxable_"+k).attr('name','taxable_'+newId);
                    jQuery("#taxable_"+k).attr('id','taxable_'+newId);
                    
                    // jQuery("#total_"+k).attr('name','total_'+newId);
                    // jQuery("#total_"+k).attr('id','total_'+newId);
                    
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

	function get_pos(id) {

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_pos',id:id },
            success:function(data)
            {	
                $("#pos_state").html(data);
                saleinvoice_forsalereturn_rowtable();
            }
        });

    }

	function get_address(){
  
        var customer =$("#customer").val();
        
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_address',customer:customer},
            success:function(data)
            {
                var bdid=data.split("#");
                jQuery("#bill_to").val(bdid[0]);
                jQuery("#state_name").val(bdid[1]);
                var code = bdid[2];
                jQuery("#ship_to").val(bdid[3]);

                jQuery("#state_code").val(code);
                jQuery("#pos_state").val(code);
            }
        }); 
    }

	function get_gstdata(this_id)
    {
        var id=this_id.split("_");
        id=id[1];

        var product = $("#product_"+id).val();
        var date = $("#date").val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_gstdata',id:id,product:product,date:date },
            success:function(data)
            {	
                var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#igst_"+id).val(bdid[0]);
                jQuery("#cgst_"+id).val(bdid[1]);
                jQuery("#sgst_"+id).val(bdid[2]);

                $(this).next().focus();
            }
        });	
    }

    function get_unit(this_id)
    {	

        var id=this_id.split("_");
        id=id[1];
        // alert(id);
        // var cnt = $("#cnt").val();
        
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

    function get_ledger(this_id,state) {

        var id=this_id.split("_");
        id=id[1];
        var pid = $("#product_"+id).val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_ledger',id: id,this_id:this_id,state:state,pid:pid},
            success:function(data)
            {	
                $("#ledger_"+id).html(data);
            }
        });
    }

	function check_batch_invoice(id) {
				
		var id=id.split("_");
		// alert(parent_id);
		var PTask = PTask;
		id=id[1];
		// alert(id);
		var product = $("#product_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'batch_sale_return',id:id,product:product},
			success:function(data)
			{	
				//alert(data);
				$("#batch2_"+id).html(data);	
				$(this).next().focus();
			}
		});

	}

	// function check_qty(this_id) {

	// 	var mainid = this_id;
	// 	var i=this_id.split("_");
	// 	i=i[1];

	// 	var quantity = $("#qty_"+i).val();
	// 	var product = $("#product_"+i).val();

	// 	if (quantity==0) {
	// 		if(quantity=='') {

	// 			alert ('please enter quantity first . . . !');
	// 			return false;
	// 		} else {

	// 			alert ('please enter quantity first . . . !');
	// 			return false;
	// 		}
	// 	} else {
			
	// 		getreturnbatch(i,quantity,mainid);
	// 	}
	// }

	function check_qty(this_id)
	{	
		var mainid = this_id;
		var i=this_id.split("_");
		i=i[1];

		var quantity = $("#qty_"+i).val();
		var product = $("#product_"+i).val();

		if (quantity==0) {
			if(quantity=='') {

				alert ('please enter quantity first . . . !');
				return false;
			} else {

				alert ('please enter quantity first . . . !');
				return false;
			}
		} else {
			getreturnbatch(product,i,mainid);
			// getreturnbatch(i,quantity,mainid);
		}
	}

	function getreturnbatch(product,i,mainid) {

		var qty = $("#qty_"+i).val();
		var stock = $("#stock_"+i).val();
		var common_id = $("#ad").val();
		var id = $("#id").val();
		var PTask = $("#PTask").val();
		var location =$("#location").val();
		var p_invoice_no = $("#p_invoice_no").val();

		jQuery.ajax({
			url: 'get_ajax_values.php',
			type: 'POST',
			data: { Type: 'sale_batch_return', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,p_invoice_no:p_invoice_no,mainid:mainid },
			success: function (data) {
				$('#salesinvoicereturnbatch').html(data);
				$('#saleinvoicereturnbatch').modal('show');
		
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error:", status, error);
			}
		});
	}

	function adjustentry() {

		var ad = $("#ad").val();
		var id = $("#id").val();
		var PTask = $("#PTask").val();
		var invoicenumber = $("#recordnumber").val();

		var supplier =$("#customer").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'adjust_sale_return',id:id,supplier:supplier, invoicenumber:invoicenumber,PTask:PTask },
			success:function(data)
			{	
				jQuery("#table_adjust").html(data);
				$('#adjustform').css('display', 'none');
				$('#submitform').css('display', 'block');
			}
		});	
	}


	function get_bill1(this_id) {

		var cust = $("#customer").val();
		var recordnumber = $("#recordnumber").val();
		var supplier = $("#customer").val();

		var id=this_id.split("_");
        id=id[1];

		var val = $("#type_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_bill', val:val, cust:cust, id:id, recordnumber:recordnumber, supplier:supplier },
			success:function(data) {
				
				// alert(data);
				$("#voucher_"+id).html(data);
			}
		});
	}

	function delete_row_adjust(rwcnt)
	{
		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cntad").val();	
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
				jQuery("#cntad").val(parseFloat(count-1)); 
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
		var count=$("#cntad").val();	
		var state=$("#state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:0%;text-align:center;'>"+i+"</td>";
	   
		cell1 += "<td style='width:7%' ><select name='type_"+i+"' class='required select2 form-select'  id='type_"+i+"' onchange='get_bill1(this.id);' style=''>\
			<option value=''>Select Type</option>\
			<option value='Advanced'>New Reference</option>\
			<option value='PO'>Against Bill</option>\
		</select></td>";

		cell1 += "<td style='width:8%'><div id='voucher_"+i+"'></div></td>";

		cell1 += "<td style='width:6%'><input name='invodate_"+i+"' id='invodate_"+i+"' readonly class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:8%'><input name='totalinvo_"+i+"' readonly id='totalinvo_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='pendingamt_"+i+"' readonly id='pendingamt_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='payamt_"+i+"' id='payamt_"+i+"' class='form-control required tdalign' type='text' onkeyup='gettotalamt(this.id);' />\
		</td>";
		
		cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row_adjust(this.id);'></i></td>";



		$("#myTable1").append(cell1);
		$("#cntad").val(i);
		// $("#particulars_"+i).select2();
		// $(".select2").select2();
		 
	}

	function getinvo_info(this_id) {

		var cust = $("#supplier").val();

		var id=this_id.split("_");
        id=id[1];

		var billno = $("#billno_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getinvo_info1',billno:billno, cust:cust},
			success:function(data)
			{
				var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#invodate_"+id).val(bdid[0]);
                jQuery("#totalinvo_"+id).val(bdid[1]);
                jQuery("#pendingamt_"+id).val(bdid[2]);
			}
		});

	}

	function gettotalamt(this_id) {

		var id=this_id.split("_");
        id=id[1];

		var totaltaxable = 0;

		$("[id^='payamt_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totaltaxable += quant;
		});

		$("#totalvalue").val(totaltaxable.toFixed(2));
	}

</script>

<?php 
	include("footer.php");
?>
