<?php 
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task==''){ $task='Add';}
	if($_REQUEST['PTask']=='view') {

		$readonly="readonly";
		$disabled="disabled";
	} else {

		$readonly="";
		$disabled="";
	}
?>

<div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-3">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Ledger Master</h4>
	</div>
	<div class="col-md-6">
	<?php if((CheckCreateMenu())==1){  ?>
		<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new"><i class="fas fa-plus-circle fa-lg"></i></button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
		<button class=" btn btn-danger btn-sm" onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
	<?php } ?>
		<!-- <button class=" btn btn-warning mr-2 btn-sm" onclick="updateledger();">Update GST & HSN</button> -->
		<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#updatestockledger" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new" onclick="updateledger();">Update GST & HSN</button>

		<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#updatereorderlvl" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new" onclick="updatereorder();">Update ReOrder Level</button>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
	<div class="card-datatable table-responsive pt-0">
		<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
			<thead>
				<tr>
					<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /></th>
					<th style="width:5% !important;">Sr.No.</th>
					<th style="width:20% !important;">Name</th>
					<th style="width:15% !important;">Under group</th>
					<th style="width:12% !important;">Category</th>
					<th style="width:10% !important;">Unit</th>
					<th>IGST</th>
					<?php if((CheckEditMenu())==1) { ?> <th>Actions</th> <?php } ?>
				</tr>
			</thead>
		
			<tbody>
			<?php
					$i=1;
					$data=$utilObj->getMultipleRow("stock_ledger","1");
					foreach($data as $info) {
						
						$href= 'stock_ledger_list.php?id='.$info['id'].'&PTask=view';
				?>
				<tr>
					<td width='3%' class='controls'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/></td> 
					<td><?php echo $i; ?></td>
					<td> <a href="<?php echo $href; ?>"><?php echo $info['name']; ?></a> </td>

					<?php
						$row1=$utilObj->getSingleRow("stock_group","id ='".$info['under_group']."'");
					?>
					<td> <a href="<?php echo $href; ?>"><?php echo $row1['name']; ?></a> </td>

					<?php $cat=$utilObj->getSingleRow("stock_category_master","id ='".$info['cat_id']."'"); ?>

					<td> <a href="<?php echo $href; ?>"><?php echo $cat['cat_name']; ?></a> </td>

					<td> <a href="<?php echo $href; ?>"><?php echo $info['unit']; ?></a> </td>

					<?php
					$rows=$utilObj->getSingleRow("gst_data","id ='".$info['igst']."'"); ?>
					<td> <a href="<?php echo $href; ?>"><?php echo $rows['igst']; ?></a> </td>
					<td>
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
					<div class="dropdown-menu">
					<?php if((CheckEditMenu())==1) {  ?>
						<a class="dropdown-item" href="stock_ledger_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i>Edit</a>
					<?php } ?>
					<?php if((CheckDeleteMenu())==1){ ?>
						<a class="dropdown-item" href="stock_ledger_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
						<a data-content='Generate QR_Code' title='Generate QR_Code' class='dropdown-item popovers' data-placement='top' data-trigger='hover' $dasable href='qr_print.php?id=<?php echo $info['id'];?>' >
						<i class='fa fa-qrcode' style="color:green;font-size:20px" ></i></a>
					<?php } ?>
					</div>
					</td>
				</tr>
				<?php 
					$i=$i+1;
				} ?>
			</tbody>
		</table>
  	</div>
</div>

<div class="modal fade" style = "max-width=40%;" id="updatestockledger" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="updatesledger" >
			
		</div>
	</div>
</div>

<div class="modal fade" style = "max-width=40%;" id="updatereorderlvl" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="updaterolvl" >
			
		</div>
	</div>
</div>

<?php 
	include("form/stock_ledger_form.php");
?>

</div>
<!--/ Content -->
		  

