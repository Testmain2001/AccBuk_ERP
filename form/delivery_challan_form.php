
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php
 $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(challan_no) AS pono from delivery_challan");
$result=mysqli_fetch_array($getinvno);
$challan_no=$result['pono']+1;  
$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("delivery_challan","id ='".$id."'");
    $challan_no=$rows['challan_no'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	//$grandtotal=$rows['grandtotal'];
	
	
} else{
	$rows=null;
}

?>
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Delivery Challan</h3>
          
        </div>
        <!-- Add role form -->
		
		<form id="" data-parsley-validate class="row g-3" action="../sale_order_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "delivery_challan"; ?>"/>
			    
			
					<div class="col-md-4">
					<label class="form-label">Challan No. <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="challan_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="challan_no" value="<?php echo $challan_no;?>"/>
					</div>

					<div class="col-md-4">
					<label class="form-label"> Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">Customer<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"  onchange="get_saleorder();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
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
					<div class="col-md-4" id="sale_order_div">
					
					</div>
				
		  
		
			
		
          <h4 class="role-title">Material Details</h4>
		  <?php 
		 $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
		  $state= $account_ledger['mail_state'];
		?>
		
        <div id="table_div" style="overflow: hidden;">
		
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
function  get_saleorder(){
	//alert('hii');
	var customer =$("#customer").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	if(customer==''){
		alert('Please Select customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_saleorder',PTask:PTask,id:id,customer:customer},
		success:function(data)
		{	
		   // alert(data);
			$("#sale_order_div").html(data);	
			if(PTask=="update"||PTask=='view'){
				saleorder_rowtable();
				
			}
		}
	}); 
}

function saleorder_rowtable(){
 
	var saleorder_no =$("#saleorder_no").val();
	// alert(saleorder_no);
	var customer =$("#customer").val();
	var PTask = $("#PTask").val();
	var id =$("#id").val();
	if(customer==''){
		alert('Please Select customer !!!!');
		return false;
	}
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'saleorder_rowtable',PTask:PTask,id:id,customer:customer,saleorder_no:saleorder_no},
		success:function(data)
		{	
		    //alert(data);
			$("#table_div").html(data);	
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
	
function get_totalqty()
{
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
		$("#submit_div").html('<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>');	
	}else{
		$("#submit_div").html('<span style="color:red;">Quantity Should Not Gratter Than Stock!!!</span><br>');	
	}
}
</script>
<script>
/*               
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
				var state=$("#state").val();	
                 alert(state);
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
                cell1 += "<td style='width:5%'><input name='cgst_"+i+"' id='cgst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
                cell1 += "<td style='width:5%'><input name='sgst_"+i+"' id='sgst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'   class='form-control number' type='text'/></td>";
				}else{
                cell1 += "<td style='width:5%'><input name='igst_"+i+"' id='igst_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
				}
                cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   onkeyup='Gettotal(id);' onchange='Gettotal(id);' class='form-control number' type='text'/></td>";
				
                cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'  onkeyup='Gettotal(id);' onchange='Gettotal(id);'  class='form-control number' type='text'/></td>";
                
				cell1 += "<td style='width:10%'><input name='total_"+i+"' id='total_"+i+"'   class='form-control number' type='text'/></td>";
			
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  } */
                
</script>

