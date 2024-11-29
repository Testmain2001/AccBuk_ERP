
<!-- Add Role Modal -->

<div class="container-xxl flex-grow-1 container-p-y " style="display:none" id="u_form">
            

			<div class="row form-validate">
			  <!-- FormValidation -->
			  <div class="col-12">
				<div class="card ">
				  <div class="card-body " >
        <!-- Add role form -->
		
          <form id="" data-parsley-validate class="row g-3" action="sale_return_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "sale_return"; ?>"/>
			    
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_sreturn_code();">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=4 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>

					<div class="col-md-4">
						<label class="form-label">Record No. <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $recordnumber;?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Return Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>

						
					
					<div class="col-md-4">
					<label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"  onchange="find_state(); get_saleinvoice();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
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
					<div class="col-md-4">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" onchange="get_saleinvoice();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
					<div class="col-md-4" id="sale_invoice_div">
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
			if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
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
<!--/ Add Role Modal -->
<script>

window.onload=function(){
	$("#date").flatpickr({
	dateFormat: "d-m-Y"
	});
}

function get_sreturn_code() {
	
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_sreturn_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#recordnumber").val(data);	
			// $(this).next().focus();
		}
	});

}

function find_state(){
	var customer =$("#customer").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'find_state',customer:customer},
			success:function(data)
			{	
			//alert(data);
				$("#state").val(data);	
			}
		}); 
}

function get_saleinvoice()
{	
   // alert('hii');
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var customer =$("#customer").val();
	var location =$("#location").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_saleinvoice',id:id,PTask:PTask,customer:customer,location:location},
		success:function(data)
		{	
			$("#sale_invoice_div").html(data);	
			if(PTask=='update'||PTask=='view'||PTask=='Add'){
				saleinvoice_forsalereturn_rowtable();
			}
		}
	}); 
			
}

