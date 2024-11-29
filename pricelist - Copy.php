<?php 
 include("header.php");
$task=$_REQUEST['PTask'];
if($task==''){ $task='Add';}
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
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'){
	///$rows=$utilObj->getSingleRow("role_master","id='".$_REQUEST['id']."'");
}
?>

        
<!-- Form -->
        
 <div class="container-xxl flex-grow-1 container-p-y " >
            
            
<h4 class="fw-bold py-3 mb-4">Price List Master</h4>
<div class="row form-validate">
  <!-- FormValidation -->
  <div class="col-12">
    <div class="card ">
      <div class="card-body ">

        <form id="" data-parsley-validate class="row g-3" action="account_ledger_masterlist.php"  method="post" data-rel="myForm">

      
		<div class="col-md-4">
				<label class="form-label">Stock Group </label>
				<div id="div_unit">
					<select id="stock_gruop" name="stock_gruop" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="get_table();">
						<?php 
							echo '<option value="">Select Stock Group</option>';
							echo '<option value="Primary" if($rows["stock_gruop"]=="Primary") echo $select="selected"; else $select="";>Primary</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 group by name");
							foreach($record as $e_rec){
							if($rows['stock_gruop']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
							}
						?> 
					</select>
				</div>
        </div>
		
		<div class="col-md-4">
				<label class="form-label">Price Level <span class="required required_lbl" style="color:red;">*</span></label>
				<div id="div_unit">
					<select id="price_level" name="price_level" <?php echo $disabled;?> class="required select2 form-select " data-allow-clear="true">
						<?php 
							echo '<option value="">Select Price Level</option>';
							//$record=$utilObj->getMultipleRow("price_level","1 group by name");
							foreach($record as $e_rec){
							if($rows['price_level']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
							}
						?> 
					</select>
				</div>
        </div>
		
		<div class="col-md-4">
            <label class="form-label">Applicable From</label>
            <input type="text" class="form-control flatpickr" id="applicable_date" name="applicable_date" required />
        </div>
		<br>
	
		<table class="table table-bordered" id="myTable"> 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;" rowspan="2">Sr.No.</th> 
					<th style="width: 15%;text-align:center;" rowspan="2">Particulars </th>
					<th style="width: 10%;text-align:center;" colspan="2">Quantity</th>
					<th style="width:10%;text-align:center;" rowspan="2">Rate</th>
					<th style="width:10%;text-align:center;" rowspan="2">Discount(%)</th>
					<th style="width:10%;text-align:center;" rowspan="2"></th>
					<th style="width:5%;text-align:center;" rowspan="2"></th>
					
				</tr>
				<tr>
					<th style="width:10%;text-align:center;">From</th> 
					<th style="width: 10%;text-align:center;">Less Than</th>
				</tr>
			</thead>
			<tbody id="myTable1">
			<?php 
					$i=0;
					$j=0;
					
					/* if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
					{ 
						//$material_record=$utilObj->getMultipleRow("purchase_product","pid='".$id."'"); 
					}
						
                    foreach($material_record as $veh_rec)
				    {
						//$demo=$utilObj->getSingleRow("material_type","ID='".$veh_rec['unit']."'"); */
						$i++;
						$j++;
						
                    ?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;"><label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?>.</label></td>
					<td>
					<div id="select_div_<?php echo $i;?>">
						<select id="particulars_<?php echo $i;?>" name="particulars_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
							
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 group by name");
								foreach($record as $e_rec)
								{
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
								?>  
						</select>
					</div>	
					</td>
					
					<td>
					 <input type="text" id="from_qty_<?php echo $i;?>" class=" form-control"  <?php echo $readonly;?> name="from_qty_<?php echo $i;?>" value="<?php echo $rows['from_qty'];?>"/>
					</td>
					<td>
					 <input type="text" id="less_qty_<?php echo $i;?>" class=" form-control"  <?php echo $readonly;?> name="less_qty_<?php echo $i;?>" value="<?php echo $rows['less_qty'];?>" onkeyup="get_button('row_<?php echo $i;?>');"/>
					</td>
					<td>
					 <input type="text" id="rate_<?php echo $i;?>" class=" form-control"  <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $rows['rate'];?>"/>
					</td>
					<td>
					 <input type="text" id="discount_<?php echo $i;?>" class=" form-control"  <?php echo $readonly;?> name="discount_<?php echo $i;?>" value="<?php echo $rows['discount'];?>"/>
					 
					</td>
					
					<td style='width:5%'>
						<div id="btn_div_<?php echo $i;?>">			
						</div>
					</td>
					<td style='width:5%'>
						 <?php //if($_REQUEST['PTask']!='view'){?>
							<i class="bx bx-trash me-1"  id='deleteRow' style="cursor: pointer;"></i>
						 <?php// } ?>
					</td>
				   
				  
				</tr>
			<?php // } ?>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
			<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
                   <td>
						<?php if($_REQUEST['PTask']!='view'){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php  } ?> 
				</td>			
			</tr>
		</table>
	 
          <div class="col-12">
          <center>  
		     <input type="button" class="btn btn-primary" name="subumit" value="Submit" onClick="mysubmit(0);" /> 
		     <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">
			 Cancel</button>
		  </center>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /FormValidation -->
</div>

 </div>
 <!--/ Content -->
 <!--/ End Form -->

          
          
<script>
window.onload=function(){
	$("#applicable_date").flatpickr({
	//enableTime: true,
	//dateFormat: "Y-m-d H:i"
	});
}
<?php 
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'){?>	
window.onload=function(){
  document.getElementById("addnew").click();
  $("#addnew").val("Show List"); 
}; 

<?php }
if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		  {
		 deletedata("<?php echo $_REQUEST['id']; ?>");
		  }
		else
		  {
			  window.location="role_master_list.php"; 

		  }
  
};
<?php } ?>
 function CheckDelete()
{
	var val='';
	$('input[type="checkbox"]').each(function()
	{	
		if(this.checked==true && this.value!='on'&&this.value!='0')
		{
			
			val +=this.value+",";
		}
	});

	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
		
			val = val.substring(0, val.length - 1);
			window.location="role_master_list.php?PTask=delete&id="+val; 
		
	}
}
 function remove_urldata()
{	
	window.location="pricelist.php";
}	
function mysubmit(a)
{
	return _isValidpopup(a);	
}

