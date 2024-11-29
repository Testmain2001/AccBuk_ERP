
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php
$getrecordno=mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from  stock_journal");
$result=mysqli_fetch_array($getrecordno);
$record_no=$result['pono']+1;  
$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow(" stock_journal","id ='".$id."'");
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
          <h3 class="role-title"> Stock Journal</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="../stock_transfer_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "stock_journal"; ?>"/>
			    
			
					<div class="col-md-4">
					<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="record_no" value="<?php echo $record_no;?>"/>
					</div>

					<div class="col-md-4">
					<label class="form-label">Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
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
				
		
        <h4 class="role-title"> Stock Journal Of Material Details </h4>
        <div id="table_div" style="overflow: hidden;">
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width:2%;text-align:center;">Type</th> 
					<th style="width: 20%;text-align:center;">Product<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit</th>
					<th style="width: 10%;text-align:center;">Location</th>
					<th style="width:10%;text-align:center;">Stock</th>
					<th style="width:10%;text-align:center;">Quantity</th>
					<th style="width:10%;text-align:center;">Rate</th>
					<th style="width:10%;text-align:center;">Amount</th>
					<?php if($_REQUEST['PTask']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ 
				    $record5=$utilObj->getMultipleRow("stock_journal_details"," parent_id='".$_REQUEST['id']."' order by id  ASC");
				}else{
					$record5[0]['id']=1;						
				
				}
					
					
					
			foreach($record5 as $row_demo)
			{ 
				$i++;
				?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
							<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td  style="width: 20%;">
					    <?php 
/* 
                        $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view'){?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
						<?php  }else{ */?>
						<select id="type_<?php echo $i;?>" name="type_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" style="width:100%;">	
							<option value="">Select Type</option>
							<option value="consumption" <?php if($row_demo['type']=="consumption"){ echo "selected";}else{ echo "";}  ?>>Consumption</option>
							<option value="production" <?php if($row_demo['type']=="production"){ echo "selected";}else{ echo "";}  ?>>Production</option>
						</select>
						<?php //} ?>
					</td>
					<td  style="width: 20%;">
					    <?php 

                        /* $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view'){?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
						<?php  }else{ */?>
						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onChange="product_check(this.id);" style="width:100%;">	
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
						<?php //} ?>
					</td>
					<td style="width: 10%;">
					<div id='unitdiv_<?php echo $i;?>'>
						<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
					</div>
					</td>
					<td  style="width: 20%;">
					    <?php 

                        /* $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view'){?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
						<?php  }else{ */?>
							<select id="location_<?php echo $i;?>" name="location_<?php echo $i;?>"  onchange="get_stock(this.id);get_qty(this.id);" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("location","id!='".$loaction."' ");
								foreach($record as $e_rec)
								{
									if($row_demo['location']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
						<?php //} ?>
					</td>
					 <td style="width: 10%;">
					 <?php 
					 
                  	 $tostock=getstock($row_demo['product'],$row_demo['unit'],date('Y-m-d'),$_REQUEST['id'],$row_demo['location']); ?>
					 <input type="text"  id="stock_<?php echo $i;?>"  <?php echo $readonly;?> readonly class=" form-control number"  name="stock_<?php echo $i;?>"     value="<?php echo $tostock;?>"/>
					 </td>

					 <td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i;?>"  class=" form-control required"   <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					 </td>
					 
					 <td style="width: 10%;">
							<input type="text" id="rate_<?php echo $i;?>"   class=" form-control required"  onChange="get_amount(this.id);"     <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>"/>
					 </td> 
					 <td style="width: 10%;">
							<input type="text" id="amount_<?php echo $i;?>"   class=" form-control required"   <?php echo $readonly;?> name="amount_<?php echo $i;?>" value="<?php echo $row_demo['amount'];?>"/>
					 </td>
					 <?php if($_REQUEST['PTask']!='view'){?>
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
						<?php 
					
						 if( $_REQUEST['PTask']!='view'){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php  }  ?> 
				</td>			
			</tr>
		</table> 
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
	//alert('hii');
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
             get_stock(this_id);	//call 	 get_stock function	
             get_qty(this_id);	//call 	 get_qty function	
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
	var location = $("#location_"+id).val();
	jQuery.ajax({
		
		url:'get_ajax_values.php', 
		type:'POST',
		data: { Type:'get_product_stock',id:id,product:product,unit:unit,location:location},
		success:function(data)
			{	
			//alert(data);
				$("#stock_"+id).val(data);	
				//$(this).next().focus();
			}
	});	
} 
function get_qty(this_id)
{	

	var id=this_id.split("_");
	id=id[1];
	var product = $("#product_"+id).val();
	var unit = $("#unit_"+id).val();
	var location = $("#location_"+id).val();
	jQuery.ajax({
		
		url:'get_ajax_values.php', 
		type:'POST',
		data: { Type:'get_qty_from_purchaseinvoice',id:id,product:product,unit:unit,location:location},
		success:function(data)
			{	
			//alert(data);
				$("#qty_"+id).val(data);	
				//$(this).next().focus();
			}
	});	
} 

function get_amount(this_id)
{	
  
	var id=this_id.split("_");
	id=id[1];
	
	var qty = $("#qty_"+id).val();
	var rate = $("#rate_"+id).val();
	var Amount=parseFloat(qty)+parseFloat(rate);
     $("#amount_"+id).val(Amount);
	
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
					
        			jQuery("#type_"+k).attr('name','type_'+newId);
        			jQuery("#type_"+k).attr('id','type_'+newId);
					
        			jQuery("#product_"+k).attr('name','product_'+newId);
        			jQuery("#product_"+k).attr('id','product_'+newId);
        			
					
        			jQuery("#unitdiv_"+k).attr('id','unitdiv_'+newId);
					
					jQuery("#unit_"+k).attr('name','unit_'+newId);
        			jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					jQuery("#location_"+k).attr('name','location_'+newId);
        			jQuery("#location_"+k).attr('id','location_'+newId);
					
					jQuery("#stock_"+k).attr('name','stock_'+newId);
        			jQuery("#stock_"+k).attr('id','stock_'+newId);
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
        			jQuery("#qty_"+k).attr('id','qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
        			jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#amount_"+k).attr('name','amount_'+newId);
        			jQuery("#amount_"+k).attr('id','amount_'+newId);
					
					
					
					jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
					
			 }
				 jQuery("#cnt").val(parseFloat(count-1)); 
				//GrandTotal();
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
			//  alert('hii');
				var count=$("#cnt").val();	
				//var state=$("#state").val();	
                 //alert(state);
				var i=parseFloat(count)+parseFloat(1);

                var cell1="<tr id='row_"+i+"'>";
				
				cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
				
				cell1 += "<td style='width:20%' ><select name='type_"+i+"'   class='select2 form-select'  id='type_"+i+"'  ><option value=''>Select</option><option value='consumption'>Consumption</option><option value='production'>production</option></select></td>";
			   
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
			     
			    cell1 += "<td style='width:20%' ><select name='location_"+i+"'  onchange='get_stock(this.id);get_qty(this.id);'  class='select2 form-select'  id='location_"+i+"' >\
                                    <option value=''>Select</option>\
									<?php
								   $record=$utilObj->getMultipleRow("location","1");
								foreach($record as $e_rec){	
									    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
										}
									   		
                                    ?>
                                  </select></td>";
             
                cell1 += "<td style='width:5%'><input name='stock_"+i+"' id='stock_"+i+"'  readonly   class='form-control number' type='text'/></td>";
				
                cell1 += "<td style='width:5%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control number' type='text'/></td>";
			
                cell1 += "<td style='width:10%'><input name='rate_"+i+"' id='rate_"+i+"'  onChange='get_amount(this.id);'    class='form-control number' type='text'/></td>";
				
                cell1 += "<td style='width:10%'><input name='amount_"+i+"' id='amount_"+i+"'   class='form-control number' type='text'/></td>";
				
                cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";
			
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>

