<?php  include("header.php");
    $task=$_REQUEST['PTask'];
    if($task=='') { $task='Add';}
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
	$common_id = uniqid();?>
<!-- Add Role Modal -->

<?php

// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice");
// $result=mysqli_fetch_array($getinvno);
// $sale_invoiceno=$result['pono']+1;

$date=date('d-m-Y');
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("sale_invoice","id ='".$id."'");
    $sale_invoiceno=$rows['sale_invoiceno'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	//$grandtotal=$rows['grandtotal'];
	
	
} else{
	$rows=null;
}

?>
<div class="container" style ="background-color: white;">

	<div class="text-center mb-4">
		<h3 class="role-title">Sale Invoice</h3>
	</div>
	
	<form id="" data-parsley-validate class="row g-3" action="../sale_invoice_list.php"  method="post" data-rel="myForm">
		
		<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
		<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
		<input type="hidden"  name="common_id"  id="common_id"  value="<?php echo $common_id;?>"/>	
		<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
		<input type="hidden"  name="table"      id="table"      value="<?php echo "sale_invoice"; ?>"/>
			
		<div class="col-md-3">
			<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_sale_ino();">
			<option value="">Select</option>
				<?php	
					$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=1 group by id"); 
					foreach($data as $info){
						if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
						echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
					}  
				?>
			</select>
		</div>

		<div class="col-md-3">
			<label class="form-label">Sale Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" id="sale_invoiceno" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="sale_invoiceno" value="<?php echo $sale_invoiceno;?>"/>
		</div>

		<div class="col-md-3">
			<label class="form-label"> Date <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
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

		<div class="col-md-3">
			<label class="form-label">Customer<span class="required required_lbl" style="color:red;">*</span></label>
			<select id="customer" name="customer" onchange="getpricelevel();get_address();get_pos(this.value);" <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
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

		<div class="col-md-3">
			<label class="form-label">State<span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" id="state_name" class="required form-control" readonly <?php echo $readonly;?> placeholder="State Name" name="state_name" value="<?php echo $rows['state_name']; ?>" />
		</div>

		<div class="col-md-3">
			<label class="form-label">State Code <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="text" id="state_code" class="required form-control" readonly <?php echo $readonly;?> placeholder="State Code" name="state_code" value="<?php echo $rows['state_code']; ?>"/>
		</div>

		<div class="col-md-3">
			<label class="form-label">Bill To<span class="required required_lbl" style="color:red;">*</span></label>
			<textarea name='bill_to' id="bill_to" placeholder="Bill Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control "><?php echo $rows['bill_to'];?></textarea>
		</div>

		<div class="col-md-3">
			<label class="form-label">Ship To<span class="required required_lbl" style="color:red;">*</span></label>
			<textarea name='ship_to' id="ship_to" placeholder="Ship Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control " ><?php echo $rows['ship_to']; ?></textarea>
		</div>

		<div class="col-md-3" >
			<label class="form-label">POS State <span class="required required_lbl" style="color:red;">*</span></label>
			<div id="show_pos">
				
				<select id="pos_state" name="pos_state" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="saleorder_delivery_rowtable();">
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
		</div>

		<div class="col-md-3">
			<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="type" name="type"  onchange="get_deliverychallan(); getlevel();" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">
			<option value="">Select</option>
				
				<option  value="Direct_Sale" <?php if($rows['type']=='Direct_Sale'){ echo 'selected';}else{ echo ' ';} ?> >Direct Sale</option>
				<option  value="Against_delivery" <?php if($rows['type']=='Against_delivery'){ echo 'selected';}else{ echo ' ';} ?> >Against Delivery Challan</option>
			</select>
		</div>

		<div class="col-md-3" id="price_level">
		<?php if($_REQUEST['PTask']=='update') {
			if($rows['pricetype']!='') { ?>
				<label class="form-label">Price Level Type<span class="required required_lbl" style="color:red;">*</span></label>
				<select id="pricetype" name="pricetype" onchange="getapplicable();" class="form-select select2 tdstax_field" data-allow-clear="true">
					<option value="">Select</option>
					<option  value="Applicable" <?php if($rows['pricetype']=='Applicable'){ echo 'selected';}else{ echo ' ';} ?> >Applicable</option>
					<option  value="NotApplicable" <?php if($rows['pricetype']=='NotApplicable'){ echo 'selected';}else{ echo ' ';} ?> >Not Applicable</option>
				</select>
			<?php } ?> 
		<?php } ?>
		</div>

		<div class="col-md-3" id="appllicable">
		<?php if($_REQUEST['PTask']=='update') {
			if($rows['pricelevel']!=''){ ?>
				<label class="form-label">Price Level <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="pricelevel" name="pricelevel" <?php echo $disabled; ?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<?php	
						$data=$utilObj->getMultipleRow("pricelist","1 AND applicable_date = (SELECT MAX(applicable_date) FROM pricelist) group by price_level"); 
						foreach($data as $info){
							if($info["price_level"]==$rows['pricelevel']){echo $select="selected";}else{echo $select="";}
							echo  '<option value="'.$info["price_level"].'" '.$select.'>'.$info["price_level"].'</option>';
						}  
					?>
				</select>
			<?php } ?>
		<?php } ?>
		</div>

		<div class="col-md-3" id="delivery_challan_div">
		<?php if($_REQUEST['PTask']=='update') {
			if($rows['delivery_challan_no']!=''){?>
				<label class="form-label"> Dellivery Challan No. <span class="required required_lbl" style="color:red;">*</span></label>		
				<select id="delivery_challan_no" name="delivery_challan_no" <?php echo $disabled; ?> class="select2 form-select " data-allow-clear="true" onchange="saleorder_delivery_rowtable();show_pos(this.value);">
					<option value=""> Select Dellivery Challan No</option>

					<?php

					$record = $utilObj->getMultipleRow("delivery_challan", "1 group by challan_no");
					foreach ($record as $e_rec) {
						if ($rows['delivery_challan_no'] == $e_rec["id"])
							echo $select = 'selected';
						else
							$select = '';
						echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["challan_no"] . '</option>';
					}
					?> 
				</select>
			<?php } ?>
		<?php } ?>
		</div>

		<h4 class="role-title">Material Details</h4>
		<?php 
			$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
			$state= $account_ledger['mail_state'];
		?>
		
		<div id="table_div" style="overflow: hidden;">
		
		</div>

		<!-- <div class="row text-center" >
			<div id="submit_div" style="margin-bottom:10px;text-align:right;" class="col-md-6">
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
			</div>
			<div class="col-md-6" style="text-align:left;">
				<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
			</div>
		</div> -->

		<div class="col-12 text-center" style="display:block;" id="adjustform">
			<?php 
				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
			?>	
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="adjustentry();"/>
			<?php } ?>
			<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
		
		</div>

		<div id="table_adjust" style="overflow: hidden;">
	

		</div>

		<div class="col-12 text-center" style="display:none;" id="submitform">
			<?php 
				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
			?>	
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
			<?php } ?>

			<?php 
				if($_REQUEST['PTask']=='view') {
			?>
				<?php if((CheckEditMenu())==1) { ?>
					<button type="button" class="add_new btn btn-warning" id="add_new" name="add_new">
						<a href="sale_invoice_form.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
					</button>
				<?php } ?>
			<?php } ?>

			<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
		
		</div>
	
	</form>
