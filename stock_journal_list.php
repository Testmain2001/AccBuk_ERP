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

	$ad = uniqid();
?>

<style>
	.hidetd{
		display: none;
	}
	.hidetxt{
		display: none;
	}
</style>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<!-- <div class="row">     
		<div class="col-md-3">       
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Journal</h4>
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

	<div class="row">     
		<div class="col-md-2">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Journal</h4>
		</div>
		<div class="col-md-2">
		<?php if((CheckCreateMenu())==1) { ?>
			<!-- <input type="button" class="add_new btn btn-primary btn-sm  " onclick="hideshow();" id="add_new" name="add_new" value="Add New" /> -->
			<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>
		<?php if((CheckDeleteMenu())==1) { ?>
			
			<!-- <input type="button" class=" btn btn-danger  btn-sm"  onclick="CheckDelete();" id="delete" name="delete" value="Delete" /> -->
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		
		</div>
	</div>
	<!-- Invoice List Table -->

	<div id="u_table" style="display:block">
		<div class="card" width="auto !important;">
			<div class="card-datatable table-responsive pt-0">
				<table class="datatables-basic table border-top"  id="datatable-buttons" role="grid">
					<thead>
						<tr>
							<th width="5%"><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> Sr.No.</th>
							<th width="20%">Record No.</th>
							<th width="25%">Date</th>
							<?php if((CheckEditMenu())==1) {  ?> <th >Actions</th> <?php } ?>
						</tr>
					</thead>
			
					<tbody>
					<?php
						$i=1;
						if($_REQUEST['PTask']!='update' && $_REQUEST['PTask']!='view') {

							$data=$utilObj->getMultipleRow("stock_journal","1");
						}
						
						foreach($data as $info){
						
							$href= 'stock_journal_list.php?id='.$info['id'].'&PTask=view';
							//$d1=$rows=$utilObj->getCount("delivery_challan","saleorder_no ='".$info['id']."'");
							if($d1>0){
								$dis="disabled";
							}else{
								$dis="";
							}
							//$productnm=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");
						
						?>
						<tr>
							<td  width="5%" class='controls'>
								<input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp&nbsp<?php echo $i; ?>
							</td> 

							<td><a href="<?php echo $href; ?>"><?php echo $info['record_no']; ?></a></td>

							<td> <?php echo $info['date']; ?></td>
							<td>
							<!--div class="dropdown"-->
							<?php
							if($d1==0) { ?>
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
								<div class="dropdown-menu">
								<?php if((CheckEditMenu())==1) { ?>
									<a class="dropdown-item" href="stock_journal_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
								<?php } ?>
								<?php if((CheckDeleteMenu())==1) { ?>
									<a class="dropdown-item" href="stock_journal_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
								<?php } ?>
								</div>
								<!--/div-->
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
	</div>
	
	<!-- --------------------- Main Form --------------------- -->
	<?php
		$getrecordno=mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from stock_journal");
		$result=mysqli_fetch_array($getrecordno);
		$record_no=$result['pono']+1;  
		$date=date('d-m-Y');	
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
			$id=$_REQUEST['id'];
			$rows=$utilObj->getSingleRow(" stock_journal","id ='".$id."'");
			$record_no=$rows['record_no'];
			$date=date('d-m-Y',strtotime($rows['date']));
			
		} else{
			$rows=null;
		}
	?>

	<div class="container-xxl flex-grow-1 container-p-y" style="display:none; background-color: white; padding: 30px; background: #fff9f9; " id="u_form">
		<div class="row form-validate">
			<div class="col-12">
				<div class="card ">
					<div class="card-body ">
						<form id="" data-parsley-validate class="row g-3" action="../stock_transfer_list.php"  method="post" data-rel="myForm">
				
							<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
							<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>
							<input type="hidden"  name="ad"         id="ad"         value="<?php echo $ad;?>"/>

							<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
							<input type="hidden"  name="table"      id="table"      value="<?php echo "stock_journal"; ?>"/>
								
							
							<div class="col-md-4">
								<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="record_no" value="<?php echo $record_no;?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label">Date <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
							</div>							

							<h4 class="role-title"> Stock Journal Of Material Details </h4>
							<div id="table_div" style="overflow: hidden;">
								<table class="table table-bordered " id="myTable" > 
									<thead>
										<tr>
											<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
											<th style="width:2%;text-align:center;">Type</th> 
											<th style="width: 15%;text-align:center;">Product<span class="required required_lbl" style="color:red;">*</span></th>
											<th style="width: 10%;text-align:center;">Unit</th>
											<th style="width: 10%;text-align:center;">Location</th>
											<th style="width:10%;text-align:center;">Stock</th>
											<th style="width:10%;text-align:center;">Quantity</th>
											<th style="width:5%;text-align:center;">Batch</th>
											<th style="width:10%;text-align:center;">Rate</th>
											<th style="width:10%;text-align:center;">Amount</th>
											<?php if($_REQUEST['PTask']!='view'){?>
											<th style="width:2%;text-align:center;"></th>
											<?php }?>
										</tr>
									</thead>
									<tbody>
									<?php 
										$i=0;
										if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ 

											$record5=$utilObj->getMultipleRow("stock_journal_details"," parent_id='".$_REQUEST['id']."' order by id  ASC");
										} else {

											$record5[0]['id']=1;
										}

										foreach($record5 as $row_demo)
										{ 
										$i++;
									?>
										<tr id='row_<?php echo $i;?>'>
											<td style="text-align:center;width:2%;">
												<label  id="idd_<?php echo $i;?>" name="idd_<?php echo $i;?>"><?php echo $i;?></label>
											</td>
											<td  style="width: 15%;">
												<select id="type_<?php echo $i; ?>" name="type_<?php echo $i; ?>" <?php echo $disabled;?> class=" form-select required" style="width:100%;">	
													<option value="">Select Type</option>
													<option value="consumption" <?php if($row_demo['type']=="consumption"){ echo "selected";}else{ echo "";}  ?>>Consumption</option>
													<option value="production" <?php if($row_demo['type']=="production"){ echo "selected"; } else { echo ""; }  ?>>Production</option>
												</select>
											</td>
											<td  style="width: 15%;">
												<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class=" form-select required" onChange="product_check(this.id);check_stockjournalbatch(this.id);" style="width:100%;">	
												<?php 
													echo '<option value="">Select</option>';
													$record=$utilObj->getMultipleRow("stock_ledger","1 group by name ");
													foreach($record as $e_rec)
													{
														if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
														echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
													}
												?> 
												</select>
												<?php //} ?>
											</td>
											<td style="width: 10%;">
												<div id='unitdiv_<?php echo $i;?>'>
													<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
												</div>
											</td>
											<td  style="width: 10%;">
												
													<select id="location_<?php echo $i;?>" name="location_<?php echo $i;?>"  onchange="get_stock(this.id);get_qty(this.id);" <?php echo $disabled;?> class=" form-select required" data-allow-clear="true"  style="width:100%;">	
													<?php 
														echo '<option value="">Select</option>';
														$record=$utilObj->getMultipleRow("location","id!='".$loaction."' ");
														foreach($record as $e_rec)
														{
															if($row_demo['location']==$e_rec["id"]) echo $select='selected'; else $select='';
															echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
														}
													?> 
												</select>
												<?php //} ?>
											</td>
											<td style="width: 10%;">
											<?php
												$totalstock = 0;
												$tostock = getlocationstock('',$row_demo['product'],date('Y-m-d'),$row_demo['location']);
												// getstock($row_demo['product'],$row_demo['unit'],date('Y-m-d'),$_REQUEST['id'],$row_demo['location']);

												if($row_demo['type']=="production") {

													$qty = $row_demo['inqty'];
												} else {

													$qty = $row_demo['outqty'];
												}
												$totalstock = $tostock - $qty;
											?>
												<input type="text" id="stock_<?php echo $i;?>" <?php echo $readonly;?> readonly class=" form-control number" name="stock_<?php echo $i;?>"  value="<?php echo $tostock;?>"/>
											</td>

											<td style="width: 10%;">
												<input type="text" id="qty_<?php echo $i;?>"  class=" form-control required"   <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $qty; ?>"/>
											</td>

											<td style="width: 5%; text-align:center;">
											<?php if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') { ?>

												<?php 
													if ($row_demo['type']=="consumption") {
												?>

													<div id='divbatch_<?php echo $i; ?>'>
														<button type="button" class="btn btn-light btn-sm" onclick="stockjournal_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#stockjournalbatch"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
													</div>
												<?php } else { ?>

													<div id='divbatch_<?php echo $i; ?>'>
														<button type="button" class="btn btn-light btn-sm" onclick="stockjournal_batchdata1('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#stockjournalbatch1"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
													</div>
												<?php } ?>	
											<?php } else { ?>

												<div id="divbatch_<?php echo $i; ?>">

												</div>
											<?php } ?>
											</td>

											<td style="width: 10%;">
												<input type="text" id="rate_<?php echo $i;?>" class=" form-control required"  onChange="get_amount(this.id);" <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>"/>
											</td> 

											<td style="width: 10%;">
												<input type="text" id="amount_<?php echo $i;?>"   class=" form-control required"   <?php echo $readonly;?> name="amount_<?php echo $i;?>" value="<?php echo $row_demo['amount'];?>"/>
											</td>

											<?php if($_REQUEST['PTask']!='view'){?>
												<td style='width:2%'>
													<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
												</td>
											<?php } ?>
										</tr>
										<?php } ?>
									
									</tbody>
									<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
								</table>
								<br>
								<table style="width:100%;" class="taxtbl" >
									<tr style="margin:10px;text-align:center;">
										<td style="text-align:right;">
										<?php 
										if( $_REQUEST['PTask']!='view') { ?>
											<!-- <button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button> -->
											
											<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
										<?php } ?> 
										</td>			
									</tr>
								</table> 
							</div>
							
							<div class="modal fade" style = "max-width=40%; " id="stockjournalbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="stkjbatch">
										
									</div>
								</div>
							</div>

							<div class="modal fade" style = "max-width=40%; " id="stockjournalbatch1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="stkjbatch1">
										
									</div>
								</div>
							</div>

							<div class="col-12 text-center">
								<?php
								if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ ?>	
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?>

								<?php 
									if($_REQUEST['PTask']=='view') {
								?>	
									<?php if((CheckEditMenu())==1) {  ?>
									<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
										<a href="stock_journal_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
									</button>
									<?php } ?>
								<?php } ?>

								<button type="reset" class="btn btn-label-secondary" onClick="remove_urldata(0);">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<!--/ Content -->
		  

<script>

function check_stockjournalbatch(id) {

	var id=id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var type = $("#type_"+id).val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
	data: { Type:'check_stockjournalbatch', id:id, product:product, type:type },
		success:function(data)
		{	
			$("#divbatch_"+id).html(data);	
			$(this).next().focus();
		}
	});	

}

