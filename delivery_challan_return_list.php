<?php 
	include("header.php");
	//include("handler/delivery_return_form.php");
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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Delivery Challan Return</h4>
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
			<th >Customer</th>
			<th >Location</th>
			<th>Product</th>
			<th>Unit</th>
			<th>Quantity</th>
			<th>Return Qty</th>
			<th >User</th>
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			$data=$utilObj->getMultipleRow("delivery_return","1");
			foreach($data as $info){
				
				    $i++;$j=0;
					$href= 'delivery_challan_list.php?id='.$info['id'].'&PTask=view';
					//$d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}
					$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
					$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
					$sale_invoice=$utilObj->getSingleRow("sale_invoice","id='".$info['sale_invoice_no']."'");
					$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
					$data1=$utilObj->getMultipleRow("delivery_return_details","parent_id='".$info['id']."'");
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
					
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $customer['name']; ?></td>
					<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td>
				
					<td><?php echo $product['name']; ?></td>
					<td><?php echo $info1['unit']; ?></td>
					<td><?php echo $info1['qty']; ?></td>
					<td><?php echo $info1['rejectedqty']; ?></td>
					
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
						   <a class="dropdown-item" href="delivery_challan_return_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
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
	$rows=$utilObj->getSingleRow("delivery_return","id ='".$id."'");
    $recordnumber=$rows['recordnumber'];	   
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
<div class="container-xxl flex-grow-1 container-p-y " style=" background-color: white; padding: 30px; background: #fff9f9; display:none" id="u_form">
            

	<div class="row form-validate">
		<!-- FormValidation -->
		<div class="col-12">
			<div class="card">
				<div class="card-body " >
			
				<form id="demo-form2" data-parsley-validate class="row g-3" action="delivery_challan_return_list.php"  method="post" data-rel="myForm">
				
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "delivery_return"; ?>"/>
					<input type="hidden"  name="common_id" id="common_id" value="<?php echo $common_id;?>"/>   
			  
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_dcreturn_code();">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=4 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['voucher_type']) {echo $select="selected";} else {echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}
							?>
						</select>
					</div>
                    
					<div class="col-md-4">
							<label class="form-label">Record No. <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $recordnumber;?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Return Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>

						
					
					<div class="col-md-4">
					<label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"  onchange="get_deliverychallan();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
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

					<!-- <div class="col-md-4">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" onchange="get_deliverychallan();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
		<input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
        <div id="table_div" style="overflow: hidden;">
		
	
		</div>
		
          <div class="col-12 text-center">
            <?php 
			if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='' ){?>	
				<input type="button" class="btn btn-primary mr-2" name="subumit" value="Submit"  onClick="mysubmit(0);"/>
			<?php } ?>

			<?php 
				if($_REQUEST['PTask']=='view') {
			?>	
				<?php if((CheckEditMenu())==1) {  ?>
				<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
						<a href="delivery_challan_return_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
				</button>
				<?php } ?>
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

	function get_dcreturn_code() {

        var voucher_type = $("#voucher_type").val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_sreturn_code1',voucher_type:voucher_type},
            success:function(data)
            {	
                //alert(data);
                $("#recordnumber").val(data);	
                // $(this).next().focus();
            }
        });

    }

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
		window.location="delivery_challan_return_list.php";
		
	}
	
}	


<?php 
if($_REQUEST['PTask']=='update'||$_REQUEST['Task']=='view'){?>
	window.onload=function(){
		get_deliverychallan();
		 }
		hideshow();
		get_table();
		 
<?php }
?>


<?php
if($_REQUEST['PTask']=='delete'){?>	

	var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		    deletedata("<?php echo $_REQUEST['id'];?>");
		}
		else
		{
			window.location="delivery_challan_return_list.php";
		}
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
			var r=confirm("Are you sure to delete?");
			if (r==true)
		{
			window.location="delivery_challan_return_list.php"; 
		}else
		{
			window.location="delivery_challan_return_list.php";
		}
			
	}
}

function mysubmit(a)
{
	// return _isValid2(a);
	savedata()
}

function remove_urldata()
{	 
	window.location="delivery_challan_return_list.php";
} 
 