function savedata(){
	
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var group_name = $("#group_name").val();
	var name = $("#name").val();
	var interst = $("#interst").val();
	var credit_limit = $("#credit_limit").val();
	var price_level = $("#price_level").val();
	var inventory_allocation = $("#inventory_allocation").val();
	var cost_tracking = $("#cost_tracking").val();
	var opening_balance = $("#opening_balance").val();
	var mailing = $("#mailing").val();
	var bank_reconcilation = $("#bank_reconcilation").val();
	var cheque_book_registor = $("#cheque_book_registor").val();
	var cheque_book_printing = $("#cheque_book_printing").val();
	var tds_tax_details = $("#tds_tax_details").val();
	var gst_tax_allocation = $("#gst_tax_allocation").val();
	
	
	jQuery.ajax({url:'handler/role_master_form.php', type:'POST',
				data: { PTask:PTask,id:id,group_name:group_name,name:name,interst:interst,credit_limit:credit_limit,price_level:price_level,inventory_allocation:inventory_allocation,cost_tracking:cost_tracking,},
				success:function(data)
				{	
					if(data!="")
					{
											
						window.location='role_master_list.php';
					}else{
						//alert('faiel');
					}
				}
			});
}
function deletedata(id){
	
	var PTask = "<?php echo $_REQUEST['PTask']; ?>";

	jQuery.ajax({url:'handler/role_master_form.php', type:'POST',
				data: { PTask:PTask,id:id},
				success:function(data)
				{	
					if(data!="")
					{			
						window.location='role_master_list.php';
					}else{
						//alert('faiel');
					}
				}
			});
}
</script>
<script>
function select_all(){	

	//select all checkboxes
	$("#select_all").change(function(){  //"select all" change

		var status = this.checked; // "select all" checked status
		$('.checkboxes').each(function(){ //iterate all listed checkbox items
			if(this.disabled==false)
			{
				this.checked = status; //change ".checkbox" checked status
				//alert(this.disabled);
			}
		});
	});

	//uncheck "select all", if one of the listed checkbox item is unchecked
	$('.checkboxes').change(function(){ //".checkbox" change

		if(this.checked == false){ //if this item is unchecked
			$("#select_all")[0].checked = false; //change "select all" checked status to false
		}
	});

}