</div>

<script>

	function show_pos(value) {

		// var customer =$("#customer").val();
		var type =$("#type").val();

		if(type=='Against_delivery') {
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'show_pos',type:type,value:value},
				success:function(data)
				{
					$('#show_pos').html(data);
					saleorder_delivery_rowtable();
				}
			}); 
		}
	}

	function get_pos(id) {

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_pos',id:id },
			success:function(data) {

				$("#pos_state").html(data);
				saleorder_delivery_rowtable();
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

window.onload=function(){
	$("#date").flatpickr({
		dateFormat: "d-m-Y"
	});
    var PTask = $("#PTask").val();
    if(PTask=="update"||PTask=='view') {
		saleorder_delivery_rowtable();
	
    }
}

function getlevel(){
	var type = $("#type").val();

	if(type!='Direct_Sale'){
		$("#price_level").hide();
	}else{
		$("#price_level").show();
	}
}

function remove_urldata()
{	 
	window.location="sale_invoice_list.php";
} 

function savedata()
{
	var PTask = $("#PTask").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var common_id = $("#common_id").val();
	var cnt = $("#cnt").val();
	var cntd = $("#cntd").val();
	
	var sale_invoiceno = $("#sale_invoiceno").val();
	var date = $("#date").val();
	var voucher_type = $("#voucher_type").val();
	var customer = $("#customer").val();
	var location = $("#location").val();
	var type = $("#type").val();
	var pricetype = $("#pricetype").val();
	var pricelevel = $("#pricelevel").val();
	var delivery_challan_no = $("#delivery_challan_no").val();
	var total_quantity = $("#total_quantity").val();
	
	// var transcost = $("#transcost").val();
	// var transgst = $("#transgst").val();
	// var transamount = $("#transamount").val();
	// var subt = $("#subt").val();
	// var trans = $("#trans").val();
	// var totcst_amt = $("#totcst_amt").val();
	// var totsgst_amt = $("#totsgst_amt").val();
	// var totigst_amt = $("#totigst_amt").val();
	// var tcs_tds = $("#tcs_tds").val();
	// var tcs_tds_percen = $("#tcs_tds_percen").val();
	// var tcs_tds_amt = $("#tcs_tds_amt").val();
	// var other = $("#other").val();
	// var roff = $("#roff").val();
	// var grandtotal = $("#grandtotal").val();
	// var otrnar = $("#otrnar").val();
	// var discount = $("#mdiscount").val();

	// ---------------------------------------------------
	var grandtotal = $("#grandtot").val();
	var totdiscount = $("#totdiscount").val();
	var totaltaxable = $("#totaltaxable").val();
	var cgstledger = $("#cgstledger").val();
	var cgstamt = $("#cgstamt").val();
	var sgstledger = $("#sgstledger").val();
	var sgstamt = $("#sgstamt").val();
	var igstledger = $("#igstledger").val();
	var igstamt = $("#igstamt").val();
	var subtotgst = $("#subtotgst").val();
	var totserviceamt = $("#totserviceamt").val();
	// ---------------------------------------------------

	var bill_to = $("#bill_to").val();
	var ship_to = $("#ship_to").val();
	var state_name = $("#state_name").val();
	var state_code = $("#state_code").val();
	var pos_state = $("#pos_state").val();
	
	var unit_array=[];
	var product_array=[];
	var ledger_array=[];
	var cgst_array=[];
	var sgst_array=[];
	var igst_array=[];
	var orderqty_array=[];
	var qty_array=[];
	var rate_array=[];
	var disc_array=[];
	var taxable_array=[];
	var total_array=[];
		
	for(var i=1;i<=cnt;i++) {

		var unit = $("#unit_"+i).val();	
		var product = $("#product_"+i).val();
		var ledger = $("#ledger_"+i).val();
		var cgst = $("#cgst_"+i).val();	
		var sgst = $("#sgst_"+i).val();	
		var igst = $("#igst_"+i).val();	
		var qty = $("#qty_"+i).val();	
		var orderqty = $("#orderqty_"+i).val();	
		var rate = $("#rate_"+i).val();	
		var disc = $("#disc_"+i).val();	
		var taxable = $("#taxable_"+i).val();	
		var total = $("#total_"+i).val();	
		
		product_array.push(product);
		ledger_array.push(ledger);
		unit_array.push(unit);
		cgst_array.push(cgst);
		sgst_array.push(sgst);
		igst_array.push(igst);
		orderqty_array.push(orderqty);
		qty_array.push(qty);
		rate_array.push(rate);
		disc_array.push(disc);
		taxable_array.push(taxable);
		total_array.push(total);
	}

	var serviceledger_array=[];
	var servicecgst_array=[];
	var servicesgst_array=[];
	var serviceigst_array=[];
	var serviceamt_array=[];

	for(var j=1;j<=cntd;j++) {
		
		var serviceledger = $("#serviceledger_"+j).val();	
		var servicecgst = $("#servicecgst_"+j).val();
		var servicesgst = $("#servicesgst_"+j).val();
		var serviceigst = $("#serviceigst_"+j).val();	
		var serviceamt = $("#serviceamt_"+j).val();
		
		serviceledger_array.push(serviceledger);
		servicecgst_array.push(servicecgst);
		servicesgst_array.push(servicesgst);
		serviceigst_array.push(serviceigst);
		serviceamt_array.push(serviceamt);
	}
	// alert('hiii');

	var cntad = $("#cntad").val();
	var totalvalue = $("#totalvalue").val();
	var type_array=[];
	var billno_array=[];
	var invodate_array=[];
	var totalinvo_array=[];
	var pendingamt_array=[];
	var payamt_array=[];

	for(var i=1; i<=cntad; i++) {

		var typead = $("#type_"+i).val();
		var billno = $("#billno_"+i).val();
		var invodate = $("#invodate_"+i).val();
		var totalinvo = $("#totalinvo_"+i).val();
		var pendingamt = $("#pendingamt_"+i).val();
		var payamt = $("#payamt_"+i).val();
			
		type_array.push(typead);
		billno_array.push(billno);
		invodate_array.push(invodate);
		totalinvo_array.push(totalinvo);
		pendingamt_array.push(pendingamt);
		payamt_array.push(payamt);
	}

	jQuery.ajax({url:'handler/sale_invoice_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,common_id:common_id,cnt:cnt,sale_invoiceno:sale_invoiceno,date:date,voucher_type:voucher_type,type:type,pricetype:pricetype,pricelevel:pricelevel,customer:customer,location:location,delivery_challan_no:delivery_challan_no,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,cgst_array:cgst_array,sgst_array:sgst_array,igst_array:igst_array,orderqty_array:orderqty_array,qty_array:qty_array,rate_array:rate_array,disc_array:disc_array,taxable_array:taxable_array,total_array:total_array,grandtotal:grandtotal,state_name:state_name,state_code:state_code,pos_state:pos_state,ledger_array:ledger_array,bill_to:bill_to,ship_to:ship_to,cntd:cntd,serviceledger_array:serviceledger_array,servicecgst_array:servicecgst_array,servicesgst_array:servicesgst_array,serviceigst_array:serviceigst_array,serviceamt_array:serviceamt_array,totdiscount:totdiscount,totaltaxable:totaltaxable,cgstledger:cgstledger,cgstamt:cgstamt,sgstledger:sgstledger,sgstamt:sgstamt,igstledger:igstledger,igstamt:igstamt,subtotgst:subtotgst,totserviceamt:totserviceamt,type_array:type_array,billno_array:billno_array,invodate_array:invodate_array,totalinvo_array:totalinvo_array,pendingamt_array:pendingamt_array,payamt_array:payamt_array,cntad:cntad,totalvalue:totalvalue },
		success:function(data)
		{	
			if(data!="") {

				// alert(data);			
				window.location='sale_invoice_list.php';
			} else {

				alert('error in handler');
			}
		}
	});
}

function getviewbatch(product,i) {

	// var location = $("#location").val();
	var PTask = $("#PTask").val();
	var common_id = $("#common_id").val();
	var id = $("#id").val();
	var delivery_no = $("#delivery_challan_no").val();

	jQuery.ajax({
		url: 'get_ajax_values.php', type: 'POST',
		data: { Type: 'viewsaleinvoicebatch', product:product, PTask:PTask, delivery_no:delivery_no,common_id:common_id, id:id },
		success: function (data) {

			$('#salesinvoicebatch').html(data);
			$('#saleinvoicebatch').modal('show');
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
		}
	});
}

function getaddbatch(i,PTask,task) {

	var qty =$("#qty_"+i).val();
	var product =$("#product_"+i).val();
	var rate =$("#rate_"+i).val();
	var stock =$("#stock_"+i).val();
	var common_id =$("#common_id").val();
	var location =$("#location").val();
	var id =$("#id").val();

	jQuery.ajax({
		url: 'get_ajax_values.php',
		type: 'POST',
		data: { Type: 'addsaleinvoicebatch',location:location,common_id:common_id,stock:stock,qty:qty,product: product,id:i,PTask:PTask,task:task,id:id,rate:rate },
		success: function (data) {

			$('#salesinvoiceaddbatch').html(data);
			$('#saleinvoiceaddbatch').modal('show');
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
		}
	});
}

function getpricelevel(){
    var customer =$("#customer").val();
    jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'getpricelevel',customer:customer},
		success:function(data)
		{	
			
			if(data == '1'){
				$('#pricelevel').val(data);
			}
			else{
				$("#price_level").html(data);
			}
		}
	}); 
}

