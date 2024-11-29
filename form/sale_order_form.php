
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php
 $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from sale_order");
$result=mysqli_fetch_array($getinvno);
$sale_order=$result['pono']+1;  
$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("sale_order","id ='".$id."'");
    $sale_order=$rows['order_no'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	$grandtotal=$rows['grandtotal'];

} else{
	$rows=null;

}

?>
<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      	<div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Sale Order</h3>
          
        </div>
        <!-- Add role form -->
		
         	<form id="" data-parsley-validate class="row g-3" action="../sale_order_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "sale_order"; ?>"/>
			    
			
					<div class="col-md-2">
						<label class="form-label">Order No. <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="order_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="order_no" value="<?php echo $sale_order;?>"/>
					</div>

					<div class="col-md-2">
						<label class="form-label">Sale Order Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>
					
					

					<!-- <div class="col-md-4">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" onchange="" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
						<label class="form-label">Customer<span class="required required_lbl" style="color:red;">*</span></label>
						<select id="customer" name="customer"  onchange="get_address();get_pos(this.value);"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
							<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['customer']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>
					
					<div class="col-md-2">
						<label class="form-label">State <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="state_name" class="required form-control" readonly <?php echo $readonly;?> placeholder="State Name" name="state_name" value="<?php echo $rows['state_name']; ?>" />
					</div>

					<div class="col-md-2">
						<label class="form-label">State Code <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="state_code" class="required form-control" readonly <?php echo $readonly;?> placeholder="State Code" name="state_code" value="<?php echo $rows['state_code']; ?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Bill To<span class="required required_lbl" style="color:red;">*</span></label>
						<textarea name='bill_to' id="bill_to" placeholder="Bill Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control "><?php echo $rows['bill_to'];?></textarea>
					</div>

					<div class="col-md-4">
						<label class="form-label">Ship To<span class="required required_lbl" style="color:red;">*</span></label>
						<textarea name='ship_to' id="ship_to" placeholder="Ship Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " ><?php echo $rows['ship_to']; ?></textarea>
					</div>

					<div class="col-md-2">
						<label class="form-label">POS State <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="pos_state" name="pos_state" onchange="get_materialtable();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true">
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
		  
          	<h4 class="role-title">Material Details</h4>
		  	<?php 
		 		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
		  		$state= $account_ledger['mail_state'];
			?>
		
        <div id="table_div" style="overflow: hidden;">
		
		</div>
		
		<div class="col-12 text-center">
		<?php
			if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='') { ?>	
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

function get_pos(id) {

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_pos',id:id },
		success:function(data)
		{	
			$("#pos_state").html(data);
			
			get_materialtable();
		}
	});

	

}

function getrate(this_id) {

    var location = $("#location").val();
    var pricelevel = $("#pricelevel").val();
    var id = this_id.split("_")[1];
    var product = $("#product_" + id).val();
    var qty = $("#qty_" + id).val();
	var elementId = $("#rate_" + id).attr("id");

    jQuery.ajax({
        url: 'get_ajax_values.php',
		type: 'POST', data: {
            Type: 'getrate', id: id, quantity:qty, product:product, pricelevel:pricelevel
        },
        success: function (data) {
            var bdid = data.split("#");
            var rate = bdid[0];
            var discount = bdid[1];

            jQuery("#rate_" + id).val(rate);
            jQuery("#disc_" + id).val(discount);

            // $("#disc_" + id).next().focus();
			// getrowgst(elementId);
			
        }
    });
}

function get_materialtable() {
  
	// var customer =$("#customer").val();
	var customer =$("#pos_state").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_materialtable',PTask:PTask,id:id,customer:customer},
		success:function(data)
		{	
		    // alert(data);
			$("#table_div").html(data);
			// $(".select2").select2();
			

		}
	}); 
}