function stockjournal_batchdata(i) {
								                      
	var qty =$("#qty_"+i).val();
	var stock =$("#stock_"+i).val();
	var common_id =$("#ad").val();
	var PTask =$("#PTask").val();
	// var ad = $("#ad").val();
	var id = $("#id").val();
	var location =$("#location_"+i).val();
	var product =$("#product_"+i).val();
	var stock_type =$("#type_"+i).val();

	jQuery.ajax({
		url: 'get_ajax_values.php',
		type: 'POST',
		data: { Type: 'stockjournal_batchdata', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,stock_type:stock_type},
		success: function (data) {

			// console.log(data);
			$('#stkjbatch').html(data);
			$('#stockjournalbatch').modal('show');
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
		}
	});
}

function stockjournal_batchdata1(i) {
								                      
	var qty =$("#qty_"+i).val();
	var rate =$("#rate_"+i).val();
	var stock =$("#stock_"+i).val();
	var common_id =$("#ad").val();
	var PTask =$("#PTask").val();
	// var ad = $("#ad").val();
	var id = $("#id").val();
	var location =$("#location_"+i).val();
	var product =$("#product_"+i).val();
	var stock_type =$("#type_"+i).val();

	jQuery.ajax({
		url: 'get_ajax_values.php',
		type: 'POST',
		data: { Type: 'stockjournal_batchdata1', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,stock_type:stock_type,rate:rate },
		success: function (data) {

			// console.log(data);
			$('#stkjbatch1').html(data);
			$('#stockjournalbatch1').modal('show');
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
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
		window.location="stock_journal_list.php";
		
	}
	
}

window.onload=function(){
	$("#date").flatpickr({
	dateFormat: "d-m-Y"
	});
}

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
	window.onload=function(){
		
		// document.getElementById("add_new").click();
		$("#add_new").val("Show List"); 
		hideshow();
		
	};  
<?php } ?>



<?php
if($_REQUEST['PTask']=='delete') { ?>	
	window.onload=function(){

		var r=confirm("Are you sure to delete?");

		if (r==true) {

			deletedata("<?php echo $_REQUEST['id'];?>");
		} else {

			window.location="stock_journal_list.php";
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
			window.location="stock_journal_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	// return _isValid2(a);
	savedata();
}

function remove_urldata()
{	 
	window.location="stock_journal_list.php";
} 
 
function savedata()
{
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var ad = $("#ad").val();
	var cnt = $("#cnt").val();
	
	var record_no = $("#record_no").val();
	var date = $("#date").val();
	//var voucher_type = $("#voucher_type").val();
	
	
	var type_array=[];
	var product_array=[];
	var unit_array=[];
	var location_array=[];
	var stock_array=[];
	var qty_array=[];
	var rate_array=[];
	var amount_array=[];
	
		
	for(var i=1;i<=cnt;i++)
	{
		var type = $("#type_"+i).val();	
		var product = $("#product_"+i).val();
		var unit = $("#unit_"+i).val();	
		var location = $("#location_"+i).val();	
		var stock = $("#stock_"+i).val();	
		var qty = $("#qty_"+i).val();	
		var rate = $("#rate_"+i).val();	
		var amount = $("#amount_"+i).val();	
		
		type_array.push(type);
		product_array.push(product);
		unit_array.push(unit);
		location_array.push(location);
		stock_array.push(stock);
		qty_array.push(qty);
		rate_array.push(rate);
		amount_array.push(amount);
		
		
	} 
	//alert('hiii');
			
	jQuery.ajax({url:'handler/stock_journal_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,record_no:record_no,date:date,type_array:type_array,product_array:product_array,unit_array:unit_array,location_array:location_array,stock_array:stock_array,qty_array:qty_array,rate_array:rate_array,amount_array:amount_array,ad:ad},
		success:function(data)
		{	
			if(data!="")
			{	
				console.log(data);
				alert("Record Added Successfully !")
				window.location='stock_journal_list.php';
			}else{
				alert('error in handler');
			}
		}
	});			 
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
	jQuery.ajax({url:'handler/stock_journal_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
				
				alert(data);					
				window.location='stock_journal_list.php';
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


function product_check(rid){
	//alert('hii');
	var rid1=rid;
  	var did=rid.split("_");
	rid=did[1];
	
	var product_arraychk=[];
	var product=jQuery("#product_"+rid).val(); 
	
	var count=jQuery("#cnt").val(); 
	
    for(var i=1;i<count;i++) {

		if(i!=rid) {

			var product1 = $("#product_"+i).val();
			product_arraychk.push(product1);
		}
	}
	// alert(product_arraychk+"--"+product);
	if(product_arraychk.includes(product)==true && product!=null) {

		alert('Please Do Not Repeat Product Again!');
		jQuery("#product_"+rid).val(''); 
		return false;

	} else {
		get_unit(rid1);//===call get_unit function
	}
	
}

function get_unit(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_unit',id:id,product:product},
		success:function(data)
		{	
			$("#unitdiv_"+id).html(data);
            //  get_stock(this_id);	//call 	 get_stock function	
            //  get_qty(this_id);	//call 	 get_qty function	
			$(this).next().focus();
		}
	});	
} 

function get_stock(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var unit = $("#unit_"+id).val();
	var location = $("#location_"+id).val();

	// alert(product);
	// alert(location);

	jQuery.ajax({
		
		url:'get_ajax_values.php', 
		type:'POST',
		data: { Type:'get_product_stock',id:id,product:product,unit:unit,location:location},
		success:function(data)
		{
			// alert(data);
			$("#stock_"+id).val(data);	
			// $(this).next().focus();
		}
	});	
} 

function get_qty(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var unit = $("#unit_"+id).val();
	var location = $("#location_"+id).val();
	
	jQuery.ajax({
		
		url:'get_ajax_values.php', 
		type:'POST',
		data: { Type:'get_qty_from_purchaseinvoice',id:id,product:product,unit:unit,location:location},
		success:function(data)
			{	
				// alert(data);
				$("#qty_"+id).val(data);	
				// $(this).next().focus();
			}
	});	
} 

function get_amount(this_id)
{	
  
	var id=this_id.split("_");
	id=id[1];
	
	var qty = $("#qty_"+id).val();
	var rate = $("#rate_"+id).val();
	var Amount=parseFloat(qty)*parseFloat(rate);
	$("#amount_"+id).val(Amount);
	
}

function delete_row(rwcnt)
{
	var id=rwcnt.split("_");
	rwcnt=id[1];
	var count=$("#cnt").val();	
	if(count>1)
	{
		var r=confirm("Are you sure!");
		if (r==true)
		{		
			
			$("#row_"+rwcnt).remove();
				  
			for(var k=rwcnt; k<=count; k++)
			{
				var newId=k-1;
				
				jQuery("#row_"+k).attr('id','row_'+newId);
				
				jQuery("#idd_"+k).attr('name','idd_'+newId);
				jQuery("#idd_"+k).attr('id','idd_'+newId);
				jQuery("#idd_"+newId).html(newId); 
				
				jQuery("#type_"+k).attr('name','type_'+newId);
				jQuery("#type_"+k).attr('id','type_'+newId);
				
				jQuery("#product_"+k).attr('name','product_'+newId);
				jQuery("#product_"+k).attr('id','product_'+newId);
				
				
				jQuery("#unitdiv_"+k).attr('id','unitdiv_'+newId);
				
				jQuery("#unit_"+k).attr('name','unit_'+newId);
				jQuery("#unit_"+k).attr('id','unit_'+newId);
				
				jQuery("#location_"+k).attr('name','location_'+newId);
				jQuery("#location_"+k).attr('id','location_'+newId);
				
				jQuery("#stock_"+k).attr('name','stock_'+newId);
				jQuery("#stock_"+k).attr('id','stock_'+newId);
				
				jQuery("#qty_"+k).attr('name','qty_'+newId);
				jQuery("#qty_"+k).attr('id','qty_'+newId);
				
				jQuery("#rate_"+k).attr('name','rate_'+newId);
				jQuery("#rate_"+k).attr('id','rate_'+newId);
				
				jQuery("#amount_"+k).attr('name','amount_'+newId);
				jQuery("#amount_"+k).attr('id','amount_'+newId);
				
				
				
				jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);

			}
			jQuery("#cnt").val(parseFloat(count-1)); 
			// GrandTotal();
		}
	}
	else 
	{
		alert("Can't remove row Atleast one row is required");
		return false;
	}	 
}

function addRow(tableID) 
{ 
	var count=$("#cnt").val();	
	// var state=$("#state").val();
	var i=parseFloat(count)+parseFloat(1);

	var cell1="<tr id='row_"+i+"'>";

	cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";

	cell1 += "<td style='width:15%' ><select name='type_"+i+"' class='select2 form-select required'  id='type_"+i+"'  ><option value=''>Select Type</option><option value='consumption'>Consumption</option><option value='production'>production</option></select></td>";

	cell1 += "<td style='width:15%'><select name='product_"+i+"' onchange='product_check(this.id);check_stockjournalbatch(this.id);' class='select2 form-select' id='product_"+i+"' onchange='get_unit(this.id);' >\
	<option value=''>Select</option>\
		<?php
			$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
			foreach($record as $e_rec){	
			echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
				
		?>
	</select></td>";
						


	cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";
		
	cell1 += "<td style='width:10%'><select name='location_"+i+"' onchange='get_stock(this.id);get_qty(this.id);' class='select2 form-select' id='location_"+i+"' >\
	<option value=''>Select</option>\
		<?php
		$record=$utilObj->getMultipleRow("location","1");
		foreach($record as $e_rec){	
			echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
				
		?>
	</select></td>";

	cell1 += "<td style='width:10%'><input name='stock_"+i+"' id='stock_"+i+"'  readonly   class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:10%;text-align:center;'><div id='divbatch_"+i+"'></div></td>";

	cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'  onChange='get_amount(this.id);' class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:10%'><input name='amount_"+i+"' id='amount_"+i+"'   class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

	$("#myTable").append(cell1);
	$("#cnt").val(i);
	$("#particulars_"+i).select2();
	$(".select2").select2();

	
}
</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