function getapplicable(){
	var pricetype =$("#pricetype").val();
	var PTask =$("#PTask").val();
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'getapplicable',pricetype:pricetype},
		success:function(data)
		{	
			$("#appllicable").html(data);
			if(PTask=="update"||PTask=='view'||PTask=='Add'){
				saleorder_delivery_rowtable();
				
			}
		}
	}); 
				
}

function get_sale_ino() {
	
	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_sale_ino',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#sale_invoiceno").val(data);	
			// $(this).next().focus();
		}
	});

}
function  get_deliverychallan(){

	var customer =$("#customer").val();
	// var location =$("#location").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	var type =$("#type").val();
	if(customer==''){
		alert('Please Select customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_deliverychallan',PTask:PTask,id:id,customer:customer,type:type},
		success:function(data)
		{	
		   // alert(data);
			$("#delivery_challan_div").html(data);
			$(".select2").select2();
			
		}
	}); 
}


function saleorder_delivery_rowtable(){
	var delivery_challan_no =$("#delivery_challan_no").val();
	// var customer =$("#customer").val();
	var customer =$("#pos_state").val();
    var type =$("#type").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	var pricetype =$("#pricetype").val();

	// if(customer==''){
	// 	alert('Please Select customer !!!!');
	// 	return false;
	// }

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'saleorder_delivery_rowtable',PTask:PTask,id:id,customer:customer,delivery_challan_no:delivery_challan_no,type:type,pricetype:pricetype},
		success:function(data)
		{	
		    // alert(data);
			$("#table_div").html(data);	
			$(".select2").select2();
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

			getrowgst(elementId);
			
        }
    });
}

