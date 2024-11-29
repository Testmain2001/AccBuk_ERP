<?php 
	include("header.php");
	include 'handler/pricelist_form.php'; 
	$task=$_REQUEST['Task'];
	if($task==''){ $task='Add';}
	if($_REQUEST['Task']=='view')
	{
		$readonly="readonly";
		$disabled="disabled";
	}
	else
	{
		$readonly="";
		$disabled="";
	}
?>
<style>
.hidetd{
	display: none;
}
.hidetxt{
	display: none;
}

</style>
<div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;"> Price List Master</h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1){ ?>
		<!-- <input type="button" class="add_new btn btn-primary btn-sm  " onclick="hideshow();" id="add_new" name="add_new" value="Add New" /> -->
		<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
			<i class="fas fa-plus-circle fa-lg"></i>
		</button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
		<!-- <input type="button" class=" btn btn-danger  btn-sm"  onclick="CheckDelete();" id="delete" name="delete" value="Delete" /> -->
		<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
			<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
		</button>
	<?php } ?>
	
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
			<th>Stock Group</th>
			<th>Price Level</th>
			<th>Applicable Date</th>
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data=$utilObj->getMultipleRow("pricelist","1 group by common_id");
			foreach($data as $info){
				
						$href= 'pricelist_form.php?id='.$info['common_id'].'&Task=view';
					
			if($info['stock_gruop']=="Primary") 
			{
				$stk_group=$info['stock_gruop'];
			}
			else
			{
				$stock_group1=$utilObj->getSingleRow("stock_group","id='".$info['stock_gruop']."'");
				$stk_group=$stock_group1['name'];
			}
		?>	
					
	
		<tr>
		<td width='3%' class='controls'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['common_id']; ?>' /> </td> 
		<td><?php echo $i; ?></td>
		<td>
		<a href="<?php echo $href; ?>"><?php echo $stk_group; ?></a> 
		</td>
		<td> <?php echo $info['price_level']; ?> </td>
		<td> <?php echo date('d-m-Y',strtotime ($info['applicable_date']));?> </td>
		
		<td>
            <!--div class="dropdown"-->
           
			<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
			<div class="dropdown-menu">
			<?php if((CheckEditMenu())==1) {  ?>
               	<a class="dropdown-item" href="pricelist_form.php?id=<?php echo $info['common_id'];?>&Task=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
			<?php } ?>
			<?php if((CheckDeleteMenu())==1){ ?>
				<a class="dropdown-item" href="pricelist_form.php?id=<?php echo $info['common_id'];?>&Task=delete"><i class="bx bx-trash me-1"></i> Delete</a>
			<?php } ?>
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
<?php	
$app_date=date('d-m-Y');
if($_REQUEST['Task']=='update'||$_REQUEST['Task']=='view')
{
	$rows=$utilObj->getSingleRow("pricelist","common_id='".$_REQUEST['id']."'");
	$app_date=date('d-m-Y',strtotime($rows['applicable_date']));
}
?>			
        <form id="demo-form2" data-parsley-validate class="row g-3" action="pricelist_form.php"  method="post" data-rel="myForm">
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['common_id'];?>"/>	
			<input type="hidden"  name="idd" id="idd" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "pricelist"; ?>"/>
      
			<div class="col-md-3">
				<label class="form-label">Stock Group <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="stock_gruop" name="stock_gruop" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_table();">
							<option value="">Select Stock Group</option>
						<?php 
						
							$record=$utilObj->getMultipleRow("stock_group","1 group by name");
							foreach($record as $e_rec){
							if($rows['stock_gruop']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
							}
						?> 
					</select>
        	</div>
	
			<div class="col-md-3">
				<label class="form-label">Price Level <span class="required required_lbl" style="color:red;">*</span></label>
				<div id="div_level">
					<select id="price_level" name="price_level" <?php echo $disabled;?> class="required select2 form-select " data-allow-clear="true" onchange="menuhide1();">
						<?php 
							echo '<option value="">Select Price Level</option>';
							echo '<option value="AddNew">Add New</option>';
							$record=$utilObj->getMultipleRow("pricelist","1 group by price_level");
							foreach($record as $e_rec){
								if($rows['price_level']==$e_rec["price_level"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["price_level"].'" '.$select.'>'.$e_rec["price_level"].'</option>';
							}
						?> 
					</select>
				</div>
        	</div>
		
			<div class="col-md-3">
				<label class="form-label">Applicable From <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" class="form-control flatpickr" id="applicable_date" name="applicable_date" required value="<?php echo $app_date;?>" <?php echo $disabled;?>/>
			</div>

			<div class="col-md-3">
				<label class="form-label">Category <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="cat_group"  name="cat_group" <?php echo $disabled;?> class="required select2 form-select " data-allow-clear="true" >
				
						<option value="">Select Group</option>';
						<?php 
						$record=$utilObj->getMultipleRow("stock_category_master","1 group by cat_name");
						foreach($record as $e_rec){
						if($rows['cat_group']==$e_rec["id"]) echo $select='selected'; else $select='';
						
						echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cat_name"].'</option>';
						}
					?> 
				</select>
			</div>
			<br>
	
			<table class="table table-bordered" id="myTable"> 
				<thead>
					<tr>
						<th style="width:2%;text-align:center;" rowspan="2">Sr.No.</th> 
						<th style="width: 15%;text-align:center;" rowspan="2">Particulars <span class="required required_lbl" style="color:red;">*</span></th>
						<th style="width: 10%;text-align:center;" colspan="2">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
						<th style="width:10%;text-align:center;" rowspan="2">Rate <span class="required required_lbl" style="color:red;">*</span></th>
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
						
						if($_REQUEST['Task']=='update' || $_REQUEST['Task']=='view' )
						{ 
							$record5=$utilObj->getMultipleRow("pricelist","common_id='".$_REQUEST['id']."' group by particulars");
							
						}
						/* else
						{
							$record5[0]['id']=1;
												
						}  */
						
						foreach($record5 as $row_demo)
						{
							$j=0;
							$i++;
							$record_row=$utilObj->getMultipleRow("pricelist","particulars='".$row_demo['particulars']."' AND  common_id='".$row_demo['common_id']."'");
							foreach($record_row as $rows)
							{ 
								
			
								
								$j++;
							
						?>
					<?php if($j==1){ ?>
					<tr id='row_<?php echo $i;?>'>
					<?php } else {?>
					<tr id='crow_<?php echo $i.$j;?>'>
					<?php } ?>
						<td style="text-align:center;">
						<?php if($j==1){ ?>
							<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
						<?php } ?>
						</td>
						
						<td>
						<?php if($j==1){ ?>
							<?php if($_REQUEST['Task']=='update') { 
								$mate1=$utilObj->getSingleRow("pricelist","common_id='".$_REQUEST['id']."'"); ?>
								<div id="select_div_<?php echo $i;?>">
									<select id="particulars_<?php echo $_REQUEST['i'];?>" name="particulars_<?php echo $_REQUEST['i'];?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
										<?php 
											echo '<option value="">Select</option>';
											$record=$utilObj->getMultipleRow("stock_ledger","cat_id='".$mate1['cat_group']."'");
											foreach($record as $e_rec)
											{
												
												if($rows['particulars']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
											}
											?>  
									</select>
								</div>	
							<?php } else { ?>
								<div id="select_div_<?php echo $i;?>">

								</div>
							<?php } ?>	
						<?php } ?>
						</td>
						
						<td>
						<input type="text" id="from_qty_<?php echo $i."_".$j;?>" class="number form-control"  <?php echo $readonly;?> name="from_qty_<?php echo $i."_".$j;?>" value="<?php echo $rows['from_qty'];?>"/>
						</td>
						<td>
						<input type="text" id="less_qty_<?php echo $i."_".$j;?>" class=" form-control"  <?php echo $readonly;?> name="less_qty_<?php echo $i."_".$j;?>" value="<?php echo $rows['less_qty'];?>" onkeyup="get_button('<?php echo $i;?>');"/>
						</td>
						<td>
						<input type="text" id="rate_<?php echo $i."_".$j;?>" class="number form-control"  <?php echo $readonly;?> name="rate_<?php echo $i."_".$j;?>" value="<?php echo $rows['rate'];?>"/>
						</td>
						<td>
						<input type="text" id="discount_<?php echo $i."_".$j;?>" class=" form-control"  <?php echo $readonly;?> name="discount_<?php echo $i."_".$j;?>" value="<?php echo $rows['discount'];?>"/>
						
						</td>
						
						<td style='width:5%'>
						<?php if($j==1){ ?>
							<div id="btn_div_<?php echo $i;?>">	
							<?php if($_REQUEST['Task']=='update' )
								{?>
									<button type="button" class="btn btn-warning btn-sm " id="addmore" onclick="addRow1('<?Php echo $i ;?>');">Add More</button>				
							<?php }
							} else{ 
									if($_REQUEST['Task']=='update' )
									{?>	
										<i class="bx bx-trash me-1"  id='deleteRow' style="cursor: pointer;" onclick="deleteChildRow('<?php echo $i ;?>','<?php echo $j;?>');"></i>
								<?php } 
									}?>						
							</div>
						</td>
						<td style='width:5%'>
						<?php if($j==1){ ?>
							<?php if($_REQUEST['Task']!='view'){?>
								<i class="bx bx-trash me-1"  id='deleteRow' style="cursor: pointer;" onclick="delete_row('<?php echo $i ;?>');"></i>
							<?php } ?>
						<?php } ?>
						</td>
					
					</tr>
				<?php 		}	?>
						<input type="hidden" name="cnt<?php echo $i;?>" id="cnt<?php echo $i;?>" value="<?php echo $j; ?>">
				<?Php		} 
							
				?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
				
				</tbody>
			</table>
			<table style="width:100%;" class="taxtbl" >
				<tr style="margin:10px;text-align:center;">
					<td>
						<?php if($_REQUEST['Task']!='view'){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php  } ?> 
					</td>			
				</tr>
			</table>
	 
			<div class="col-12">
			<center>
				<?php if($_REQUEST['Task']=='update' || $_REQUEST['Task']==''){?>			  
					<input type="button" class="btn btn-primary" name="subumit" value="Submit" onClick="mysubmit(0);" /> 
				<?php } ?>
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

function product_check(i) {

	var val = $('#cat_group').val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: {Type:'product_check', val:val,i:i},
		success:function(data)
		{	
			if(data!="")
			{			
				// alert(data);
				$("#select_div_"+i).html(data);
			}else{
				// alert('Failed');
			}
		}
	});

}

window.onload=function(){
	$("#applicable_date").flatpickr({
	//enableTime: true,
	dateFormat: "d-m-Y"
	});
}
<?php 
if($_REQUEST['Task']=='update'||$_REQUEST['Task']=='view'){?>	
		hideshow();
		get_table();
		
<?php }
if($_REQUEST['Task']=='delete'){?>	
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
		//document.getElementById('button').style.display="none"
		//$('#demo-form2').hide();
		$('#demo-form2').show();
		$("#add_new").val("Show List");
		
		
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
		window.location="pricelist_form.php?Task=delete&id="+val; 
		
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


function deletedata(id){
	
	var PTask = "<?php echo $_REQUEST['Task']; ?>"; 
	

	jQuery.ajax({url:'handler/pricelist_form.php', type:'POST',
				data: {PTask:PTask,id:id},
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
						data: { Type:'get_table',stock_gruop:stock_gruop,cnt:cnt},
						success:function(data)
						{	
							
								$("#select_div_"+cnt).html(data);

						}
					});	
}
function get_button(rowid)
{	


	var j = $("#cnt"+rowid).val();
	var PTask = $("#PTask").val();
	var less_qty = $("#less_qty_"+rowid+"_"+j).val();

			jQuery.ajax({url:'get_ajax_values.php', type:'POST',
						data: { Type:'get_button',less_qty:less_qty,rowid:rowid,PTask:PTask},
						success:function(data)
						{	
								$("#btn_div_"+rowid).html(data);
						}
					});	
}

function menuhide1()
{
	var level = $("#price_level").val();
	
	if (level=='AddNew')
	{
		setTimeout(function()
		{
			$("#div_level").html('<input type="text" class="required form-control"  placeholder="Enter Price Level" name="price_level" id="price_level" >');
			$("#price_level").focus();
		},1);
	}
}
</script>

<script>
//delete parent row
function delete_row(rwcnt)
{
	var count=$("#cnt").val();	
	var childCount=$("#cnt"+rwcnt).val();

	 if(count>1)
	{
		var r=confirm("Are you sure!");
		if (r==true)
		{		
				
            	
				  $("#row_"+rwcnt).remove();
				  
			 for(var k=rwcnt+1; k<=count; k++){
				 
				// console.log("next Row"+k);
				// console.log(k-1);
				 
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
				  
            ////delete parent with child row	

			
			 	if(childCount!=1){
					  
					  for(var l=2;l<=childCount;l++)
					  {
							$("#crow_"+rwcnt+l).remove();
							$("#cnt"+rwcnt+l).remove();
					  }
					  
					   for(var p=rwcnt;p<=count;p++)
					   {
						
							var child=$("#cnt"+p).val();
							let cnewId=p-1; 
							
							 for(var k=1;k<=child;k++)
							{
								 
									jQuery("#from_qty_"+p+"_"+k).attr('name','from_qty_'+cnewId+"_"+k);
									jQuery("#from_qty_"+p+"_"+k).attr('id','from_qty_'+cnewId+"_"+k);
									
									jQuery("#less_qty_"+p+"_"+k).attr('name','less_qty_'+cnewId+"_"+k);
									jQuery("#less_qty_"+p+"_"+k).attr('id','less_qty_'+cnewId+"_"+k);
									
									jQuery("#rate_"+p+"_"+k).attr('name','rate_'+cnewId+"_"+k);
									jQuery("#rate_"+p+"_"+k).attr('id','rate_'+cnewId+"_"+k);
									
									jQuery("#discount_"+p+"_"+k).attr('name','discount_'+cnewId+"_"+k);
									jQuery("#discount_"+p+"_"+k).attr('id','discount_'+cnewId+"_"+k); 
									
									
							}
							jQuery("#cnt"+p).attr('name','cnt'+cnewId); 
							jQuery("#cnt"+p).attr('id','cnt'+cnewId); 
							
							jQuery("#idd_"+p).attr('name','idd_'+cnewId);
							jQuery("#idd_"+p).attr('id','idd_'+cnewId);
							jQuery("#idd_"+cnewId).html(cnewId); 
					  
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

////Add Parent Row
              function addRow(tableID) { 
			
				var count=$("#cnt").val();
				
				var i=parseFloat(count)+parseFloat(1);
				product_check(i);
				

				var count1=0;	
				var j=parseFloat(count1)+parseFloat(1);				

				
                var cell1="<tr id='row_"+i+"'>";
				
				cell1 += "<td style='width:2%;text-align:center;'><label name='idd_"+i+"' id='idd_"+i+"' >"+i+"</label></td>";
				 
				cell1 += "<td style='width:5%' ><div id='select_div_"+i+"'></div></td>";
           
			  	cell1 += "<td style='width:15%'><input name='from_qty_"+i+"_"+j+"' id='from_qty_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"_"+j+"' id='less_qty_"+i+"_"+j+"'   class='form-control' type='text' onkeyup='get_button("+i+");'/></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"_"+j+"' id='rate_"+i+"_"+j+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"_"+j+"' id='discount_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				cell1 += "<td style='width:10%'><div id='btn_div_"+i+"'></div></td>";

              cell1 += "<td style='width:5%'><i class='bx bx-trash me-1' id='deleteRow' style='cursor: pointer;'  onclick='delete_row("+i+");'></i></td>";
			  cell1 += "<input name='cnt' id='cnt'  class='form-control' type='hidden' value='"+i+"'/> </tr>";
			  cell1 += "<input name='cnt"+i+"' id='cnt"+i+"'  class='form-control' type='hidden' value='"+j+"'/> </tr>";
               
				
                $("#myTable").append(cell1);
                $("#cnt").val(i);
				$("#particulars_"+i).select2(); 
				
                 
			  }
                
</script>
<script>
////Add Child Row

 function addRow1(rwcnt) { 
				
				var i=rwcnt;///count i(parent row)
				var count=$("#cnt"+i).val();	
				var j=parseFloat(count)+parseFloat(1);	///count j(child row)	
				
                var cell1="<tr id='crow_"+i+j+"'>";
				
			  	cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'></td>";
			  	
				cell1 += "<td style='width:15%'><input name='from_qty_"+i+"_"+j+"' id='from_qty_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				
                cell1 += "<td style='width:15%'><input name='less_qty_"+i+"_"+j+"' id='less_qty_"+i+"_"+j+"'   class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='rate_"+i+"_"+j+"' id='rate_"+i+"_"+j+"' class='form-control' type='text' /></td>";
				
				cell1 += "<td style='width:15%'><input name='discount_"+i+"_"+j+"' id='discount_"+i+"_"+j+"'  class='form-control' type='text'/></td>";
				 <?php if($_REQUEST['Task']!='view'){?>
				cell1 += "<td style='width:10%'><i class='bx bx-trash me-1'  id='deleteRow' style='cursor: pointer;' onclick='deleteChildRow("+i+","+j+");'></i></td>";
				 <?php } ?>
				cell1 += "<td style='width:5%'></td></tr>";
               
					
	 	if(count=="1"){
				$("#row_"+i).after(cell1);
			}else{
				$("#crow_"+i+count).after(cell1);
			}
			$("#cnt"+i).val(j);
				//$(cell1).insertAfter($("#row_"+i)).closest('tr'); 
                //$("#row_"+i).append(cell1);
                
			//alert("After Row Adding  Count "+j);
			  }
			
function deleteChildRow(idd,jid)
{//delete child row
//alert(idd);
	$("#crow_"+idd+jid).remove();
}
			

</script>

<!-- Footer -->
<?php 
include("footer.php");
?>