function savedata()
{
	
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var common_id = $("#common_id").val();
	var cnt = $("#cnt").val();
	//alert();
	
 	var recordnumber = $("#recordnumber").val();
	var date = $("#date").val();
	var customer = $("#customer").val();
	var location = $("#location").val();
	var voucher_type = $("#voucher_type").val();
	var challan_no = $("#challan_no").val();
	
	var transcost = $("#transcost").val();
	var transgst = $("#transgst").val();
	var transamount = $("#transamount").val();
	var subt = $("#subt").val();
	var trans = $("#trans").val();
	var totcst_amt = $("#totcst_amt").val();
	var totsgst_amt = $("#totsgst_amt").val();
	var totigst_amt = $("#totigst_amt").val();
	var tcs_tds = $("#tcs_tds").val();
	var tcs_tds_percen = $("#tcs_tds_percen").val();
	var tcs_tds_amt = $("#tcs_tds_amt").val();
	var other = $("#other").val();
	var roff = $("#roff").val();
	var grandtotal = $("#grandtotal").val();
	var otrnar = $("#otrnar").val();

	
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
	
	jQuery.ajax({url:'handler/delivery_return_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,common_id:common_id,cnt:cnt,recordnumber:recordnumber,date:date,customer:customer,location:location,voucher_type:voucher_type,challan_no:challan_no,transcost:transcost,transgst:transgst,transamount:transamount,subt:subt,trans:trans,totcst_amt:totcst_amt,totsgst_amt:totsgst_amt,totigst_amt:totigst_amt,tcs_tds:tcs_tds,tcs_tds_percen:tcs_tds_percen,tcs_tds_amt:tcs_tds_amt,other:other,roff:roff,grandtotal:grandtotal,otrnar:otrnar,product_array:product_array,unit_array:unit_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,qty_array:qty_array,rate_array:rate_array,disc_array:disc_array,taxable_array:taxable_array,rejectedqty_array:rejectedqty_array,total_array:total_array},
		success:function(data)
		{	
			if(data!="")
			{	
				// $("#handler_data").val(data);	
			alert('Record has been Added Successfully!!');
				window.location='delivery_challan_return_list.php';
			}else{
				alert('error in handler');
			}
		}
	});
}

function deletedata(id){
		
		var PTask =	"delete";
		var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		jQuery.ajax({url:'handler/delivery_return_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
								//alert(data);					
								window.location='delivery_challan_return_list.php';
						}else{
							window.location='delivery_challan_return_list.php';
						}
					}
				});
			}else{
				window.location='delivery_challan_return_list.php';
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
		data: { Type:'get_sreturn_code1',voucher_type:voucher_type},
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
			//alert(data);
				$("#state").val(data);	
			}
		}); 
}

function get_deliverychallan()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var customer =$("#customer").val();
	var location =$("#location").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values_sale.php', type:'POST',
		data: { Type:'get_deliverychallan',id:id,PTask:PTask,customer:customer,location:location},
		success:function(data)
		{	
			$("#sale_invoice_div").html(data);	
			$(".select2").select2();
			if(PTask=='update'||PTask=='view'||PTask=='Add'){
				deliverychallan_return_rowtable();
			}
		}
	}); 
			
}

function deliverychallan_return_rowtable()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var challan_no =$("#challan_no").val();
	var customer =$("#customer").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values_sale.php', type:'POST',
		data: { Type:'deliverychallan_return_rowtable',type:type,id:id,PTask:PTask,challan_no:challan_no,customer:customer},
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

function check_qty(i) {
	var quantity = $("#rejectedqty_"+i).val();
	var PTask = $("#PTask").val();

	if (quantity == '' || quantity=='0') {
		alert ('please enter quantity first . . . !');

	} else {
		getreturnbatch(i,quantity,PTask);
	}
}

function getreturnbatch(i,quantity,PTask){
	var qty =$("#rejectedqty_"+i).val();
	var product =$("#product_"+i).val();
	var common_id =$("#common_id").val();
	var challan_no = $("#challan_no").val();
	var id = $("#id").val();

	jQuery.ajax({
		url: 'get_ajax_values_sale.php',
		type: 'POST',
		data: { Type: 'addchallanreturnbatch',challan_no:challan_no,common_id:common_id,qty:qty,product: product,id:i,quantity:quantity,PTask:PTask,id:id},
		success: function (data) {
			$('#saleschallanreturnbatch').html(data);
			$('#salechallanreturnbatch').modal('show');
	
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
		}
	});
}

function getqty(id){
	var batqty = parseFloat($("#batqty_" + id).val(), 10);
    var batchrmv = parseFloat($("#batch_remove_" + id).val(), 10);
	if(batqty<batchrmv){
		alert('Quantity is not greater than batch quantity');
		$("#batch_remove_"+id).val('');
	}
}
</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
