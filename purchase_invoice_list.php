<?php 
 include("header.php");
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
?>

<div class="container-xxl flex-grow-1 container-p-y ">
<style>
.taxtbl td{
	padding:5px;
}
</style>
            
<div class="row">     
	<div class="col-md-3">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Purchase Invoice</h4>
	</div>
	<div class="col-md-4">
		<?php if((CheckCreateMenu())==1) { ?>
			<!-- <button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button> -->
			<button class="btn btn-primary mr-2  btn-sm"><a href="purchase_invoiceform1.php" style = "color: white; "><i class="fas fa-plus-circle fa-lg"></i></a></button>
		<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
		<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
	<?php } ?>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
		<div class="card-datatable table-responsive pt-0" >
			
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
			<thead>
					<tr>
					<th width='10%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> &nbsp; Sr NO</th>
					<th>Date</th>
					<th>Purchase <br>invoice <br>No</th>
					<!-- <th>Type</th> -->
					
					<!-- <th>Location</th> -->
					<th>Voucher Type</th>
					<th>Supplier</th>
					<!-- <th>Product</th>
					<th>Unit</th>
					<th>Quantity</th>
					<th> Total Amount</th> -->
					<th>User</th>
					<?php if((CheckEditMenu())==1) { ?> <th>Actions</th> <?php } ?>
				</tr>
			</thead>
		
			<tbody>
				<?php
					$i=0;
					$data=$utilObj->getMultipleRow("purchase_invoice","1");
					foreach($data as $info)
					{
						$i++;$j=0;
						$href='purchase_invoiceform1.php?id='.$info['id'].'&PTask=view';
						$d1=$rows=$utilObj->getCount("purchase_return","purchase_invoice_no ='".$info['id']."'");
						if($d1>0){
							$dis="disabled";
						}
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
						$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
						$data1=$utilObj->getMultipleRow("purchase_invoice_details","parent_id='".$info['id']."'");
						foreach($data1 as $info1)
						{
							$j++;
							$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
							if($j==1) {
								$rowspan=Count($data1);
								$hidetd="";
							} else {
								$rowspan=1;
								$hidetd="hidetd";
							}
					?>
							<tr>
								<td   class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' class='checkboxes' <?php // echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/>  &nbsp; <?php echo $i; ?></td> 
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['pur_invoice_code']; ?></a> </td>
								<!-- <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['type']; ?></td> -->
								
								<!-- <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td> -->
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $supplier['name']; ?></td>
								<!-- <td><?php echo $product['name']; ?></td>
								<td><?php echo $info1['unit']; ?></td>
								<td><?php echo $info1['qty']; ?></td>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['grandtotal']; ?></td> -->
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
									<a class="dropdown-item" href="purchase_invoiceform1.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
									<?php } ?> 
									<?php if((CheckDeleteMenu())==1){ ?>
										<a class="dropdown-item" href="purchase_invoice_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
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
									
									//User - Updated Entry
									if($info['updateduser']!='')
									{
										//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['updateduser']."'");
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
<div id="handler_data" value="<?php echo $_REQUEST['handler_data'] ; ?>">
</div>

<?php 
	// include("form/purchase_invoice_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>

window.onload=function(){
	$("#date").flatpickr({
	dateFormat: "d-m-Y"
	});
}

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
	window.onload = function() {
		
		// Call chk_type function
		chk_type();
		purchaseorder_rowtable_invoice();
		// adjustentry();
	}; 
<?php } ?>



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
			window.location="purchase_invoice_list.php";
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
		}
	});
	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
			val = val.substring(0, val.length - 1);
			window.location="purchase_invoice_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	return _isValidpopup(a);	
}

function remove_urldata()
{	 
	window.location="purchase_invoice_list.php";
} 
 
