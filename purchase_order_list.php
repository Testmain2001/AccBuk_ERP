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

<style>

	/* #loader {
		display: none;
		border: 16px solid #f3f3f3;
		border-radius: 50%;
		border-top: 16px solid #3498db;
		width: 120px;
		height: 120px;
		animation: spin 2s linear infinite;
	}
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	} */

</style>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Purchase Order</h4>
		</div>
		<div class="col-md-2">
			<?php if((CheckCreateMenu())==1) { ?>
				<!-- <button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button> -->
				<button type="button" class="add_new btn btn-primary btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new" name="add_new">
					<i class="fas fa-plus-circle fa-lg"></i>
				</button>
			<?php } ?>
			<?php if((CheckDeleteMenu())==1) { ?>
				<!-- <button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button> -->
				<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
					<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
				</button>
			<?php } ?>
		</div>
	</div>

	<div class="card">
		<div class="card-datatable table-responsive pt-0"  style="overflow-x: auto;">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp;Sr.No.</th>
						<th style="width:8% !important;">Date</th>
						<th style="width:15% !important;">Purchase Order No</th>
						<th style="width:20% !important;">Supplier</th>
						<th style="width:15% !important;">Type</th>
						<th>User</th>
						<?php if((CheckEditMenu())==1) { ?> <th>Actions</th> <?php } ?>
					</tr>
				</thead>

				<tbody>
				<?php
					$i=0;
					$data=$utilObj->getMultipleRow("purchase_order","1");
					foreach($data as $info) {
						$i++;$j=0;
						$href= 'purchase_order_list.php?id='.$info['id'].'&PTask=view';
						$d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
						$d2=$rows=$utilObj->getCount("purchase_invoice","purchaseorder_no ='".$info['id']."'");

						if($d1>0 || $d2>0){
							$dis="disabled";
						} else {
							$dis="";
						}
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
							
						$data1=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$info['id']."'");
						foreach($data1 as $info1){
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
						<td width='3%'  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>">
							<input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp; <?php echo $i; ?>
						</td> 
						
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php echo date('d-m-Y',strtotime($info['date'])); ?>
						</td>

						
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<a href="<?php echo $href; ?>"><?php echo $info['order_code']; ?></a>
						</td>

						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php echo $supplier['name']; ?>
						</td>

						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>" >
							<?php echo $info['type']; ?>
						</td>

						

						<?php $username=$utilObj->getSingleRow("employee","id='".$info['user']."' "); ?>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php echo $username['name']; ?>
						</td>
						
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php 
								if($d1==0) { 
							?>
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
								<div class="dropdown-menu">
									<?php if((CheckEditMenu())==1) {  ?>
										<a class="dropdown-item" href="purchase_order_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
									<?php } ?>
									<?php if((CheckDeleteMenu())==1) { ?>
										<a class="dropdown-item" href="purchase_order_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
									<?php } ?>
								</div>
							<!--/div-->
							<?php } ?>
							<?php if($info['Created']!='')
							{
								//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
								$username = mysqli_fetch_array($query);
								$created=date('d-m-Y h:i A',strtotime($info['Created']));	
								$user = $username['fname'] . "  ".  $username['lname'];
								$createuser = "Created : ".$user." ".$created;
							} else {
								$createuser="";	
							}	
							
							if($info['updateduser']!='')
							{
								//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['updateduser']."'");
								$username = mysqli_fetch_array($query);											
								$created=date('d-m-Y h:i A',strtotime($info['LastEdited']));	
								$user = $username['fname'] . "  ".  $username['lname'];
								$createuser.= "&#10; Updated : ".$user." ".$created;
							} else {
								$createuser.="";	
							}
							?>
							<a $dasable ata-content='clock' title='<?php echo $createuser;?>' class='popovers' data-placement='top' style='color:brown;' data-trigger='hover'  href='#' ><i class='fa fa-clock-o' ></i></a>
						</td>
					</tr>
					<?php } ?>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php 
		include("form/purchase_order_form.php");
	?>

</div>
<!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
	
  document.getElementById("add_new").click();
   $("#add_new").val("Show List"); 
   chk_type();
    
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
		window.location="purchase_order_list.php";
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
			window.location="purchase_order_list.php?PTask=delete&id="+val; 
			
	}
}

	function mysubmit(a)
	{
		// return _isValidpopup(a);
		savedata()
	}

	function remove_urldata()
	{	 
		window.location="purchase_order_list.php";
	} 
 
	function savedata()
	{
		var PTask = $("#PTask").val();
		var table = $("#table").val();
		var LastEdited = $("#LastEdited").val();
		var id = $("#id").val();
		var cnt = $("#cnt").val();
		var cntd = $("#cntd").val();
		var order_no = $("#order_no").val();
		var type = $("#type").val();
		var voucher_type = $("#voucher_type").val();
		
		var requisition_no = $("#requisition_no").val();
		var supplier = $("#supplier").val();
		var location = $("#location").val();
		var date = $("#date").val();
		var bill_to = $("#bill_to").val();
		var ship_to = $("#ship_to").val();
		var state_name = $("#state_name").val();
		var state_code = $("#state_code").val();
		var pos_state = $("#pos_state").val();

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
		
		// alert(cntd);
		// alert(cnt);

		var unit_array=[];
		var product_array=[];
		var ledger_array=[];
		var cgst_array=[];
		var sgst_array=[];
		var igst_array=[];
		var qty_array=[];
		var rate_array=[];
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
		// alert('hiii');

		jQuery.ajax({url:'handler/purchase_order_form.php', type:'POST',
			data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,order_no:order_no,type:type,requisition_no:requisition_no,supplier:supplier,location:location,date:date,unit_array:unit_array,product_array:product_array,ledger_array:ledger_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,qty_array:qty_array,rate_array:rate_array,taxable_array:taxable_array,grandtotal:grandtotal,bill_to:bill_to,ship_to:ship_to,state_name:state_name,state_code:state_code,pos_state:pos_state,voucher_type:voucher_type,cntd:cntd,serviceledger_array:serviceledger_array,servicecgst_array:servicecgst_array,servicesgst_array:servicesgst_array,serviceigst_array:serviceigst_array,serviceamt_array:serviceamt_array,totdiscount:totdiscount,totaltaxable:totaltaxable,cgstledger:cgstledger,cgstamt:cgstamt,sgstledger:sgstledger,sgstamt:sgstamt,igstledger:igstledger,igstamt:igstamt,subtotgst:subtotgst,totserviceamt:totserviceamt },
			success:function(data)
			{
				if(data!="")
				{	
					// alert(data);
					alert('Record Has Been Added Successfully !!!');
					window.location='purchase_order_list.php';
				} else {
					alert('error in handler');
				}
			}
		});
	}


	function deletedata(id){
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
		jQuery.ajax({url:'handler/purchase_order_form.php', type:'POST',
			data: { PTask:PTask,id:id},
			success:function(data)
			{	
				if(data!="")
				{
						//alert(data);					
						window.location='purchase_order_list.php';
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
