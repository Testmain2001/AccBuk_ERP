
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php

// $getbatchno=mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from packaging");
// $result=mysqli_fetch_array($getbatchno);
// $batch_no=$result['pono']+1;  

$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("packaging","id ='".$id."'");
	$batch_no=$rows['pack_code'];
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
          <h3 class="role-title">Packaging</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="../packaging_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "packaging"; ?>"/>
			    
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
					<select id="product" name="product" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getunit_forpackaging();" style="width:100%;">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","bill_of_material=1 AND id in(select product from bill_of_material ) ");
								foreach($record as $e_rec)
								{
									if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</div>	
					
					<div class="col-md-4">
					<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
						<div id='unitdiv'>
								<input type="text" style="width:100%;"  class=" form-control  smallinput " onchange="get_billofmaterial_rowtable_forpackaging();" readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $rows['unit'];?>"/>
					</div>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">Quantity <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="qty" class="required form-control"  onchange="get_billofmaterial_rowtable_forpackaging();"onblur="get_billofmaterial_rowtable_forpackaging();"onkeyup="get_billofmaterial_rowtable_forpackaging();" <?php echo $readonly;?> placeholder="Enter Quantity" name="qty" value="<?php echo $rows['qty'] ;?>"/>
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
			var unit=$("#unit").val();
			var location=$("#location").val();
			var qty=$("#qty").val();
			var id=$("#id").val();
			var PTask=$("#PTask").val();
			//alert(mtype);
			//alert(product+"="+mtype+"="+qty+"="+id);
			jQuery.ajax({url:'get_ajax_values.php',
			type:'POST',
			
			data: { Type:'get_billofmaterial_rowtable_forpackaging',product:product,unit:unit,qty:qty,id:id,PTask:PTask,location:location},
			
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
			  
			  function addRow(tableID) 
			  { 
				var count=$("#cnt").val();	
				
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
				
                cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   onkeyup='Gettotal(id);' onchange='Gettotal(id);' class='form-control number' type='text'/></td>";
				
             
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			} */
                
</script>

