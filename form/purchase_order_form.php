
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order");
	// $result=mysqli_fetch_array($getinvno);
	// $order_no=$result['pono']+1;

	$date=date('d-m-Y');	
	if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {
		$id=$_REQUEST['id'];
		$rows=$utilObj->getSingleRow("purchase_order","id ='".$id."'");

		$order_no=$rows['order_no'];	
		$requisition_no=$rows['requisition_no'];	
		$date=date('d-m-Y',strtotime($rows['date']));
		
		if($requisition_no!='')
		{		
			if($readonly!="readonly") {
				$read="readonly";
			}
		}else{
			$read="";
		}
	} else {
		$rows = "";
	}
?>
  	<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    	<div class="modal-content p-3 p-md-5">
      		<div class="modal-body ">
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Purchase Order</h3>
				</div>

          		<form id="mainform" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">
			
					<input type="hidden"  name="PTask" id="PTask" value="<?php echo $task; ?>" />  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>" />	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>" />
					<input type="hidden"  name="table" id="table" value="<?php echo "purchase_order"; ?>"/>
			    
					<div class="col-md-8" style="border-right: 0.5px solid black;">
						<div class="row">

						<div class="col-md-2">
							<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="voucher_type" name="voucher_type" <?php echo $disabled; ?> class="required form-select select2" data-allow-clear="true" onchange = "get_order_code();">
								<option value="">Select</option>
								<?php
									$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=23 group by id"); 
									foreach($data as $info){
										if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
										echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
									}  
								?>
							</select>
						</div>

						<div class="col-md-3">
							<label class="form-label">Order No. <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="order_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="order_no" value="<?php echo $order_no; ?>"/>
						</div>

						<div class="col-md-2">
							<label class="form-label">Order Date <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date; ?>" <?php echo $disabled; ?> />
						</div>

						<div class="col-md-5">
							<label class="form-label">Supplier<span class="required required_lbl" style="color:red;">*</span></label>
							<select id="supplier" name="supplier"  onchange="get_address();get_pos(this.value);" <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" style="width:100%;">
								<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
								foreach($data as $info) {
									if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}
							?>
							</select>
						</div>

						<!-- <div class="col-md-4">
							<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="location" name="location"  <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
						
						<div class="col-md-4" >
							<label class="form-label">POS State <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="pos_state" name="pos_state" onchange="requisition_rowtable();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" style="width:100%;">
							<?php
								echo '<option value="">Select Location</option>';
								$record=$utilObj->getMultipleRow("states","1");
								foreach($record as $e_rec)
								{
									if($rows['pos_state']==$e_rec["code"]) echo $select='selected'; else $select='';
									echo '<option value="'.$e_rec["code"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>  
							</select>
						</div>
						
						<div class="col-md-4">
							<label class="form-label">State <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="state_name" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="state_name" value="<?php echo $rows['state_name']; ?>" />
						</div>

						<div class="col-md-4">
							<label class="form-label">State Code <span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="state_code" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="state_code" value="<?php echo $rows['state_code']; ?>"/>
						</div>

						<div class="col-md-4">
							<label class="form-label">Bill To<span class="required required_lbl" style="color:red;">*</span></label>
							<textarea name='bill_to' id="bill_to" placeholder="Bill Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " rows ="4" ><?php echo $rows['bill_to'];?></textarea>
						</div>

						<div class="col-md-4">
							<label class="form-label">Ship To<span class="required required_lbl" style="color:red;">*</span></label>
							<textarea name='ship_to' id="ship_to" placeholder="Ship Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " rows ="4" ><?php echo $rows['ship_to']; ?></textarea>
						</div>

						<div class="col-md-4">
							<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
							<select id="type" name="type"  onchange="chk_type();" <?php  echo $disabled ;?> class="form-select select2 tdstax_field required" data-allow-clear="true" style="width:100%;">
							<option value="">Select</option>
								<option  value="Direct_Purchase" <?php if($rows['type']=='Direct_Purchase'){ echo 'selected';}else{ echo ' ';} ?> >Direct Purchase</option>
								<option  value="Against_Requisition" <?php if($rows['type']=='Against_Requisition'){ echo 'selected';}else{ echo ' ';} ?> >Against Requisition</option>
							</select>
						</div>

						<div class="col-md-4">
							<label class="form-label">Bill To Pincode<span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="bill_pincode" class="required form-control" readonly <?php echo $readonly;?> placeholder="Pincode" name="bill_pincode" value="<?php echo $rows['bill_pincode']; ?>" />
						</div>

						<div class="col-md-4">
							<label class="form-label">Ship To Pincode<span class="required required_lbl" style="color:red;">*</span></label>
							<input type="text" id="ship_pincode" class="required form-control" readonly <?php echo $readonly;?> placeholder="Pincode" name="ship_pincode" value="<?php echo $rows['ship_pincode']; ?>"/>
						</div>

						<div class="col-md-4" id="requisition_order_div">

						</div>

						
						<?php 
							$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
							$state= $account_ledger['mail_state'];
						?>
					
						</div>
					</div>

					<div class="col-md-4" >
						<div class="row">
							<div class="col-md-12">
								<label class="form-label">T & C.<span class="required required_lbl" style="color:red;">*</span></label>
								<textarea name='terms_con' id="terms_con" <?php echo $readonly; ?> <?php echo $disabled; ?> class="form-control " rows ="12" ><?php echo $rows['terms_con']; ?></textarea>
							</div>
						</div>
					</div>

					<div id="table_div" style="overflow: hidden;">
				
					</div>
							
					<div class="col-12 text-center">
						<?php 
						if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
							<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
						<?php } ?>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
			
          			</div>
				</form>
			<!--/ Add role form -->
      		</div>
    	</div>
  	</div>
</div>
<!--/ Add Role Modal -->
<script>

	window.onload=function(){
		$("#date").flatpickr({
			dateFormat: "d-m-Y"
		});
	}

	function get_order_code() {

		var voucher_type = $("#voucher_type").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_order_code',voucher_type:voucher_type},
			success:function(data)
			{	
				// alert(data);
				$("#order_no").val(data);
			}
		});

	}

	// function find_state(){
	// 	var supplier =$("#supplier").val();
	// 	if(supplier==''){
	// 		alert('Please Select Supplier !!!!');
	// 		return false;
	// 	}
	// 	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
	// 		data: { Type:'find_state',supplier:supplier},
	// 		success:function(data)
	// 		{	
	// 			// alert(data);
	// 			$("#state_name").val(data);
	// 		}
	// 	}); 
	// }

	function get_address(){
	
		var customer =$("#supplier").val();
		// $('#loader').show();
		// $("#mainform").addClass("loader");
		// $("#bill_to").addClass("loader");
		
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_address',customer:customer},
			success:function(data)
			{	
				// $('#loader').hide();
				// $("#mainform").removeClass("loader");
				// $("#bill_to").removeClass("loader");

				var bdid=data.split("#");
				jQuery("#bill_to").val(bdid[0]);
				jQuery("#state_name").val(bdid[1]);
				jQuery("#state_code").val(bdid[2]);
				jQuery("#ship_to").val(bdid[3]);
				jQuery("#bill_pincode").val(bdid[4]);
				jQuery("#ship_pincode").val(bdid[5]);
			}
		}); 
	}

	function get_pos(id) {

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_pos',id:id },
			success:function(data)
			{	
				$("#pos_state").html(data);
				
			}
		});

	}

	function requisition_rowtable()
	{	
		// $('#loader').show(); 
		// $("#table_div").addClass("loader");

		var PTask = $("#PTask").val();
		var id = $("#id").val();
		var type =$("#type").val();
		var location =$("#location").val();
		var requisition_no =$("#requisition_no").val();
		// var supplier =$("#supplier").val();
		var supplier =$("#pos_state").val();

		// if(supplier==''){
		// 	alert('Please Select Supplier !!!!');
		// 	return false;
		// }

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'requisition_rowtable',type:type,id:id,PTask:PTask,requisition_no:requisition_no,supplier:supplier,location:location},
			success:function(data)
			{	
				// $('#loader').hide(); 
				$("#table_div").html(data);
				// $("#table_div").removeClass("loader");
			}
		});
	}

	function chk_type()
	{
		var PTask = $("#PTask").val();
		var id = $("#id").val();
		var type = $("#type").val();

		if(type=="Against_Requisition"){
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'chk_requisitionno_type',type:type,id:id,PTask:PTask},
				success:function(data)
				{	
					$("#requisition_order_div").html(data);	
					
					var requisition_no =$("#requisition_no").val();

					$("#requisition_no").select2({
						dropdownParent: $('#requisition_order_div')
					});

					

					// alert("rr"+requisition_no);
					if(PTask=='update'&&requisition_no!=null || PTask=='view'){
						requisition_rowtable();
					}
					else {
						requisition_rowtable();
					}
				}
			});	
		}else if(type=="Direct_Purchase"){
			
			$("#requisition_order_div").html(" ");
			
			var requisition_no =$("#requisition_no").val();
			if((requisition_no==null &&PTask!='')|| PTask=='view'){
				requisition_rowtable();
			}
		}			
	}

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
				$("#igst_"+id).focus();
			}
		});
	}

	function get_gstdata(this_id)
	{
		var id=this_id.split("_");
		id=id[1];
		// var cnt = $("#cnt").val();

		var product = $("#product_"+id).val();
		var date = $("#date").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_gstdata',id:id,product:product,date:date },
			success:function(data)
			{	
				var bdid=data.split("#");
				var meas=bdid[0].split(",");
				jQuery("#igst_"+id).val(bdid[0]);
				jQuery("#cgst_"+id).val(bdid[1]);
				jQuery("#sgst_"+id).val(bdid[2]);

				$(this).next().focus();
			}
		});	
	}
