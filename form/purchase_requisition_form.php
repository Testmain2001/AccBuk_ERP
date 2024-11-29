<?php
	include '../config.php';

	$getinvno= mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from purchase_requisition");
	$result=mysqli_fetch_array($getinvno);
	$record_no=$result['pono']+1; 	
	
	$date=date('d-m-Y');	 
	if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {

		$id=$_REQUEST['id'];
		$rows=$utilObj->getSingleRow("purchase_requisition","id ='".$id."'"); 
		$record_no=$rows['record_no'];	
		$date=date('d-m-Y',strtotime($rows['date']));
	} else {

		$rows=null;
	}

	if($_REQUEST['PTask']=='update') {

		$username=$utilObj->getSingleRow("employee","id='".$rows['user']."' ");
	} else {

		$username=$utilObj->getSingleRow("employee","id='".$_SESSION['Ck_User_id']."' ");
	}
	
	$requisition_by = $username['name'];

	$user=$utilObj->getSingleRow("employee","id ='".$_SESSION['Ck_User_id']."' ");

?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body ">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
        <div class="text-center mb-4">
          <h3 class="role-title">Purchase Requisition</h3>
          
        </div>
        <!-- Add role form -->
		
		<form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table" id="table" value="<?php echo "purchase_requisition"; ?>"/>
			
			<div class="col-md-2">
				<label class="form-label">Requisition No. <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Requisition No." name="record_no" value="<?php echo $record_no;?>"/>
			</div>
			
			<div class="col-md-2">
				<label class="form-label">Requisition Date <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
			</div>
			
			<div class="col-md-2">
				<label class="form-label">Requisition By <span class="required required_lbl" style="color:red;">*</span></label>
				<input type="text" id="requisition_by" class="required form-control"  <?php echo $readonly;?> placeholder="Requisition By" name="requisition_by" value="<?php echo $requisition_by; ?>" readonly/>
			</div>
			
			<div class="col-md-2">
				<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="location" name="location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
				<?php 
					echo '<option value="">Select Location</option>';
					$place = explode(",",$user['multiloc']);
					foreach($place as $pid) {

						$data=$utilObj->getMultipleRow("location","id = '".$pid."' group by id");

						foreach($data as $info) {

							$loc=$utilObj->getSingleRow("location","1 ");

							if($info['id']==$rows['location']){ echo $select="selected"; } else { echo $select=""; }
							echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
						}  
					}
				?>
				<?php

					
				?>
				</select>
			</div>

			<div class="col-md-4">
				<label for="first-name" class="control-label" >Narration</label>
				<textarea type="text" <?php echo $readonly;?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 100%;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $purchase_invoice['otrnar'];?></textarea>
			</div>
				
			
			<h4 class="role-title">Material Details</h4>
        
		<table class="table table-bordered" id="myTable"> 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.No.</th> 
					<th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit<span class="required required_lbl" style="color:red;">*</span> </th>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:5%;text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i=0;
				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
				{ 
						$record5=$utilObj->getMultipleRow("purchase_requisition_details","parent_id='".$_REQUEST['id']."'");
				}
				else
				{
					$record5[0]['id']=1;					
				}  
				foreach($record5 as $row_demo)
				{ 
					$i++;
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td>
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
					<td>
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					<td>
					 	<input type="text" id="qty_<?php echo $i;?>" class="number form-control"  <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					</td>
					<td style='width:5%'>
						<?php if($_REQUEST['Task']!='view') { ?>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
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

function get_unit(this_id)
{	
	//alert('hii');
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
	
	cell1 += "<td style='width:15%' ><select name='product_"+i+"'   class='select2 form-select'  id='product_"+i+"' onchange='get_unit(this.id);' >\
		<option value=''>Select</option>\
		<?php
			$record=$utilObj->getMultipleRow("stock_ledger","1 group by name"); 
			foreach($record as $e_rec){	
			echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
				
		?>
	</select></td>";

	cell1 += "<td style='width:10%'><div id='unitdiv_"+i+"'><input name='unit_"+i+"' id='unit_"+i+"'  class='form-control' type='text'/></div></td>";
	
	cell1 += "<td style='width:10%'><input name='qty_"+i+"' id='qty_"+i+"'   class='form-control' type='text'/></td>";

	cell1 += "<td style='width:5%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

	$("#myTable").append(cell1);
	$("#cnt").val(i);

	$("#product_"+i).select2({
		dropdownParent: $('#myTable')
	});

	
}
                
</script>

