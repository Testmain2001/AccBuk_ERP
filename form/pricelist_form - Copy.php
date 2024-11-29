<?php 
 include("../header.php");
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

      <div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;"> Price List Master</h4>
	</div>
	<div class="col-md-2">
	<input type="button" class="add_new btn btn-primary btn-sm  " onclick="hideshow();" id="add_new" name="add_new" value="Add New" />
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button>
	</div>
</div>
<!-- Invoice List Table -->
 <div id="u_table" style="display:block">

<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
		  <th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /></th>
          <th>Sr.No.</th>
          <th>Currency Symbol</th>
          <th>Formal Name</th>
          <th>Decimal Places</th>
          <th>Actions</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data=$utilObj->getMultipleRow("currency","1");
			foreach($data as $info){
				
						$href= 'pricelist_list.php?id='.$info['id'].'&PTask=view';
					
		?>
		<tr>
		<td width='3%' class='controls'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>' /> </td> 
		<td><?php echo $i; ?></td>
		<td> <a href="<?php echo $href; ?>"><?php echo $info['currency_symbol']; ?></a> </td>
		<td> <?php echo $info['formal_name']; ?> </td>
		<td> <?php echo $info['decimal_places']; ?> </td>
		
		<td>
            <!--div class="dropdown"-->
           
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
               <a class="dropdown-item" href="pricelist_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                <a class="dropdown-item" href="pricelist_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
              </div>
            <!--/div-->
        </td>
		</tr>
		<?php 
		$i=$i+1;
		} ?>
	  </tbody>
	   </table>
  </div>
</div>  
</div>  

<!-- Form -->
        
 <div class="container-xxl flex-grow-1 container-p-y " style="display:none" id="u_form">
            

<div class="row form-validate">
  <!-- FormValidation -->
  <div class="col-12">
    <div class="card ">
      <div class="card-body " > 

        <form id="demo-form2" data-parsley-validate class="row g-3" action="../pricelist_list.php"  method="post" data-rel="myForm">
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "pricelist"; ?>"/>
      
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
					
						$i++;
						$j++;
						
                    ?>
				
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;"><label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label></td>
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
					 <input type="text" id="less_qty_<?php echo $i."_".$j;?>" class=" form-control"  <?php echo $readonly;?> name="less_qty_<?php echo $i."_".$j;?>" value="<?php echo $rows['less_qty'];?>" onkeyup="get_button('<?php echo $i;?>');"/>
					</td>
					<td>
					 <input type="text" id="rate_<?php echo $i."_".$j;?>" class=" form-control"  <?php echo $readonly;?> name="rate_<?php echo $i."_".$j;?>" value="<?php echo $rows['rate'];?>"/>
					</td>
					<td>
					 <input type="text" id="discount_<?php echo $i."_".$j;?>" class=" form-control"  <?php echo $readonly;?> name="discount_<?php echo $i."_".$j;?>" value="<?php echo $rows['discount'];?>"/>
					 
					</td>
					
					<td style='width:5%'>
						<div id="btn_div_<?php echo $i;?>">			
						</div>
					</td>
					<td style='width:5%'>
						 <?php //if($_REQUEST['PTask']!='view'){?>
							<i class="bx bx-trash me-1"  id='deleteRow' style="cursor: pointer;" onclick="delete_row('<?php echo $i;?>');"></i>
						 <?php// } ?>
					</td>
				   
				</tr>
			
			<?php // } ?>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
			<input type="hidden" name="cnt<?php echo $i;?>" id="cnt<?php echo $i;?>" value="<?php echo $j; ?>">
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
 // document.getElementById("addnew").click();
  //$("#addnew").val("Show List"); 
  
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
			  window.location="pricelist_form.php"; 

		  }
  
};
<?php } ?>