</script>
<script>
              
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
						
						jQuery("#unit_"+k).attr('name','unit_'+newId);
						jQuery("#unit_"+k).attr('id','unit_'+newId);
						
						jQuery("#cgst_"+k).attr('name','cgst_'+newId);
						jQuery("#cgst_"+k).attr('id','cgst_'+newId);
						
						jQuery("#sgst_"+k).attr('name','sgst_'+newId);
						jQuery("#sgst_"+k).attr('id','sgst_'+newId);
						
						jQuery("#igst_"+k).attr('name','igst_'+newId);
						jQuery("#igst_"+k).attr('id','igst_'+newId);
						
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
		
		cell1 += "<td  style='width:15%;'><select name='product_"+i+"' class='select2 form-select' id='product_"+i+"' onchange='get_unit(this.id);get_gstdata(this.id);get_ledger(this.id,"+state+");' style='width:120px;'>\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
				foreach($record as $e_rec) {	
					echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
			?>
		</select></td>";

		cell1 += "<td style='width:10%' ><select id='ledger_"+i+"' name='ledger_"+i+"' class='select2 form-select' >\
		<option value=''>Select Ledger</option>\
			<?php
				$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
				foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
					
			?>
		</select></td>";

		cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";
		if(state==27){
			cell1 += "<td style='width:7%'><input name='cgst_"+i+"' id='cgst_"+i+"'   class='form-control number' type='text'/></td>";
			cell1 += "<td style='width:7%'><input name='sgst_"+i+"' id='sgst_"+i+"'   class='form-control number' type='text'/></td>";

		} else {
			cell1 += "<td style='width:7%'><input name='igst_"+i+"' id='igst_"+i+"'   class='form-control number' type='text'/></td>";
		}

		// cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"' class='tdalign form-control number' type='text'/></td>";
		
		// cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"' class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'   onkeyup='getrowgst(this.id);gettotgst("+i+");' class='tdalign form-control number' type='text'/>\
		<input type='hidden' name='rowcgstamt_"+i+"' id='rowcgstamt_"+i+"' value='0' >\
			<input type='hidden' name='rowsgstamt_"+i+"' id='rowsgstamt_"+i+"' value='0' >\
			<input type='hidden' name='rowigstamt_"+i+"' id='rowigstamt_"+i+"' value='0' ></td>";

		cell1 += "<td style='width:10%'><input name='taxable_"+i+"' id='taxable_"+i+"' class='tdalign form-control number' type='text' value='' /></td>";
		
		// cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'  onchange='Getgst(this.id);showgrandtotal();' onkeyup='showgrandtotal();' onblur='showgrandtotal();' class='tdalign form-control number' type='text' value='0' /></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";


		$("#myTable").append(cell1);
		$("#cnt").val(i);

		$("#product_"+i).select2({
			dropdownParent: $('#table_div')
		});

		$("#ledger_"+i).select2({
			dropdownParent: $('#table_div')
		});
		
	}

	function addRowdetail(tableID) 
	{ 
		var count=$("#cntd").val();	
		var state=$("#state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row2_"+i+"'>";
		
		cell1 += "<td style='width:2%;><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
		
		cell1 += "<td style='width:15%;'><div id='ledgerdiv_"+i+" ?>'><select name='serviceledger_"+i+"' class='select2 form-select' id='serviceledger_"+i+"' onchange='getservice(this.id);' >\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
				foreach($record as $e_rec) {	
					echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
			?>
		</select></div></td>";

		cell1 += "<td style='width:10%'></td>";

		cell1 += "<td style='width:10%'></td>";

		if(state==27) {
			cell1 += "<td style='width:7%'><input name='servicecgst_"+i+"' id='servicecgst_"+i+"' class='form-control number' type='text' readonly/></td>";
			cell1 += "<td style='width:7%'><input name='servicesgst_"+i+"' id='servicesgst_"+i+"' class='form-control number' type='text' readonly/></td>";
		} else {
			cell1 += "<td style='width:7%'><input name='serviceigst_"+i+"' id='serviceigst_"+i+"' class='form-control number' type='text' readonly/></td>";
		}

		cell1 += "<td style='width:10%'></td>";
		
		cell1 += "<td style='width:10%'></td>";

		cell1 += "<td style='width:10%'><input name='serviceamt_"+i+"' id='serviceamt_"+i+"' class='tdalign form-control number' type='text' value='0' onkeyup='servicegstsum(this.id);servicetotgst("+i+");' />\
		<input type='hidden' name='serviceigstamt_"+i+"' id='serviceigstamt_"+i+"' value='0' >\
		<input type='hidden' name='servicecgstamt_"+i+"' id='servicecgstamt_"+i+"' value='0' >\
		<input type='hidden' name='servicesgstamt_"+i+"' id='servicesgstamt_"+i+"' value='0' ></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";


		$("#dtable").append(cell1);
		$("#cntd").val(i);

		$("#product_"+i).select2({
			dropdownParent: $('#ledgerdiv_'+i)
		});

		
	}

	function Gettotgst()
	{
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		// var did=rid.split("_");
		// var rid=did[1];
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		var totcgst=0;var totsgst=0;var totigst=0;
		
		for(var i=1; i<=count;i++)
		{
			var total=jQuery("#taxable_"+i).val();

			var cgst=jQuery("#cgst_"+i).val();
			
			var cgst_amt=parseFloat(total*cgst)/100;
			
			// totcgst += parseFloat(totcgst)+parseFloat(cgst_amt);
			totsgst+=cgst_amt;

			//alert(totcgst+"<<<"+cgst_amt);
			var sgst=jQuery("#sgst_"+i).val();
			
			var sgst_amt=parseFloat(total*sgst)/100;
			totsgst+=sgst_amt;
			var igst=jQuery("#igst_"+i).val();
			
			var igst_amt=parseFloat(total*igst)/100;
			totigst+=igst_amt;
		}
		
		if(totcgst==''||isNaN(totcgst)){totcgst=0;}
		if(totsgst==''||isNaN(totsgst)){totsgst=0;}
		if(totigst==''||isNaN(totigst)){totigst=0;}
		// alert(totcgst);

		jQuery('#totcst_amt').val(totcgst.toFixed(2));
		// jQuery('#totcst_amt').val(cgst_amt);

		jQuery('#totsgst_amt').val(totsgst.toFixed(2));
		jQuery('#totigst_amt').val(totigst.toFixed(2));
		showgrandtotal();
	}
	
	function Getgst(rid)
	{
		
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		var did=rid.split("_");
		var rid=did[1]; 
		//alert(rid);
		var val=0;
		var total=0;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		var totcgst=0;var totsgst=0;var totigst=0;
		//alert(rid);
		
		var total=jQuery("#taxable_"+rid).val();

		var cgst=jQuery("#cgst_"+rid).val();
		// alert("total-"+total+"="+cgst);
		if(cgst==''){cgst=0;}
		var cgst_amt=parseFloat(total*cgst)/100;

		var sgst=jQuery("#sgst_"+rid).val();
		if(sgst==''){sgst=0;}
		var sgst_amt=parseFloat(total*sgst)/100;

		var igst=jQuery("#igst_"+rid).val();
		if(igst==''){igst=0;}
		var igst_amt=parseFloat(total*igst)/100;
		
		
		if(cgst_amt==''||isNaN(cgst_amt)){cgst_amt=0;}
		if(sgst_amt==''||isNaN(sgst_amt)){sgst_amt=0;}
		if(igst_amt==''||isNaN(igst_amt)){igst_amt=0;}
		
		//alert(total+"=>"+cgst_amt+"=>"+sgst_amt+"=>"+igst_amt);
		var grandsum=(parseFloat(total)+parseFloat(cgst_amt)+parseFloat(sgst_amt)+parseFloat(igst_amt));
		//alert(total+"=>"+cgst_amt+"=>"+sgst_amt+"=>"+igst_amt);
		// alert(grandsum);

		jQuery("#total_"+rid).val(grandsum.toFixed(2));
		// jQuery('#totcst_amt').val(cgst_amt.toFixed(2));

		var subtotal=0;
		for(var i=1; i<=count;i++)
		{
			if(jQuery("#taxable_"+i).val()!='' && floatRegex.test(jQuery("#taxable_"+i).val()))
			subtotal = parseFloat(subtotal)+parseFloat(jQuery("#taxable_"+i).val());

		}
		if(grandsum<0)
		{
			jQuery("#"+rid).val(val);
			alert('Please Enter valid entry!');
			Gettotal(rid)
			jQuery("#"+rid).focus("");
		}
		//var cnt=jQuery("#cnt").val();
		
		jQuery("#subt").val(subtotal.toFixed(2));
		jQuery("#totaltaxable").val(subtotal.toFixed(2));
	
	}
	
	function Gettotal(rid)
	{
			
		var rid1 = rid;
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		Getgst(rid);
		Gettotgst();
		var did=rid.split("_");
		var rid=did[1]; 
		
		var val=subtotal=0;
		var total=0;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		
		var qty=jQuery("#qty_"+rid).val();
		var rate=jQuery("#rate_"+rid).val();
		// var disc=jQuery("#disc_"+rid).val();
			
		// if(disc==""){ disc=0; }
		if(floatRegex.test(qty) && floatRegex.test(rate)){
			var cal =parseFloat(qty*rate);
			// var discamt=(cal*disc)/100;
			// total=parseFloat(cal)-parseFloat(discamt);
			total=cal;
			//alert(total);
			//var taxable_amt=jQuery("#taxable_"+rid).val(total.toFixed(0));
			for(var i=1; i<=count;i++)
			{
				if(jQuery("#taxable_"+i).val()!='' && floatRegex.test(jQuery("#taxable_"+i).val()))
				subtotal = parseFloat(subtotal)+parseFloat(jQuery("#taxable_"+i).val());


			}	
			jQuery("#subt").val(subtotal.toFixed(2));
			jQuery("#totaltaxable").val(subtotal.toFixed(2));
			
		} 
			
		// jquery('#total_'+rid).val(grandsum.toFixed(0));

		jQuery("#taxable_"+rid).val(total.toFixed(2));
		// alert(cgst);
		// getRate(rid1);
		Gettotgst();
		showgrandtotal();
			
	}
	
	
	function getRate(rid)
	{
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		var did=rid.split("_");
		var rid=did[1]; 
		var val=subtotal=0;
		var total=0;
		var rid1 = rid;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		// var disc=jQuery("#disc_"+rid).val();
		// if(disc==''){disc=0;}
		var rate=jQuery("#rate_"+rid).val();
		
		var qty=jQuery("#qty_"+rid).val();
		if(qty=='')
		{
			qty=1;
			
		}
		// alert(qty);
		if(floatRegex.test(qty) && floatRegex.test(rate)){
			total=rate * qty;
			//alert(total);
			
			//var taxable_amt=jQuery("#taxable_"+rid).val(total.toFixed(2));
			var taxable_amt=$('#rate_'+rid).val();
			for(var i=1; i<=count;i++)
			{
				var qty=jQuery("#qty_"+i).val();
				if(jQuery("#rate_"+i).val()!='' && floatRegex.test(jQuery("#rate_"+i).val()))
				subtotal = parseFloat(subtotal)+parseFloat(parseFloat(jQuery("#rate_"+i).val())*parseFloat(qty));


			}	
			jQuery("#subt").val(subtotal.toFixed(2));
			jQuery("#totaltaxable").val(subtotal.toFixed(2));
			
		} 
		
		jQuery("#total_"+rid).val(total.toFixed(2));	
		var taxamt = total;
		// alert(taxamt);
		jQuery("#taxable_"+rid).val(taxamt.toFixed(2));
		Gettotgst();
		// Gettotal(rid1);
		showgrandtotal();
	}
	
	function Gettotal1(rid)
	{
		var rid1 = rid;
        var table = document.getElementById('myTable');
        var rowCount = table.rows.length;
        var count=parseFloat(rowCount-1);
        var did=rid.split("_");
        var rid=did[1]; 
        var val=subtotal=0;
        var total=0;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        //var getamt=$('#mrp_'+rid).val();
        if(getamt==''){getamt=0;}
        if(getamt>0)
        {
            var calrate=0;
            var totalgst=0;
            var cgst=$('#cgst_'+rid).val();
            var sgst=$('#sgst_'+rid).val();
            var igst=$('#igst_'+rid).val();
            var quant=$('#qty_'+rid).val();
        	// alert("rate = "+rate);
            totalgst=parseFloat($('#cgst_'+rid).val())+parseFloat($('#sgst_'+rid).val())+parseFloat($('#igst_'+rid).val());
			var per=parseFloat(totalgst)/100;
			
			var amt=(1+parseFloat(per));
			
			var rate = parseFloat(getamt)/amt;
			
			var out = (rate.toFixed(2));
			
			if($('#mrp_'+rid).val()!="")
			{
			$('#rate_'+rid).val(out);
			}
			//var disc = $('#disc').val();
			//if(disc==""){ disc=0; }
			//var discamt = parseFloat(parseFloat(rate*disc)/100);
			//$('#disc_'+rid).val(discamt.toFixed(2));
				
			//getRate(rid1);
				
			//Gettotal(rid1);
			//showgrandtotal();	
		}
	}
	
	function showgrandtotal()
	{
		
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		var finaltotal=subtotal=0;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		var regex=/^-?\d+(\.\d+)?$/;
		var grandtotal=0;
		
		var total_vat=0;
		var total_g=0;
		
		// var qty=jQuery("#qty_"+rid).val();
		// var rate=jQuery("#rate_"+rid).val();
		// var disc=jQuery("#disc").val();
		
		
		var grandtotal=jQuery("#subt").val();

		<?php
			if (!empty($rows['disc'])||!empty($rows['cst'])||!empty($rows['excs'])||!empty($rows['TotalVat'])) { 
		?>	
			var subtotalval=jQuery("#subt").val();	
			// var disc=jQuery("#disc").val();
			// if(disc==''){ disc=0;}
			if(floatRegex.test(disc)){
				discval=0;
				var	discval=(subtotalval*disc)/100;

				dicsvalamt=(subtotalval-discval);
				grandtotal = parseFloat(dicsvalamt);
			}
		
			var exc=jQuery("#exc").val();
			if(exc==''){ exc=0;}
			if(floatRegex.test(exc)){
				var	excval=(grandtotal*exc)/100;
				grandtotal = parseFloat(grandtotal)+parseFloat(excval);
			}
		
			var vatval=jQuery("#vatval").val();
			if(vatval==''){ vatval=0;}
			if(floatRegex.test(vatval)){
				var vatamount=(grandtotal*vatval)/100;

				jQuery("#vatamt").val(vatamount.toFixed(2));
				grandtotal = parseFloat(grandtotal)+parseFloat(vatamount);
			}
		
			var cst=jQuery("#cst").val();
			if(cst==''){ cst=0;}
			if(floatRegex.test(cst)){
				var	cstval=(grandtotal*cst)/100;
			grandtotal = parseFloat(grandtotal)+parseFloat(cstval);
			}
			<?php
			}
			else { ?>
				var totcgst=jQuery('#totcst_amt').val();	
				if(floatRegex.test(totcgst)){			
					grandtotal = parseFloat(grandtotal)+parseFloat(totcgst);
				}		
				var totsgst=jQuery('#totsgst_amt').val();
				if(floatRegex.test(totsgst)){			
					grandtotal = parseFloat(grandtotal)+parseFloat(totsgst);
				}	
				var totigst=jQuery('#totigst_amt').val();	
				if(floatRegex.test(totigst)){			
					grandtotal = parseFloat(grandtotal)+parseFloat(totigst);
				}	
			<?php } ?>
			var trans=jQuery("#trans").val();
			if(trans==''){ trans=0;}
			if(floatRegex.test(trans)){
				grandtotal = parseFloat(grandtotal)+parseFloat(trans);
			}
				var other=jQuery("#other").val();
			if(other==''){ other=0;}
			if(floatRegex.test(other)){
				grandtotal = parseFloat(grandtotal)+parseFloat(other);
			}
			var roff=jQuery("#roff").val();
			if(roff==''){ roff=0;}
			if(regex.test(roff)){
				grandtotal = parseFloat(grandtotal)+parseFloat(roff);
			}
			//=================TCS TDS===================
			
			var amount2=$("#tcs_tds_percen").val();	
			if(amount2==""){	amount2=0;}

			var total1=(grandtotal*amount2)/100;
			$("#tcs_tds_amt").val(total1.toFixed(2));

			/* var total2 =parseFloat(grandtotal)+parseFloat(total1);
			$("#trans").val(total2.toFixed(2)); */
			

			var tcs_tds=jQuery("#tcs_tds_amt").val();
			if(tcs_tds==''){ tcs_tds=0;}
			if(floatRegex.test(tcs_tds)){
			
				grandtotal = parseFloat(grandtotal)+parseFloat(tcs_tds);
			}
			//================================================================
			jQuery("#grandtotal").val(grandtotal.toFixed(2));
		
		
	}

	function tran(){
		var amount1=$("#transcost").val();
		var amount2=$("#transgst").val();

		if(amount1=="")
		{
			amount1=0;
		}
		if(amount2=="")
		{
			amount2=0;
		}

		var total1=(amount1*amount2)/100;
		$("#transamount").val(total1);

		var total2 =parseFloat(amount1)+parseFloat(total1);
		$("#trans").val(total2);

		//$("#transamount").val(($("#transcost").val()*$("#transgst").val())/100);
		//$("#trans").val(($("#transcost").val())+(($("#transcost").val()*$("#transgst").val())/100));
		//alert('hi');

	}
	
	// ----------------------------------------------------------------------------------
	
	function gettotgst(id) {

        var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;

        var gst_subtot = 0;
        var grandtotal = 0;

        var state = $("#pos_state").val();
        var service_subtotal = $("#service_subtotal").val();
        var totaltaxable = 0;

		$("[id^='taxable_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totaltaxable += quant;
		});

		$("#totaltaxable").val(totaltaxable.toFixed(2));

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='rowcgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst += quant;
            });

			// alert(totcgst);
            $("#cgsttot").val(totcgst.toFixed(2));
            // $("#cgstamt").val(totcgst);

            // Assuming batqty1_id elements are input fields
            $("[id^='rowsgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst += quant;
            });

			// alert(totsgst);
            $("#sgsttot").val(totsgst.toFixed(2));
            // $("#sgstamt").val(totsgst);

            // gst_subtot = totcgst+totsgst;

            // $("#gst_subtotal").val(gst_subtot);

        // } else {

            // Assuming batqty1_id elements are input fields
            $("[id^='rowigstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totigst += quant;
            });

			// alert(totigst);
            $("#igsttot").val(totigst.toFixed(2));
            // $("#igstamt").val(totigst);

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);

		gettotalgstrate();

    }

	function getrowgst(this_id) {

        var id=this_id.split("_");
        id=id[1];

		// alert("Hello");
        var qty = $("#qty_"+id).val();
        var rate = $("#rate_"+id).val();

		var total = parseFloat(rate*qty);
		$("#taxable_"+id).val(total.toFixed(2));
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var igst=$("#igst_"+id).val();
		var igst_amt=parseFloat(total*igst)/100;

        var sgst=$("#sgst_"+id).val();
		var sgst_amt=parseFloat(total*sgst)/100;

        var cgst=$("#cgst_"+id).val();
		var cgst_amt=parseFloat(total*cgst)/100;

		// alert(igst_amt);
		// alert(cgst_amt);
		// alert(sgst_amt);

        $("#rowigstamt_"+id).val(igst_amt.toFixed(2));
        $("#rowsgstamt_"+id).val(sgst_amt.toFixed(2));
        $("#rowcgstamt_"+id).val(cgst_amt.toFixed(2));

		gettotgst(id);

    }

	function servicegstsum(this_id) {

        var id=this_id.split("_");
        id=id[1];

        var total = $("#serviceamt_"+id).val();
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var igst=$("#serviceigst_"+id).val();
		var igst_amt=parseFloat(total*igst)/100;

        var sgst=$("#servicesgst_"+id).val();
		var sgst_amt=parseFloat(total*sgst)/100;

        var cgst=$("#servicecgst_"+id).val();
		var cgst_amt=parseFloat(total*cgst)/100;

		// alert(igst_amt);
		// alert(cgst_amt);
		// alert(sgst_amt);

        $("#serviceigstamt_"+id).val(igst_amt.toFixed(2));
        $("#servicesgstamt_"+id).val(sgst_amt.toFixed(2));
        $("#servicecgstamt_"+id).val(cgst_amt.toFixed(2));

		// gettotgst(id);

    }

	function servicetotgst(id) {

        var totcgst1 = $("#cgstamt").val();
        var totsgst1 = $("#sgstamt").val();
        var totigst1 = $("#igstamt").val();

        var totcgst2 = 0;
        var totsgst2 = 0;
        var totigst2 = 0;

        var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;
		var totserviceamt = 0;


        var gst_subtot = 0;
        var grandtotal = 0;
		
        var state = $("#pos_state").val();

		$("[id^='serviceamt_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totserviceamt += quant;
		});

		$("#totserviceamt").val(totserviceamt.toFixed(2));

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='servicecgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst2 += quant;
            });

			// totcgst = parseFloat(totcgst1)+parseFloat(totcgst2);
            $("#totservicecgst").val(totcgst2.toFixed(2));

            // Assuming batqty1_id elements are input fields
            $("[id^='servicesgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst2 += quant;
            });

			// totsgst = parseFloat(totsgst1)+parseFloat(totsgst2);
            $("#totservicesgst").val(totsgst2.toFixed(2));

            // gst_subtot = totcgst+totsgst;

            // $("#gst_subtotal").val(gst_subtot);

        // } else {

            // Assuming batqty1_id elements are input fields
            $("[id^='serviceigstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totigst2 += quant;
            });

			// totigst = parseFloat(totigst1)+parseFloat(totigst2);
            $("#totserviceigst").val(totigst2.toFixed(2));

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);
		
		gettotalgstrate();

    }

	function gettotalgstrate() {

		var totcgst1 = $("#cgsttot").val();
        var totsgst1 = $("#sgsttot").val();
        var totigst1 = $("#igsttot").val();

		var totcgst2 = $("#totservicesgst").val();
        var totsgst2 = $("#totservicesgst").val();
        var totigst2 = $("#totserviceigst").val();

		var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;

		totcgst = parseFloat(totcgst1)+parseFloat(totcgst2);
		totsgst = parseFloat(totsgst1)+parseFloat(totsgst2);
		totigst = parseFloat(totigst1)+parseFloat(totigst2);

		$("#cgstamt").val(totcgst.toFixed(2));
		$("#sgstamt").val(totsgst.toFixed(2));
		$("#igstamt").val(totigst.toFixed(2));

		getsubtotgst();

	}

	function getsubtotgst() {

		var totcgst = $("#cgstamt").val();
        var totsgst = $("#sgstamt").val();
        var totigst = $("#igstamt").val();

		var totaltaxable = $("#totaltaxable").val();
		var totserviceamt = $("#totserviceamt").val();

		// alert(totcgst);
		// alert(totsgst);
		// alert(totigst);
		// alert(totaltaxable);
		// alert(totserviceamt);
		
		var state = $("#pos_state").val();

		var subtotgst = 0;
		var grandtot = 0;

		if(state==27) {

			subtotgst = parseFloat(totsgst)+parseFloat(totcgst);
			$("#subtotgst").val(subtotgst.toFixed(2));

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot.toFixed(2));

		} else {

			subtotgst = parseFloat(totigst);
			$("#subtotgst").val(subtotgst.toFixed(2));

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot.toFixed(2));

		}

	}

	function getservice(this_id) {

		var id=this_id.split("_");
        id=id[1];

		var service_ledger = $("#serviceledger_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'getservice',service_ledger:service_ledger },
            success:function(data)
            {	
                // alert(data);
                var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#serviceigst_"+id).val(bdid[0]);
                jQuery("#servicesgst_"+id).val(bdid[1]);
                jQuery("#servicecgst_"+id).val(bdid[2]);
            }
        }); 

	}

	function get_discamt() {

		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

		var disc = $("#totdiscount").val();
        var total = $("#totaltaxable").val();

		var discamt=parseFloat(total*disc)/100;
		var disctottaxable = parseFloat(total)-parseFloat(discamt);

		$("#totaltaxable").val(disctottaxable.toFixed(2));

	}

</script>

