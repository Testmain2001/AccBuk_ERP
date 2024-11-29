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
<?php 	

	$date=date('d-m-Y');	
	if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
		$id=$_REQUEST['id'];
		$rows=$utilObj->getSingleRow("grn","id ='".$id."'");

		$grn_no=$rows['grn_code'];	
		$requisition_no=$rows['requisition_no'];	
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

	$ad = uniqid();

	
?>
<style>
    .alertify-logs {
        right: 20px !important;
        left: auto !important;
    }
</style>

<div class="container " style =" ">
	
	<div class="text-center mb-4">
		
		<h3 class="role-title">GRN(Goods Receipt Notes) New Form</h3>

		
	
	</div>
	<!-- Add role form -->
	
	<form id="" data-parsley-validate class="row g-3" action="../GRN_list.php"  method="post" data-rel="myForm">
		
		<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
		<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
		<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
		<input type="hidden"  name="table" id="table" value="<?php echo "grn"; ?>"/>
		<input type="hidden" name="ad" id="ad" value = "<?php echo $ad; ?>">
			
		<div class="col-md-4">
			<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_voucher_code();">
			<option value="">Select</option>
				<?php	
					$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=9 group by id"); 
					foreach($data as $info){
						if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
						echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
					}  
				?>
			</select>
		</div>

		<div class="col-md-4">
			<label class="form-label">GRN No. <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" id="grn_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="grn_no" value="<?php echo $grn_no;?>"/>
		</div>

		<div class="col-md-4">
			<label class="form-label">GRN Date <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
		</div>

		<div class="col-md-4">
			<label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="supplier" name="supplier"  onchange="chk_type()" <?php echo $disabled; ?> class="required form-select select2" data-allow-clear="true">
				<option value="">Select</option>
				<?php
					$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
					foreach($data as $info){
						if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
						echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
					}  
				?>
			</select>
		</div>

		<!-- <div class="col-md-4">
			<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="location" name="location" onchange="chk_type();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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

		<div class="col-md-4">
			<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="type" name="type"  onchange="chk_type();purchaseorder_rowtable();" class="form-select select2 tdstax_field" data-allow-clear="true">
				<option value="">Select</option>
				<option  value="Direct_Purchase" <?php if($rows['type']=='Direct_Purchase'){ echo 'selected'; } else { echo ' '; } ?> >Direct Purchase</option>
				<option  value="Against_Purchaseorder" <?php if($rows['type']=='Against_Purchaseorder'){ echo 'selected';}else{ echo ' ';} ?> >Against Purchase Order</option>
			</select>
		</div>

		<div class="col-md-4">
			<label for="first-name" class="control-label" >Narration</label>
			<textarea type="text" <?php echo $readonly;?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 100%;" name="otrnar"><?php echo $purchase_invoice['otrnar']; ?></textarea>
		</div>

		<div class="col-md-4" id="purchase_order_div">
			
		</div>
					
		<input type="hidden" name="multipid" id="multipid" value="<?php echo $rows['multipid']; ?>">

		<h4 class="role-title">Material Details</h4>
			
			<?php 
				$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
				$state= $account_ledger['mail_state'];
			?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/>
		
		<div id="table_div" style="overflow: hidden;">
		

		</div>
		
		<div class="col-12 text-center">
			<?php 
			if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit" onClick="savedata()"/>
			<?php } ?>

			<?php 
				if($_REQUEST['PTask']=='view') {
			?>	
				<?php if((CheckEditMenu())==1) {  ?>
				<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
						<a href="grn_form1.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
				</button>
				<?php } ?>
			<?php } ?>

			<button type="reset" class="btn btn-label-secondary"   onClick="remove_urldata(0);">Cancel</button>
			
		</div>
	</form>
</div>

<!-- ----------------------------------------------- JavaScript ----------------------------------------------- -->
<script>

function getmultipid() {

	var pids = $("#purchaseorder_no").val();
	$("#multipid").val(pids);

	purchaseorder_rowtable();
}

function find_state() {

	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier . . . !');
		return false;
	}

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'find_state',supplier:supplier},
		success:function(data)
		{	
			// alert(data);
			$("#state").val(data);	
		}
	}); 
}

function purchaseorder_rowtable()
{
	
   	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type = $("#type").val();
	// var location = $("#location").val();
	var purchaseorder_no = $("#purchaseorder_no").val();
	var ad = $("#ad").val();
	var mpids = $("#multipid").val();
	// alert(mpids);
	// alert(purchaseorder_no);
	
	var supplier =$("#supplier").val();

	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'purchaseorder_rowtable',type:type,id:id,PTask:PTask,purchaseorder_no:purchaseorder_no,supplier:supplier,ad:ad,mpids:mpids },
		success:function(data)
		{	
			// alert(data);
			$("#table_div").html(data);
			$(".select2").select2();
		}
	}); 
}

