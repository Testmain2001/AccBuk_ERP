
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
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
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Physical Stock</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="../stock_transfer_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "physical_stock"; ?>"/>
			    
			
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

function get_locationwise_productstock_forphysical()
{	

	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var location = $("#location").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_locationwise_productstock_forphysical',location:location,id:id,PTask:PTask},
		success:function(data)
		{	
		    //alert(data);
			$("#table_div").html(data);	
		}
	});	
}

function product_check(rid){
	var rid1=rid;
  	var did=rid.split("_");
	rid=did[1];
	
	var product_arraychk=[];
	var product=jQuery("#product_"+rid).val(); 
	
	var count=jQuery("#cnt").val(); 
	
    for(var i=1;i<count;i++){
		if(i!=rid){
		var product1 = $("#product_"+i).val();
		product_arraychk.push(product1);
		}
	}
	//alert(product_arraychk+"--"+product);
	if(product_arraychk.includes(product)==true && product!=null){
		alert('Please Do Not Repeat Product Again!');
		jQuery("#product_"+rid).val(''); 
		return false;
	} else{
		get_unit(rid1);//===call get_unit function
	}
	
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
             get_stock(this_id);	//call get_stock function	
			$(this).next().focus();
		}
	});	
}

function get_stock(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var unit = $("#unit_"+id).val();
	var location = $("#location").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_product_stock',id:id,product:product,unit:unit,location:location},
		success:function(data)
		{	
		//alert(data);
			$("#stock_"+id).val(data);	
			//$(this).next().focus();
		}
	});	
} 
function add_less_stock(rid){
	
  	var did=rid.split("_");
	rid=did[1];
	//alert(rid);
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
	//var state=$("#state").val();	
	// alert(state);
	var i=parseFloat(count)+parseFloat(1);

	var cell1="<tr id='row_"+i+"'>";
	
	cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
	
	cell1 += "<td style='width:20%' ><select name='product_"+i+"'  onchange='product_check(this.id);'  class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
						<option value=''>Select</option>\
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
	

	cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

	$("#myTable").append(cell1);
	$("#cnt").val(i);
	$("#particulars_"+i).select2(); 
	
		
}
                
</script>

