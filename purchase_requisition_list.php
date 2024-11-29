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
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Purchase Requisition</h4>
		</div>
		<div class="col-md-2">

		<?php if((CheckCreateMenu())==1) { ?>
			<!-- <button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new">Add New</button> -->
			<button type="button" class="add_new btn btn-primary btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>

		<?php if((CheckDeleteMenu())==1) { ?>
			<!-- <button class=" btn btn-danger btn-sm"  onclick="CheckDelete();">Delete</button> -->
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		
		</div>
	</div>
<!-- Invoice List Table -->


	<div class="card">
	<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
		
		<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
		<thead>
			<tr>
				<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp; Sr.No.</th>
				<th>Date</th>
				<th>Record NO.</th>
				<th>Requisition By</th>
				<!-- <th>Location</th> -->
				<!-- <th>Product</th>
				<th>Unit</th>
				<th>Quantity</th> -->
				<th>User</th>
				<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
			</tr>
		</thead>
	
		<tbody>
		<?php
			$i=0;
			$data=$utilObj->getMultipleRow("purchase_requisition","1");
			foreach($data as $info)
			{
				$i++;$j=0;
				
				$href= 'purchase_requisition_list.php?id='.$info['id'].'&PTask=view';
				$location1=$utilObj->getSingleRow("location","id='".$info['location']."'");
				
				$d1=$rows=$utilObj->getCount("purchase_order","requisition_no ='".$info['id']."'");
				if($d1>0){
					$dis="disabled";
				}else{
					$dis=" ";
				}
				$data1=$utilObj->getMultipleRow("purchase_requisition_details","parent_id='".$info['id']."'");
				foreach($data1 as $info1)
				{
					$j++;
					$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."' ");
					if($j==1){
						$rowspan=Count($data1);
						$hidetd="";
					} else {
						$rowspan=1;
						$hidetd="hidetd";
					}  
					
					?>
					<tr>
						<td width='5%' class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp <?php echo $i; ?></td> 
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['record_no']; ?></a> </td>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <?php echo $info['requisition_by']; ?></td>
						<!-- <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location1['name']; ?></td> -->
						<!-- <td><?php echo $product['name']; ?></td>
						<td><?php echo $info1['unit']; ?></td>
						<td><?php echo $info1['qty']; ?></td> -->
						<?php $username=$utilObj->getSingleRow("employee","id='".$info['user']."'"); ?>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
							
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<!--div class="dropdown"-->
						<?php if($d1==0){ ?>
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
							<div class="dropdown-menu">
							<?php if((CheckEditMenu())==1) {  ?>
							<a class="dropdown-item" href="purchase_requisition_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
							<?php } ?>
							<?php if((CheckDeleteMenu())==1){ ?>
								<a class="dropdown-item" href="purchase_requisition_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
								<?php } ?>
							</div>
							<?php }?>
							<!--/div-->
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
			}?>
		</tbody>
		</table>
	</div>
	</div>


	<?php 
	include("form/purchase_requisition_form.php");
	?>

</div>
          
<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ ?>	
	window.onload=function(){
	document.getElementById("add_new").click();
	$("#add_new").val("Show List"); 
    
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
				window.location="purchase_requisition_list.php";
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
			window.location="purchase_requisition_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	return _isValidpopup(a);	
}

function remove_urldata()
{	 
	window.location="purchase_requisition_list.php";
} 
 
function savedata()
{

	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	var record_no = $("#record_no").val();
	var date = $("#date").val();
	var requisition_by = $("#requisition_by").val();
	var location = $("#location").val();
		//alert(cnt);	
	
	
	var unit_array=[];
	var product_array=[];
	var qty_array=[];
	
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var qty = $("#qty_"+i).val();	
		
		product_array.push(product);
		unit_array.push(unit);
		qty_array.push(qty);
	
	} 
	
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();

	jQuery.ajax({url:'handler/purchase_requisition_form.php', type:'POST',
		data: { PTask:PTask,id:id,cnt:cnt,record_no:record_no,date:date,table:table,LastEdited:LastEdited,requisition_by:requisition_by,location:location,unit_array:unit_array,product_array:product_array,qty_array:qty_array},
		success:function(data)
		{	
			if(data!="")
			{	
				//alert(data);				
				window.location='purchase_requisition_list.php';
			}
		}
	});	
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
	
	jQuery.ajax({url:'handler/purchase_requisition_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
					alert(data);					
					window.location='purchase_requisition_list.php';
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
