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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Dispatch </h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1){  ?>
	<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button>
	<?php } ?>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
			<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
			<th width='10%'>Dispatch Record  No.</th>
			<th width='10%'>Date</th>
			<th width='10%'>Customer</th>
			<?php if((CheckEditMenu())==1) {  ?><th width='10%'>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data=$utilObj->getMultipleRow("dispatch","1");
			foreach($data as $info){
				
					$href= 'dispatch_list.php?id='.$info['id'].'&PTask=view';
					/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}else{
						$dis="";
					} */
					$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
		
		?>
		<tr>
		<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
		<td><a href="<?php echo $href; ?>"><?php echo $info['record_no']; ?></td>
		<td> <?php echo $info['date']; ?> </td>
		<td><?php echo $customer['name']; ?></td>
		<td>
        <?php 
		
		if($d1==0){ ?>
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
			  	<?php if((CheckEditMenu())==1) {  ?>
               	<a class="dropdown-item" href="dispatch_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
				<?php } ?>
				<?php if((CheckDeleteMenu())==1){ ?>
                <a class="dropdown-item" href="dispatch_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
				<?php } ?>
              </div>
           
		<?php }?>
        </td>
		</tr>
		<?php 
		$i=$i+1;
		} ?>
	  </tbody>
	   </table>
  </div>
</div>


<?php 
include("form/dispatch_form.php");
?>

</div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
	
	document.getElementById("add_new").click();
	$("#add_new").val("Show List"); 
	get_saleinvoice_fordispatch();

    
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
			window.location="dispatch_list.php";
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
			window.location="dispatch_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	return _isValidpopup(a);	
}

function remove_urldata()
{	 
	window.location="dispatch_list.php";
} 
 
function savedata()
{
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	
	var record_no = $("#record_no").val();
	var date = $("#date").val();
	var voucher_type = $("#voucher_type").val();
	var customer = $("#customer").val();
	var location = $("#location").val();
	var sale_invoice_no = $("#sale_invoice_no").val();
	var total_quantity = $("#total_quantity").val();
	

	
	var unit_array=[];
	var product_array=[];
	var cgst_array=[];
	var sgst_array=[];
	var igst_array=[];
	var qty_array=[];
	
		
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var cgst = $("#cgst_"+i).val();	
		var sgst = $("#sgst_"+i).val();	
		var igst = $("#igst_"+i).val();	
		var qty = $("#qty_"+i).val();	
			
		
		product_array.push(product);
		unit_array.push(unit);
		cgst_array.push(cgst);
		sgst_array.push(sgst);
		igst_array.push(igst);
		qty_array.push(qty);
	
	} 
	//alert('hiii');
			
	jQuery.ajax({url:'handler/dispatch_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,record_no:record_no,date:date,voucher_type:voucher_type,customer:customer,location:location,sale_invoice_no:sale_invoice_no,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,qty_array:qty_array},
		success:function(data)
		{	
			if(data!="")
			{	
				//alert(data);				
				window.location='dispatch_list.php';
			}else{
				alert('error in handler');
			}
		}
	});			 
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
	jQuery.ajax({url:'handler/dispatch_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
				//alert(data);					
				window.location='dispatch_list.php';
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
