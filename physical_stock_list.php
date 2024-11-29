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
    

	<div class="row">     
		<div class="col-md-2">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Physical Stock</h4>
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

	<!-- <div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Physical Stock</h4>
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
						$data=$utilObj->getMultipleRow("physical_stock","1");
						foreach($data as $info){
						
							$href= 'physical_stock_list.php?id='.$info['id'].'&PTask=view';
							//$d1=$rows=$utilObj->getCount("delivery_challan","saleorder_no ='".$info['id']."'");
							if($d1>0){
								$dis="disabled";
							}else{
								$dis="";
							}
							//$productnm=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");
						
					?>
						<tr>
							<td  width="5%" class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp&nbsp<?php echo $i; ?></td> 
							<td><a href="<?php echo $href; ?>"><?php echo $info['record_no']; ?></a> </td>
							<td> <?php echo $info['date']; ?> </td>
							<td>
							<!--div class="dropdown"-->
							<?php 
								//echo $d1;
								if($d1==0){?>
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
								<div class="dropdown-menu">
								<?php if((CheckEditMenu())==1) {  ?>
								<a class="dropdown-item" href="physical_stock_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
								<?php } ?>
								<?php if((CheckDeleteMenu())==1){ ?>
									<a class="dropdown-item" href="physical_stock_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
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
	<!-- ------------------------------ Main Form ------------------------------ -->
	
	<?php
		$getrecordno=mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from  physical_stock");
		$result=mysqli_fetch_array($getrecordno);
		$record_no=$result['pono']+1;  
		$date=date('d-m-Y');	
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
			$id=$_REQUEST['id'];
			$rows=$utilObj->getSingleRow(" physical_stock","id ='".$id."'");
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
					<div class="card-body " >

						<form id="demo-form2" data-parsley-validate class="row g-3" action="physical_stock_list.php"  method="post" data-rel="myForm">
				
							<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
							<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
							<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
							<input type="hidden"  name="table"      id="table"      value="<?php echo "physical_stock"; ?>"/>

							<input type="hidden"  name="ad"         id="ad"         value="<?php echo $ad;?>"/>
					
				
							<div class="col-md-4">
								<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="record_no" value="<?php echo $record_no;?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label">Date <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
							</div>
						
							<div class="col-md-4">
								<label class="form-label">location<span class="required required_lbl" style="color:red;">*</span></label>
								<select id="location" name="location"  onchange="get_locationwise_productstock_forphysical();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">
								<?php 
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("location","1 ");
									foreach($record as $e_rec)
									{
										if($rows['location']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?> 
								</select>
							</div>	
							<!--div class="col-md-4">
							<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="voucher_type" name="voucher_type"    <?php  //echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
							<option value="">Select</option>
								<?php	
									/* $data=$utilObj->getMultipleRow("voucher_type","parent_voucher=10 group by id"); 
									foreach($data as $info){
										if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
										echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
									}   */
								?>
							</select>
							</div-->	

							<h4 class="role-title">Material Stock Locationwise</h4>
							<div id="table_div" style="overflow: hidden;">

							</div>
			
							<div class="col-12 text-center">
								<?php 
									if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='') {
								?>	
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?>

								<?php 
									if($_REQUEST['PTask']=='view') {
								?>	
									<?php if((CheckEditMenu())==1) {  ?>
									<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
											<a href="physical_stock_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
									</button>
									<?php } ?>
								<?php } ?>
								
								<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
								
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
		window.location="physical_stock_list.php";
		
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
	get_locationwise_productstock_forphysical();
	hideshow();
    
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
			window.location="physical_stock_list.php";
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
			window.location="physical_stock_list.php?PTask=delete&id="+val; 
			
	}
}

function mysubmit(a)
{
	// return _isValid2(a);	
	savedata();
}

function remove_urldata()
{	 
	window.location="physical_stock_list.php";
} 
 
function savedata()
{
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	var ad = $("#ad").val();
	
	var record_no = $("#record_no").val();
	var date = $("#date").val();
	var location = $("#location").val();
	//var voucher_type = $("#voucher_type").val();
	
	
	var unit_array=[];
	var product_array=[];
	var physicalstock_array=[];
	var stock_array=[];
	var addstock_array=[];
	var lessstock_array=[];
	
		
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var physicalstock = $("#physicalstock_"+i).val();	
		var stock = $("#stock_"+i).val();	
		var addstock = $("#addstock_"+i).val();	
		var lessstock = $("#lessstock_"+i).val();	
		
		product_array.push(product);
		unit_array.push(unit);
		physicalstock_array.push(physicalstock);
		stock_array.push(stock);
		addstock_array.push(addstock);
		lessstock_array.push(lessstock);
		
		
	} 
	//alert('hiii');
			
	jQuery.ajax({url:'handler/physical_stock_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,record_no:record_no,date:date,location:location,product_array:product_array,unit_array:unit_array,physicalstock_array:physicalstock_array,stock_array:stock_array,addstock_array:addstock_array,lessstock_array:lessstock_array,ad:ad},
		success:function(data)
		{	
			if(data!="")
			{	
				// alert(data);
				console.log(data);
				window.location='physical_stock_list.php';
			}else{
				alert('error in handler');
			}
		}
	});			 
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
	jQuery.ajax({url:'handler/physical_stock_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
				
				alert(data);					
				window.location='physical_stock_list.php';
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

function get_locationwise_productstock_forphysical()
{
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var ad = $("#ad").val();
	var location = $("#location").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_locationwise_productstock_forphysical',location:location,id:id,PTask:PTask,ad:ad},
		success:function(data)
		{	
		    // alert(data);
			$("#table_div").html(data);	
			$(".select2").select2();
		}
	});	
}

function product_check(rid){
	var rid1=rid;
  	var did=rid.split("_");
	rid=did[1];
	
	var product_arraychk=[];
	var product = jQuery("#product_"+rid).val(); 
	
	var count=jQuery("#cnt").val();

	for(var i=1; i<=cnt;i++)
	{
		if(rid!=i){
			if(product==jQuery("#product_"+i).val())
			{ 	 
				alert('You have already selected this Product make Please Select Other');
				$('#product_'+rid).select2('val', '')
				return false;
			} else {
				// get_unit(rid1);
				alert("Hola . . . .!")
			}
		}
	}
}

function product_check(rid){

	var did=rid.split("_");
	var rid=did[1];

	var product=jQuery("#product_"+rid).val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_main_stock',id:id,product:product},
		success:function(data)
		{	
			$("#stock_"+id).val(data);
			$(this).next().focus();
		}
	});	
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
		}
	});	
}

