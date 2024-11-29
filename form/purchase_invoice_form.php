
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true" style="width:100% !IMPORTANT;">
<?php

// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice");
// $result=mysqli_fetch_array($getinvno);
// $Invoicenumber=$result['pono']+1;


$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("purchase_invoice","id ='".$id."'");
    $Invoicenumber=$rows['pur_invoice_code'];	   
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
?>

<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Purchase Invoice</h3>
          
        </div>
        <!-- Add role form -->
		
		<form id="" data-parsley-validate class="row g-3" action="purchase_requisition_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "purchase_invoice"; ?>"/>
			    
			<div class="col-md-4">
				<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_purchase_invoice_code();">
				<option value="">Select</option>
					<?php	
						$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=2 group by id"); 
						foreach($data as $info){
							if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
							echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
						}  
					?>
				</select>
			</div>

			<div class="col-md-4">
				<label class="form-label">Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="invoicenumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Invoice No." name="invoicenumber" value="<?php echo $Invoicenumber;?>"/>
			</div>

			<div class="col-md-4">
				<label class="form-label">Invoice Date <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
			</div>
			
			<div class="col-md-4">
				<label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="supplier" name="supplier"  onchange="find_state();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
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
			
			<div class="col-md-4">
				<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="location" name="location" onchange="chk_type();purchaseorder_rowtable_invoice();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
			</div>	
			
			<div class="col-md-4">
				<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="type" name="type"  onchange="chk_type();" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">
					<option value="">Select</option>
					<option  value="Direct_Purchase" <?php if($rows['type']=='Direct_Purchase'){ echo 'selected';}else{ echo ' ';} ?> >Direct Purchase</option>
					<option  value="Against_Purchaseorder" <?php if($rows['type']=='Against_Purchaseorder'){ echo 'selected';}else{ echo ' ';} ?> >Against Purchase Order</option>
				</select>
			</div>
			<div class="col-md-4" id="purchase_order_div">
			</div>
		  
		
			
		
			<h4 class="role-title">Material Details</h4>
			<?php 
				$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
				$state= $account_ledger['mail_state'];
			?>
			<input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
			<div id="table_div" style="overflow: hidden;">
		
	
			</div>
		
          	<div class="col-12 text-center">
				<?php 
					if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
				?>	
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
function find_state(){
	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'find_state',supplier:supplier},
			success:function(data)
			{	
			//alert(data);
				$("#state").val(data);	
			}
		}); 
}