function get_unit(this_id)
{	
	var location = $("#location").val();
	var pricelevel = $("#pricelevel").val();
	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_productdata',id:id,product:product,location:location,pricelevel:pricelevel},
		success:function(data)
		{	
			var bdid=data.split("#");
			var meas=bdid[0].split(",");
			jQuery("#unit_"+id).val(bdid[0]);
			jQuery("#igst_"+id).val(bdid[1]);
			jQuery("#cgst_"+id).val(bdid[2]);
			jQuery("#sgst_"+id).val(bdid[3]);
			jQuery("#stock_"+id).val(bdid[4]);

			$(this).next().focus();
		}
	});	
}
	

		function check_batch_type1(id){
			
			var id=id.split("_");
			id=id[1];
			var product = $("#product_"+id).val();
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'check_batch_type2',id:id,product:product},
				success:function(data)
				{	
					$("#divbatch_"+id).html(data);	
					$(this).next().focus();
				}
			});	

		}
		
		function check_qty(i) 
		{
			var quantity = $("#qty_"+i).val();
			var PTask = $("#PTask").val();

			if (quantity == '' || quantity=='0') {
				alert ('please enter quantity first . . . !');

			} else {
				getaddbatch(i,quantity,PTask);
			}
		}

							
function get_totalqty()
{
	//alert("hii");
	var cnt=jQuery("#cnt").val();
	//alert(cnt);
	var grandtotal=0;	
	for(var i=1; i<=cnt;i++)
	{	
		var qty= jQuery("#qty_"+i).val();
		if(qty==''){ qty=0;}
		grandtotal = parseFloat(grandtotal)+parseFloat(qty);
	}
	//alert(grandtotal);
	jQuery("#total_quantity").val(parseFloat(grandtotal).toFixed(2));
	
}

