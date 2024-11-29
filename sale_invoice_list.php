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
            
<div class="row">     
	<div class="col-md-3">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Invoice </h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1){  ?>
		<button class="btn btn-primary mr-2  btn-sm"><a href="sale_invoice_form.php" style = "color: white; "><i class="fas fa-plus-circle fa-lg"></i></a></button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
		<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
	<?php } ?>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
			<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
			<th width='10%'>Date</th>
			<th width='10%'>Sale Invoice No.</th>
			<th width='10%'>Customer</th>
			<th width='10%'>Location</th>
			<th width='10%'>Voucher Type</th>
			<th width='10%'>Challan No</th>
			<th>Product</th>
			<th>Unit</th>
			<th>Quantity</th>
			<th>Amount</th>
			<th>User</th>
			<?php if((CheckEditMenu())==1) {  ?> <th width='10%'>Actions</th> <?php } ?>
        </tr> 
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			$data=$utilObj->getMultipleRow("sale_invoice","1");
			foreach($data as $info)
			{
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
							<td  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <?php echo date('d-m-Y',strtotime($info['date'])); ?> </td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><a href="<?php echo $href; ?>"><?php echo $info['sale_invoiceno']; ?></td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $customer['name']; ?></td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $delivery_challan['challan_no']; ?></td>
							<td ><?php echo $product['name']; ?></td>
						    <td><?php echo $info1['unit']; ?></td>
						    <td><?php echo $info1['qty']; ?></td>
						    <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['grandtotal']; ?></td>
							<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
						    <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
							<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
								<a data-content="Sale Invoice  Print" title="" href="sale_invoice_print.php?id=<?php echo $info['id'] ;?>&Task=update" class="btn btn-warning btn-sm" data-original-title="Sale Invoice Print">
									<i class="fas fa-print fa-lg" style="color: #101370;"></i>
								</a>
								<?php 
									if($d1==0) { ?>
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
									<div class="dropdown-menu">
									<?php if((CheckEditMenu())==1) { ?>
										
										<a class="dropdown-item" href="sale_invoice_form.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
									<?php } ?>
									<?php if((CheckDeleteMenu())==1) { ?>

										<a class="dropdown-item" href="sale_invoice_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
									<?php } ?>
									</div>
								<?php } ?>
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


<?php 
include("form/sale_invoice_form.php");
?>

</div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') { ?>	

	window.onload=function() {
		document.getElementById("add_new").click();
		$("#add_new").val("Show List"); 
		get_deliverychallan();
		get_totalqty();
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
 
// function savedata()
// {
// 	var PTask = $("#PTask").val();
// 	var table = $("#table").val();
// 	var LastEdited = $("#LastEdited").val();
// 	var id = $("#id").val();
// 	var cnt = $("#cnt").val();
	
// 	var sale_invoiceno = $("#sale_invoiceno").val();
// 	var date = $("#date").val();
// 	var voucher_type = $("#voucher_type").val();
// 	var customer = $("#customer").val();
// 	var location = $("#location").val();
// 	var delivery_challan_no = $("#delivery_challan_no").val();
// 	var total_quantity = $("#total_quantity").val();
	
// 	var transcost = $("#transcost").val();
// 	var transgst = $("#transgst").val();
// 	var transamount = $("#transamount").val();
// 	var subt = $("#subt").val();
// 	var trans = $("#trans").val();
// 	var totcst_amt = $("#totcst_amt").val();
// 	var totsgst_amt = $("#totsgst_amt").val();
// 	var totigst_amt = $("#totigst_amt").val();
// 	var tcs_tds = $("#tcs_tds").val();
// 	var tcs_tds_percen = $("#tcs_tds_percen").val();
// 	var tcs_tds_amt = $("#tcs_tds_amt").val();
// 	var other = $("#other").val();
// 	var roff = $("#roff").val();
// 	var grandtotal = $("#grandtotal").val();
// 	var otrnar = $("#otrnar").val();
	
// 	var unit_array=[];
// 	var product_array=[];
// 	var cgst_array=[];
// 	var sgst_array=[];
// 	var igst_array=[];
// 	var orderqty_array=[];
// 	var qty_array=[];
// 	var rate_array=[];
// 	var taxable_array=[];
// 	var total_array=[];
		
// 	for(var i=1;i<=cnt;i++)
// 	{
// 		var unit = $("#unit_"+i).val();	
// 		var product = $("#product_"+i).val();
// 		var cgst = $("#cgst_"+i).val();	
// 		var sgst = $("#sgst_"+i).val();	
// 		var igst = $("#igst_"+i).val();	
// 		var qty = $("#qty_"+i).val();	
// 		var orderqty = $("#orderqty_"+i).val();	
// 		var rate = $("#rate_"+i).val();	
// 		var taxable = $("#taxable_"+i).val();	
// 		var total = $("#total_"+i).val();	
		
// 		product_array.push(product);
// 		unit_array.push(unit);
// 		cgst_array.push(cgst);
// 		sgst_array.push(sgst);
// 		igst_array.push(igst);
// 		orderqty_array.push(orderqty);
// 		qty_array.push(qty);
// 		rate_array.push(rate);
// 		taxable_array.push(taxable);
// 		total_array.push(total);
	
// 	} 
// 	//alert('hiii');
			
// 	jQuery.ajax({url:'handler/sale_invoice_form.php', type:'POST',
// 		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,sale_invoiceno:sale_invoiceno,date:date,voucher_type:voucher_type,customer:customer,location:location,delivery_challan_no:delivery_challan_no,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,orderqty_array:orderqty_array,qty_array:qty_array,rate_array:rate_array,taxable_array:taxable_array,total_array:total_array,transcost:transcost,transgst:transgst,transamount:transamount,subt:subt,trans:trans,totcst_amt:totcst_amt,totsgst_amt:totsgst_amt,totigst_amt:totigst_amt,tcs_tds:tcs_tds,tcs_tds_percen:tcs_tds_percen,tcs_tds_amt:tcs_tds_amt,other:other,roff:roff,grandtotal:grandtotal,otrnar:otrnar},
// 		success:function(data)
// 		{	
// 			if(data!="")
// 			{	
// 				//alert(data);				
// 				window.location='sale_invoice_list.php';
// 			}else{
// 				alert('error in handler');
// 			}
// 		}
// 	});			 
// }


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


</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
