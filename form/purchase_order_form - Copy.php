
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php

$getinvno= mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order");
$result=mysqli_fetch_array($getinvno);
$order_no=$result['pono']+1; 	
$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view')
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("purchase_order","id ='".$id."'");

    $order_no=$rows['order_no'];	
    $requisition_no=$rows['requisition_no'];	
	$date=date('d-m-Y',strtotime($rows['date']));
	
	if($requisition_no!='')
	{		
		if($readonly!="readonly"){
			$read="readonly";
		}
	} else {
		
		$read=" ";
	}
} 
?>
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Purchase Order</h3>
          
        </div>
        <!-- Add role form -->
		
          <form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "purchase_order"; ?>"/>
			    
			
					
					
					<div class="col-md-6">
					<label class="form-label">Order No. <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="order_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="order_no" value="<?php echo $order_no;?>"/>
					</div>

					<div class="col-md-6">
					<label class="form-label">Order Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>

					<div class="col-md-6">
					<label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"  onchange="find_state();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
							foreach($data as $info){
								if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
					</div>
					<div class="col-md-6">
						<label class="form-label" for="formValidationSelect2"> Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="type" name="type"  onchange="chk_type();" <?php  echo $disabled ;?> class="form-select select2 tdstax_field" data-allow-clear="true">
						<option value="">Select</option>
							
									<option  value="Direct_Purchase" <?php if($rows['type']=='Direct_Purchase'){ echo 'selected';}else{ echo ' ';} ?> >Direct Purchase</option>
									<option  value="Against_Requisition" <?php if($rows['type']=='Against_Requisition'){ echo 'selected';}else{ echo ' ';} ?> >Against Requisition</option>
						</select>
					</div>
					<div class="col-md-6" id="requisition_order_div">
					</div>
		  
		
			
		
          <h4 class="role-title">Material Details</h4>
		  <?php 
		 $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
		  $state= $account_ledger['mail_state'];
		?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
        <div id="table_div" style="overflow: hidden;">
		
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<?php if( $state==21){?>
					<th style="width: 5%;text-align:center;">CGST </th>
					<th style="width: 5%;text-align:center;">SGST </th>
					<?php }else{?>
					<th style="width: 5%;text-align:center;">IGST </th>
					<?php }?>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Rate <span class="required required_lbl" style="color:red;">*</span></th>
						 <?php if($_REQUEST['PTask']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
					 <?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				  
				 if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
					{ 
				
						 $record5=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$_REQUEST['id']."'");
					}
					else
					{
						echo "hello";
						$record5[0]['id']=1;					
					}  
					foreach($record5 as $row_demo)
					{ 
						$i++;
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 20%;">
						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?>  
						</select>
					</td>
					<td style="width: 10%;">
					<div id='unitdiv_<?php echo $i;?>'>
						<input type="text" id="unit_<?php echo $i;?>" class=" form-control form-p required"  readonly <?php echo $readonly;echo $read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
					</div>
					</td>
					<?php if( $state==21){?>
					<td style="width: 5%;">
					 <input type="text" id="cgst_<?php echo $i;?>" class=" form-control form-p number"  <?php echo $readonly;?> name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
					 </td>
					 
					 <td style="width: 5%;">
					 <input type="text" id="sgst_<?php echo $i;?>" class=" form-control form-p number"  <?php echo $readonly;?> name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
					 </td>
					<?php }else{ ?>
					 <td style="width: 5%;">
					 <input type="text" id="igst_<?php echo $i;?>" class=" form-control form-p number"  <?php echo $readonly;?> name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
					 </td>
					<?php }?>
					 <td style="width: 10%;">
					 <input type="text" id="qty_<?php echo $i;?>" class="number form-control form-p"  <?php //echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					 </td>
					 
					 <td style="width: 10%;">
					 <input type="text" id="rate_<?php echo $i;?>" class="number form-control form-p"  <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>"/>
					 </td>
					 <?php if($_REQUEST['PTask']!='view'){?>
							<td style='width:2%'>
									<i class="bx bx-trash me-1"  id='deleteRow' style="cursor: pointer;" onclick="delete_row('<?php echo $i ;?>');"></i>
							</td>
					 <?php } ?>
				</tr>
			<?php } ?>
					<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
		</table>
		
		
		 <table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
                   <td>
						<?php 
						
						if($_REQUEST['PTask']!='view' && $requisition_no==''){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php  } ?> 
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

function requisition_rowtable()
{	

  
   var PTask = $("#PTask").val();
	var id = $("#id").val();
	var type =$("#type").val();
	var requisition_no =$("#requisition_no").val();
	var supplier =$("#supplier").val();
	if(supplier==''){
		alert('Please Select Supplier !!!!');
		return false;
	}
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'requisition_rowtable',type:type,id:id,PTask:PTask,requisition_no:requisition_no,supplier:supplier},
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
	if(type=="Against_Requisition"){
		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'chk_requisitionno_type',type:type,id:id,PTask:PTask},
			success:function(data)
			{	
				$("#requisition_order_div").html(data);	
				var requisition_no =$("#requisition_no").val();
				if(PTask=='update'&&requisition_no!=null){
					requisition_rowtable();
				}
			}
		});	
	}else if(type=="Direct_Purchase"){
		
		$("#requisition_order_div").html(" ");	
		var requisition_no =$("#requisition_no").val();
		if(requisition_no==null&&PTask!=''){
			requisition_rowtable();
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
                cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number' type='text'/></td>";
				
                cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'   class='form-control number' type='text'/></td>";
			
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>

