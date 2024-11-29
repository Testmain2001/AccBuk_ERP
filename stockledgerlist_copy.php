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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Ledger Master</h4>
	</div>
	<div class="col-md-2">
	<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button>
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
		  <th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /></th>
          <th>Sr.No.</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data=$utilObj->getMultipleRow("stock_ledger","1");
			foreach($data as $info){
				
					$href= 'stock_ledger_list.php?id='.$info['id'].'&PTask=view';
					
					
		
		?>
		<tr>
		<td width='3%' class='controls'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/></td> 
		<td><?php echo $i; ?></td>
		<td> <a href="<?php echo $href; ?>"><?php echo $info['name']; ?></a> </td>
		
		<td>
            <!--div class="dropdown"-->
        
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
               <a class="dropdown-item" href="stock_ledger_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
			
                <a class="dropdown-item" href="stock_ledger_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
				 <a data-content='Generate QR_Code' title='Generate QR_Code' class='dropdown-item popovers' data-placement='top' data-trigger='hover' $dasable href='qr_print.php?id=<?php echo $info['id'];?>' >
			                	            <i class='fa fa-qrcode' style="color:green;font-size:20px" ></i></a>
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


<?php 
include("form/stock_ledger_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
  document.getElementById("add_new").click();
   $("#add_new").val("Show List"); 
    get_unit_formula();
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
			window.location="stock_ledger_list.php";
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
			window.location="stock_ledger_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	return _isValidpopup(a);	
}

function remove_urldata()
{	 
	window.location="stock_ledger_list.php";
} 
 
function savedata(){
	
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var name = $("#name").val();
	var under_group = $("#under_group").val();
	var negative_stk_block =$('input[name="negative_stk_block"]:checked').val();
	var sale_invoicing =$('input[name="sale_invoicing"]:checked').val();
	var unit = $("#unit").val();
	var alt_unit = $("#alt_unit").val();
	var unit_qty = $("#unit_qty").val();
	var altunit_qty = $("#altunit_qty").val();
	var batch_maintainance =$('input[name="batch_maintainance"]:checked').val();
	var bill_of_material =$('input[name="bill_of_material"]:checked').val();
	var cost_tracking =$('input[name="cost_tracking"]:checked').val();
	var costing_method = $("#costing_method").val();
	var new_mfg =$('input[name="new_mfg"]:checked').val();
	var consumed =$('input[name="consumed"]:checked').val();

	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();

	 jQuery.ajax({url:'handler/stock_ledger_form.php', type:'POST',
				data: { PTask:PTask,id:id,name:name,under_group:under_group,negative_stk_block:negative_stk_block,table:table,LastEdited:LastEdited,sale_invoicing:sale_invoicing,unit:unit,alt_unit:alt_unit,unit_qty:unit_qty,altunit_qty:altunit_qty,batch_maintainance:batch_maintainance,bill_of_material:bill_of_material,cost_tracking:cost_tracking,costing_method:costing_method,new_mfg:new_mfg,consumed:consumed},
				success:function(data)
				{	
				
					if(data!="")
					{	alert(data);			
						window.location='stock_ledger_list.php';
					}else{
						//alert("hiii handler prob");
					}
				}
			});  
}


function deletedata(id){
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
		jQuery.ajax({url:'handler/stock_ledger_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
								alert(data);					
								window.location='stock_ledger_list.php';
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