function get_address(){
  
	var customer =$("#customer").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_address',PTask:PTask,id:id,customer:customer},
		success:function(data)
		{
			var bdid=data.split("#");
			var meas=bdid[0].split(",");
			jQuery("#bill_to").val(bdid[0]);
			jQuery("#state_name").val(bdid[1]);
			jQuery("#state_code").val(bdid[2]);
			jQuery("#ship_to").val(bdid[3]);

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
function Gettotal(id)
{ 
	var did=id.split("_");
	var rid=did[1];	
	var Rate=jQuery("#rate_"+rid).val();
	var qty=jQuery("#qty_"+rid).val();
	var per_cgst=jQuery("#cgst_"+rid).val();
	var per_sgst=jQuery("#sgst_"+rid).val();
	var per_igst=jQuery("#igst_"+rid).val();
	
	if(Rate==''){ Rate=0;}
	if(qty==''){ qty=0;}
	
	var  tatal=parseFloat(Rate)*parseFloat(qty);
	var cgst_amt = (parseFloat(per_cgst) / 100) * parseFloat(tatal);
	var sgst_amt = (parseFloat(per_sgst) / 100) * parseFloat(tatal);
	var igst_amt = (parseFloat(per_igst) / 100) * parseFloat(tatal);
	jQuery("#total_"+rid).val(tatal);   
	GrandTotal();
}

function GrandTotal()
{
	var cnt=jQuery("#cnt").val();
	var grandtotal=0;	
	for(var i=1; i<=cnt;i++)
	{	
		var total= jQuery("#total_"+i).val();
		if(total==''){ total=0;}
		grandtotal = parseFloat(grandtotal)+parseFloat(total);
	}
	jQuery("#grandtotal").val(parseFloat(grandtotal).toFixed(2));	
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
				
				jQuery("#total_"+k).attr('name','total_'+newId);
				jQuery("#total_"+k).attr('id','total_'+newId);
				
				jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
			}
			jQuery("#cnt").val(parseFloat(count-1)); 
			GrandTotal();
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
		var state=$("#pos_state").val();	
		// alert(state);
		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"'>"+i+"</label></td>";
		
		cell1 += "<td style='width:20%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);get_saleledger(this.id,"+state+");get_gstdata(this.id);' >\
		<option value=''>Select</option>\
		<?php
			$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
			foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
				
		?>
		</select></td>";

		cell1 += "<td style='width:10%'><select id='ledger_"+i+"' name='ledger_"+i+"' class='select2 form-select'>\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
				foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
					
			?>
		</select></td>";

		cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";
		if(state==27){
			cell1 += "<td style='width:5%'><input name='cgst_"+i+"' id='cgst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
			cell1 += "<td style='width:5%'><input name='sgst_"+i+"' id='sgst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'   class='form-control number' type='text'/></td>";
		}else{
			cell1 += "<td style='width:5%'><input name='igst_"+i+"' id='igst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
		}
		cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   onkeyup='Gettotal(id);' onchange='Gettotal(id);' class='form-control number' type='text'/></td>";
		
		cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
		
		cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'   class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

		$("#myTable").append(cell1);
		$("#cnt").val(i);
		// $("#particulars_"+i).select2(); 
		// $(".select2").select2();

		$("#product_"+i).select2({
			dropdownParent: $('#table_div')
		});

		$("#ledger_"+i).select2({
			dropdownParent: $('#table_div')
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
		<input type='hidden' name='serviceigstamt_"+i+"' id='serviceigstamt_"+i+"' value='' >\
		<input type='hidden' name='servicecgstamt_"+i+"' id='servicecgstamt_"+i+"' value='' >\
		<input type='hidden' name='servicesgstamt_"+i+"' id='servicesgstamt_"+i+"' value='' ></td>";

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";


		$("#dtable").append(cell1);
		$("#cntd").val(i);

		$("#product_"+i).select2({
			dropdownParent: $('#ledgerdiv_'+i)
		});

		
	}


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

		// gettotgst(id);

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