function get_table()
{	
	var stock_gruop = $("#stock_gruop").val();
	var cnt = $("#cnt").val();
	
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
						data: { Type:'get_table',stock_gruop:stock_gruop},
						success:function(data)
						{	
							for(var i=1;i<=cnt;i++)
							{
								$("#select_div_"+i).html(data);
							}
						}
					});	
}
function get_button(rowid)
{	
//alert(rowid);
	var cnt = $("#cnt").val();
	
	var less_qty = $("#less_qty_"+cnt).val();
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
						data: { Type:'get_button',less_qty:less_qty,rowid:rowid},
						success:function(data)
						{	
							for(var i=0;i<=cnt;i++)
							{
								$("#btn_div_"+i).html(data);
							}
						}
					});	
}
</script>

<script>
$('#myTable').on('click', '#deleteRow', function () 
{
alert('hi');
	var count=$("#cnt").val();	
		
	 if(count>1)
	{
		var r=confirm("Are you sure!");
		if (r==true)
		{		
            	var id=$(this).closest('tr').attr('id');
            	var rw=id.split("_");
            	var j=rw[1];
				
            	var cnt=$("#cnt").val();
            	for(var k=j-1; k<=cnt; k++)
				{
            		var id=parseFloat(k+1);
					
					//for removing div
        			jQuery("#row_"+id).attr('id','row_'+k);
					jQuery("#select_div_"+id).attr('id','select_div_'+k);
					
					
					
					jQuery("#idd_"+id).attr('name','idd_'+k);
        			jQuery("#idd_"+id).attr('id','idd_'+k);
					jQuery("#idd_"+k).html(k);
					
        			jQuery("#particulars_"+id).attr('name','particulars_'+k);
        			jQuery("#particulars_"+id).attr('id','particulars_'+k);
        			
					jQuery("#from_qty_"+id).attr('name','from_qty_'+k);
        			jQuery("#from_qty_"+id).attr('id','from_qty_'+k);
					
					jQuery("#less_qty_"+id).attr('name','less_qty_'+k);
        			jQuery("#less_qty_"+id).attr('id','less_qty_'+k);
					
					jQuery("#rate_"+id).attr('name','rate_'+k);
        			jQuery("#rate_"+id).attr('id','rate_'+k);
					
					jQuery("#discount_"+id).attr('name','discount_'+k);
        			jQuery("#discount_"+id).attr('id','discount_'+k);
			
                }
            	jQuery("#cnt").val(parseFloat(count-1));

                $(this).closest('tr').remove();
		}
	}
	else 
	{
		alert("Can't remove row Atleast one row is required");
		return false;
	}	 
}) 
				 
			
              
              function addRow(tableID) { 
				var count=$("#cnt").val();	
				var i=parseFloat(count)+parseFloat(1);				
                var cell1="<tr id='row_"+i+"'>";
				
				cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+".</label></td>";
			   
				cell1 += "<td style='width:5%' ><div id='select_div_"+i+"'><select name='particulars_"+i+"'   class='select2 form-select'  id='particulars_"+i+"' >\
                                    <option value=''>Select</option>\
									<?php
								     	$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
										foreach($record as $e_rec){	
									    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
										}
									   		
                                    ?>
                                  </select></div></td>";
           
			  	cell1 += "<td style='width:15%'><input name='from_qty_"+i+"' id='from_qty_"+i+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"' id='less_qty_"+i+"'   class='form-control' type='text' onkeyup='get_button();'/></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"' id='rate_"+i+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"' id='discount_"+i+"'  class='form-control' type='text'/></td>";
				cell1 += "<td style='width:10%'><div id='btn_div_"+i+"'></div></td>";

              cell1 += "<td style='width:5%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'></i></td>";
               
				
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>
<script>
 function addRow1(table) { 
				var cnt=$("#cnt").val();	
				var k=parseFloat(cnt)+parseFloat(1);	///count i(main row)
				
				var count=$("#cnt1").val();	
				var i=parseFloat(count)+parseFloat(1);	///count j(child row)			
                var cell1="<tr id='crow_"+i+"'>";
				
			  	cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'><input name='from_qty_"+i+"' id='from_qty_"+i+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"' id='less_qty_"+i+"'   class='form-control' type='text' onclick='get_button();'/></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"' id='rate_"+i+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"' id='discount_"+i+"'  class='form-control' type='text'/></td>";
				cell1 += "<td style='width:10%'></td>";

				cell1 += "<td style='width:5%'></td>";
               
				
                $("#myTable1").append(cell1);
                $("#cnt1").val(i);
			
			  }
</script>
<!-- Footer -->
<?php 
include("footer.php");
?>
