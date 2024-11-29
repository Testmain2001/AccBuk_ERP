<?php 
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task==''){ $task='Add'; }
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
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Packaging Of Material</h4>
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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Packaging</h4>
		</div>
		<div class="col-md-2">
		<?php if((CheckCreateMenu())==1) { ?>
			<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>

		<?php if((CheckDeleteMenu())==1){ ?>
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		</div>
	</div>

	<!-- -------------------------- Packaging Table -------------------------- -->

	<div id="u_table" style="display:block">
		<div class="card">
			<div class="card-datatable table-responsive pt-0">
				<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
					<thead>
						<tr>
							<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> Sr.No.</th>
							<th>Batch No.</th>
							<th>Date</th>
							<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th><?php } ?>
						</tr>
					</thead>
				
					<tbody>
						<?php
							$i=1;
							$data=$utilObj->getMultipleRow("packaging","1");
							foreach($data as $info){
								
							$href= 'packaging_list.php?id='.$info['id'].'&PTask=view';
							//$d1=$rows=$utilObj->getCount("delivery_challan","saleorder_no ='".$info['id']."'");
							if($d1>0){
								$dis="disabled";
							}else{
								$dis="";
							}
							$productnm=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");
						
						?>
						<tr>
							<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp&nbsp<?php echo $i; ?> </td> 
							<td><a href="<?php echo $href; ?>"><?php echo $productnm['name']; ?></a> </td>
							<td> <?php echo $info['date']; ?> </td>
							<td>
							<!--div class="dropdown"-->
							<?php 
							//echo $d1;
							if($d1==0){ ?>
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
								<div class="dropdown-menu">
								<?php if((CheckEditMenu())==1) {  ?>
									<a class="dropdown-item" href="packaging_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>

								<?php } ?>
								<?php if((CheckDeleteMenu())==1){ ?>
									<a class="dropdown-item" href="packaging_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
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

	<!-- ---------------------- Main Form ---------------------- -->
	<?php
		$date=date('d-m-Y');	
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
			$id=$_REQUEST['id'];
			$rows=$utilObj->getSingleRow("packaging","id ='".$id."'");
			$batch=$utilObj->getSingleRow("purchase_batch","parent_id ='".$id."'");
			$batch_no=$rows['pack_code'];
			$date=date('d-m-Y',strtotime($rows['date']));
			
		} else {
			$rows=null;
		}
	?>
	<div class="container-xxl flex-grow-1 container-p-y" style="display:none; background-color: white; padding: 30px; background: #fff9f9; " id="u_form">
		
	
	<div class="row form-validate">
		<div class="col-12">
				<div class="card ">
					<div class="card-body " >
						<form id="demo-form2" data-parsley-validate class="row g-3" action="packaging_list.php"  method="post" data-rel="myForm">
						
							<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
							<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>
							
							<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
							<input type="hidden"  name="table"      id="table"      value="<?php echo "packaging"; ?>"/>
							<input type="hidden"  name="ad"         id="ad"         value="<?php echo $ad; ?>"/>
								
							<div class="col-md-4">
								<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
								<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_pack_code();">
								<option value="">Select</option>
									<?php	
										$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=12 group by id"); 
										foreach($data as $info){
											if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
											echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
										}  
									?>
								</select>
							</div>

							<div class="col-md-4">
								<label class="form-label">Batch No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="batch_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Batch No." name="batch_no" value="<?php echo $batch_no;?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label">Packaging  Date <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
							</div>
									
							<div class="col-md-4">
								<label class="form-label">location<span class="required required_lbl" style="color:red;">*</span></label>
								<select id="location" name="location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
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

							<div class="col-md-4">
								<label class="form-label">Product<span class="required required_lbl" style="color:red;">*</span></label>
								<select id="product" name="product" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getunit_forpackaging();get_bom_pack();" style="width:100%;">	
									<?php 
										echo '<option value="">Select</option>';
										$record=$utilObj->getMultipleRow("stock_ledger","bill_of_material=1");

										// $record=$utilObj->getMultipleRow("stock_ledger","1");
										foreach($record as $e_rec)
										{
											if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
										}
									?> 
								</select>
							</div>	

							<div class="col-md-4" id="show_bom">
							<?php
								if($_REQUEST['PTask']=='update') {
							?>
								<label class="form-label">BOM<span class="required required_lbl" style="color:red;">*</span></label>
								<select id="bom" name="bom" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_billofmaterial_rowtable_forpackaging();" style="width:100%;">	
									<?php
										echo '<option value="">Select</option>';
										$record=$utilObj->getMultipleRow("bill_of_material","product='".$rows['product']."' ");
										foreach($record as $e_rec)
										{
											if($rows['bom']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["bom_name"] .'</option>';
										}
									?>
								</select>
							<?php } ?>
							</div>
									
							<div class="col-md-4">
								<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
								<div id='unitdiv'>
										<input type="text" style="width:100%;"  class=" form-control  smallinput " onchange="get_billofmaterial_rowtable_forpackaging();" readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $rows['unit'];?>"/>
								</div>
							</div>
									
							<div class="col-md-4">
								<label class="form-label">Quantity <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="qty" class="required form-control tdalign"  onchange="get_billofmaterial_rowtable_forpackaging();"onblur="get_billofmaterial_rowtable_forpackaging();"onkeyup="get_billofmaterial_rowtable_forpackaging();" <?php echo $readonly;?> placeholder="Enter Quantity" name="qty" value="<?php echo $rows['qty']; ?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label">Batch Name<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pack_batch_name" class="required form-control" placeholder="Enter Batch Name" name="pack_batch_name" value="<?php echo $batch['batchname']; ?>"/>
							</div>

							<div class="col-md-4">
								<label class="form-label">Batch Rate<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pro_batch_rate" name="pro_batch_rate" class="tdalign required form-control"  value="<?php echo $batch['bat_rate']; ?>" readonly />
							</div>
								
							<h4 class="role-title">Material Details</h4>
							<?php 
								$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
								$state= $account_ledger['mail_state'];
							?>

							<div id="table_div" style="overflow: hidden;">

							</div>
						
							<div class="col-12 text-center">
								<?php 
								if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?>

								<?php
									if($_REQUEST['PTask']=='view') {
								?>
									<?php if((CheckEditMenu())==1) { ?>
										<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
											<a href="packaging_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
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

function get_bom_pack() {

	var product = $("#product").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_bom_pack',product:product },
		success:function(data)
		{	
			// alert(data);
			$("#show_bom").html(data);
			$(".select2").select2();
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
		window.location="production_list.php";
		
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
		get_billofmaterial_rowtable_forpackaging();
		hideshow();
		
	};  
<?php } ?>



<?php
	if($_REQUEST['PTask']=='delete'){ ?>	
		window.onload=function(){
		var r=confirm("Are you sure to delete?");
		if (r==true)
		{
			deletedata("<?php echo $_REQUEST['id'];?>");
		}
		else
		{
			window.location="packaging_list.php";
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
			window.location="packaging_list.php?PTask=delete&id="+val; 
				
		}
	}

	function mysubmit(a)
	{
		// return _isValid2(a);
		savedata();
	}

	function remove_urldata()
	{	 
		window.location="packaging_list.php";
	} 
	
	function savedata()
	{
		var PTask = $("#PTask").val();
		var table = $("#table").val();
		var LastEdited = $("#LastEdited").val();
		var id = $("#id").val();
		var ad = $("#ad").val();
		var cnt = $("#cnt").val();
		
		var batch_no = $("#batch_no").val();
		var date = $("#date").val();
		var location = $("#location").val();
		var voucher_type = $("#voucher_type").val();
		var product = $("#product").val();
		var bom=$("#bom").val();
		var unit = $("#unit").val();
		var qty = $("#qty").val();
		var pack_batch_name = $("#pack_batch_name").val();
		var pro_batch_rate = $("#pro_batch_rate").val();
		// alert(pack_batch_name);
		
		var total_req = $("#total_req").val();
		var grand_total = $("#grand_total").val();
		
		var unit_array=[];
		var product_array=[];
		var requiredqty_array=[];
		var qty_array=[];
		var totalsum_array=[];
		
			
		for(var i=1;i<=cnt;i++)
		{
			var unit1 = $("#unit_"+i).val();	
			var product1 = $("#product_"+i).val();
			var requiredqty1 = $("#requiredqty_"+i).val();	
			var qty1 = $("#qty_"+i).val();
			var totalsum = $("#totalsum_"+i).val();	
			
			product_array.push(product1);
			unit_array.push(unit1);
			qty_array.push(qty1);
			requiredqty_array.push(requiredqty1);
			totalsum_array.push(totalsum);
			
		} 
		//alert('hiii');
				
		jQuery.ajax({url:'handler/packaging_form.php', type:'POST',
			data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,batch_no:batch_no,date:date,location:location,voucher_type:voucher_type,unit_array:unit_array,product:product,unit:unit,qty:qty,product_array:product_array,requiredqty_array:requiredqty_array,qty_array:qty_array,ad:ad,pack_batch_name:pack_batch_name,bom:bom,pro_batch_rate:pro_batch_rate,totalsum_array:totalsum_array,grand_total:grand_total,total_req:total_req },
			success:function(data)
			{	
				if(data!="")
				{	
					//alert(data);				
					window.location='packaging_list.php';
				}else{
					alert('error in handler');
				}
			}
		});			 
	}


	function deletedata(id){
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
			
		jQuery.ajax({url:'handler/packaging_form.php', type:'POST',
			data: { PTask:PTask,id:id},
			success:function(data)
			{	
				if(data!="")
				{
					//alert(data);					
					window.location='packaging_list.php';
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

	

	function get_pack_code() {
		
		// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
		// $result=mysqli_fetch_array($getinvno);
		// $grn_no=$result['pono']+1;

		var voucher_type = $("#voucher_type").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_pack_code',voucher_type:voucher_type},
			success:function(data)
			{	
				//alert(data);
				$("#batch_no").val(data);	
				// $(this).next().focus();
			}
		});

	}

	function getunit_forpackaging()
	{
		var product = $("#product").val();
		//alert(product);
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getunit_forpackaging',product:product},
			success:function(data)
			{	
			//alert(data);
				$("#unitdiv").html(data);	
				//$(this).next().focus();
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
				$(this).next().focus();
			}
		});	
	}

	function get_billofmaterial_rowtable_forpackaging()
	{
		//alert('hii');
		var product=$("#product").val();
		var bom=$("#bom").val();
		var unit=$("#unit").val();
		var location=$("#location").val();
		var qty=$("#qty").val();
		var id=$("#id").val();
		var PTask=$("#PTask").val();
		var ad = $("#ad").val();
		//alert(mtype);
		//alert(product+"="+mtype+"="+qty+"="+id);
		jQuery.ajax({url:'get_ajax_values.php',
			type:'POST',
			
			data: { Type:'get_billofmaterial_rowtable_forpackaging',product:product,unit:unit,qty:qty,id:id,PTask:PTask,location:location,ad:ad,bom:bom },
			
			success:function(data)
			{
				//alert(data);
			$('#table_div').html(data);

			}
		});
	}

	function Checkstk(rid){
			
		var did=rid.split("_");	
		var rid=did[1];		
		var material=jQuery("#qty_"+rid).val(); 
		var stock=jQuery("#stock_"+rid).val(); 
		
		if(parseFloat(material)>parseFloat(stock))
		{
			$("#qty_"+rid).val('');
			alert("stock is not available");
			return false;				
		}
		checkquantity(rid);
	}

	function checkquantity(rid){
			
	/*   var did=rid.split("_");	
		var rid=did[1];	 */	
		var material=jQuery("#qty_"+rid).val(); 
		var requiredqty=jQuery("#requiredqty_"+rid).val(); 
		
		if(parseFloat(material)>parseFloat(requiredqty))
		{
			$("#qty_"+rid).val('');
			alert("quantity should not gretter than required quantity!!");
			return false;				
		}
		
	}

	function addRow(tableID) 
	{ 
		var count=$("#cnt").val();	
		var type=$("#type").val();	
		var pricetype=$("#pricetype").val();
		var state=$("#state").val();	
		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"'>"+i+"</label></td>";
		
		cell1 += "<td style='width:15%' ><select name='product_"+i+"' class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
				foreach($record as $e_rec){	
					echo "<option value='".$e_rec['id']."' >".$e_rec['name']."</option>";
				}
			?>
		</select></td>";

		cell1 += "<td style='width: 10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"' readonly class='form-control required' type='text'/></div></td>";

		cell1 += "<td style='width: 10%'></td>";

		cell1 += "<td style='width: 10%'><input name='qty_"+i+"' id='qty_"+i+"' class='form-control number' type='text'/></td>";

		cell1 += "<td style='width: 10%;text-align:center;'><button type='button' class='btn btn-primary btn-sm' onclick='check_qty("+i+");' data-bs-toggle='modal' data-bs-target='#purreturnbatch'>Batch</button></td>";

		cell1 += "<td style='width: 10%;'><input type='text' id='totalsum_"+i+"' class='tdalign number form-control' readonly name='totalsum_"+i+"' value=''/></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";

		$("#myTable").append(cell1);
		$("#cnt").val(i);
		$(".select2").select2();

	}


	function delete_row(rwcnt)
	{
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
					
					jQuery("#unit_"+k).attr('name','unit_'+newId);
					jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
					jQuery("#qty_"+k).attr('id','qty_'+newId);
					
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

</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