function chk_type()
{	
	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type = $("#type").val();
	// var location = $("#location").val();
	var supplier = $("#supplier").val();
	var ad = $("#ad").val();

	if(type=="Against_Purchaseorder"){
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_purchaseorderno',type:type,id:id,PTask:PTask,ad:ad,supplier:supplier },
			success:function(data)
			{	
				$("#purchase_order_div").html(data);	
				$(".select2").select2();
				var purchaseorder_no =$("#purchaseorder_no").val();

				// alert("rr"+requisition_no);
				if(PTask=='update'&&purchaseorder_no!=null || PTask=='view'){
					purchaseorder_rowtable();
				}
			}
		});	
	}else if(type=="Direct_Purchase"){
		
		$("#purchase_order_div").html(" ");	
		$(".select2").select2();
		var purchaseorder_no =$("#purchaseorder_no").val();
		if((purchaseorder_no==null&&PTask!='')|| PTask=='view'){
			purchaseorder_rowtable();
		}
	}			
}
window.onload=function(){
	$("#date").flatpickr({
		dateFormat: "d-m-Y"
	});
}

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload = function() {
	chk_type();
};


<?php } ?>

function get_unit(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	
	// alert(id);
	// var cnt = $("#cnt").val();
	var product = $("#product_"+id).val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_unit',id:id,product:product},
		success:function(data)
		{	
			//alert(data);
			$("#unitdiv_"+id).html(data);	
			$(this).next().focus();
		}
	});	
}

function get_voucher_code() {

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_voucher_code',voucher_type:voucher_type},
		success:function(data)
		{	
			// alert(data);
			$("#grn_no").val(data);	
			// $(this).next().focus();
		}
	});

}

// function mysubmit(a)
// {
// 	return _isValidpopup(a);	
// }

function remove_urldata()
{	 
	window.location="GRN_list.php";
} 
 
function savedata()
{
   
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	var ad = $("#ad").val();
	
	var grn_no = $("#grn_no").val();
	var type = $("#type").val();
	var location = $("#location").val();
	var voucher_type = $("#voucher_type").val();
	var purchaseorder_no = $("#purchaseorder_no").val();
	var supplier = $("#supplier").val();
	var date = $("#date").val();
	var mpids = $("#multipid").val();
	var checksub = 0;
	// alert(purchaseorder_no);

	var unit_array=[];
	var product_array=[];
	var qty_array=[];
	var rate_array=[];
	var res_array=[];
	
	for(var i=1;i<=cnt;i++)
	{
		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var qty = $("#qty_"+i).val();
		var rate = $("#rate_"+i).val();
		var res = $("#res_"+i).val();
		
		product_array.push(product);
		unit_array.push(unit);
		qty_array.push(qty);
		rate_array.push(rate);
		res_array.push(res);
	
	}


	for(var i=1;i<=cnt;i++)
	{
		var producttxt = $("#product_"+i).find("option:selected").text();
		var res = $("#res_"+i).val();
		var qty = $("#qty_"+i).val();

		if(res!=1 || qty==0 || qty=='') {

			alert("Plase Add Batch for this "+producttxt);
			checksub = 1;
			break;
		}
	}

	if(checksub==0) {
		jQuery.ajax({url:'handler/GRN_form.php', type:'POST',
			data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,ad:ad,cnt:cnt,grn_no:grn_no,type:type,location:location,voucher_type:voucher_type,purchaseorder_no:purchaseorder_no,supplier:supplier,date:date,unit_array:unit_array,product_array:product_array,qty_array:qty_array,rate_array:rate_array,mpids:mpids },
				success:function(data)
				{
					if(data!="")
					{
						// alert(data);
						alert("Record added successfully . . . !");
						window.location.href = 'GRN_list.php';
						// alertify.success('Success message');

						// window.location='GRN_list.php'+alertify.success('Success message');
						// alertify.success('Success message');
						// setTimeout(function() {
						// 	window.location.href = 'GRN_list.php';
						// }, 2000); // Redirect after 3 seconds (3000 milliseconds)

					} else {
						alert('error in handler');
					}
				}
		});
	}
	// }
}



</script>
<script>

	function delete_row(rwcnt)
	{
		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cnt").val();
		
		if(count>1) {

			var r=confirm("Are you sure!");
			if (r==true) {
				
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
					
					jQuery("#unit_"+k).attr('name','unit_'+newId);
					jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
					jQuery("#qty_"+k).attr('id','qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
					jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
				}
				jQuery("#cnt").val(parseFloat(count-1)); 
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
		var state=$("#state").val();	

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
	   
		cell1 += "<td style='width:15%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);check_batch_grn(this.id)' style='width:210px;'>\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
				foreach($record as $e_rec) {
					echo "<option value='".$e_rec['id']."' >".$e_rec['name']."</option>";
				}
			?>
		</select></td>";

		cell1 += "<td style='width:5%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";

		cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number tdalign' type='text'/></td>";

		cell1 += "<td style='width:8%'><input name='rate_"+i+"' id='rate_"+i+"' class='form-control required tdalign' type='text'/>\
		<input type='hidden' name='res_"+i+"' id='res_"+i+"' value=''></td>";
		

		// cell1 += "<td style='width:2%'><button type='button' class='btn btn-primary'      onClick='check_qty("+i+")'>Add Batch</button></td>";

		cell1 += "<td style='width:3%;text-align:center;'><div id='batchgrn_"+i+"'></div></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";



		$("#myTable").append(cell1);
		$("#cnt").val(i);
		// $("#particulars_"+i).select2();
		$(".select2").select2();
		 
	}
                
</script>

<!-- Footer -->
<?php 
include("footer.php");
?>