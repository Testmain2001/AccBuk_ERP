<?php 
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task=='') { $task='Add'; }
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
	unset($_SESSION['FromDate']);
	unset($_SESSION['ToDate']);
	// unset($_SESSION['cname']);

	if($_REQUEST['Task']=='filter') {

		$from=$_REQUEST['FromDate'];
		$Date1=date('Y-m-d',strtotime($from));
		
		$to=$_REQUEST['ToDate'];
		$Date=date('Y-m-d',strtotime($to));
		
		
		$_SESSION['FromDate']=date($Date1);
		$_SESSION['ToDate']=date($Date);
		$inputfrom=date('d-m-Y',strtotime($from));
		$inputto=date('d-m-Y',strtotime($to));
		// $_SESSION['cname']=$_REQUEST['cname'];

	} else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='') {

		$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}
?>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Registor </h4>
		</div>
	</div>
	<div class="row" style="margin-bottom:12px;">
		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
			<div class="col-md-3 ">
            <label  class="form-label">FromDate</label>
            <input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
            </div> 
		  <div class="col-md-3 ">
            <label  class="form-label">ToDate</label>
            <input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
          </div>
				<div class="col-md-3">
					<label class="form-label" >Customer: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['customer']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['customer']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>
				<div class="col-md-3" style="padding-top:25px;">
				     
					  <input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
		 </form>
</div>
<!-- Invoice List Table -->


	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='2%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> &nbsp; Sr. NO</th>
						<th width='2%'>Date</th>
						<th width='12%'>Particular</th>
						<th width='10%'>Voucher Type</th>
						<th width='5%'>Voucher No</th>
						<th width='5%'>Ass. Value</th>
						<th width='5%'>CGST AMT</th>
						<th width='5%'>SGST AMT</th>
						<th width='5%'>IGST AMT</th>
						<th width='5%'>User</th>
					</tr>
				</thead>
			
				<tbody>
				<?php
					$i=0;
					if($_REQUEST['Task']=='filter'&& $_REQUEST['customer']!="All"&&$_REQUEST['customer']!=""){

						$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND customer='".$_REQUEST['customer']."'";
					} else {

						$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					}

					$data=$utilObj->getMultipleRow("sale_invoice"," $cnd");
					foreach($data as $info) {
							
						$i++;$j=0;;
						$href= 'sale_invoice_list.php?id='.$info['id'].'&PTask=view';
						/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
						if($d1>0){
							$dis="disabled";
						}else{
							$dis="";
						} */

						$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$delivery_challan=$utilObj->getSingleRow("delivery_challan","id='".$info['delivery_challan_no']."'");
						$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");

						$data1=$utilObj->getMultipleRow("sale_invoice_details","parent_id='".$info['id']."'");
						foreach($data1 as $info1) {

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
						<td >
							<input type='checkbox' class='checkboxes' <?php //echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/>  &nbsp; <?php echo $i; ?>
						</td>

						<td >
							<?php echo date('d-m-Y',strtotime($info['date'])); ?>
						</td>

						<td >
							<a href="<?php echo $href; ?>"><?php echo $customer['name']; ?></a>
						</td>

						<td>
							<?php echo $voucher['name']; ?>
						</td>

						<td>
							<?php echo $info['saleino_code']; ?>
						</td>

						<td>
							<?php echo $info['grandtotal']; ?>
						</td>

						<td>
							<?php echo $info['cgstamt']; ?>
						</td>
						
						<td>
							<?php echo $info['sgstamt']; ?>
						</td>

						<td>
							<?php echo $info['igstamt']; ?>
						</td>

						<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php echo $username['name']; ?>
						</td>
					</tr>
					<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>




</div>

<script>
window.onload=function(){
	$("#fromdate").flatpickr({
	dateFormat: "d-m-Y"
	});
	$("#todate").flatpickr({
	dateFormat: "d-m-Y"
	});
}

function Search(){
	var fromdate=$('#fromdate').val();
	var todate=$('#todate').val();
	var customer=$('#customer').val();
	window.location="sale_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&customer="+customer+"&Task=filter";
	

}


/* <?php
if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		    deletedata("<?php echo $_REQUEST['id'];?>");
		 }
		else
		{
			window.location="sale_invoice_list.php";
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
			window.location="sale_invoice_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	return _isValidpopup(a);	
}

function remove_urldata()
{	 
	window.location="sale_invoice_list.php";
} 
 
function savedata()
{
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	
	var sale_invoiceno = $("#sale_invoiceno").val();
	var date = $("#date").val();
	var voucher_type = $("#voucher_type").val();
	var customer = $("#customer").val();
	var location = $("#location").val();
	var delivery_challan_no = $("#delivery_challan_no").val();
	var total_quantity = $("#total_quantity").val();
	
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
	
	var unit_array=[];
	var product_array=[];
	var cgst_array=[];
	var sgst_array=[];
	var igst_array=[];
	var orderqty_array=[];
	var qty_array=[];
	var rate_array=[];
	var taxable_array=[];
	var total_array=[];
		
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var cgst = $("#cgst_"+i).val();	
		var sgst = $("#sgst_"+i).val();	
		var igst = $("#igst_"+i).val();	
		var qty = $("#qty_"+i).val();	
		var orderqty = $("#orderqty_"+i).val();	
		var rate = $("#rate_"+i).val();	
		var taxable = $("#taxable_"+i).val();	
		var total = $("#total_"+i).val();	
		
		product_array.push(product);
		unit_array.push(unit);
		cgst_array.push(cgst);
		sgst_array.push(sgst);
		igst_array.push(igst);
		orderqty_array.push(orderqty);
		qty_array.push(qty);
		rate_array.push(rate);
		taxable_array.push(taxable);
		total_array.push(total);
	
	} 
	//alert('hiii');
			
	jQuery.ajax({url:'handler/sale_invoice_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,sale_invoiceno:sale_invoiceno,date:date,voucher_type:voucher_type,customer:customer,location:location,delivery_challan_no:delivery_challan_no,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,orderqty_array:orderqty_array,qty_array:qty_array,rate_array:rate_array,taxable_array:taxable_array,total_array:total_array,transcost:transcost,transgst:transgst,transamount:transamount,subt:subt,trans:trans,totcst_amt:totcst_amt,totsgst_amt:totsgst_amt,totigst_amt:totigst_amt,tcs_tds:tcs_tds,tcs_tds_percen:tcs_tds_percen,tcs_tds_amt:tcs_tds_amt,other:other,roff:roff,grandtotal:grandtotal,otrnar:otrnar},
		success:function(data)
		{	
			if(data!="")
			{	
				//alert(data);				
				window.location='sale_invoice_list.php';
			}else{
				alert('error in handler');
			}
		}
	});			 
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
	jQuery.ajax({url:'handler/sale_invoice_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
				//alert(data);					
				window.location='sale_invoice_list.php';
			}else{
			  alert('error in handler');	
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
 */

</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