function hideshow()
{ 
		
	if(document.getElementById('u_form').style.display=="none")
	{
		document.getElementById('u_form').style.display="block"
		document.getElementById('u_table').style.display="none"
		document.getElementById('button').style.display="none"
		$('#demo-form2').hide();
		$(".add_new").val("Show List");
		
		
	}
	else
	{
		document.getElementById('u_form').style.display="none"
		document.getElementById('u_table').style.display="block"
		$(".add_new").val("Add New");
		$('#demo-form2').show();		
		window.location="pricelist_form.php";
		
	}
	
}		  

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
			window.location="pricelist_form.php?PTask=delete&id="+val; 
		
	}
}
 function remove_urldata()
{	
	window.location="pricelist_form.php";
}	

function mysubmit(a)
{
	return _isValid(a);		
}

/* function savedata(){
	
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
											
						window.location='pricelist_form.php';
					}else{
						//alert('faiel');
					}
				}
			});
} */
function deletedata(id){
	
	var PTask = "<?php echo $_REQUEST['PTask']; ?>";

	jQuery.ajax({url:'../handler/role_master_form.php', type:'POST',
				data: { PTask:PTask,id:id},
				success:function(data)
				{	
					if(data!="")
					{			
						window.location='pricelist_form.php';
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
	var i = $("#cnt").val();
	var j = $("#cnt"+i).val();
	var less_qty = $("#less_qty_"+i+"_"+j).val();
			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
						data: { Type:'get_button',less_qty:less_qty,rowid:rowid},
						success:function(data)
						{	
						//alert(data);
							
								$("#btn_div_"+i).html(data);
							
						}
					});	
}
</script>

<script>

	function delete_row(rwcnt)
	{
	//	alert(rwcnt);
	var count=$("#cnt").val();	
	var childCount=$("#cnt"+count).val();
	//alert(childCount);
	 if(count>1)
	{
		var r=confirm("Are you sure!");
		if (r==true)
		{		
				
            	  // alert("row count"+rwcnt);
				  $("#row_"+rwcnt).remove();
				  
			 for(var k=rwcnt+1; k<=count; k++){
				 
				 console.log("next Row"+k);
				 console.log(k-1);
				 
				 let newId=k-1;
			
				 
				 jQuery("#row_"+k).attr('id','row_'+newId);
					jQuery("#select_div_"+k).attr('id','select_div_'+newId);
					
				 	jQuery("#idd_"+k).attr('name','idd_'+newId);
        			jQuery("#idd_"+k).attr('id','idd_'+newId);
					jQuery("#idd_"+newId).html(newId); 
					
        			jQuery("#particulars_"+k).attr('name','particulars_'+newId);
        			jQuery("#particulars_"+k).attr('id','particulars_'+newId);
        			
					jQuery("#from_qty_"+k).attr('name','from_qty_'+newId);
        			jQuery("#from_qty_"+k).attr('id','from_qty_'+newId);
					
					jQuery("#less_qty_"+k).attr('name','less_qty_'+newId);
        			jQuery("#less_qty_"+k).attr('id','less_qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
        			jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#discount_"+k).attr('name','discount_'+newId);
        			jQuery("#discount_"+k).attr('id','discount_'+newId);
				 
			 }
				
				  
				 jQuery("#cnt").val(parseFloat(count-1)); 
				  
            	
			 	if(childCount!=1){
					  
					  for(var l=2;l<=childCount;l++){
						// console.log("Row count"+rwcnt+"  child row count"+l);
					   $("#crow_"+rwcnt+l).remove();
					  }
					  
					   for(var p=rwcnt;p<=count;p++){
						
							var child=$("#cnt"+p).val();
							let cnewId=p-1; 
							
						 for(var k=1;k<=child;k++)
						{
							 console.log("child Rows is : "+k);
								 jQuery("#from_qty_"+p+"_"+k).attr('name','from_qty_'+cnewId+"_"+k);
								jQuery("#from_qty_"+p+"_"+k).attr('id','from_qty_'+cnewId+"_"+k);
								
								jQuery("#less_qty_"+p+"_"+k).attr('name','less_qty_'+cnewId+"_"+k);
								jQuery("#less_qty_"+p+"_"+k).attr('id','less_qty_'+cnewId+"_"+k);
								
								jQuery("#rate_"+p+"_"+k).attr('name','rate_'+cnewId+"_"+k);
								jQuery("#rate_"+p+"_"+k).attr('id','rate_'+cnewId+"_"+k);
								
								jQuery("#discount_"+p+"_"+k).attr('name','discount_'+cnewId+"_"+k);
								jQuery("#discount_"+p+"_"+k).attr('id','discount_'+cnewId+"_"+k); 
						}
					  
					  }
				  } 


              
		}
	}
	else 
	{
		alert("Can't remove row Atleast one row is required");
		return false;
	}	 
}
</script>
<script>
              function addRow(tableID) { 
				var count=$("#cnt").val();	
				var i=parseFloat(count)+parseFloat(1);

				var count1=0;	
				var j=parseFloat(count1)+parseFloat(1);				

				
                var cell1="<tr id='row_"+i+"'>";
				
				cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
			   
				cell1 += "<td style='width:5%' ><div id='select_div_"+i+"'><select name='particulars_"+i+"'   class='select2 form-select'  id='particulars_"+i+"' >\
                                    <option value=''>Select</option>\
									<?php
								     	$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
										foreach($record as $e_rec){	
									    echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
										}
									   		
                                    ?>
                                  </select></div></td>";
           
			  	cell1 += "<td style='width:15%'><input name='from_qty_"+i+"_"+j+"' id='from_qty_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"_"+j+"' id='less_qty_"+i+"_"+j+"'   class='form-control' type='text' onkeyup='get_button("+i+");'/></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"_"+j+"' id='rate_"+i+"_"+j+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"_"+j+"' id='discount_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				cell1 += "<td style='width:10%'><div id='btn_div_"+i+"'></div></td>";

              cell1 += "<td style='width:5%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			  cell1 += "<input name='cnt"+i+"' id='cnt"+i+"'  class='form-control' type='hidden' value='"+j+"'/> </tr>";
               
				
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>
<script>
 function addRow1(rwcnt) { 
				//var cnt=$("#cnt").val();	
				//var rw=table.split("_");	///count i(main row)
				var i=rwcnt;
				//alert("Row  Count "+i);
				var count=$("#cnt"+i).val();	
				//alert("Initial Count "+count);
				var j=parseFloat(count)+parseFloat(1);	///count j(child row)	
				
                var cell1="<tr id='crow_"+i+j+"'>";
				
			  	cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'><input name='from_qty_"+i+"_"+j+"' id='from_qty_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"_"+j+"' id='less_qty_"+i+"_"+j+"'   class='form-control' type='text' onclick='get_button('"+i+"');'/></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"_"+j+"' id='rate_"+i+"_"+j+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"_"+j+"' id='discount_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				cell1 += "<td style='width:10%'><i class='bx bx-trash me-1'  id='deleteRow' style='cursor: pointer;' onclick='deleteChildRow("+i+","+j+");'></i></td>";

				cell1 += "<td style='width:5%'></td></tr>";
               
					
	 	if(count=="1"){
				$("#row_"+i).after(cell1);
			}else{
				$("#crow_"+i+count).after(cell1);
			}
				//$(cell1).insertAfter($("#row_"+i)).closest('tr'); 
                //$("#row_"+i).append(cell1);
                $("#cnt"+i).val(j);
			//alert("After Row Adding  Count "+j);
			  }
			
function deleteChildRow(idd,jid){
	
	alert("i count "+idd+" j count"+jid);
	
	$("#crow_"+idd+jid).remove();
	
}
			

</script>

<!-- Footer -->
<?php 
include("footer.php");
?>
