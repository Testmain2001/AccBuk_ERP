
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="addRecordModal" tabindex="-1" aria-hidden="true">
<?php

// $getrecordno=mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from stock_transfer");
// $result=mysqli_fetch_array($getrecordno);
// $record_no=$result['pono']+1;  

$date=date('d-m-Y');	
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("stock_transfer","id ='".$id."'");
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
          <h3 class="role-title">Stock Transfer</h3>
          
        </div>
        <!-- Add role form -->
		
         <form id="" data-parsley-validate class="row g-3" action="../stock_transfer_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "stock_transfer"; ?>"/>
			    
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_stockt_code();">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=10 group by id"); 
								foreach($data as $info){
									if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>	

					<div class="col-md-4">
					<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="record_no" value="<?php echo $record_no;?>"/>
					</div>

					<div class="col-md-4">
					<label class="form-label">Stock  Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">location<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location"  onchange="get_locationwise_productstock();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
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

function get_stockt_code() {
	
	// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
	// $result=mysqli_fetch_array($getinvno);
	// $grn_no=$result['pono']+1;

	var voucher_type = $("#voucher_type").val();

	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_stockt_code',voucher_type:voucher_type},
		success:function(data)
		{	
			//alert(data);
			$("#record_no").val(data);
		}
	});

}

function get_locationwise_productstock()
{	
 
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var location = $("#location").val();
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_locationwise_productstock',location:location,id:id,PTask:PTask},
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
function stock_check(rid){
		
    var did=rid.split("_");	
	var rid=did[1];	
	//alert(rid);	
	var fromstock=jQuery("#fromstock_"+rid).val(); 
	var tostock=jQuery("#tostock_"+rid).val(); 
	 
	if(parseFloat(tostock)>parseFloat(fromstock))
		{
			$("#tostock_"+rid).val('');
			alert("stock should not grather than availiable stock");
			return false;				
		}
}
</script>