function showgrandtotal1(){
	var total=0;
	var showtotal=0;
	var mdiscount = $("#mdiscount").val();
	var grandtotal = $("#subt").val();
	total = grandtotal*mdiscount/100;
	showtotal = grandtotal-total;
	jQuery("#grandtotal").val(parseFloat(showtotal).toFixed(2));
}

function stock_check()
{
	var cnt=jQuery("#cnt").val();
	var stock_chk=0;	
	for(var i=1; i<=cnt;i++)
	{	
		var qty= jQuery("#qty_"+i).val();
		var stock= jQuery("#stock_"+i).val();
		//alert("qty="+qty+'stock='+stock);
		if(parseFloat(qty)>parseFloat(stock)){ stock_chk++;}
	}
	//alert("chk="+stock_chk);
	if(stock_chk<=0){
		$("#submit_div").html('<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>');	
	}else{
		$("#submit_div").html('<span style="color:red;">Quantity Should Not Gratter Than Stock!!!</span><br>');	
	}
}
/* function Gettotal(id)
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
} */
</script>
<script>

function Gettotgst()
	{
		
			var table = document.getElementById('myTable');
			var rowCount = table.rows.length;
			var count=parseFloat(rowCount-2);
			//var did=rid.split("_");
			//var rid=did[1]; 
			
			var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
			var totcgst=0;var totsgst=0;var totigst=0;
			//alert('count'+count)
			for(var i=1; i<=count;i++)
			{
				var total=jQuery("#taxable_"+i).val();
				var cgst=jQuery("#cgst_"+i).val();
				
				var cgst_amt=parseFloat(total*cgst)/100;
				
				totcgst = parseFloat(totcgst)+parseFloat(cgst_amt);
				//alert(i+'='+totcgst+"<<<"+cgst_amt);
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
			//alert(totcgst+'=>'+totsgst+'=>'+totigst);
			jQuery('#totcst_amt').val(totcgst.toFixed(2));
			jQuery('#totsgst_amt').val(totsgst.toFixed(2));
			jQuery('#totigst_amt').val(totigst.toFixed(2));
			showgrandtotal();
	}
	
	function  Getgst(rid)
	{
		var type = $("#type").val();
		var table = document.getElementById('myTable');
		var rowCount = table.rows.length;
		var count=parseFloat(rowCount-1);
		var did=rid.split("_");
		var rid=did[1]; 
		//alert(rid);
		var val=0;
		var total=0;
		var showtotal=0;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
		var totcgst=0;var totsgst=0;var totigst=0;
		//alert(rid);
		
		var total=jQuery("#taxable_"+rid).val();
		var discount=jQuery("#disc_"+rid).val();

		if(discount==''){discount=0;}
		var total_amt=parseFloat(total*discount)/100;

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
		var sum=(parseFloat(total_amt)+parseFloat(cgst_amt)+parseFloat(sgst_amt)+parseFloat(igst_amt));
			
		showtotal = total-sum;
		if(type=='Direct_Sale'){
		jQuery("#total_"+rid).val(parseFloat(showtotal).toFixed(2));
		}
		//jQuery("#total_"+rid).val(parseFloat(showtotal).toFixed(2));


		var subtotal=0;
		for(var i=1; i<=count;i++)
		{
			if(jQuery("#taxable_"+i).val()!='' && floatRegex.test(jQuery("#taxable_"+i).val()))
				subtotal = parseFloat(subtotal)+parseFloat(jQuery("#taxable_"+i).val());
		}
		if(showtotal<0)
		{
		jQuery("#"+rid).val(val);
		alert('Please Enter valid entry!');
		Gettotal(rid)
		jQuery("#"+rid).focus("");
		}
		//var cnt=jQuery("#cnt").val();
		
		jQuery("#subt").val(subtotal.toFixed(2));
	
	}
	
function Gettotal(rid)
{
		//alert('hiihello');
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
	var disc=jQuery("#disc_"+rid).val();
		
			if(disc==""){ disc=0; }
			if(floatRegex.test(qty) && floatRegex.test(rate)){
			var cal =parseFloat(qty*rate);
			var discamt=(cal*disc)/100;
			total=parseFloat(cal);//-parseFloat(discamt);		
			//alert(total);
			//var taxable_amt=jQuery("#taxable_"+rid).val(total.toFixed(0));
			for(var i=1; i<=count;i++)
			{
				if(jQuery("#total_"+i).val()!='' && floatRegex.test(jQuery("#total_"+i).val()))
					subtotal = parseFloat(subtotal)+parseFloat(jQuery("#total_"+i).val());

			}	
			jQuery("#subt").val(subtotal.toFixed(2));
			 
			} 
			
		//jquery('#total_'+rid).val(grandsum.toFixed(0));

			jQuery("#taxable_"+rid).val(total.toFixed(2));
			//alert(cgst);
			//getRate(rid1);
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
			var disc=jQuery("#disc_"+rid).val();
			if(disc==''){disc=0;}
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
					subtotal = parseFloat(subtotal)+parseFloat(parseFloat(jQuery("#rate_"+i).val()-jQuery("#disc_"+i).val())*parseFloat(qty));


			}	
			jQuery("#subt").val(subtotal.toFixed(2));
			 
			} 
			
			jQuery("#total_"+rid).val(total.toFixed(2));	
			var taxamt = total - disc;
			//alert(taxamt);
			jQuery("#taxable_"+rid).val(taxamt.toFixed(2));
			Gettotgst();
			//Gettotal(rid1);
			showgrandtotal();
	}
	function Gettotal1(rid)
	{
		//alert('hii');
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
		
		//var qty=jQuery("#qty_"+rid).val();
		//var rate=jQuery("#rate_"+rid).val();
		//var disc=jQuery("#disc").val();
		
		
		var grandtotal=jQuery("#subt").val();

	<?php
		if (!empty($rows['disc'])||!empty($rows['cst'])||!empty($rows['excs'])||!empty($rows['TotalVat'])) { 
		?>	
		var subtotalval=jQuery("#subt").val();	
		var disc=jQuery("#disc").val();
		if(disc==''){ disc=0;}
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
		else{ ?>
			
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
		<?php }
		?>
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

	// $("#transamount").val(($("#transcost").val()*$("#transgst").val())/100);
	// $("#trans").val(($("#transcost").val())+(($("#transcost").val()*$("#transgst").val())/100));
	// alert('hi');

}
	
</script>
<script>       
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
		var type=$("#type").val();	
		var pricetype=$("#pricetype").val();
		var state=$("#pos_state").val();
		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
		
		cell1 += "<td style='width:20%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);check_batch_type1(this.id);get_saleledger(this.id,"+state+");get_gstdata(this.id)' >\
			<option value=''>Select</option>\
			<?php
				$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
				foreach($record as $e_rec){	
					echo "<option value='".$e_rec['id']."' >".$e_rec['name']."</option>";
				}
					
			?>
		</select></td>";

		cell1 += "<td style='width:10%'><select id='ledger_"+i+"' name='ledger_"+i+"' class='select2 form-select'>\
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
			cell1 += "<td style='width:5%'><input name='cgst_"+i+"' id='cgst_"+i+"'  class='form-control number' type='text'/></td>";
			cell1 += "<td style='width:5%'><input name='sgst_"+i+"' id='sgst_"+i+"'  class='form-control number' type='text'/></td>";
		}else{
			cell1 += "<td style='width:5%'><input name='igst_"+i+"' id='igst_"+i+"'  class='form-control number' type='text'/></td>";
		}

		cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"' class='form-control number' type='text' onblur='getrate(this.id);' /></td>";
		
		if(type=='Direct_Sale') {

			if(pricetype!='NotApplicable') {

				cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'  readonly class='form-control number' type='text' onkeyup='getrowgst(this.id);gettotgst("+i+");' />\
				<input type='hidden' name='rowcgstamt_"+i+"' id='rowcgstamt_"+i+"' value='' >\
				<input type='hidden' name='rowsgstamt_"+i+"' id='rowsgstamt_"+i+"' value='' >\
				<input type='hidden' name='rowigstamt_"+i+"' id='rowigstamt_"+i+"' value='' ></td>";

				cell1 += "<td style='width:10%'><div id='divbatch_"+i+"'></div></td>";

				cell1 += "<td style='width:10%'><input name='disc_"+i+"' id='disc_"+i+"'  readonly class='form-control number' type='text'/></td>";
			} else {

				cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'   class='form-control number' type='text' onkeyup='getrowgst(this.id);gettotgst("+i+");' />\
				<input type='hidden' name='rowcgstamt_"+i+"' id='rowcgstamt_"+i+"' value='' >\
				<input type='hidden' name='rowsgstamt_"+i+"' id='rowsgstamt_"+i+"' value='' >\
				<input type='hidden' name='rowigstamt_"+i+"' id='rowigstamt_"+i+"' value='' ></td>";

				cell1 += "<td style='width:10%'><div id='divbatch_"+i+"'></div></td>";

				cell1 += "<td style='width:10%'><input name='disc_"+i+"' id='disc_"+i+"'   class='form-control number' type='text'/></td>";
			}
		} else {
			
			cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"' class='form-control number' type='text' onkeyup='getrowgst(this.id);gettotgst("+i+");' />\
			<input type='hidden' name='rowcgstamt_"+i+"' id='rowcgstamt_"+i+"' value='' >\
			<input type='hidden' name='rowsgstamt_"+i+"' id='rowsgstamt_"+i+"' value='' >\
			<input type='hidden' name='rowigstamt_"+i+"' id='rowigstamt_"+i+"' value='' ></td>";
			
			cell1 += "<td style='width:10%'><div id='divbatch_"+i+"'></div></td>";

			cell1 += "<td style='width:10%'><input name='disc_"+i+"' id='disc_"+i+"' class='form-control number' type='text'/></td>";
		}
		cell1 += "<td style='width:10%'><input name='taxable_"+i+"' id='taxable_"+i+"'  readonly class='form-control number' type='text'/></td>";
		
		// cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'   class='form-control number' readonly type='text'/></td>";

		

		cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";

		$("#myTable").append(cell1);
		$("#cnt").val(i);
		$("#particulars_"+i).select2(); 
		$(".select2").select2();
	
		
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

		// alert(id);
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

		$("#totaltaxable").val(totaltaxable);

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='rowcgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst += quant;
            });

			// alert(totcgst);
            $("#cgsttot").val(totcgst);
            // $("#cgstamt").val(totcgst);

            // Assuming batqty1_id elements are input fields
            $("[id^='rowsgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst += quant;
            });

			// alert(totsgst);
            $("#sgsttot").val(totsgst);
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
            $("#igsttot").val(totigst);
            // $("#igstamt").val(totigst);

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);

		gettotalgstrate();

    }

	function getrowgst(this_id) {

		// alert(this_id);
        var id=this_id.split("_");
        id=id[1];

		// alert("Hello");
        var qty = $("#qty_"+id).val();
        var rate = $("#rate_"+id).val();

		var total = parseFloat(rate*qty);
		$("#taxable_"+id).val(total);
		
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

        $("#rowigstamt_"+id).val(igst_amt);
        $("#rowsgstamt_"+id).val(sgst_amt);
        $("#rowcgstamt_"+id).val(cgst_amt);

		gettotgst(id);

    }

	function servicegstsum(this_id) {

		// alert("hi4");
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

        $("#serviceigstamt_"+id).val(igst_amt);
        $("#servicesgstamt_"+id).val(sgst_amt);
        $("#servicecgstamt_"+id).val(cgst_amt);

		// gettotgst(id);

    }

	function servicetotgst(id) {

		// alert("hi3");
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

		$("#totserviceamt").val(totserviceamt);

        // if(state==21) {

            // Assuming batqty1_id elements are input fields
            $("[id^='servicecgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst2 += quant;
            });

			// totcgst = parseFloat(totcgst1)+parseFloat(totcgst2);
            $("#totservicecgst").val(totcgst2);

            // Assuming batqty1_id elements are input fields
            $("[id^='servicesgstamt_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst2 += quant;
            });

			// totsgst = parseFloat(totsgst1)+parseFloat(totsgst2);
            $("#totservicesgst").val(totsgst2);

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
            $("#totserviceigst").val(totigst2);

            // gst_subtot=totigst;
            // $("#gst_subtotal").val(gst_subtot);

        // }

        // grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        // $("#grandtotal").val(grandtotal);
		
		gettotalgstrate();

    }

	function gettotalgstrate() {

		// alert("hi2");
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

		$("#cgstamt").val(totcgst);
		$("#sgstamt").val(totsgst);
		$("#igstamt").val(totigst);

		getsubtotgst();

	}

	function getsubtotgst() {

		// alert("hi1");
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
			$("#subtotgst").val(subtotgst);

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot);

		} else {

			subtotgst = parseFloat(totigst);
			$("#subtotgst").val(subtotgst);

			grandtot = parseFloat(totaltaxable)+parseFloat(totserviceamt)+parseFloat(subtotgst);
			$("#grandtot").val(grandtot);

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

		$("#totaltaxable").val(disctottaxable);

	}

	function adjustentry() {

		var ad = $("#ad").val();
		var id = $("#id").val();
		var PTask = $("#PTask").val();
		var invoicenumber = $("#sale_invoiceno").val();

		var supplier =$("#customer").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'adjust_sale_invoice',id:id,supplier:supplier, invoicenumber:invoicenumber,PTask:PTask },
			success:function(data)
			{	
				jQuery("#table_adjust").html(data);
				$('#adjustform').css('display', 'none');
				$('#submitform').css('display', 'block');
			}
		});	
	}


	function get_bill1(this_id) {

		var cust = $("#customer").val();
		var recordnumber = $("#sale_invoiceno").val();
		var supplier = $("#customer").val();

		var id=this_id.split("_");
        id=id[1];

		var val = $("#type_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_bill', val:val, cust:cust, id:id, recordnumber:recordnumber, supplier:supplier },
			success:function(data) {

				// alert(data);
				$("#voucher_"+id).html(data);
			}
		});
	}

	function delete_row_adjust(rwcnt) {

		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cntad").val();
		if(count>1) {

			var r=confirm("Are you sure!");
			if (r==true) {
				
				$("#row_"+rwcnt).remove();

				for(var k=rwcnt; k<=count; k++) {

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
				jQuery("#cntad").val(parseFloat(count-1)); 
			}
		}
		else {

			alert("Can't remove row Atleast one row is required");
			return false;
		}	 
	}		  		  
			  
	function addRow(tableID) 
	{ 
		var count=$("#cntad").val();	
		var state=$("#state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:0%;text-align:center;'>"+i+"</td>";
	   
		cell1 += "<td style='width:7%' ><select name='type_"+i+"' class='required select2 form-select'  id='type_"+i+"' onchange='get_bill1(this.id);' style=''>\
			<option value=''>Select Type</option>\
			<option value='Advanced'>New Reference</option>\
			<option value='PO'>Against Bill</option>\
		</select></td>";

		cell1 += "<td style='width:8%'><div id='voucher_"+i+"'></div></td>";

		cell1 += "<td style='width:6%'><input name='invodate_"+i+"' id='invodate_"+i+"' readonly class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:8%'><input name='totalinvo_"+i+"' readonly id='totalinvo_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='pendingamt_"+i+"' readonly id='pendingamt_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='payamt_"+i+"' id='payamt_"+i+"' class='form-control required tdalign' type='text' onkeyup='gettotalamt(this.id);' />\
		</td>";
		
		cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row_adjust(this.id);'></i></td>";



		$("#myTable1").append(cell1);
		$("#cntad").val(i);
		// $("#particulars_"+i).select2();
		// $(".select2").select2();
		 
	}

	function getinvo_info(this_id) {

		var cust = $("#customer").val();

		var id=this_id.split("_");
        id=id[1];

		var billno = $("#billno_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getinvo_info1',billno:billno, cust:cust},
			success:function(data)
			{
				var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#invodate_"+id).val(bdid[0]);
                jQuery("#totalinvo_"+id).val(bdid[1]);
                jQuery("#pendingamt_"+id).val(bdid[2]);
			}
		});

	}

	function gettotalamt(this_id) {

		var id=this_id.split("_");
        id=id[1];

		var totaltaxable = 0;

		$("[id^='payamt_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totaltaxable += quant;
		});

		$("#totalvalue").val(totaltaxable.toFixed(2));
	}

</script>
<?php 
    include("footer.php");
?>
