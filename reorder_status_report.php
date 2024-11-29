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
	unset($_SESSION['FromDate']);
	unset($_SESSION['ToDate']);
	//unset($_SESSION['cname']);
	if($_REQUEST['Task']=='filter')
	{
		$from=$_REQUEST['FromDate'];
		$Date1=date('Y-m-d',strtotime($from));
		
		$to=$_REQUEST['ToDate'];
		$Date=date('Y-m-d',strtotime($to));
		
		
		$_SESSION['FromDate']=date($Date1);
		$_SESSION['ToDate']=date($Date);
		$inputfrom=date('d-m-Y',strtotime($from));
		$inputto=date('d-m-Y',strtotime($to));
		//$_SESSION['cname']=$_REQUEST['cname'];

		
	}
else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='')
	{
		$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}

?>

<div class="container-xxl flex-grow-1 container-p-y ">
    
    <div class="row">     
		<div class="col-md-3">       
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Reorder Status Report </h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">

		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
				<div class="col-md-3 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 
				<div class="col-md-3 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div>
				<!-- <div class="col-md-3">
					<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['location']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("location","1 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['location']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div> -->

                <div class="col-md-2" >
                        
                <button class=" btn btn-primary mr-2 "style = "margin-top: 29px;"><a href="purchase_requisition_list.php#addRecordModal"> Add New</a></button> 
                </div>

				<div class="col-md-2" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" style="margin-top: 2px;" />
				</div>

                
			</div>
		</form>
	</div>

    <!-------------------------------- Invoice List Table --------------------------------->

	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
		
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
            
				<thead>
                    
					<tr>
						<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
						<th >Date</th>
						<th >Sale Order No</th>
						<th >Customer Name</th>
                        <th >Location</th>
						<th >Product</th>
						<th >Unit</th>
						<th >Ordered Quantity</th>
						<th >Available Stock</th>
						<th >User</th>
					</tr>
				</thead>
	
				<tbody>
					<?php
						$i=0;$k=0;
						// if($_REQUEST['Task']=='filter' && $_REQUEST['location']!="All" && $_REQUEST['location']!=""){
						// 	$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND location='".$_REQUEST['location']."'";
						// }else{
						// 	$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
						// }
						$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
						$data=$utilObj->getMultipleRow("sale_order","$cnd"); // AND id not in(select delivery_challan_no from sale_invoice where 1)
						foreach($data as $info){
                           
							$i++;$j=0;
							$href= 'reorder_status_report.php?id='.$info['id'].'&PTask=view';
							
							
							$data1=$utilObj->getSingleRow("sale_order_details","parent_id='".$info['id']."'");
                            $data2=$utilObj->getSingleRow("stock_ledger","id='".$data1['product']."'");
                            $data3=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
                            $data4=$utilObj->getSingleRow("location","id='".$info['location']."'");

							$j++; 
                            
							$k++;
							
							?>
							<tr>
								<td  class=" controls" ><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $k; ?></td> 
								<td class="" > <?php echo $info['date']; ?> </td>
								<td class="" > <?php echo $info['order_no']; ?></td>
								<td class="" > <?php echo $data3['name']; ?></td>
                                <td ><?php echo $data4['name']; ?></td>
								<td><?php echo $data2['name']; ?></td>
								<td><?php echo $data1['unit']; ?></td>
								<td><?php echo $data1['qty']; ?></td>
								<td><?php echo getstock($data2['id'],$data1['unit'],$info['date'],'',$info['location']); ?></td>
								<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
								<td ><?php echo $username['name']; ?></td>
							</tr>
							<?php 
							
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

    
    
</div>

<script>

window.onload=function(){
	$("#fromdate").flatpickr({
	dateFormat: "d-m-Y"
	});
	$("#todate").flatpickr({
	dateFormat: "d-m-Y"
	});
}

function Search(){
	var fromdate=$('#fromdate').val();
	var todate=$('#todate').val();
	// var location=$('#location').val();
	window.location="reorder_status_report.php?FromDate="+fromdate+"&ToDate="+todate+"&Task=filter";
}
// "&location="+location+
</script>

<!-- Footer -->
<?php 
include("footer.php");
?>