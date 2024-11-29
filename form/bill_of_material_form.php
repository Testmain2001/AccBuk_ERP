
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php
/*  $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from sale_order");
$result=mysqli_fetch_array($getinvno);
$sale_order=$result['pono']+1;  
$date=date('d-m-Y');	 */
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("bill_of_material","id ='".$id."'");
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
          <h3 class="role-title">Bill Of Material</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="bill_of_material_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "bill_of_material"; ?>"/>
			    
			
					<!-- <div class="col-md-4">
						<label class="form-label">BOM Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="BOM_type" name="BOM_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
							<option value="">Select</option>					
							<option  value="production" <?php if($rows['BOM_type']=='production'){ echo 'selected';}else{ echo ' ';} ?> >production</option>
							<option  value="packaging" <?php if($rows['BOM_type']=='packaging'){ echo 'selected';}else{ echo ' ';} ?> >packaging</option>
						</select>
					</div>	 -->
					
					<div class="col-md-4">
						<label class="form-label">product<span class="required required_lbl" style="color:red;">*</span></label>
						<select id="product" name="product" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit_billofmaterial();" style="width:100%;">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","bill_of_material=1 ");
								foreach($record as $e_rec)
								{
									if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</div>

					<div class="col-md-4">
						<label class="form-label">BOM Name <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="bom_name" class="required form-control"  <?php echo $readonly;?> placeholder="Enter BOM Name" name="bom_name" value="<?php echo $rows['bom_name'] ;?>"/>
					</div>
					
					<div class="col-md-4">
						<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
						<div id='unitdiv'>
							<input type="text" style="width:100%;"  class=" form-control  smallinput "  readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $record['unit'];?>"/>

							<!-- <select id="unit" name="unit" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
								<?php 
									echo '<option value="">Select</option>';
									
									$record=$utilObj->getMultipleRow("stock_ledger","bill_of_material=1 ");
									foreach($record as $e_rec)
									{
										if($rows['unit']==$e_rec["unit"]) echo $select='selected'; else $select='';
										echo '<option value="'.$e_rec["unit"].'" '.$select.'>'.$e_rec["unit"] .'</option>';
									}
								?> 
							</select> -->
						</div>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">Quantity <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="qty" class="required form-control"  <?php echo $readonly;?> placeholder="Enter Quantity" name="qty" value="<?php echo $rows['qty'] ;?>"/>
					</div>
				
		  
		
			
		
          <h4 class="role-title">Material Details</h4>
		  <?php 
		 $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
		  $state= $account_ledger['mail_state'];
		?>
		
		
		
        <div id="table_div" style="overflow: hidden;">
		 <input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
			<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					 <?php if($_REQUEST['Task']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
					 <?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				  if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')
					{ 
				      //echo "condi 1";
						 $record5=$utilObj->getMultipleRow("bill_of_material_details","parent_id='".$_REQUEST['id']."'");
					}
					 else
					{ 
						$record5[0]['id']=1;					
					}  
			foreach($record5 as $row_demo)
			{ 
				$i++;
				//echo ">>".$row_demo['product'];
				?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
							<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td  style="width: 20%;">
					    <?php 

                        $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view'){?>
							<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
							<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>"/>
						<?php }else{?>
							<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);product_check(this.id);" style="width:100%;">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 group by name ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
							</select>
						<?php }?>
					</td>
					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>

					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly.$read;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					</td>
						
					<?php if($_REQUEST['Task']!='view') { ?>
						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
			<?php } ?>
					
			</tbody>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
		</table>

		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php if(($_REQUEST['PTask']!='view' )) { ?>			
						<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table> 
		
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

function product_check(rid){
	//alert(rid);
	var did=rid.split("_");
	var rid=did[1];
	var cnt=jQuery("#cnt").val();
	var MaterialID=jQuery("#product_"+rid).val();
	//var Mtype=jQuery("#mtype_"+rid).val();
	jQuery("#qty"+rid).val('0');

	for(var i=1; i<=cnt;i++)
	{
		if(rid!=i){
			if(MaterialID==jQuery("#product_"+i).val())
			{ 	 
				alert('You have already selected this Material make Please Select Other');
				//$('#mtype_'+rid).val(' ');
				//alert(rid);
				//$('#product_'+rid).select2('val', '');
				$("#product_" + rid).val(null).trigger('change');
				//$('#mtype_'+rid).trigger('liszt:updated');
				return false;
			}
		}
	}
}

function get_unit_billofmaterial()
{	

	var product = $("#product").val();
	//alert(product);
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_unit_billofmaterial',product:product},
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
				
			
				jQuery("#qty_"+k).attr('name','qty_'+newId);
				jQuery("#qty_"+k).attr('id','qty_'+newId);
				
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

	var i=parseFloat(count)+parseFloat(1);

	var cell1="<tr id='row_"+i+"'>";

	cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";

	cell1 += "<td style='width:20%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);product_check(this.id);' >\
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


	cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

	$("#myTable").append(cell1);
	$("#cnt").val(i);
	// $("#product_"+i).select2();
	// // $(".select2").select2();

	$("#product_"+i).select2({
		dropdownParent: $('#table_div')
	});
	
}
                
</script>