function savedata() {

	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	var cntd = $("#cntd").val();
	var cntad = $("#cntad").val();
	var ad = $("#ad").val();

	var cgst_ledger = $("#cgst_ledger").val();
	var sgst_ledger = $("#sgst_ledger").val();
	var igst_ledger = $("#igst_ledger").val();
	
	
 	var invoicenumber = $("#invoicenumber").val();
	var date = $("#date").val();
	var supplier = $("#supplier").val();
	var location = $("#location").val();
	var voucher_type = $("#voucher_type").val();
	var type = $("#type").val();
	var purchaseorder_no = $("#purchaseorder_no").val();

	var bill_to = $("#bill_to").val();
	var ship_to = $("#ship_to").val();
	var state_name = $("#state_name").val();
	var state_code = $("#state_code").val();
	var pos_state = $("#pos_state").val();
	
	// var transcost = $("#transcost").val();
	// var transgst = $("#transgst").val();
	// var transamount = $("#transamount").val();
	// var subt = $("#subt").val();
	// var trans = $("#trans").val();
	// var totcst_amt = $("#totcst_amt").val();
	// var totsgst_amt = $("#totsgst_amt").val();
	// var totigst_amt = $("#totigst_amt").val();
	// var tcs_tds = $("#tcs_tds").val();
	// var tcs_tds_percen = $("#tcs_tds_percen").val();
	// var tcs_tds_amt = $("#tcs_tds_amt").val();
	// var other = $("#other").val();
	// var roff = $("#roff").val();
	// var grandtotal = $("#grandtotal").val();
	// var otrnar = $("#otrnar").val();


	var grandtotal = $("#grandtot").val();
	// var totdiscount = $("#totdiscount").val();
	var totdiscount = 0;
	var totaltaxable = $("#totaltaxable").val();
	var cgstledger = $("#cgstledger").val();
	var cgstamt = $("#cgstamt").val();
	var sgstledger = $("#sgstledger").val();
	var sgstamt = $("#sgstamt").val();
	var igstledger = $("#igstledger").val();
	var igstamt = $("#igstamt").val();
	var subtotgst = $("#subtotgst").val();
	var totserviceamt = $("#totserviceamt").val();
	
    // alert(grandtotal);
	
    var product_array=[];
	var ledger_array=[];
	var unit_array=[];
	var cgst_array=[];
	var sgst_array=[];
	var igst_array=[];
	var qty_array=[];
	var rate_array=[];
	var disc_array=[];
	var taxable_array=[];
	// var total_array=[];
	
		
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var ledger = $("#ledger_"+i).val();
		var cgst = $("#cgst_"+i).val();	
		var sgst = $("#sgst_"+i).val();	
		var igst = $("#igst_"+i).val();	
		var qty = $("#qty_"+i).val();	
		var rate = $("#rate_"+i).val();	
		var disc = $("#disc_"+i).val();	
		var taxable = $("#taxable_"+i).val();	
		// var total = $("#total_"+i).val();	
		
		product_array.push(product);
		ledger_array.push(ledger);
		unit_array.push(unit);
		cgst_array.push(cgst);
		sgst_array.push(sgst);
		igst_array.push(igst);
		qty_array.push(qty);
		rate_array.push(rate);
		disc_array.push(disc);
		taxable_array.push(taxable);
		// total_array.push(total);
	
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

	var checksub = 0;

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

	for(var i=1;i<=cnt;i++)
	{
		var producttxt = $("#product_"+i).find("option:selected").text();
		var res = $("#res_"+i).val();

		if(res!=1 && type=='Direct_Purchase') {

			alert("Plase Add Batch for this "+producttxt);
			checksub = 1;
			break;
		}
	}

	if(checksub==0 || type=='Against_Purchaseorder') {

		jQuery.ajax({url:'handler/purchase_invoice_form.php', type:'POST',
			data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,invoicenumber:invoicenumber,date:date,supplier:supplier,location:location,voucher_type:voucher_type,type:type,purchaseorder_no:purchaseorder_no,  product_array:product_array,unit_array:unit_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,qty_array:qty_array,rate_array:rate_array,disc_array:disc_array,taxable_array:taxable_array,ad:ad,ledger_array:ledger_array,bill_to:bill_to,ship_to:ship_to,state_name:state_name,state_code:state_code,pos_state:pos_state,cgst_ledger:cgst_ledger,sgst_ledger:sgst_ledger,igst_ledger:igst_ledger,cntd:cntd,serviceledger_array:serviceledger_array,servicecgst_array:servicecgst_array,servicesgst_array:servicesgst_array,serviceigst_array:serviceigst_array,serviceamt_array:serviceamt_array,totdiscount:totdiscount,totaltaxable:totaltaxable,cgstledger:cgstledger,cgstamt:cgstamt,sgstledger:sgstledger,sgstamt:sgstamt,igstledger:igstledger,igstamt:igstamt,subtotgst:subtotgst,totserviceamt:totserviceamt,grandtotal:grandtotal,type_array:type_array,billno_array:billno_array,invodate_array:invodate_array,totalinvo_array:totalinvo_array,pendingamt_array:pendingamt_array,payamt_array:payamt_array,cntad:cntad },
			success:function(data)
			{	
				if(data!="")
				{	
					window.location='purchase_invoice_list.php';
					// alert(data);
					if(PTask=='update') {
						alert("Record updated successfully");
					} else {
						alert("Record added successfully");
					}
					
				}else{
					alert('error in handler');
				}
			}
		});
	}
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
	
	jQuery.ajax({url:'handler/purchase_invoice_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
					//alert(data);					
					window.location='purchase_invoice_list.php';
			}else{
				
			}
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


</script>


<!-- Footer -->
<?php 
	include("footer.php");
?>