function saleinvoice_forsalereturn_rowtable()
{	
    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var sale_invoice_no =$("#sale_invoice_no").val();
	var customer =$("#customer").val();
	if(customer==''){
		alert('Please Select Customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'saleinvoice_forsalereturn_rowtable',type:type,id:id,PTask:PTask,sale_invoice_no:sale_invoice_no,customer:customer},
		success:function(data)
		{	
			$("#table_div").html(data);	
		}
	}); 
			
} 
/* function chk_type()
{	

    var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type = $("#type").val();
	if(type=="Against_Purchaseorder"){
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_purchaseorderno_invoice',type:type,id:id,PTask:PTask},
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
} */
</script>
<script>
              
	/* function delete_row(rwcnt)
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
					
					jQuery("#disc_"+k).attr('name','disc_'+newId);
        			jQuery("#disc_"+k).attr('id','disc_'+newId);
					
					jQuery("#taxable_"+k).attr('name','taxable_'+newId);
        			jQuery("#taxable_"+k).attr('id','taxable_'+newId);
					
					jQuery("#total_"+k).attr('name','total_'+newId);
        			jQuery("#total_"+k).attr('id','total_'+newId);
					
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
			
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                 */
</script>
<script>
 	function Gettotal(rid)
	{
     var table = document.getElementById('myTable');
     var rowCount = table.rows.length;
    var count=parseFloat(rowCount-1);
 	var did=rid.split("_");
	var rid=did[1]; 
	var val=0;
	var total=0;
	var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	var qty=jQuery("#qty_"+rid).val();
	var rate=jQuery("#rate_"+rid).val();
   // var vat=jQuery("#vat_"+rid).val();
	
	 
	 
	  
	 
    var rejct=jQuery("#rejectedqty_"+rid).val();
    
    if (parseFloat(qty)<parseFloat(rejct))
    {
    	$('#rejectedqty_'+rid).val("");
    	alert("Rejected Quantity is more than Quantity");
    	return false;
    }
    
	if(floatRegex.test(rejct) && floatRegex.test(rate)){

	var cal =parseFloat(rejct*rate);
    
    total=parseFloat(cal);	
    
	// cgst_per=total*tax_cgst/100;
	
	// $(".exc").val(cgst_per);
	
	//alert(tax_igst);
	
	} 
	
	jQuery("#total_"+rid).val(total.toFixed(2));
	
	if(total<0)
	{
		alert('hii');
	jQuery("#"+rid).val(val);
	alert('Please Enter valid entry!');
	//Gettotal(rid);
	jQuery("#"+rid).focus("");
	}
	//var cnt=jQuery("#cnt").val();
	var subtotal=0;        
    var Grandvat=0;
	var cgst_per=0;
	var sgst_per=0;
	var igst_per=0;
	
	for(var i=1; i<=count;i++)
	{
		var	cgst=parseFloat(jQuery("#cgst_"+i).val());
		var sgst=parseFloat(jQuery("#sgst_"+i).val());
		var igst=parseFloat(jQuery("#igst_"+i).val());
		
		//alert(cgst+'=='+sgst+'=='+igst);
		if(jQuery("#total_"+i).val()!='' && floatRegex.test(jQuery("#total_"+i).val()))
			
		    subtotal = parseFloat(subtotal)+parseFloat(jQuery("#total_"+i).val());
            cgst_per = parseFloat(cgst_per)+ parseFloat((jQuery("#total_"+i).val()* cgst)/100);
		    sgst_per = parseFloat(sgst_per)+ parseFloat((jQuery("#total_"+i).val()* sgst)/100);
		    igst_per = parseFloat(igst_per)+ parseFloat((jQuery("#total_"+i).val()* igst)/100);
         		 
        	
	}
	
	if(subtotal==''){ subtotal=0;}
	if(cgst_per==''){ cgst_per=0;}
	if(sgst_per==''){ sgst_per=0;}
	if(igst_per==''){ igst_per=0;}
	jQuery("#subt").val(subtotal.toFixed(2));
	jQuery("#totcst_amt").val(cgst_per.toFixed(2));
	jQuery("#totsgst_amt").val(sgst_per.toFixed(2));
	jQuery("#totigst_amt").val(igst_per.toFixed(2));
	
	
	showgrandtotal();
	}
	
	function showgrandtotal()
	{
	//alert('hii');
	var table = document.getElementById('myTable');
     var rowCount = table.rows.length;
    var count=parseFloat(rowCount-1);
	var finaltotal=0;
	var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	var regex=/^-?\d+(\.\d+)?$/;
	var grandtotal=0;
	
	var total_vat=0;
	var total_g=0;
	var subt=jQuery("#subt").val();
	grandtotal = parseFloat(subt);
	
	var disc=jQuery("#disc").val();
	if(disc==''){ disc=0;}
	if(floatRegex.test(disc)){
		var discval=(subt*disc)/100;
			grandtotal = parseFloat(grandtotal)-parseFloat(discval);
	}
	var exc=jQuery("#totcst_amt").val();
	if(exc==''){ exc=0;}
	if(floatRegex.test(exc)){
		//var excval=(grandtotal+exc);
			grandtotal = parseFloat(grandtotal)+parseFloat(exc);
	}
	
	var vatval=jQuery("#totsgst_amt").val();
	if(vatval==''){ vatval=0;}
	if(floatRegex.test(vatval)){
		//var vatvalamt=(grandtotal+vatval);
			grandtotal = parseFloat(grandtotal)+parseFloat(vatval);
	}
	
	var cst=jQuery("#totigst_amt").val();
	if(cst==''){ cst=0;}
	if(floatRegex.test(cst)){
	//	var cstval=(grandtotal+cst);
			grandtotal = parseFloat(grandtotal)+parseFloat(cst);
	}
	
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
		
			
	jQuery("#grandtotal").val(grandtotal.toFixed(2));
    
	}


		function tran()
			{
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