<script>

	// window.onload=function(){
	// 	$("#date").flatpickr({
	// 		dateFormat: "d-m-Y"
	// 	});
	// }

	<?php 
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {
	?>	
		window.onload=function(){
			document.getElementById("add_new").click();
			$("#add_new").val("Show List"); 
			get_unit_formula();
		};  
	<?php } ?>


	<?php
		if($_REQUEST['PTask']=='delete') {
	?>	
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

	function updateledger() {

		var sid='';

		$('input[type="checkbox"]').each(function()
		{	
			if(this.checked==true && this.value!='on')
			{
				sid +=this.value+",";
			}
		});

		if(sid=='') {

			alert('Please Select Atleast 1 record!!!!');
			location.reload();

		} else {

			// alert(sid);
			$('#updatesledger').addClass('loader');

			jQuery.ajax({
				url: 'get_ajax_values.php',
				type: 'POST',
				data: { Type: 'updateledger', sid:sid },
				success: function (data) {
					$('#updatesledger').html(data);
					$('#updatestockledger').modal('show');
					$('#updatesledger').removeClass('loader');
			
				}
			});

		}
		
	}

	function updatereorder() {

		var sid='';

		$('input[type="checkbox"]').each(function()
		{	
			if(this.checked==true && this.value!='on')
			{
				sid +=this.value+",";
			}
		});

		if(sid=='') {

			alert('Please Select Atleast 1 record!!!!');
			location.reload();

		} else {

			// alert(sid);
			$('#updaterolvl').addClass('loader');

			jQuery.ajax({
				url: 'get_ajax_values.php',
				type: 'POST',
				data: { Type: 'updatereorder', sid:sid },
				success: function (data) {
					$('#updaterolvl').html(data);
					$('#updatereorderlvl').modal('show');
					$('#updaterolvl').removeClass('loader');
			
				}
			});

		}
		
	}

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
		var reorderlvl = $("#reorderlvl").val();
		var under_group = $("#under_group").val();
		var cat_group = $("#cat_group").val();
		var negative_stk_block =$('input[name="negative_stk_block"]:checked').val();
		var sale_invoicing =$('input[name="sale_invoicing"]:checked').val();
		var unit = $("#unit").val();
		var alt_unit = $("#alt_unit").val();
		var unit_qty = $("#unit_qty").val();
		var altunit_qty = $("#altunit_qty").val();
		var batch_maintainance =$('input[name="batch_maintainance"]:checked').val();
		var mfg_maintainance =$('input[name="mfg_maintainance"]:checked').val();
		var exp_maintainance =$('input[name="exp_maintainance"]:checked').val();
		var bill_of_material =$('input[name="bill_of_material"]:checked').val();
		var cost_tracking =$('input[name="cost_tracking"]:checked').val();
		var costing_method = $("#costing_method").val();
		var new_mfg =$('input[name="new_mfg"]:checked').val();
		var consumed =$('input[name="consumed"]:checked').val();
		var description = $("#description").val();
		var hsn_sac = $("#hsn_sac").val();
		var non_gst =$('input[name="non_gst"]:checked').val();
		var cal_type = $("#cal_type").val();
		var taxability = $("#taxability").val();
		var rev_charge =$('input[name="rev_charge"]:checked').val();
		var ineligible_input =$('input[name="ineligible_input"]:checked').val();
		var igst = $("#igst").val();
		var cgst = $("#cgst").val();
		var sgst = $("#sgst").val();
		var cess = $("#cess").val();
		var sale_local = $("#sale_local").val();
		var purchase_local = $("#purchase_local").val();
		var sale_outstate = $("#sale_outstate").val();
		var purchase_outstate = $("#purchase_outstate").val();

		var table = $("#table").val();
		var LastEdited = $("#LastEdited").val();

		jQuery.ajax({url:'handler/stock_ledger_form.php', type:'POST',
			data: { PTask:PTask,id:id,name:name,under_group:under_group,cat_group:cat_group,negative_stk_block:negative_stk_block,table:table,LastEdited:LastEdited,sale_invoicing:sale_invoicing,unit:unit,alt_unit:alt_unit,unit_qty:unit_qty,altunit_qty:altunit_qty,batch_maintainance:batch_maintainance,bill_of_material:bill_of_material,cost_tracking:cost_tracking,costing_method:costing_method,new_mfg:new_mfg,description:description,hsn_sac:hsn_sac,non_gst:non_gst,cal_type:cal_type,taxability:taxability,rev_charge:rev_charge,ineligible_input:ineligible_input,igst:igst,cgst:cgst,sgst:sgst,cess:cess,sale_local:sale_local,purchase_local:purchase_local,sale_outstate:sale_outstate,purchase_outstate:purchase_outstate,consumed:consumed,reorderlvl:reorderlvl,exp_maintainance:exp_maintainance,mfg_maintainance:mfg_maintainance },
			success:function(data)
			{	
			
				if(data!="") {
					window.location='stock_ledger_list.php';
					alert("Record added successfully.");
					// alert(data);
				} else {
					alert("Error occured while submitting . . .");
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


<?php 
	include("footer.php");
?>
