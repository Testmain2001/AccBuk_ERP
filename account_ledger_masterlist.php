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
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'){
	$rows=$utilObj->getSingleRow("account_ledger","id='".$_REQUEST['id']."'");
}
//echo $_SESSION['Client_Id'];
?>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<!--div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold py-3 mb-4"> Role Master</h4>
	</div>
	<div class="col-md-2">
	<button class=" btn btn-primary mr-2" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="addnew">Add New</button>
	</div>
</div-->
<div class="row">     
<div class="col-md-3">       
<h4 class="fw-bold mb-4" style="padding-top:2px;"> Account Ledger Master</h4>
</div>
<div class="col-md-2">
<!--button type="button" class="btn btn-danger btn-sm" id="confirm-text" onclick="CheckDelete();">Delete</button-->
<?php if((CheckCreateMenu())==1){  ?>   
<button class=" btn btn-primary mr-2 btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="addnew"><i class="fas fa-plus-circle fa-lg"></i></button>
<?php } ?>		
<?php if((CheckDeleteMenu())==1){ ?>		
<button   class=" btn btn-danger  btn-sm " onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
<?php } ?>	
</div>
</div>
<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    
	<table class=" table table-bordered dataTable no-footer dataTables_wrapper dt-bootstrap5" id="datatable-buttons" role="grid">
      <thead>
        <tr>
			<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /></th>
			<th width="15%" >Name</th>
			<th width="30%" >Group Name</ht>
			<th width="18%" >Opening Balance Method</th>
			<th width="18%" >Opening Balance Amount</th>
			<!-- <th>price level</th>
			<th>inventory allocation</th>
			<th>cost_tracking</th> -->
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
		$data=$utilObj->getMultipleRow("account_ledger","1");
		foreach($data as $info){
			
			if($info['id']==1){
			   $dasable="disabled";	
			}else{
				$dasable="";	
			}
			$href= 'account_ledger_masterlist.php?id='.$info['id'].'&PTask=view';
			$gruopnm=$utilObj->getSingleRow("group_master","id='".$info['group_name']."'");
		?>
		<tr>
		<td width=3% class='controls'><input type='checkbox' class='checkboxes' <?php echo $dasable ;?> name='check_list' value='<?php echo $info['id']; ?>' /> </td> 
		<td><?php echo $info['name']; ?> </td> 
		<td><a href="<?php echo $href; ?>"> <?php echo $gruopnm['group_name']; ?></a> </td>
		<td> <?php echo $info['op_method']; ?> </td>
		<td class="tdalign"> <?php echo formatNumber($info['op_balance']); ?></td>
		<!-- <td> <?php // echo $info['price_level']; ?> </td>
		<td> <?php // echo $info['inventory_allocation']; ?> </td>
		<td> <?php // echo $info['cost_tracking']; ?> </td> -->
		
		<td>
            <!--div class="dropdown"-->
           
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu">
					<?php if(CheckEditMenu()==1) {  ?>
					<a class="dropdown-item" href="account_ledger_masterlist.php?PTask=update&id=<?php  echo $info['id']; ?>"><i class="bx bx-edit-alt me-1"></i> Edit</a>
					<?php } ?>
					<?php if((CheckDeleteMenu())==1){ ?>
					<a class="dropdown-item" href="account_ledger_masterlist.php?PTask=delete&id=<?php  echo $info['id']; ?>"><i class="bx bx-trash me-1"></i> Delete</a>
					<?php } ?>
              </div>
            <!--/div-->
        </td>
		</tr>
		<?php 
		$i=$i+1;
		} ?>
	  </tbody>
	   </table>
  </div>
</div>
<!-- Content -->
<!-- Add Role Modal -->
<?php 
	include("form/account_ledger_masterform.php");
?>
<script>
// add update and submit script function
<?php 
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'){ ?>	
	window.onload=function(){
		document.getElementById("addnew").click();
		$("#addnew").val("Show List"); 
		getfield();
		show_mailingdetails();
		show_tdstax_details();
		show_gsttaxdetails();
		show_paydetails();
	};
<?php } ?>