function get_stock(this_id) {	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var unit = $("#unit_"+id).val();
	var location = $("#location").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_product_stock',id:id,product:product,unit:unit,location:location},
		success:function(data)
		{	
			// alert(data);
			$("#stock_"+id).val(data);	
			$(this).next().focus();
		}
	});	
} 

function add_less_stock(rid){
	
	var did=rid.split("_");
	rid=did[1];
	// alert(rid);
	var physical_stock=jQuery("#physicalstock_"+rid).val(); 
	var stock=jQuery("#stock_"+rid).val(); 
	
	if(parseFloat(physical_stock)<parseFloat(stock)){
		var amt=parseFloat(stock)-parseFloat(physical_stock);
		$('#lessstock_'+rid).val(amt);
		$('#addstock_'+rid).val(0);
		
	} else if(parseFloat(physical_stock) > parseFloat(stock)){
		var amt=parseFloat( physical_stock)-parseFloat(stock);
		$('#addstock_'+rid).val(amt);
		$('#lessstock_'+rid).val(0);
	}else if(parseFloat(physical_stock)== parseFloat(stock)){
		$('#addstock_'+rid).val(0);
		$('#lessstock_'+rid).val(0);
	}
  
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
				
				jQuery("#product_"+k).attr('name','product_'+newId);
				jQuery("#product_"+k).attr('id','product_'+newId);
				
				
				jQuery("#unitdiv_"+k).attr('id','unitdiv_'+newId);
				
				jQuery("#unit_"+k).attr('name','unit_'+newId);
				jQuery("#unit_"+k).attr('id','unit_'+newId);
				
				jQuery("#physicalstock_"+k).attr('name','physicalstock_'+newId);
				jQuery("#physicalstock_"+k).attr('id','physicalstock_'+newId);
				
				jQuery("#stock_"+k).attr('name','stock_'+newId);
				jQuery("#stock_"+k).attr('id','stock_'+newId);
				
				jQuery("#addstock_"+k).attr('name','addstock_'+newId);
				jQuery("#addstock_"+k).attr('id','addstock_'+newId);
				
				jQuery("#lessstock_"+k).attr('name','lessstock_'+newId);
				jQuery("#lessstock_"+k).attr('id','lessstock_'+newId);
				
				
				
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
 	// alert('hii');
	var count=$("#cnt").val();	
	// var state=$("#state").val();	
	// alert(state);
	var i=parseFloat(count)+parseFloat(1);

	var cell1="<tr id='row_"+i+"'>";
	
	cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
	
	cell1 += "<td style='width:20%' ><select name='product_"+i+"'  onchange='check_physicalbatch(this.id);get_unit(this.id);'  class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
		<option value=''></option>\
		<?php
			$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
			foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
				
		?>
		</select></td>";

	cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";

	cell1 += "<td style='width:5%'><input name='physicalstock_"+i+"' id='physicalstock_"+i+"'   onchange='add_less_stock(this.id);'  class='form-control number' type='text'/></td>";
	cell1 += "<td style='width:5%'><input name='stock_"+i+"' id='stock_"+i+"'  readonly   class='form-control number' type='text'/></td>";
	
	cell1 += "<td style='width:5%'><input name='addstock_"+i+"' id='addstock_"+i+"' readonly  class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:10%'><input name='lessstock_"+i+"' id='lessstock_"+i+"' readonly  class='form-control number' type='text'/></td>";
	
	cell1 += "<td style='width:10%;text-align:center;'><div id='divbatch_"+i+"'></div></td>";

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