function purchaseorder_rowtable_invoice()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var purchaseorder_no =$("#purchaseorder_no").val();
	var location = $("#location").val();
	if(location==''){
		alert('Please Select Location !!!!');
		return false;
	}
	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'purchaseorder_rowtable_invoice',type:type,id:id,PTask:PTask,purchaseorder_no:purchaseorder_no,supplier:supplier,location:location},
		success:function(data)
		{	
			$("#table_div").html(data);	
		}
	}); 
			
}
function chk_type()
{	

    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type = $("#type").val();
	var location = $("#location").val();
	if(type=="Against_Purchaseorder"){
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_purchaseorderno_invoice',type:type,id:id,PTask:PTask,location:location},
			success:function(data)
			{	
				$("#purchase_order_div").html(data);	
				var purchaseorder_no =$("#purchaseorder_no").val();
				//alert("rr"+requisition_no);
				if(PTask=='update'&&purchaseorder_no!=null || PTask=='view'){
					purchaseorder_rowtable_invoice();
				}
			}
		});	
	}else if(type=="Direct_Purchase"){
		
		$("#purchase_order_div").html(" ");	
		var purchaseorder_no =$("#purchaseorder_no").val();
		if((purchaseorder_no==null&&PTask!='')|| PTask=='view'){
			purchaseorder_rowtable_invoice();
		}
	}		
}
function get_unit(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	//alert(id);
	//var cnt = $("#cnt").val();
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

// ---------------------------------------------------------------------------------------------------------

function get_purchase_invoice_code() {

var voucher_type = $("#voucher_type").val();

jQuery.ajax({url:'get_ajax_values.php', type:'POST',
	data: { Type:'get_purchase_invoice_code',voucher_type:voucher_type},
	success:function(data)
	{	
		//alert(data);
		$("#invoicenumber").val(data);	
		// $(this).next().focus();
	}
});

}

// --------------------------------------------------------------------------------------------------------
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
					
					jQuery("#disc_"+k).attr('name','disc_'+newId);
        			jQuery("#disc_"+k).attr('id','disc_'+newId);
					
					jQuery("#taxable_"+k).attr('name','taxable_'+newId);
        			jQuery("#taxable_"+k).attr('id','taxable_'+newId);
					
					jQuery("#total_"+k).attr('name','total_'+newId);
        			jQuery("#total_"+k).attr('id','total_'+newId);
					
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
	
	cell1 += "<td style='width:20%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
						<option value=''>Select</option>\
						<?php
							$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
							foreach($record as $e_rec){	
							echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
							}
								
						?>
						</select></td>";

	cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  readonly class='form-control required' type='text'/></div></td>";
	if(state==21){
	cell1 += "<td style='width:5%'><input name='cgst_"+i+"' id='cgst_"+i+"'   class='form-control number' type='text'/></td>";
	cell1 += "<td style='width:5%'><input name='sgst_"+i+"' id='sgst_"+i+"'   class='form-control number' type='text'/></td>";
	}else{
	cell1 += "<td style='width:5%'><input name='igst_"+i+"' id='igst_"+i+"'   class='form-control number' type='text'/></td>";
	}
	cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"' onkeyup='Gettotal(this.id);' onblur='Gettotal(this.id);'   class='form-control number' type='text'/></td>";
	
	cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'   onkeyup='Gettotal(this.id);' onblur='Gettotal(this.id);'  class='form-control number' type='text'/></td>";

	cell1 += "<td style='width:10%'><input name='disc_"+i+"' id='disc_"+i+"'    onkeyup='Gettotal(this.id);' onblur='Gettotal(this.id);'  class='form-control number' type='text' value='0' /></td>";
	cell1 += "<td style='width:10%'><input name='taxable_"+i+"' id='taxable_"+i+"'  onkeyup='showgrandtotal();' onblur='showgrandtotal();' class='form-control number' type='text' value='0' /></td>";
	cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'  onchange='Getgst(this.id);showgrandtotal();' onkeyup='showgrandtotal();' onblur='showgrandtotal();' class='form-control number' type='text' value='0' /></td>";

	cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

	$("#myTable").append(cell1);
	$("#cnt").val(i);
	$("#particulars_"+i).select2(); 
	
		
	}

	
                
</script>
<script>

function Gettotgst()
	{
			var table = document.getElementById('myTable');
			var rowCount = table.rows.length;
			var count=parseFloat(rowCount-1);
			//var did=rid.split("_");
			//var rid=did[1]; 
			
			var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
			var totcgst=0;var totsgst=0;var totigst=0;
			
			for(var i=1; i<=count;i++)
			{
				var total=jQuery("#taxable_"+i).val();

				var cgst=jQuery("#cgst_"+i).val();
				
				var cgst_amt=parseFloat(total*cgst)/100;
				
				totcgst = parseFloat(totcgst)+parseFloat(cgst_amt);
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
			//alert(totcgst+'=>'+totsgst+'=>'+totigst);
			jQuery('#totcst_amt').val(totcgst.toFixed(2));
			jQuery('#totsgst_amt').val(totsgst.toFixed(2));
			jQuery('#totigst_amt').val(totigst.toFixed(2));
			showgrandtotal();
	}
	
	function  Getgst(rid)
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
	jQuery("#total_"+rid).val(grandsum.toFixed(2));

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
	var disc=jQuery("#disc_"+rid).val();
		
			if(disc==""){ disc=0; }
			if(floatRegex.test(qty) && floatRegex.test(rate)){
			var cal =parseFloat(qty*rate);
			var discamt=(cal*disc)/100;
			total=parseFloat(cal)-parseFloat(discamt);	
			//alert(total);
			//var taxable_amt=jQuery("#taxable_"+rid).val(total.toFixed(0));
			for(var i=1; i<=count;i++)
			{
				if(jQuery("#taxable_"+i).val()!='' && floatRegex.test(jQuery("#taxable_"+i).val()))
					subtotal = parseFloat(subtotal)+parseFloat(jQuery("#taxable_"+i).val());


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

//$("#transamount").val(($("#transcost").val()*$("#transgst").val())/100);
//$("#trans").val(($("#transcost").val())+(($("#transcost").val()*$("#transgst").val())/100));
//alert('hi');

}
	
</script>