<?php if($_REQUEST['PTask']=='delete'){ ?>	
	window.onload=function(){
		var r = confirm("Are you sure to delete?");
		if (r==true)
		{
			deletedata("<?php echo $_REQUEST['id']; ?>");
		}
		else
		{
			window.location="account_ledger_masterlist.php"; 
			// return false;
		}
	};
<?php } ?>
 function CheckDelete()
{
	// alert("chk_delte");   
	var val='';
	$('input[type="checkbox"]').each(function()
	{	
		if(this.checked==true && this.value!='on'&&this.value!='0')
		{
			//if(this.value==='on'){ continue;}
			val +=this.value+",";
		}
	});
 	// alert(val);
	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
		
			val = val.substring(0, val.length - 1);
			window.location="account_ledger_masterlist.php?PTask=delete&id="+val; 
			/* var array = [val];
			alert('array'+array);
			deletedata(array); */
		 
	}
}
function remove_urldata()
{	
	window.location="account_ledger_masterlist.php";
}	
function mysubmit(a)
{   
	return _isValidpopup(a);	
}

function savedata(){
	//alert("hii");
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var LastEdited = $("#LastEdited").val();
	var table = $("#table").val();
	var group_name = $("#group_name").val();
	var actgrp = $("#actgrp").val();
	var name = $("#name").val();
	var interst = $("#interst").val();
	var credit_limit = $("#credit_limit").val();
	var credit_period = $("#credit_period").val();
	var price_level = $('input[name="price_level"]:checked').val(); 
	var price_level_group = $("#price_level_group").val();

	// var inventory_allocation = $("#inventory_allocation").val();
	// var cost_tracking = $("#cost_tracking").val();
	
	var inventory_allocation = $('input[name="inventory_allocation"]:checked').val();
	var cost_tracking = $('input[name="cost_tracking"]:checked').val();
	var bank_paydetails = $('input[name="bank_paydetails"]:checked').val();

	var opening_balance = $('input[name="opening_balance"]:checked').val();
	var op_method = $("#op_method").val();
	var op_balance = $("#op_balance").val();
	var mailing = $('input[name="mailing"]:checked').val();
	var bank_reconcilation = $('input[name="bank_reconcilation"]:checked').val();
	var bill_wise_details = $('input[name="bill_wise_details"]:checked').val();
	var cheque_book_registor = $('input[name="cheque_book_registor"]:checked').val();
	var cheque_book_printing = $('input[name="cheque_book_printing"]:checked').val();
	var tds_tax_details = $('input[name="tds_tax_details"]:checked').val();
	var linking_inventory = $('input[name="linking_inventory"]:checked').val();
	var gst_tax_allocation = $('input[name="gst_tax_allocation"]:checked').val();
	

	// ================= Mailing Details =================
	var mail_nameforprint = $("#mail_nameforprint").val();
	var address_cnt = $("#address_cnt").val();
	var mail_add;
	// var mail_address='0';
	var mail_address=[];
	for(var i=1;i<=address_cnt;i++) {
		mail_add=$("#mail_address"+i).val(); 
		// mail_address=mail_add+"#"+mail_address;
		mail_address.push(mail_add);
	} 
	// alert(mail_address);
	var mail_state = $("#mail_state").val();
	var mail_pin = $("#mail_pin").val();
	var mail_contactno1 = $("#mail_contactno1").val();
	var mail_contactno2 = $("#mail_contactno2").val();
	var mail_emailno = $("#mail_emailno").val();
	var mail_panno = $("#mail_panno").val();
	var mail_gstno = $("#mail_gstno").val();
	var mail_fassaino = $("#mail_fassaino").val();
	// ================= TDS Tax Details =================
	var tdstax_deductor = $("#tdstax_deductor").val();
	var tdstax_deducteetype = $("#tdstax_deducteetype").val();
	var tdstax_tds_deductionentry = $('input[name="tdstax_tds_deductionentry"]:checked').val();
	//==============GST Tax Details========================
	var gsttax_gst_applicable = $('input[name="gsttax_gst_applicable"]:checked').val();
	var gsttax_calculatefrom = $("#gsttax_calculatefrom").val();
	var description = $("#description").val();
	var hsn_sac = $("#hsn_sac").val();
	var cal_type = $("#cal_type").val();
	var taxability = $("#taxability").val();
	var rev_charge =$('input[name="rev_charge"]:checked').val();
	var ineligible_input =$('input[name="ineligible_input"]:checked').val();
	var igst = $("#igst").val();
	var cgst = $("#cgst").val();
	var sgst = $("#sgst").val();
	var cess = $("#cess").val();

	var gst_ledger_type = $("#gst_ledger_type").val();
	var gst_ledger_usage = $("#gst_ledger_usage").val();
	var gst_type = $("#gst_type").val();

	var bank_acc_no = $("#bank_acc_no").val();
	var bank_name = $("#bank_name").val();
	var ifsc = $("#ifsc").val();
	var branch_name = $("#branch_name").val();
	var upi_id = $("#upi_id").val();
	var upi_mob_no = $("#upi_mob_no").val();
	
	jQuery.ajax({url:'handler/account_ledger_masterform.php', type:'POST',
		data: {PTask:PTask,id:id,LastEdited:LastEdited,table:table,group_name:group_name,name:name,interst:interst,credit_limit:credit_limit,credit_period:credit_period,price_level:price_level,price_level_group:price_level_group,inventory_allocation:inventory_allocation,cost_tracking:cost_tracking,opening_balance:opening_balance,op_method:op_method,op_balance:op_balance,mailing:mailing,bank_reconcilation:bank_reconcilation,bill_wise_details:bill_wise_details,cheque_book_registor:cheque_book_registor,cheque_book_printing:cheque_book_printing,tds_tax_details:tds_tax_details,linking_inventory:linking_inventory,gst_tax_allocation:gst_tax_allocation,mail_nameforprint:mail_nameforprint,mail_address:mail_address,mail_state:mail_state,mail_pin:mail_pin,mail_contactno1:mail_contactno1,mail_contactno2:mail_contactno2,mail_emailno:mail_emailno,mail_panno:mail_panno,mail_gstno:mail_gstno,mail_fassaino:mail_fassaino,tdstax_deductor:tdstax_deductor,tdstax_deducteetype:tdstax_deducteetype,tdstax_tds_deductionentry:tdstax_tds_deductionentry,gsttax_gst_applicable:gsttax_gst_applicable,gsttax_calculatefrom:gsttax_calculatefrom,description:description,hsn_sac:hsn_sac,cal_type:cal_type,taxability:taxability,rev_charge:rev_charge,ineligible_input:ineligible_input,igst:igst,cgst:cgst,sgst:sgst,cess:cess,gst_ledger_type:gst_ledger_type,gst_ledger_usage:gst_ledger_usage,gst_type:gst_type,bank_paydetails:bank_paydetails,bank_acc_no:bank_acc_no,bank_name:bank_name,ifsc:ifsc,branch_name:branch_name,upi_id:upi_id,upi_mob_no:upi_mob_no,actgrp:actgrp },
		success:function(data)
		{	
			if(data!="")
			{
				alert("Record Inserted Successfully!!!");
				window.location='account_ledger_masterlist.php';
			}else{
				alert('error inthe handler!!');
			}
		}
	});
}
function deletedata(id){
	
	var PTask = "<?php echo $_REQUEST['PTask']; ?>";
	//var id = "<?php echo $_REQUEST['id']; ?>";
	//alert("deletedata="+id);
	jQuery.ajax({url:'handler/account_ledger_masterform.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
				//alert(data);
				//alert("Record has been Deleted Sucessfully!!!!");					
				window.location='account_ledger_masterlist.php';
			}else{
				alert('faiel');
			}
		}
	});
}
</script>
<script>
<!--function of multiple delete-->
function getrowchk(cname){	
var cname_new=cname.split("_");
var j=cname_new[1];
	if ($("#"+cname).is(':checked')) {
		$(".chkCreate_"+j).prop("checked", true);
		$(".chkEdit_"+j).prop("checked", true);
		$(".chkDelete_"+j).prop("checked", true);
		$(".chkView_"+j).prop("checked", true);
		
		
	} else {
		$(".chkCreate_"+j).prop("checked", false);
		$(".chkEdit_"+j).prop("checked", false);
		$(".chkDelete_"+j).prop("checked", false);
		$(".chkView_"+j).prop("checked", false);
		
		
	}
}
	
function getcheck(cname){
	if ($("#"+cname).is(':checked')) {
		$("."+cname).prop("checked", true);
		getrowchk(cname);
	} else {
		$("."+cname).prop("checked", false);
		getrowchk(cname);
	}
}	

function getCheckAll(cname){	

	var cname_new=cname.split("_");
	var j=cname_new[1];
	var chkcount=$("#chkcount").val();
	for(var k=j; k<chkcount; k++){
		var id=parseFloat(k+1);
		//alert(id);	
		if ($("#"+cname).is(':checked')) {
			  // alert(id);					
			$(".chk_"+id).prop("checked", true);
			getcheck("chk_"+id);					
		} else {
			$(".chk_"+id).prop("checked", false);
			getcheck("chk_"+id);
		}				
	}				
}

function get_Check_All(selectid){
	
	//alert(selectid);
	if ($("#"+selectid).is(':checked')) { //alert("1");
			$("."+selectid).prop("checked", true);
	} else { //alert("2");
			$("."+selectid).prop("checked", false);
	}
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
