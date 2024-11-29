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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">GRN (Goods Receipt Notes)</h4>
		</div>
		<div class="col-md-4">
			<?php if((CheckCreateMenu())==1) { ?>
				<button class="btn btn-primary mr-2  btn-sm"><a href="grn_form1.php" style = "color: white; "><i class="fas fa-plus-circle fa-lg"></i></a></button>
			<?php } ?>
			<?php if((CheckDeleteMenu())==1) { ?>
				<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
			<?php } ?>
		</div>
	</div>


	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> &nbsp; Sr.No.</th>
						<th>Date</th>
						<th>GRN No</th>
						<th>Type</th>
						<th>Supplier</th>
						<!-- <th>Location</th> -->
						<th>Voucher Type</th>
						<!-- <th>Product</th>
						<th>Unit</th>
						<th>Quantity</th> -->
						<th>User</th>
						<?php if((CheckEditMenu())==1) { ?><th>Actions</th><?php } ?>
					</tr>
				</thead>
		
				<tbody>
				<?php
					$i=0;
					$data=$utilObj->getMultipleRow("grn","1");

					foreach($data as $info)
					{
						$i++;$j=0;
						$href= 'grn_form1.php?id='.$info['id'].'&PTask=view';					

						//$location1=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
						$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
						$data1=$utilObj->getMultipleRow("grn_details","parent_id='".$info['id']."'");
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
										<td width='3%'  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' class='checkboxes' <?php //echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp; <?php echo $i; ?></td>

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['grn_code']; ?></a> </td>

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['type']; ?></td>

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $supplier['name']; ?></td>

										<!-- <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td> -->

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>

										<!-- <td><?php echo $product['name']; ?></td>

										<td><?php echo $info1['unit']; ?></td>

										<td><?php echo $info1['qty']; ?></td> -->

										<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>

										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
										
										
										<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
											<!--div class="dropdown"-->
										
											<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
											<div class="dropdown-menu">
												<?php if((CheckEditMenu())==1)  {  ?>
													<a class="dropdown-item"  href="grn_form1.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
												<?php } ?>
												<?php if((CheckDeleteMenu())==1){ ?>
													<a class="dropdown-item" href="GRN_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
												<?php } ?>
											</div>
											<!--/div-->
											
											
											<?php if($info['Created']!='') {
												
												// $query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
												$username = mysqli_fetch_array($query);
												$created=date('d-m-Y h:i A',strtotime($info['Created']));	
												$user = $username['fname'] . "  ".  $username['lname'];
												$createuser = "Created : ".$user." ".$created;
											} else {

												$createuser="";	
											}	
											
											// User - Updated Entry
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
						} 
					?>
				</tbody>
			</table>
		</div>
	</div>


<?php 
 //include("grn_form1.php");
?>

          </div>
          <!--/ Content -->
<!-- <script>
document.addEventListener("keydown", function(event) {
//alert("hii");
  if (event.key === "+") {
	//alert("hii");
       $("#addRecordModal").modal('show');
  }
});
</script>		   -->

<script>



<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload = function() {
            // // Redirect to "grn_form1.php"
            // window.top.location.href = "grn_form1.php";
            
            // // Call chk_type function
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
			window.location="GRN_list.php";
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
			window.location="GRN_list.php?PTask=delete&id="+val; 
			
	}
}

// function mysubmit(a)
// {
// 	return _isValidpopup(a);	
// }

// function remove_urldata()
// {	 
// 	window.location="GRN_list.php";
// } 
 
function savedata()
{
   
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	
	var grn_no = $("#grn_no").val();
	var type = $("#type").val();
	var location = $("#location").val();
	var voucher_type = $("#voucher_type").val();
	var purchaseorder_no = $("#purchaseorder_no").val();
	var supplier = $("#supplier").val();
	var date = $("#date").val();
	

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
		//alert('hiii');
		

			jQuery.ajax({url:'handler/GRN_form.php', type:'POST',
				data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,grn_no:grn_no,type:type,location:location,voucher_type:voucher_type,purchaseorder_no:purchaseorder_no,supplier:supplier,date:date,unit_array:unit_array,product_array:product_array,qty_array:qty_array },
				success:function(data)
				{	
					if(data!="")
					{	
						//alert(data);				
						window.location='GRN_list.php';
					}else{
						alert('error in handler');
					}
				}
			});			 
}


function deletedata(id){
	//alert(id);
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		//alert(PTask)
		jQuery.ajax({url:'handler/GRN_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
							//alert(data);					
							window.location='GRN_list.php';
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
